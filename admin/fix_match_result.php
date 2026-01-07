<?php
require_once 'auth.php';
requireLogin();
require_once '../db_connect.php';

// Set timezone
date_default_timezone_set('Asia/Kolkata');

$message = '';
$error = '';

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === UPDATE RESULT & SCORES ===
    if (isset($_POST['action']) && $_POST['action'] === 'update_result') {
        try {
            $id = (int)$_POST['match_id'];
            $winner_team = (int)$_POST['winner_team']; // 0 for draw, or team_id
            $win_description = trim($_POST['win_description']);
            $status = $_POST['status'];
            
            // 1. Update Match Metadata (Winner, Status, Desc)
            $stmt = $conn->prepare("UPDATE matches SET winner_team = :winner, win_description = :desc, status = :status WHERE id = :id");
            $stmt->execute([
                'winner' => $winner_team,
                'desc' => $win_description,
                'status' => $status,
                'id' => $id
            ]);
            
            // 2. Update Scores in JSON
            $new_score1 = $_POST['score1']; // can be string or int
            $new_score2 = $_POST['score2'];
            
            // Fetch validation
            if ($new_score1 !== '' && $new_score2 !== '') {
                $stmt = $conn->prepare("SELECT score_json FROM live_scores WHERE match_id = :id");
                $stmt->execute(['id' => $id]);
                $row = $stmt->fetch();
                
                $score_data = $row ? json_decode($row['score_json'], true) : [];
                if (!$score_data) $score_data = [];
                
                // Update keys
                $score_data['team1_score'] = $new_score1;
                $score_data['team2_score'] = $new_score2;
                
                // Save back
                $json_str = json_encode($score_data);
                
                // Check if row exists to Decide Update or Insert
                if ($row) {
                    $upd = $conn->prepare("UPDATE live_scores SET score_json = :json WHERE match_id = :id");
                    $upd->execute(['json' => $json_str, 'id' => $id]);
                } else {
                    $ins = $conn->prepare("INSERT INTO live_scores (match_id, score_json) VALUES (:id, :json)");
                    $ins->execute(['id' => $id, 'json' => $json_str]);
                }
            }
            
            $message = "Match #$id updated successfully.";
        } catch (Exception $e) {
            $error = "Error updating: " . $e->getMessage();
        }
    }
    
    // === DELETE MATCH ===
    elseif (isset($_POST['action']) && $_POST['action'] === 'delete_match') {
        try {
            $id = (int)$_POST['match_id'];
            
            // Delete from live_scores first (foreign key constraint might not exist/cascade, safest to delete)
            $stmt = $conn->prepare("DELETE FROM live_scores WHERE match_id = :id");
            $stmt->execute(['id' => $id]);
            
            // Delete from matches
            $stmt = $conn->prepare("DELETE FROM matches WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            $message = "Match #$id deleted permanently.";
        } catch (Exception $e) {
            $error = "Error deleting: " . $e->getMessage();
        }
    }
}

// Fetch all matches with details AND joined scores
$stmt = $conn->query("
    SELECT m.id, m.match_time, m.sport_id, m.status, m.winner_team, m.win_description,
           m.team1_id, m.team2_id,
           t1.name as t1_name, t2.name as t2_name, s.name as sport_name,
           ls.score_json
    FROM matches m
    JOIN teams t1 ON m.team1_id = t1.id
    JOIN teams t2 ON m.team2_id = t2.id
    JOIN sports s ON m.sport_id = s.id
    LEFT JOIN live_scores ls ON m.id = ls.match_id
    ORDER BY m.id DESC
");
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Match Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(id) {
            return confirm("Are you sure you want to DELETE Match #" + id + "? This cannot be undone.");
        }
    </script>
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; }
        .status-msg { margin-bottom: 20px; }
        .match-card { background: white; border-radius: 8px; padding: 15px; margin-bottom: 15px; border: 1px solid #dee2e6; transition: all 0.2s; }
        .match-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .sport-badge { font-size: 0.75rem; padding: 3px 8px; border-radius: 4px; background: #e9ecef; font-weight: bold; text-transform: uppercase; }
        .score-input { width: 60px !important; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Super Admin Match Tool</h2>
                <p class="text-muted mb-0">Fix Results, Update Scores, or Delete Matches</p>
            </div>
            <div class="d-flex gap-2">
                <a href="fix_match_time.php" class="btn btn-outline-primary">Go to Time Fixer</a>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success status-msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger status-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($matches as $match): 
                // Parse scores for display
                $scores = $match['score_json'] ? json_decode($match['score_json'], true) : [];
                $s1 = $scores['team1_score'] ?? 0;
                $s2 = $scores['team2_score'] ?? 0;
            ?>
            <div class="col-12">
                <form method="POST" class="match-card">
                    <input type="hidden" name="action" value="update_result">
                    <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                    
                    <div class="row align-items-center gx-3">
                        <!-- Match Info -->
                        <div class="col-md-3 border-end">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="sport-badge"><?= htmlspecialchars($match['sport_name']) ?></span>
                                <span class="badge bg-light text-secondary border">#<?= $match['id'] ?></span>
                            </div>
                            <div class="fw-bold text-primary text-truncate"><?= htmlspecialchars($match['t1_name']) ?></div>
                            <div class="text-muted small text-center" style="line-height: 1;">vs</div>
                            <div class="fw-bold text-primary text-truncate"><?= htmlspecialchars($match['t2_name']) ?></div>
                        </div>

                        <!-- Scores -->
                        <div class="col-md-2 text-center border-end">
                            <label class="form-label small text-muted mb-1">Scores</label>
                            <div class="d-flex justify-content-center gap-2 align-items-center">
                                <input type="number" name="score1" class="form-control form-control-sm score-input" value="<?= $s1 ?>">
                                <span>-</span>
                                <input type="number" name="score2" class="form-control form-control-sm score-input" value="<?= $s2 ?>">
                            </div>
                        </div>
                        
                        <!-- Status & Winner -->
                        <div class="col-md-2 border-end">
                            <div class="mb-2">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="live" <?= $match['status'] === 'live' ? 'selected' : '' ?>>Live</option>
                                    <option value="completed" <?= $match['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="upcoming" <?= $match['status'] === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                                </select>
                            </div>
                            <div>
                                <select name="winner_team" class="form-select form-select-sm">
                                    <option value="0" <?= $match['winner_team'] == 0 ? 'selected' : '' ?>>Draw / No Winner</option>
                                    <option value="<?= $match['team1_id'] ?>" <?= $match['winner_team'] == $match['team1_id'] ? 'selected' : '' ?>>
                                        Win: Team 1
                                    </option>
                                    <option value="<?= $match['team2_id'] ?>" <?= $match['winner_team'] == $match['team2_id'] ? 'selected' : '' ?>>
                                        Win: Team 2
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">Result Text</label>
                            <input type="text" name="win_description" class="form-control form-control-sm" value="<?= htmlspecialchars($match['win_description'] ?? '') ?>" placeholder="e.g. Team A won by 5 pts">
                        </div>

                        <!-- Actions -->
                        <div class="col-md-2 text-end">
                            <button type="submit" class="btn btn-sm btn-success w-100 mb-2">Save Changes</button>
                            <button type="submit" name="action" value="delete_match" class="btn btn-sm btn-outline-danger w-100" onclick="return confirmDelete(<?= $match['id'] ?>)">Delete Match</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

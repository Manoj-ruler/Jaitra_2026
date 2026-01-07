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
    if (isset($_POST['action']) && $_POST['action'] === 'update_result') {
        try {
            $id = (int)$_POST['match_id'];
            $winner_team = (int)$_POST['winner_team']; // 0 for draw, or team_id
            $win_description = trim($_POST['win_description']);
            $status = $_POST['status'];
            
            $stmt = $conn->prepare("UPDATE matches SET winner_team = :winner, win_description = :desc, status = :status WHERE id = :id");
            $stmt->execute([
                'winner' => $winner_team,
                'desc' => $win_description,
                'status' => $status,
                'id' => $id
            ]);
            
            $message = "Match #$id result updated.";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch all matches with details
$stmt = $conn->query("
    SELECT m.id, m.match_time, m.sport_id, m.status, m.winner_team, m.win_description,
           m.team1_id, m.team2_id,
           t1.name as t1_name, t2.name as t2_name, s.name as sport_name 
    FROM matches m
    JOIN teams t1 ON m.team1_id = t1.id
    JOIN teams t2 ON m.team2_id = t2.id
    JOIN sports s ON m.sport_id = s.id
    ORDER BY m.id DESC
");
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Match Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; }
        .status-msg { margin-bottom: 20px; }
        .match-card { background: white; border-radius: 8px; padding: 15px; margin-bottom: 15px; border: 1px solid #dee2e6; }
        .sport-badge { font-size: 0.8rem; padding: 4px 8px; border-radius: 4px; background: #e9ecef; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Match Result Correction Tool</h2>
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
            <?php foreach ($matches as $match): ?>
            <div class="col-12">
                <form method="POST" class="match-card">
                    <input type="hidden" name="action" value="update_result">
                    <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                    
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <span class="sport-badge mb-2 d-inline-block"><?= htmlspecialchars($match['sport_name']) ?></span>
                            <div class="small text-muted">ID: <?= $match['id'] ?></div>
                            <div class="fw-bold text-primary">
                                <?= htmlspecialchars($match['t1_name']) ?>
                                <span class="text-dark small mx-1">vs</span>
                                <?= htmlspecialchars($match['t2_name']) ?>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="live" <?= $match['status'] === 'live' ? 'selected' : '' ?>>Live</option>
                                <option value="completed" <?= $match['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="upcoming" <?= $match['status'] === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small text-muted">Winner</label>
                            <select name="winner_team" class="form-select form-select-sm">
                                <option value="0" <?= $match['winner_team'] == 0 ? 'selected' : '' ?>>Draw / None</option>
                                <option value="<?= $match['team1_id'] ?>" <?= $match['winner_team'] == $match['team1_id'] ? 'selected' : '' ?>>
                                    Def. <?= htmlspecialchars($match['t1_name']) ?>
                                </option>
                                <option value="<?= $match['team2_id'] ?>" <?= $match['winner_team'] == $match['team2_id'] ? 'selected' : '' ?>>
                                    Def. <?= htmlspecialchars($match['t2_name']) ?>
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small text-muted">Result Text (e.g., "Team A won by 2 points")</label>
                            <input type="text" name="win_description" class="form-control form-control-sm" value="<?= htmlspecialchars($match['win_description'] ?? '') ?>" placeholder="Result description...">
                        </div>

                        <div class="col-md-1 text-end">
                            <button type="submit" class="btn btn-sm btn-success mt-4">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

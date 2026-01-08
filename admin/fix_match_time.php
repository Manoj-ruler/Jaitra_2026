<?php
require_once 'auth.php';
requireLogin();
require_once '../db_connect.php';

// Set timezone to IST for display
date_default_timezone_set('Asia/Kolkata');

$message = '';
$error = '';

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            if ($_POST['action'] === 'update_single') {
                $id = (int)$_POST['match_id'];
                $new_time = $_POST['match_time'];
                
                $stmt = $conn->prepare("UPDATE matches SET match_time = :time WHERE id = :id");
                $stmt->execute(['time' => $new_time, 'id' => $id]);
                $message = "Match #$id updated successfully.";
            } 
            elseif ($_POST['action'] === 'bulk_offset') {
                $hours = (int)$_POST['offset_hours'];
                $minutes = (int)$_POST['offset_minutes'];
                $ids = $_POST['selected_ids'] ?? [];
                
                if (!empty($ids)) {
                    $offset_str = sprintf("%+d hours %+d minutes", $hours, $minutes);
                    
                    // Construct placeholder string for IN clause
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    
                    $sql = "UPDATE matches SET match_time = DATE_ADD(match_time, INTERVAL '$hours:$minutes' HOUR_MINUTE) WHERE id IN ($placeholders)";
                    
                    // We can't bind INTERVAL directly easily in pure PDO w/ placeholders for the interval string depending on driver, 
                    // safer to calculate in PHP or use standard SQL date addition logic.
                    // Let's use PHP to loop and update to be safe and database-agnostic.
                    
                    $count = 0;
                    $fetchStmt = $conn->prepare("SELECT id, match_time FROM matches WHERE id IN ($placeholders)");
                    $fetchStmt->execute($ids);
                    $matchesToUpdate = $fetchStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $updateStmt = $conn->prepare("UPDATE matches SET match_time = :new_time WHERE id = :id");
                    
                    foreach ($matchesToUpdate as $m) {
                        $time = new DateTime($m['match_time']); // Parse DB time
                        // Note: DB time might be read as server time. We just want to add offset.
                        $interval = new DateInterval(sprintf('PT%dH%dM', abs($hours), abs($minutes)));
                        if ($hours < 0 || ($hours == 0 && $minutes < 0)) {
                            $interval->invert = 1;
                        }
                        
                        $time->add($interval); // or sub if negative, DateInterval handles it if constructed right or use modify
                        // Simpler modify
                        $time->modify("$offset_str");
                        
                        $updateStmt->execute([
                            'new_time' => $time->format('Y-m-d H:i:s'),
                            'id' => $m['id']
                        ]);
                        $count++;
                    }
                    
                    $message = "Updated $count matches with offset $offset_str.";
                } else {
                    $error = "No matches selected.";
                }
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch all matches
$stmt = $conn->query("
    SELECT m.id, m.match_time, m.sport_id, t1.name as t1_name, t2.name as t2_name, s.name as sport_name 
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
    <title>Fix Match Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; }
        .status-msg { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Match Time Correction Tool</h2>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success status-msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger status-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card p-4 mb-4">
            <h4>Bulk Offset Correction</h4>
            <p class="text-muted">Select matches using the checkboxes below and apply a time shift (e.g., +5 hours, +30 minutes).</p>
            <form method="POST" id="bulkForm">
                <input type="hidden" name="action" value="bulk_offset">
                <div class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">Hours</label>
                        <input type="number" name="offset_hours" class="form-control" value="0">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Minutes</label>
                        <input type="number" name="offset_minutes" class="form-control" value="30">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Apply Offset to Selected</button>
                    </div>
                </div>
            
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Sport</th>
                                <th>Match</th>
                                <th>Current DB Time</th>
                                <th>Update to Exact Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($matches as $match): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ids[]" value="<?= $match['id'] ?>" form="bulkForm">
                                </td>
                                <td><?= $match['id'] ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($match['sport_name']) ?></span></td>
                                <td>
                                    <strong><?= htmlspecialchars($match['t1_name']) ?></strong><br>
                                    <small class="text-muted">vs</small><br>
                                    <strong><?= htmlspecialchars($match['t2_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="fw-bold"><?= $match['match_time'] ?></span>
                                </td>
                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="action" value="update_single">
                                        <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                                        <input type="datetime-local" class="form-control form-control-sm" name="match_time" value="<?= date('Y-m-d\TH:i', strtotime($match['match_time'])) ?>" required>
                                        <button type="submit" class="btn btn-sm btn-success">Save</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </form> <!-- Close bulk form -->
    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            document.querySelectorAll('input[name="selected_ids[]"]').forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>

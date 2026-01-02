<?php
/**
 * Admin Dashboard - JAITRA 2026
 */
require_once 'auth.php';
requireLogin();
require_once '../db_connect.php';

$allowed_sport_id = getAllowedSportId();
$username = getAdminUsername();

// Fetch sports based on permission
if ($allowed_sport_id === null) {
    $stmt = $conn->query("SELECT * FROM sports ORDER BY id");
} else {
    $stmt = $conn->prepare("SELECT * FROM sports WHERE id = :id");
    $stmt->execute(['id' => $allowed_sport_id]);
}
$sports = $stmt->fetchAll();

// Fetch scheduled matches for quick start
$matchQuery = "SELECT m.*, s.name as sport_name, 
               t1.name as team1_name, t1.college_name as team1_college,
               t2.name as team2_name, t2.college_name as team2_college
               FROM matches m
               JOIN sports s ON m.sport_id = s.id
               JOIN teams t1 ON m.team1_id = t1.id
               JOIN teams t2 ON m.team2_id = t2.id
               WHERE m.status IN ('upcoming', 'live')";
if ($allowed_sport_id !== null) {
    $matchQuery .= " AND m.sport_id = :sport_id";
}
$matchQuery .= " ORDER BY m.match_time ASC LIMIT 10";

$matchStmt = $conn->prepare($matchQuery);
if ($allowed_sport_id !== null) {
    $matchStmt->execute(['sport_id' => $allowed_sport_id]);
} else {
    $matchStmt->execute();
}
$matches = $matchStmt->fetchAll();

$sportColors = [
    'kabaddi' => '#dc2626',
    'volleyball' => '#6366f1',
    'badminton' => '#0891b2',
    'pickleball' => '#059669'
];

$sportEmojis = [
    'kabaddi' => '🤼',
    'volleyball' => '🏐',
    'badminton' => '🏸',
    'pickleball' => '🥒'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | JAITRA 2026</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #1a2332 0%, #2563eb 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 1.5rem; }
        .header h1 span { color: #fbbf24; }
        .header-actions { display: flex; gap: 1rem; align-items: center; }
        .header-actions span { opacity: 0.8; }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .btn-light { background: rgba(255,255,255,0.2); color: white; }
        .btn-light:hover { background: rgba(255,255,255,0.3); }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .section-title {
            font-size: 1.25rem;
            color: #1a2332;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .sports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .sport-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            border-top: 4px solid var(--sport-color);
        }
        .sport-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .sport-emoji { font-size: 3rem; margin-bottom: 1rem; }
        .sport-name { font-size: 1.25rem; font-weight: 600; color: #1a2332; }
        .sport-action {
            margin-top: 1rem;
            color: var(--sport-color);
            font-weight: 500;
            font-size: 0.875rem;
        }
        .matches-list { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .match-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .match-item:last-child { border-bottom: none; }
        .match-info {}
        .match-teams { font-weight: 600; color: #1a2332; margin-bottom: 0.25rem; }
        .match-meta { font-size: 0.875rem; color: #6b7280; }
        .match-status { padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .status-live { background: #fef2f2; color: #dc2626; }
        .status-upcoming { background: #f0fdf4; color: #16a34a; }
        .match-actions { display: flex; gap: 0.5rem; }
        .btn-small { padding: 0.375rem 0.75rem; font-size: 0.8rem; border-radius: 4px; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .empty-state { padding: 3rem; text-align: center; color: #6b7280; }
        .empty-state p { margin-top: 0.5rem; }
    </style>
</head>
<body>
    <header class="header">
        <h1>JAITRA <span>2026</span> Admin</h1>
        <div class="header-actions">
            <span>Welcome, <?= htmlspecialchars($username) ?></span>
            <a href="../index.php" class="btn btn-light">View Site</a>
            <a href="logout.php" class="btn btn-light">Logout</a>
        </div>
    </header>
    
    <div class="container">
        <h2 class="section-title">Select Sport to Manage</h2>
        <div class="sports-grid">
            <?php foreach ($sports as $sport): ?>
                <a href="control.php?sport=<?= $sport['id'] ?>" class="sport-card" style="--sport-color: <?= $sportColors[$sport['name']] ?>">
                    <div class="sport-emoji"><?= $sportEmojis[$sport['name']] ?></div>
                    <div class="sport-name"><?= ucfirst($sport['name']) ?></div>
                    <div class="sport-action">Manage Scores →</div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <h2 class="section-title">Active & Upcoming Matches</h2>
        <div class="matches-list">
            <?php if (empty($matches)): ?>
                <div class="empty-state">
                    <div style="font-size:2rem">📅</div>
                    <p>No scheduled matches yet. Create a match from the sport control panel.</p>
                </div>
            <?php else: ?>
                <?php foreach ($matches as $match): ?>
                    <div class="match-item">
                        <div class="match-info">
                            <div class="match-teams">
                                <?= htmlspecialchars($match['team1_name']) ?> vs <?= htmlspecialchars($match['team2_name']) ?>
                            </div>
                            <div class="match-meta">
                                <?= ucfirst($match['sport_name']) ?> • <?= date('M j, g:i A', strtotime($match['match_time'])) ?>
                                <?php if ($match['venue']): ?> • <?= htmlspecialchars($match['venue']) ?><?php endif; ?>
                            </div>
                        </div>
                        <div class="match-actions">
                            <span class="match-status status-<?= $match['status'] ?>"><?= ucfirst($match['status']) ?></span>
                            <a href="control.php?sport=<?= $match['sport_id'] ?>&match=<?= $match['id'] ?>" class="btn btn-small btn-primary">
                                <?= $match['status'] === 'live' ? 'Update' : 'Start' ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

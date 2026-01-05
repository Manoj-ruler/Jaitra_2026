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
// Prioritize 'live' matches, then by time
$matchQuery .= " ORDER BY CASE WHEN m.status = 'live' THEN 0 ELSE 1 END, m.match_time ASC LIMIT 10";

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
            background: #f8f9fa;
            min-height: 100vh;
        }
        .header {
            background: #1a2332;
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
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            border-top: 4px solid var(--sport-color);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .sport-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .sport-emoji { font-size: 3rem; margin-bottom: 0.5rem; }
        .sport-name { font-size: 1.25rem; font-weight: 600; color: #1a2332; margin-bottom: auto; }
        .sport-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-top: auto;
        }
        .sport-btn {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }
        .sport-btn-primary {
            background: var(--sport-color);
            color: white;
        }
        .sport-btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .sport-btn-secondary {
            background: white;
            color: var(--sport-color);
            border: 2px solid var(--sport-color);
        }
        .sport-btn-secondary:hover {
            background: var(--sport-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-icon { font-size: 1.25rem; }
        .btn-text { font-size: 0.75rem; font-weight: 600; }
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
                <div class="sport-card" style="--sport-color: <?= $sportColors[$sport['name']] ?>">
                    <div class="sport-emoji"><?= $sportEmojis[$sport['name']] ?></div>
                    <div class="sport-name"><?= ucfirst($sport['name']) ?></div>
                    <div class="sport-actions">
                        <a href="control.php?sport=<?= $sport['id'] ?>" class="sport-btn sport-btn-primary">
                            <span class="btn-icon">⚡</span>
                            <span class="btn-text">Start Match</span>
                        </a>
                        <button onclick="openScheduleModal(<?= $sport['id'] ?>, '<?= $sport['name'] ?>')" class="sport-btn sport-btn-secondary">
                            <span class="btn-icon">📅</span>
                            <span class="btn-text">Schedule</span>
                        </button>
                    </div>
                </div>
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

    <!-- Schedule Match Modal -->
    <div id="scheduleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; color: #1a2332;">Schedule Match - <span id="modalSportName"></span></h3>
                <button onclick="closeScheduleModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280;">&times;</button>
            </div>
            
            <form id="scheduleForm" onsubmit="submitSchedule(event)">
                <input type="hidden" id="scheduleSportId" name="sport_id">
                
                <!-- Team 1 -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1a2332;">Team 1</label>
                    <select id="scheduleTeam1Select" class="form-select" onchange="toggleNewTeamSchedule(1)" style="margin-bottom: 0.5rem;">
                        <option value="">-- Select Existing --</option>
                        <option value="new">+ Add New Team</option>
                    </select>
                    <div id="scheduleNewTeam1" style="display: none;">
                        <input type="text" id="scheduleTeam1Name" placeholder="Team Name" style="width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        <input type="text" id="scheduleTeam1College" placeholder="College Name" style="width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        <select id="scheduleTeam1Gender" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                        </select>
                    </div>
                </div>
                
                <!-- Team 2 -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1a2332;">Team 2</label>
                    <select id="scheduleTeam2Select" class="form-select" onchange="toggleNewTeamSchedule(2)" style="margin-bottom: 0.5rem;">
                        <option value="">-- Select Existing --</option>
                        <option value="new">+ Add New Team</option>
                    </select>
                    <div id="scheduleNewTeam2" style="display: none;">
                        <input type="text" id="scheduleTeam2Name" placeholder="Team Name" style="width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        <input type="text" id="scheduleTeam2College" placeholder="College Name" style="width: 100%; padding: 0.5rem; margin-bottom: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                        <select id="scheduleTeam2Gender" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                        </select>
                    </div>
                </div>
                
                <!-- Date and Time -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1a2332;">Match Date</label>
                        <input type="date" id="scheduleDate" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1a2332;">Match Time</label>
                        <input type="time" id="scheduleTime" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    </div>
                </div>
                
                <!-- Venue and Round -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1a2332;">Venue</label>
                        <input type="text" id="scheduleVenue" placeholder="e.g., Court 1" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #1a2332;">Round/Category</label>
                        <input type="text" id="scheduleRound" placeholder="e.g., Semi Final" style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    </div>
                </div>
                
                <button type="submit" style="width: 100%; padding: 0.75rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Schedule Match
                </button>
            </form>
        </div>
    </div>

    <script>
        let currentSportTeams = [];
        
        function openScheduleModal(sportId, sportName) {
            document.getElementById('scheduleSportId').value = sportId;
            document.getElementById('modalSportName').textContent = sportName.charAt(0).toUpperCase() + sportName.slice(1);
            document.getElementById('scheduleModal').style.display = 'flex';
            
            // Fetch teams for this sport
            fetch(`../api/get_teams.php?sport_id=${sportId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        currentSportTeams = data.teams || [];
                        populateTeamSelects();
                    }
                })
                .catch(err => console.error('Failed to load teams:', err));
        }
        
        function closeScheduleModal() {
            document.getElementById('scheduleModal').style.display = 'none';
            document.getElementById('scheduleForm').reset();
            document.getElementById('scheduleNewTeam1').style.display = 'none';
            document.getElementById('scheduleNewTeam2').style.display = 'none';
        }
        
        function populateTeamSelects() {
            const team1Select = document.getElementById('scheduleTeam1Select');
            const team2Select = document.getElementById('scheduleTeam2Select');
            
            // Clear existing options except first two
            team1Select.innerHTML = '<option value="">-- Select Existing --</option><option value="new">+ Add New Team</option>';
            team2Select.innerHTML = '<option value="">-- Select Existing --</option><option value="new">+ Add New Team</option>';
            
            currentSportTeams.forEach(team => {
                const option1 = document.createElement('option');
                option1.value = team.id;
                option1.textContent = `${team.name} (${team.college_name}) - ${team.gender || 'N/A'}`;
                option1.dataset.name = team.name;
                option1.dataset.college = team.college_name;
                team1Select.appendChild(option1);
                
                const option2 = option1.cloneNode(true);
                option2.dataset.name = team.name;
                option2.dataset.college = team.college_name;
                team2Select.appendChild(option2);
            });
        }
        
        function toggleNewTeamSchedule(num) {
            const select = document.getElementById(`scheduleTeam${num}Select`);
            const newTeamDiv = document.getElementById(`scheduleNewTeam${num}`);
            newTeamDiv.style.display = select.value === 'new' ? 'block' : 'none';
        }
        
        async function submitSchedule(event) {
            event.preventDefault();
            
            const team1Select = document.getElementById('scheduleTeam1Select');
            const team2Select = document.getElementById('scheduleTeam2Select');
            
            const data = {
                action: 'schedule_match',
                sport_id: document.getElementById('scheduleSportId').value,
                team1_id: team1Select.value !== 'new' && team1Select.value ? team1Select.value : null,
                team2_id: team2Select.value !== 'new' && team2Select.value ? team2Select.value : null,
                team1_name: document.getElementById('scheduleTeam1Name')?.value || 
                    (team1Select.selectedOptions[0]?.dataset.name || ''),
                team1_college: document.getElementById('scheduleTeam1College')?.value || 
                    (team1Select.selectedOptions[0]?.dataset.college || ''),
                team1_gender: document.getElementById('scheduleTeam1Gender')?.value || 'Men',
                team2_name: document.getElementById('scheduleTeam2Name')?.value || 
                    (team2Select.selectedOptions[0]?.dataset.name || ''),
                team2_college: document.getElementById('scheduleTeam2College')?.value || 
                    (team2Select.selectedOptions[0]?.dataset.college || ''),
                team2_gender: document.getElementById('scheduleTeam2Gender')?.value || 'Men',
                match_date: document.getElementById('scheduleDate').value,
                match_time: document.getElementById('scheduleTime').value,
                venue: document.getElementById('scheduleVenue').value,
                round: document.getElementById('scheduleRound').value
            };
            
            try {
                const res = await fetch('update_score.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                
                if (result.success) {
                    alert('Match scheduled successfully!');
                    closeScheduleModal();
                    location.reload(); // Refresh to show new scheduled match
                } else {
                    alert('Error: ' + (result.error || 'Failed to schedule match'));
                }
            } catch (err) {
                alert('Network error: ' + err.message);
            }
        }
    </script>
</body>
</html>

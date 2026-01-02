<?php
/**
 * Score Control Panel - JAITRA 2026
 * Based on reference repo ssantoshhhhh/live_scores - Kabaddi Features
 */
require_once 'auth.php';
requireLogin();
require_once '../db_connect.php';

$sport_id = isset($_GET['sport']) ? (int)$_GET['sport'] : 0;
$match_id = isset($_GET['match']) ? (int)$_GET['match'] : 0;

// Check permission
if (!canAccessSport($sport_id)) {
    header('Location: dashboard.php');
    exit;
}

// Get sport info
$stmt = $conn->prepare("SELECT * FROM sports WHERE id = :id");
$stmt->execute(['id' => $sport_id]);
$sport = $stmt->fetch();

if (!$sport) {
    header('Location: dashboard.php');
    exit;
}

// Get teams for this sport
$stmt = $conn->prepare("SELECT * FROM teams WHERE sport_id = :sport_id ORDER BY name");
$stmt->execute(['sport_id' => $sport_id]);
$teams = $stmt->fetchAll();

$sportName = $sport['name'];

// Check for active match to persist state
$activeMatch = null;
$initialState = null;

if ($match_id) {
    $stmt = $conn->prepare("
        SELECT m.*, t1.name as t1_name, t2.name as t2_name 
        FROM matches m 
        JOIN teams t1 ON m.team1_id = t1.id 
        JOIN teams t2 ON m.team2_id = t2.id 
        WHERE m.id = :id AND m.sport_id = :sport_id
    ");
    $stmt->execute(['id' => $match_id, 'sport_id' => $sport_id]);
    $activeMatch = $stmt->fetch();

    if ($activeMatch) {
        $stmt = $conn->prepare("SELECT score_json FROM live_scores WHERE match_id = :id");
        $stmt->execute(['id' => $match_id]);
        $scoreRow = $stmt->fetch();
        $initialState = $scoreRow ? $scoreRow['score_json'] : null;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($sportName) ?> Control | JAITRA 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
        }
        .navbar-custom { background-color: #002147 !important; }
        .glass-card {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid #334155;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        .control-btn { height: 70px; font-size: 1.1rem; font-weight: bold; }
        .score-display { font-size: 5rem; font-weight: 800; }
        .active-raider-btn { 
            box-shadow: 0 0 20px #facc15; 
            border-color: #facc15 !important; 
            background: rgba(250, 204, 21, 0.2) !important;
        }
        .btn-timeout-active {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        @media (max-width: 768px) {
            .score-display { font-size: 3rem; }
            .control-btn { height: 50px; font-size: 0.9rem; }
        }
        ::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        input, select {
            color: white !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <span class="navbar-brand fw-bold"><?= ucfirst($sportName) ?> Control</span>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">← Dashboard</a>
        </div>
    </nav>

    <div class="container py-4">
        <!-- SETUP SECTION -->
        <div id="setup-panel" class="glass-card p-4 mx-auto <?= $activeMatch ? 'd-none' : '' ?>" style="max-width: 600px;">
            <h3 class="text-white mb-3">Start New Match</h3>
            <div class="mb-3">
                <label class="text-secondary">Team 1</label>
                <select id="team1-select" class="form-select bg-dark text-white border-secondary" onchange="toggleNewTeam(1)">
                    <option value="">-- Select Existing --</option>
                    <option value="new">+ Add New Team</option>
                    <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>" data-name="<?= htmlspecialchars($t['name']) ?>" data-college="<?= htmlspecialchars($t['college_name']) ?>">
                            <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['college_name']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="new-team1" class="mt-2 d-none">
                    <input type="text" id="team1-name" class="form-control bg-dark text-white border-secondary mb-2" placeholder="e.g. Thunders FC">
                    <input type="text" id="team1-college" class="form-control bg-dark text-white border-secondary mb-2" placeholder="e.g. IIT Madras">
                    <select id="team1-gender" class="form-select bg-dark text-white border-secondary">
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="text-secondary">Team 2</label>
                <select id="team2-select" class="form-select bg-dark text-white border-secondary" onchange="toggleNewTeam(2)">
                    <option value="">-- Select Existing --</option>
                    <option value="new">+ Add New Team</option>
                    <?php foreach ($teams as $t): ?>
                        <option value="<?= $t['id'] ?>" data-name="<?= htmlspecialchars($t['name']) ?>" data-college="<?= htmlspecialchars($t['college_name']) ?>">
                            <?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['college_name']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="new-team2" class="mt-2 d-none">
                    <input type="text" id="team2-name" class="form-control bg-dark text-white border-secondary mb-2" placeholder="e.g. Paws United">
                    <input type="text" id="team2-college" class="form-control bg-dark text-white border-secondary mb-2" placeholder="e.g. IIT Bombay">
                    <select id="team2-gender" class="form-select bg-dark text-white border-secondary">
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <input type="text" id="venue" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Court 1">
                </div>
                <div class="col-6">
                    <input type="text" id="round" class="form-control bg-dark text-white border-secondary" placeholder="e.g. Semi Final">
                </div>
            </div>
            <button onclick="startMatch()" class="btn btn-primary w-100 py-3 fw-bold">START MATCH</button>
        </div>

        <!-- LIVE CONTROL SECTION -->
        <div id="live-panel" class="<?= $activeMatch ? '' : 'd-none' ?>">
            
            <!-- Edit Names Toggle -->
            <div class="text-end mb-2">
                <button onclick="toggleEditNames()" class="btn btn-sm btn-outline-secondary text-white-50">
                    <i class="fas fa-edit me-1"></i> Edit Teams
                </button>
            </div>

            <!-- Edit Names Panel -->
            <div id="edit-names-panel" class="glass-card p-3 mb-4 d-none">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" id="edit-t1" class="form-control bg-dark text-white text-center" placeholder="Team 1 Name">
                    </div>
                    <div class="col-md-2 text-center text-secondary">vs</div>
                    <div class="col-md-5">
                        <input type="text" id="edit-t2" class="form-control bg-dark text-white text-center" placeholder="Team 2 Name">
                    </div>
                    <div class="col-12 mt-2 text-center">
                        <button onclick="saveTeamNames()" class="btn btn-success btn-sm px-4">Save Changes</button>
                    </div>
                </div>
            </div>

            <!-- Scoreboard Display -->
            <div class="row mb-4">
                <div class="col-5">
                    <div class="glass-card p-3 text-center">
                        <h4 id="lbl-t1" class="text-primary mb-2"><?= $activeMatch ? htmlspecialchars($activeMatch['t1_name']) : 'Team 1' ?></h4>
                        <div class="score-display text-white" id="val-t1">0</div>
                    </div>
                </div>
                <div class="col-2 d-flex align-items-center justify-content-center">
                    <span class="text-secondary h2">VS</span>
                </div>
                <div class="col-5">
                    <div class="glass-card p-3 text-center">
                        <h4 id="lbl-t2" class="text-danger mb-2">Team 2</h4>
                        <div class="score-display text-white" id="val-t2">0</div>
                    </div>
                </div>
            </div>

            <?php if ($sportName === 'kabaddi'): ?>
            <!-- KABADDI TIMEOUT -->
            <div class="text-center mb-4">
                <button onclick="toggleTimeout()" id="btn-timeout" class="btn btn-warning fw-bold px-5 py-2">
                    ⏱️ START TIMEOUT
                </button>
            </div>
            <?php endif; ?>

            <!-- Score Controls -->
            <div class="glass-card p-4 mb-4">
                <div class="row g-3">
                    <!-- TEAM 1 -->
                    <div class="col-md-6 border-end border-secondary">
                        <h5 class="text-primary text-center mb-3">TEAM 1 SCORING</h5>
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <button onclick="addPoints('t1', 1)" class="btn btn-outline-primary w-100 control-btn">+1</button>
                            </div>
                            <div class="col-4">
                                <button onclick="addPoints('t1', 2)" class="btn btn-outline-primary w-100 control-btn">+2</button>
                            </div>
                            <div class="col-4">
                                <button onclick="addPoints('t1', 3)" class="btn btn-primary w-100 control-btn">+3</button>
                            </div>
                        </div>
                        <button onclick="addPoints('t1', -1)" class="btn btn-outline-danger w-100 mb-3">-1</button>
                        
                        <?php if ($sportName === 'kabaddi'): ?>
                        <hr class="border-secondary">
                        <div class="text-secondary small mb-2">Animations (No Points)</div>
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <button onclick="triggerAnimation('t1', 'SUPER RAID')" class="btn btn-warning w-100 py-2 small">🔥 SUPER RAID</button>
                            </div>
                            <div class="col-4">
                                <button onclick="triggerAnimation('t1', 'SUPER TACKLE')" class="btn btn-info w-100 py-2 small">💪 SUPER TKL</button>
                            </div>
                            <div class="col-4">
                                <button onclick="triggerAnimation('t1', 'ALL OUT')" class="btn btn-danger w-100 py-2 small">❌ ALL OUT</button>
                            </div>
                        </div>
                        
                        <button onclick="setRaider('team1')" id="btn-raid-t1" class="btn btn-outline-warning w-100 py-2 mb-2">
                            <i class="fas fa-running me-1"></i> Set Raiding
                        </button>
                        
                        <div class="bg-dark p-2 rounded text-center">
                            <small class="text-secondary">Players on Court</small>
                            <div class="d-flex align-items-center justify-content-center gap-3 mt-1">
                                <button onclick="updatePlayers('t1', -1)" class="btn btn-sm btn-secondary">-</button>
                                <span id="pcount-t1" class="h4 mb-0 text-white">7</span>
                                <button onclick="updatePlayers('t1', 1)" class="btn btn-sm btn-secondary">+</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- TEAM 2 -->
                    <div class="col-md-6">
                        <h5 class="text-danger text-center mb-3">TEAM 2 SCORING</h5>
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <button onclick="addPoints('t2', 1)" class="btn btn-outline-danger w-100 control-btn">+1</button>
                            </div>
                            <div class="col-4">
                                <button onclick="addPoints('t2', 2)" class="btn btn-outline-danger w-100 control-btn">+2</button>
                            </div>
                            <div class="col-4">
                                <button onclick="addPoints('t2', 3)" class="btn btn-danger w-100 control-btn">+3</button>
                            </div>
                        </div>
                        <button onclick="addPoints('t2', -1)" class="btn btn-outline-danger w-100 mb-3">-1</button>
                        
                        <?php if ($sportName === 'kabaddi'): ?>
                        <hr class="border-secondary">
                        <div class="text-secondary small mb-2">Animations (No Points)</div>
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <button onclick="triggerAnimation('t2', 'SUPER RAID')" class="btn btn-warning w-100 py-2 small">🔥 SUPER RAID</button>
                            </div>
                            <div class="col-4">
                                <button onclick="triggerAnimation('t2', 'SUPER TACKLE')" class="btn btn-info w-100 py-2 small">💪 SUPER TKL</button>
                            </div>
                            <div class="col-4">
                                <button onclick="triggerAnimation('t2', 'ALL OUT')" class="btn btn-danger w-100 py-2 small">❌ ALL OUT</button>
                            </div>
                        </div>
                        
                        <button onclick="setRaider('team2')" id="btn-raid-t2" class="btn btn-outline-warning w-100 py-2 mb-2">
                            <i class="fas fa-running me-1"></i> Set Raiding
                        </button>
                        
                        <div class="bg-dark p-2 rounded text-center">
                            <small class="text-secondary">Players on Court</small>
                            <div class="d-flex align-items-center justify-content-center gap-3 mt-1">
                                <button onclick="updatePlayers('t2', -1)" class="btn btn-sm btn-secondary">-</button>
                                <span id="pcount-t2" class="h4 mb-0 text-white">7</span>
                                <button onclick="updatePlayers('t2', 1)" class="btn btn-sm btn-secondary">+</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- End Match -->
            <button onclick="endMatch()" class="btn btn-danger w-100 py-3 fw-bold">END MATCH</button>
        </div>
    </div>

<script>
    const SPORT_ID = <?= $sport_id ?>;
    let matchId = <?= $activeMatch ? $activeMatch['id'] : 'null' ?>;
    
    // Score state - matches reference repo structure
    let state = <?= $activeMatch && $initialState ? $initialState : json_encode([
        'team1_score' => 0,
        'team2_score' => 0,
        't1_players' => 7,
        't2_players' => 7,
        'current_raider' => 'team1',
        'is_timeout' => false,
        'last_animation' => null,
        'history' => []
    ]) ?>;
    
    // Ensure defaults if state was partial
    state = {
        team1_score: 0,
        team2_score: 0,
        t1_players: 7,
        t2_players: 7,
        current_raider: 'team1',
        is_timeout: false,
        last_animation: null,
        history: [],
        ...state
    };
    
    // Initial Render
    if (matchId) {
        document.addEventListener('DOMContentLoaded', () => {
            render();
            setRaider(state.current_raider || 'team1');
        });
    }

    function toggleNewTeam(num) {
        const select = document.getElementById(`team${num}-select`);
        document.getElementById(`new-team${num}`).classList.toggle('d-none', select.value !== 'new');
    }
    
    async function startMatch() {
        const team1Select = document.getElementById('team1-select');
        const team2Select = document.getElementById('team2-select');
        
        const data = {
            action: 'start_match',
            sport_id: SPORT_ID,
            team1_id: team1Select.value !== 'new' && team1Select.value ? team1Select.value : null,
            team2_id: team2Select.value !== 'new' && team2Select.value ? team2Select.value : null,
            team1_name: document.getElementById('team1-name')?.value || 
                (team1Select.selectedOptions[0]?.dataset.name || ''),
            team1_college: document.getElementById('team1-college')?.value || 
                (team1Select.selectedOptions[0]?.dataset.college || ''),
            team1_gender: document.getElementById('team1-gender')?.value || 'Men',
            team2_name: document.getElementById('team2-name')?.value || 
                (team2Select.selectedOptions[0]?.dataset.name || ''),
            team2_college: document.getElementById('team2-college')?.value || 
                (team2Select.selectedOptions[0]?.dataset.college || ''),
            team2_gender: document.getElementById('team2-gender')?.value || 'Men',
            venue: document.getElementById('venue').value,
            round: document.getElementById('round').value
        };
        
        try {
            const res = await fetch('update_score.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            
            if (result.success) {
                matchId = result.match_id;
                // Update URL without reloading to ensure persistence on refresh
                const newUrl = `${window.location.pathname}?sport=${SPORT_ID}&match=${matchId}`;
                window.history.pushState({path: newUrl}, '', newUrl);

                document.getElementById('lbl-t1').innerText = result.team1_name || data.team1_name;
                document.getElementById('lbl-t2').innerText = result.team2_name || data.team2_name;
                document.getElementById('setup-panel').classList.add('d-none');
                document.getElementById('live-panel').classList.remove('d-none');
                render();
            } else {
                alert('Error: ' + (result.error || 'Failed to start'));
            }
        } catch (err) {
            alert('Network error: ' + err.message);
        }
    }
    
    function addPoints(team, points) {
        const key = team === 't1' ? 'team1_score' : 'team2_score';
        state[key] = Math.max(0, (state[key] || 0) + points);
        sync();
    }
    
    function toggleTimeout() {
        state.is_timeout = !state.is_timeout;
        sync();
    }
    
    function triggerAnimation(team, type) {
        // Store animation in state - will be synced to DB and picked up by user-side
        state.last_animation = {
            id: Date.now(),
            team: team,
            type: type
        };
        sync();
    }
    
    function setRaider(team) {
        state.current_raider = team;
        
        const btnT1 = document.getElementById('btn-raid-t1');
        const btnT2 = document.getElementById('btn-raid-t2');
        if (btnT1) btnT1.classList.toggle('active-raider-btn', team === 'team1');
        if (btnT2) btnT2.classList.toggle('active-raider-btn', team === 'team2');
        
        sync();
    }
    
    function updatePlayers(team, change) {
        const key = team === 't1' ? 't1_players' : 't2_players';
        let val = (state[key] || 7) + change;
        state[key] = Math.max(0, Math.min(7, val));
        sync();
    }
    
    function sync() {
        render();
        
        if (!matchId) return;
        
        fetch('update_score.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'update_score',
                match_id: matchId,
                scores: state
            })
        }).catch(err => console.error('Sync failed:', err));
    }
    
    function render() {
        document.getElementById('val-t1').innerText = state.team1_score || 0;
        document.getElementById('val-t2').innerText = state.team2_score || 0;
        
        // Kabaddi specific
        const pT1 = document.getElementById('pcount-t1');
        const pT2 = document.getElementById('pcount-t2');
        if (pT1) pT1.innerText = state.t1_players ?? 7;
        if (pT2) pT2.innerText = state.t2_players ?? 7;
        
        // Timeout button state
        const btnTimeout = document.getElementById('btn-timeout');
        if (btnTimeout) {
            if (state.is_timeout) {
                btnTimeout.innerText = '▶️ END TIMEOUT';
                btnTimeout.className = 'btn btn-danger fw-bold px-5 py-2 btn-timeout-active';
            } else {
                btnTimeout.innerText = '⏱️ START TIMEOUT';
                btnTimeout.className = 'btn btn-warning fw-bold px-5 py-2';
            }
        }
    }

    function toggleEditNames() {
        const panel = document.getElementById('edit-names-panel');
        const isHidden = panel.classList.contains('d-none');
        
        if (isHidden) {
            // Pre-fill current names
            document.getElementById('edit-t1').value = document.getElementById('lbl-t1').innerText;
            document.getElementById('edit-t2').value = document.getElementById('lbl-t2').innerText;
            panel.classList.remove('d-none');
        } else {
            panel.classList.add('d-none');
        }
    }

    async function saveTeamNames() {
        if (!matchId) return;

        const t1 = document.getElementById('edit-t1').value;
        const t2 = document.getElementById('edit-t2').value;

        if (!t1 || !t2) {
            alert('Team names cannot be empty');
            return;
        }

        try {
            const res = await fetch('update_score.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update_team_names',
                    match_id: matchId,
                    team1_name: t1,
                    team2_name: t2
                })
            });
            const result = await res.json();
            
            if (result.success) {
                document.getElementById('lbl-t1').innerText = t1;
                document.getElementById('lbl-t2').innerText = t2;
                toggleEditNames(); // Hide panel
            } else {
                alert('Error: ' + (result.error || 'Failed to update names'));
            }
        } catch (err) {
            alert('Network error: ' + err.message);
        }
    }
    
    async function endMatch() {
        if (!matchId) return;
        
        const s1 = state.team1_score || 0;
        const s2 = state.team2_score || 0;
        const t1 = document.getElementById('lbl-t1').innerText;
        const t2 = document.getElementById('lbl-t2').innerText;
        
        let winner = null;
        let desc = 'Match Drawn';
        
        if (s1 > s2) {
            winner = t1;
            desc = `${t1} won by ${s1 - s2} points`;
        } else if (s2 > s1) {
            winner = t2;
            desc = `${t2} won by ${s2 - s1} points`;
        }
        
        if (!confirm(`End Match?\n\nResult: ${desc}`)) return;
        
        // Trigger winner animation for user-side
        if (winner) {
            state.last_animation = {
                id: Date.now(),
                team: s1 > s2 ? 't1' : 't2',
                type: 'WINNER'
            };
            await fetch('update_score.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'update_score',
                    match_id: matchId,
                    scores: state
                })
            });
        }
        
        // End match
        await fetch('update_score.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'end_match',
                match_id: matchId,
                winner_team: s1 > s2 ? 1 : (s2 > s1 ? 2 : 0),
                win_description: desc
            })
        });
        
        alert('Match Ended!');
        window.location.href = 'dashboard.php';
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
/**
 * Score Update API - AJAX Endpoint
 * Handles start_match, update_score, end_match actions
 */
session_start();
header('Content-Type: application/json');

// Check auth
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once '../db_connect.php';

/**
 * Get initial score schema based on sport type
 * All sports include 'is_timeout' as a common field
 */
function getInitialScoreSchema($sport_name) {
    $sport = strtolower(trim($sport_name));
    
    switch ($sport) {
        case 'kabaddi':
            return [
                'team1_score' => 0,
                'team2_score' => 0,
                'current_raider' => 'team1',
                't1_players' => 7,
                't2_players' => 7,
                'is_timeout' => false,
                'last_animation' => null
            ];
            
        case 'badminton':
            return [
                'team1_score' => 0,
                'team2_score' => 0,
                'server' => null,
                't1_sets' => 0,
                't2_sets' => 0,
                'current_set' => 1,
                'is_timeout' => false
            ];
            
        case 'volleyball':
        case 'pickleball':
            return [
                'team1_score' => 0,
                'team2_score' => 0,
                't1_sets' => 0,
                't2_sets' => 0,
                'current_set' => 1,
                'server' => null,
                'is_timeout' => false
            ];
            
        default:
            // Generic fallback
            return [
                'team1_score' => 0,
                'team2_score' => 0,
                'is_timeout' => false
            ];
    }
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$action = $data['action'];

try {
    switch ($action) {
        case 'start_match':
            $sport_id = (int)$data['sport_id'];
            $venue = $data['venue'] ?? '';
            $round = $data['round'] ?? '';
            
            // Handle Team 1
            if (!empty($data['team1_id'])) {
                $team1_id = (int)$data['team1_id'];
                $stmt = $conn->prepare("SELECT name, college_name FROM teams WHERE id = :id");
                $stmt->execute(['id' => $team1_id]);
                $team1 = $stmt->fetch();
                $team1_name = $team1['name'];
                $team1_college = $team1['college_name'];
            } else {
                // Create new team
                $team1_name = trim($data['team1_name'] ?? '');
                $team1_college = trim($data['team1_college'] ?? '');
                $team1_gender = $data['team1_gender'] ?? 'Men'; // Default to Men

                if (empty($team1_name) || empty($team1_college)) {
                    echo json_encode(['success' => false, 'error' => 'Team 1 name and college required']);
                    exit;
                }
                $stmt = $conn->prepare("INSERT INTO teams (name, college_name, gender, sport_id) VALUES (:name, :college, :gender, :sport) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
                $stmt->execute(['name' => $team1_name, 'college' => $team1_college, 'gender' => $team1_gender, 'sport' => $sport_id]);
                $team1_id = $conn->lastInsertId();
            }
            
            // Handle Team 2
            if (!empty($data['team2_id'])) {
                $team2_id = (int)$data['team2_id'];
                $stmt = $conn->prepare("SELECT name, college_name FROM teams WHERE id = :id");
                $stmt->execute(['id' => $team2_id]);
                $team2 = $stmt->fetch();
                $team2_name = $team2['name'];
                $team2_college = $team2['college_name'];
            } else {
                $team2_name = trim($data['team2_name'] ?? '');
                $team2_college = trim($data['team2_college'] ?? '');
                $team2_gender = $data['team2_gender'] ?? 'Men'; // Default to Men

                if (empty($team2_name) || empty($team2_college)) {
                    echo json_encode(['success' => false, 'error' => 'Team 2 name and college required']);
                    exit;
                }
                $stmt = $conn->prepare("INSERT INTO teams (name, college_name, gender, sport_id) VALUES (:name, :college, :gender, :sport) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
                $stmt->execute(['name' => $team2_name, 'college' => $team2_college, 'gender' => $team2_gender, 'sport' => $sport_id]);
                $team2_id = $conn->lastInsertId();
            }
            
            // Create match
            $stmt = $conn->prepare("
                INSERT INTO matches (sport_id, team1_id, team2_id, status, match_time, venue, round)
                VALUES (:sport, :t1, :t2, 'live', NOW(), :venue, :round)
            ");
            $stmt->execute([
                'sport' => $sport_id,
                't1' => $team1_id,
                't2' => $team2_id,
                'venue' => $venue,
                'round' => $round
            ]);
            $match_id = $conn->lastInsertId();
            
            // Get sport name to initialize proper schema
            $stmt = $conn->prepare("SELECT name FROM sports WHERE id = :id");
            $stmt->execute(['id' => $sport_id]);
            $sport = $stmt->fetch();
            $sport_name = $sport ? $sport['name'] : 'generic';
            
            // Initialize scores with sport-specific schema
            $initial_scores = json_encode(getInitialScoreSchema($sport_name));
            $stmt = $conn->prepare("INSERT INTO live_scores (match_id, score_json) VALUES (:id, :scores)");
            $stmt->execute(['id' => $match_id, 'scores' => $initial_scores]);
            
            echo json_encode([
                'success' => true,
                'match_id' => $match_id,
                'team1_name' => $team1_name,
                'team1_college' => $team1_college,
                'team2_name' => $team2_name,
                'team2_college' => $team2_college
            ]);
            break;
            
        case 'update_score':
            $match_id = (int)$data['match_id'];
            $scores = $data['scores'];
            
            $stmt = $conn->prepare("UPDATE live_scores SET score_json = :scores WHERE match_id = :id");
            $stmt->execute(['scores' => json_encode($scores), 'id' => $match_id]);
            
            $rowCount = $stmt->rowCount();
            
            // If no rows were updated, the entry might not exist - create it
            if ($rowCount === 0) {
                error_log("No rows updated for match_id $match_id, attempting INSERT");
                $insertStmt = $conn->prepare("INSERT INTO live_scores (match_id, score_json) VALUES (:id, :scores)");
                $insertStmt->execute(['id' => $match_id, 'scores' => json_encode($scores)]);
            }
            
            echo json_encode(['success' => true, 'rows_affected' => $rowCount]);
            break;
            
        case 'end_match':
            $match_id = (int)$data['match_id'];
            $winner_team = isset($data['winner_team']) ? (int)$data['winner_team'] : null;
            $win_description = $data['win_description'] ?? '';
            
            $stmt = $conn->prepare("
                UPDATE matches 
                SET status = 'completed', winner_team = :winner, win_description = :desc
                WHERE id = :id
            ");
            $stmt->execute([
                'winner' => $winner_team,
                'desc' => $win_description,
                'id' => $match_id
            ]);
            
            echo json_encode(['success' => true]);
            break;
            
        case 'trigger_timeout':
            $match_id = (int)$data['match_id'];
            $is_timeout = $data['is_timeout'] ? true : false;
            
            // Get current scores
            $stmt = $conn->prepare("SELECT score_json FROM live_scores WHERE match_id = :id");
            $stmt->execute(['id' => $match_id]);
            $row = $stmt->fetch();
            $scores = $row ? json_decode($row['score_json'], true) : [];
            
            // Update timeout flag
            $scores['is_timeout'] = $is_timeout;
            
            $stmt = $conn->prepare("UPDATE live_scores SET score_json = :scores WHERE match_id = :id");
            $stmt->execute(['scores' => json_encode($scores), 'id' => $match_id]);
            
            echo json_encode(['success' => true]);
            break;
            
        case 'trigger_animation':
            $match_id = (int)$data['match_id'];
            $animation_type = $data['animation_type'] ?? 'SUPER RAID';
            $team = $data['team'] ?? 't1';
            
            // Get current scores
            $stmt = $conn->prepare("SELECT score_json FROM live_scores WHERE match_id = :id");
            $stmt->execute(['id' => $match_id]);
            $row = $stmt->fetch();
            $scores = $row ? json_decode($row['score_json'], true) : [];
            
            // Set animation with unique ID
            $anim_id = isset($scores['last_animation']['id']) ? $scores['last_animation']['id'] + 1 : 1;
            $scores['last_animation'] = [
                'id' => $anim_id,
                'type' => $animation_type,
                'team' => $team,
                'timestamp' => time()
            ];
            
            $stmt = $conn->prepare("UPDATE live_scores SET score_json = :scores WHERE match_id = :id");
            $stmt->execute(['scores' => json_encode($scores), 'id' => $match_id]);
            
            echo json_encode(['success' => true, 'anim_id' => $anim_id]);
            break;
            
        case 'schedule_match':
            $sport_id = (int)$data['sport_id'];
            $venue = $data['venue'] ?? '';
            $round = $data['round'] ?? '';
            $match_date = $data['match_date'] ?? ''; // Format: YYYY-MM-DD
            $match_time = $data['match_time'] ?? ''; // Format: HH:MM
            
            // Validate date and time
            if (empty($match_date) || empty($match_time)) {
                echo json_encode(['success' => false, 'error' => 'Match date and time are required']);
                exit;
            }
            
            // Combine date and time
            $match_datetime = $match_date . ' ' . $match_time . ':00';
            
            // Handle Team 1
            if (!empty($data['team1_id'])) {
                $team1_id = (int)$data['team1_id'];
                $stmt = $conn->prepare("SELECT name, college_name FROM teams WHERE id = :id");
                $stmt->execute(['id' => $team1_id]);
                $team1 = $stmt->fetch();
                $team1_name = $team1['name'];
                $team1_college = $team1['college_name'];
            } else {
                // Create new team
                $team1_name = trim($data['team1_name'] ?? '');
                $team1_college = trim($data['team1_college'] ?? '');
                $team1_gender = $data['team1_gender'] ?? 'Men';

                if (empty($team1_name) || empty($team1_college)) {
                    echo json_encode(['success' => false, 'error' => 'Team 1 name and college required']);
                    exit;
                }
                $stmt = $conn->prepare("INSERT INTO teams (name, college_name, gender, sport_id) VALUES (:name, :college, :gender, :sport) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
                $stmt->execute(['name' => $team1_name, 'college' => $team1_college, 'gender' => $team1_gender, 'sport' => $sport_id]);
                $team1_id = $conn->lastInsertId();
            }
            
            // Handle Team 2
            if (!empty($data['team2_id'])) {
                $team2_id = (int)$data['team2_id'];
                $stmt = $conn->prepare("SELECT name, college_name FROM teams WHERE id = :id");
                $stmt->execute(['id' => $team2_id]);
                $team2 = $stmt->fetch();
                $team2_name = $team2['name'];
                $team2_college = $team2['college_name'];
            } else {
                $team2_name = trim($data['team2_name'] ?? '');
                $team2_college = trim($data['team2_college'] ?? '');
                $team2_gender = $data['team2_gender'] ?? 'Men';

                if (empty($team2_name) || empty($team2_college)) {
                    echo json_encode(['success' => false, 'error' => 'Team 2 name and college required']);
                    exit;
                }
                $stmt = $conn->prepare("INSERT INTO teams (name, college_name, gender, sport_id) VALUES (:name, :college, :gender, :sport) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
                $stmt->execute(['name' => $team2_name, 'college' => $team2_college, 'gender' => $team2_gender, 'sport' => $sport_id]);
                $team2_id = $conn->lastInsertId();
            }
            
            // Create scheduled match (status = 'upcoming', no live_scores entry)
            $stmt = $conn->prepare("
                INSERT INTO matches (sport_id, team1_id, team2_id, status, match_time, venue, round)
                VALUES (:sport, :t1, :t2, 'upcoming', :match_time, :venue, :round)
            ");
            $stmt->execute([
                'sport' => $sport_id,
                't1' => $team1_id,
                't2' => $team2_id,
                'match_time' => $match_datetime,
                'venue' => $venue,
                'round' => $round
            ]);
            $match_id = $conn->lastInsertId();
            
            echo json_encode([
                'success' => true,
                'match_id' => $match_id,
                'team1_name' => $team1_name,
                'team1_college' => $team1_college,
                'team2_name' => $team2_name,
                'team2_college' => $team2_college,
                'match_datetime' => $match_datetime
            ]);
            break;
            
        case 'update_team_names':
            $match_id = (int)$data['match_id'];
            $t1_name = trim($data['team1_name']);
            $t2_name = trim($data['team2_name']);
            
            if (empty($t1_name) || empty($t2_name)) {
                echo json_encode(['success' => false, 'error' => 'Team names cannot be empty']);
                exit;
            }
            
            // Get team IDs from match
            $stmt = $conn->prepare("SELECT team1_id, team2_id FROM matches WHERE id = :id");
            $stmt->execute(['id' => $match_id]);
            $match = $stmt->fetch();
            
            if ($match) {
                // Update Team 1
                $stmt = $conn->prepare("UPDATE teams SET name = :name WHERE id = :id");
                $stmt->execute(['name' => $t1_name, 'id' => $match['team1_id']]);
                
                // Update Team 2
                $stmt = $conn->prepare("UPDATE teams SET name = :name WHERE id = :id");
                $stmt->execute(['name' => $t2_name, 'id' => $match['team2_id']]);
                
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Match not found']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

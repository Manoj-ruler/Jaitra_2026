<?php
/**
 * Public API - Get Single Match Score
 * Parameter: id (match ID)
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../db_connect.php';

$match_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$match_id) {
    echo json_encode(['success' => false, 'error' => 'Match ID required']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            m.id,
            m.status,
            m.match_time,
            m.venue,
            m.round,
            m.winner_team,
            m.win_description,
            s.id as sport_id,
            s.name as sport,
            t1.id as team1_id,
            t1.name as team1_name,
            t1.college_name as team1_college,
            t2.id as team2_id,
            t2.name as team2_name,
            t2.college_name as team2_college,
            ls.score_json
        FROM matches m
        JOIN sports s ON m.sport_id = s.id
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        LEFT JOIN live_scores ls ON m.id = ls.match_id
        WHERE m.id = :id
    ");
    $stmt->execute(['id' => $match_id]);
    $match = $stmt->fetch();
    
    if (!$match) {
        echo json_encode(['success' => false, 'error' => 'Match not found']);
        exit;
    }
    
    $match['scores'] = $match['score_json'] ? json_decode($match['score_json'], true) : null;
    unset($match['score_json']);
    
    echo json_encode([
        'success' => true,
        'match' => $match
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

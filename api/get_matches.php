<?php
/**
 * Public API - Get Matches
 * Filters: sport, status
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../db_connect.php';

$sport = $_GET['sport'] ?? null;
$status = $_GET['status'] ?? null;

try {
    $query = "
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
            t1.gender as gender,
            t2.id as team2_id,
            t2.name as team2_name,
            t2.college_name as team2_college,
            ls.score_json
        FROM matches m
        JOIN sports s ON m.sport_id = s.id
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        LEFT JOIN live_scores ls ON m.id = ls.match_id
        WHERE 1=1
    ";
    
    $params = [];
    
    if ($sport) {
        $query .= " AND s.name = :sport";
        $params['sport'] = $sport;
    }
    
    if ($status) {
        $query .= " AND m.status = :status";
        $params['status'] = $status;
    }

    $gender = $_GET['gender'] ?? null;
    if ($gender) {
        // Filter by gender column in teams table (using t1 as representative)
        $query .= " AND t1.gender = :gender";
        $params['gender'] = $gender;
    }
    
    $query .= " ORDER BY 
        CASE WHEN m.status = 'live' THEN 1 
             WHEN m.status = 'upcoming' THEN 2 
             ELSE 3 END,
        m.match_time DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $matches = $stmt->fetchAll();
    
    // Parse score_json
    foreach ($matches as &$match) {
        $match['scores'] = $match['score_json'] ? json_decode($match['score_json'], true) : null;
        unset($match['score_json']);
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($matches),
        'matches' => $matches
    ]);
    
} catch (PDOException $e) {
    error_log("[" . date("Y-m-d H:i:s") . "] get_matches error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../db_errors.log');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

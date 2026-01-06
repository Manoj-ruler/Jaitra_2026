<?php
/**
 * Public API - Get Live Match by Venue
 * Parameter: venue (string)
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../db_connect.php';

$venue = isset($_GET['venue']) ? trim($_GET['venue']) : '';

if (empty($venue)) {
    echo json_encode(['success' => false, 'error' => 'Venue required']);
    exit;
}

try {
    // Fetch the single most recent LIVE match for this venue
    // We order by id DESC to get the latest one if multiple exist (though ideally only 1 should be live)
    // Build query with optional sport_id
    $sql = "
        SELECT 
            m.id as match_id,
            t1.name as team1_name,
            t2.name as team2_name
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        WHERE m.venue = :venue 
        AND m.status = 'live'
    ";
    
    $params = ['venue' => $venue];

    $sport_id = isset($_GET['sport_id']) ? intval($_GET['sport_id']) : 0;
    if ($sport_id > 0) {
        $sql .= " AND m.sport_id = :sport_id ";
        $params['sport_id'] = $sport_id;
    }

    $sql .= " ORDER BY m.id DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($match) {
        echo json_encode([
            'success' => true,
            'has_match' => true,
            'data' => $match
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'has_match' => false,
            'message' => 'No live match found for this venue'
        ]);
    }

} catch (PDOException $e) {
    error_log("[" . date("Y-m-d H:i:s") . "] get_live_match error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../db_errors.log');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

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
    $stmt = $conn->prepare("
        SELECT 
            m.id as match_id,
            t1.name as team1_name,
            t2.name as team2_name
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.id
        JOIN teams t2 ON m.team2_id = t2.id
        WHERE m.venue = :venue 
        AND m.status = 'live'
        ORDER BY m.id DESC
        LIMIT 1
    ");
    
    $stmt->execute(['venue' => $venue]);
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
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

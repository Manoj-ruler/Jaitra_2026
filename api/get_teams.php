<?php
/**
 * Get Teams API - Fetch teams for a specific sport
 */
header('Content-Type: application/json');
require_once '../db_connect.php';

$sport_id = isset($_GET['sport_id']) ? (int)$_GET['sport_id'] : 0;

if ($sport_id === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid sport ID']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, name, college_name, gender FROM teams WHERE sport_id = :sport_id ORDER BY name");
    $stmt->execute(['sport_id' => $sport_id]);
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'teams' => $teams]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

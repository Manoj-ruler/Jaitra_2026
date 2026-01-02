<?php
/**
 * Database Connection - JAITRA 2026
 * Uses PDO with prepared statements for SQL injection prevention
 */

$db_host = 'localhost';
$db_port = '3306';
$db_name = 'jaitra_scores';
$db_user = 'root';
$db_pass = '';

try {
    $conn = new PDO(
        "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}
?>

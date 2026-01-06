<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log file
$logFile = __DIR__ . '/connection_test_log.txt';

function logMessage($msg) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMsg = "[$timestamp] $msg" . PHP_EOL;
    file_put_contents($logFile, $logMsg, FILE_APPEND);
    echo nl2br($msg . "\n");
}

logMessage("Starting connection test...");

// Credentials from db_connect.php (User said they are correct)
$db_host = 'localhost';
$db_port = '3306';
$db_name = 'jaithra2026';
$db_user = 'jaithra2026';
$db_pass = 'Jaithra*2026';

logMessage("Attempting to connect to host: $db_host, db: $db_name, user: $db_user");

try {
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    $conn = new PDO(
        $dsn,
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    logMessage("Connection SUCCESSFUL!");
    
    // Test a query
    try {
        $stmt = $conn->query("SELECT 1");
        $stmt->fetchAll();
        logMessage("Query 'SELECT 1' execution SUCCESSFUL!");
        
        // Show tables
         $stmt = $conn->query("SHOW TABLES");
         $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
         logMessage("Tables found: " . implode(", ", $tables));

    } catch (Exception $e) {
        logMessage("Query execution failed: " . $e->getMessage());
    }

} catch (PDOException $e) {
    logMessage("Connection FAILED: " . $e->getMessage());
}
?>

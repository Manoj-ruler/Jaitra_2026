<?php
/**
 * Admin Logout - JAITRA 2026
 */
session_start();
session_destroy();
header('Location: login.php');
exit;
?>

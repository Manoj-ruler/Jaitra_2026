<?php
/**
 * API endpoint to increment page view count
 * Called via JavaScript when a new tab/session is detected
 */

header('Content-Type: application/json');

$viewCountFile = __DIR__ . '/../assets/page_views.json';

// Ensure the file exists
if (!file_exists($viewCountFile)) {
    file_put_contents($viewCountFile, json_encode(['index' => 0]));
}

// Read current count
$viewData = json_decode(file_get_contents($viewCountFile), true) ?: ['index' => 0];
$currentCount = $viewData['index'] ?? 0;

// Increment the count
$currentCount++;
$viewData['index'] = $currentCount;

// Save back to file
file_put_contents($viewCountFile, json_encode($viewData));

// Return the new count
echo json_encode(['success' => true, 'count' => $currentCount]);
?>

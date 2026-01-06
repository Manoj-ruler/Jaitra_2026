<?php
/**
 * Manage Live Streams - Admin
 */
require_once 'auth.php';
requireLogin();
require_once '../db_connect.php';

$username = getAdminUsername();

// Handle Form Submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $title = trim($_POST['title']);
            $url = trim($_POST['youtube_url']);
            
            if (!empty($title) && !empty($url)) {
                // Extract Video ID if full URL provided
                // Supports: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/live/ID
                $videoId = $url;
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
                    $videoId = $matches[1];
                }
                
                // Construct embed URL
                $embedUrl = "https://www.youtube.com/embed/" . $videoId; // Store ID and construct on fly? Or store full URL. Let's store full URL but ensure we can get ID.
                // storing just the input for now, but better to extract ID if possible or keep as is.
                // Requirements say "Admin to add URL".
                
                try {
                    $stmt = $conn->prepare("INSERT INTO featured_videos (title, youtube_url) VALUES (:title, :url)");
                    $stmt->execute(['title' => $title, 'url' => $url]);
                    header("Location: manage_streams.php?msg=Video added successfully");
                    exit;
                } catch (PDOException $e) {
                    $error = "Error adding video: " . $e->getMessage();
                }
            } else {
                $error = "Please provide both title and URL.";
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            try {
                $stmt = $conn->prepare("DELETE FROM featured_videos WHERE id = :id");
                $stmt->execute(['id' => $id]);
                header("Location: manage_streams.php?msg=Video removed successfully");
                exit;
            } catch (PDOException $e) {
                $error = "Error removing video: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'toggle') {
            $id = $_POST['id'];
            $currentStatus = $_POST['current_status'];
            $newStatus = $currentStatus ? 0 : 1;
            try {
                $stmt = $conn->prepare("UPDATE featured_videos SET is_active = :status WHERE id = :id");
                $stmt->execute(['status' => $newStatus, 'id' => $id]);
                header("Location: manage_streams.php?msg=Status updated");
                exit;
            } catch (PDOException $e) {
                $error = "Error updating status: " . $e->getMessage();
            }
        }
    }
}

// Fetch Videos
$videos = [];
try {
    $videos = $conn->query("SELECT * FROM featured_videos ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    $error = "Table 'featured_videos' not found. Please run the SQL migration.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Streams | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reusing Dashboard Styles for consistency */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #1a2332 0%, #2d3748 100%);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 1.5rem; font-weight: 700; }
        .header-actions { display: flex; gap: 1rem; align-items: center; }
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-block;
        }
        .btn-light { background: rgba(255,255,255,0.15); color: white; }
        .btn-light:hover { background: rgba(255,255,255,0.25); }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        
        .container { max-width: 1000px; margin: 2rem auto; padding: 0 1rem; }
        .card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-family: inherit;
        }
        .video-list { list-style: none; }
        .video-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .video-item:last-child { border-bottom: none; }
        .video-info h3 { font-size: 1rem; margin-bottom: 0.25rem; }
        .video-meta { font-size: 0.875rem; color: #6b7280; }
        .video-actions { display: flex; gap: 0.5rem; }
        
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            .header-actions {
                width: 100%;
                justify-content: center;
            }
            .video-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .video-actions {
                width: 100%;
                justify-content: flex-end;
            }
            .container {
                padding: 0 1rem;
                margin: 1rem auto;
            }
            .card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Manage Live Streams</h1>
        <div class="header-actions">
            <a href="dashboard.php" class="btn btn-light">← Back to Dashboard</a>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['err'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['err']) ?></div>
        <?php endif; ?>

        <!-- Add New Video -->
        <div class="card">
            <h2 style="margin-bottom: 1.5rem;">Add New Stream</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Video Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Day 1 - Morning Session" required>
                </div>
                <div class="form-group">
                    <label>YouTube URL</label>
                    <input type="text" name="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required>
                </div>
                <button type="submit" class="btn btn-primary">Add Video</button>
            </form>
        </div>

        <!-- Existing Videos -->
        <div class="card">
            <h2 style="margin-bottom: 1.5rem;">Existing Streams</h2>
            <?php if (empty($videos)): ?>
                <p style="color: #6b7280;">No videos added yet.</p>
            <?php else: ?>
                <ul class="video-list">
                    <?php foreach ($videos as $video): ?>
                        <li class="video-item" style="opacity: <?= $video['is_active'] ? '1' : '0.6' ?>">
                            <div class="video-info">
                                <h3><?= htmlspecialchars($video['title']) ?></h3>
                                <div class="video-meta">
                                    <a href="<?= htmlspecialchars($video['youtube_url']) ?>" target="_blank" style="color: #2563eb;"><?= htmlspecialchars($video['youtube_url']) ?></a>
                                    <span style="margin: 0 0.5rem;">•</span>
                                    <?= $video['is_active'] ? '<span style="color: green;">Active</span>' : '<span style="color: orange;">Inactive</span>' ?>
                                </div>
                            </div>
                            <div class="video-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?= $video['id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $video['is_active'] ?>">
                                    <button type="submit" class="btn btn-light" style="background: #e5e7eb; color: #374151;">
                                        <?= $video['is_active'] ? 'Disable' : 'Enable' ?>
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this video?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $video['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

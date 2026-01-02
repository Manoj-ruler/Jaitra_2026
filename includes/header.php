<?php
/**
 * JAITRA 2026 - Common Header Include
 * Include this at the top of every page after setting $pageTitle and $pageDescription
 * 
 * Required variables before including:
 * - $pageTitle (string): The page title
 * - $pageDescription (string): Meta description for the page
 * - $currentPage (string): Current page identifier for nav highlighting ('home', 'scoreboard', 'match')
 * 
 * Optional variables:
 * - $pageStyles (string): Additional inline CSS for the page
 * - $additionalCss (array): Additional CSS files to include
 */

// Set defaults if not provided
$pageTitle = $pageTitle ?? 'JAITRA 2026 | SRKR Engineering College';
$pageDescription = $pageDescription ?? 'JAITRA 2026 - The Ultimate Sports Carnival for All A.P. State Engineering Colleges.';
$currentPage = $currentPage ?? '';
$pageStyles = $pageStyles ?? '';
$additionalCss = $additionalCss ?? [];
$showNav = $showNav ?? true; // Set to false to hide navigation
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta name="keywords" content="JAITRA 2026, SRKR Engineering College, Sports Carnival, Engineering College Sports, AP State, Live Sports, Bhimavaram">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
    
    <?php foreach ($additionalCss as $cssFile): ?>
    <!-- Additional Page Styles -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($cssFile); ?>">
    <?php endforeach; ?>
    
    <?php if (!empty($pageStyles)): ?>
    <style>
        <?php echo $pageStyles; ?>
    </style>
    <?php endif; ?>
</head>

<body>
    <!-- Live YouTube Ticker -->
    <div class="live-ticker">
        <div class="ticker-content">
            <div class="ticker-item">
                Volleyball Finals - Watch Live:
                <a href="https://youtube.com/live/example1" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Kabaddi Semi-Finals - Watch Live:
                <a href="https://youtube.com/live/example2" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Badminton Quarters - Watch Live:
                <a href="https://youtube.com/live/example3" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Pickleball Opening Match - Watch Live:
                <a href="https://youtube.com/live/example4" target="_blank">YouTube Stream</a>
            </div>
            <!-- Duplicate for seamless loop -->
            <div class="ticker-item">
                Volleyball Finals - Watch Live:
                <a href="https://youtube.com/live/example1" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Kabaddi Semi-Finals - Watch Live:
                <a href="https://youtube.com/live/example2" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Badminton Quarters - Watch Live:
                <a href="https://youtube.com/live/example3" target="_blank">YouTube Stream</a>
            </div>
            <span class="ticker-separator">•</span>
            <div class="ticker-item">
                Pickleball Opening Match - Watch Live:
                <a href="https://youtube.com/live/example4" target="_blank">YouTube Stream</a>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-main">
            <div class="logo-section">
                <img src="assets/logo.png" alt="SRKR Engineering College Logo" class="logo"
                    onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2245%22 fill=%22%23384959%22/><text x=%2250%22 y=%2260%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2230%22 font-weight=%22bold%22>S</text></svg>'">
                <div class="brand-info">
                    <div class="brand-text">
                        <h1 class="event-name">JAITRA <span>2026</span></h1>
                        <span class="event-tagline">Sports Carnival for All A.P. State Engineering Colleges</span>
                    </div>
                </div>
            </div>
            <div class="header-info">
                <div class="event-dates">
                    <div class="dates">7, 8 & 9 January 2026</div>
                    <div class="venue">SRKR Marg, Bhimavaram</div>
                </div>
                <div class="prize-badge">
                    <div class="label">Prize Pool</div>
                    <div class="amount">₹5 Lakhs</div>
                </div>
            </div>
        </div>
    </header>

    <?php if ($showNav !== false): ?>
    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-container">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="scoreboard.php" class="nav-link <?php echo $currentPage === 'scoreboard' ? 'active' : ''; ?>" data-sport="all">
                        All Sports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="scoreboard.php?sport=volleyball" class="nav-link" data-sport="volleyball">
                        Volleyball
                    </a>
                </li>
                <li class="nav-item">
                    <a href="scoreboard.php?sport=kabaddi" class="nav-link" data-sport="kabaddi">
                        Kabaddi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="scoreboard.php?sport=badminton" class="nav-link" data-sport="badminton">
                        Badminton
                    </a>
                </li>
                <li class="nav-item">
                    <a href="scoreboard.php?sport=pickleball" class="nav-link" data-sport="pickleball">
                        Pickleball
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Main Content Starts Here -->

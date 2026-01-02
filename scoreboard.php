<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="JAITRA 2026 - Sports Carnival for All A.P. State Engineering Colleges. Live scores for Volleyball, Kabaddi, Badminton, and Pickleball.">
    <meta name="keywords"
        content="JAITRA 2026, SRKR Engineering College, Sports Carnival, Volleyball, Kabaddi, Badminton, Pickleball, Live Scores">
    <title>JAITRA 2026 | SRKR Engineering College - Sports Scoreboard</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
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

    <!-- Navigation -->
    <nav class="nav">
        <div class="nav-container">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link active" data-sport="all">
                        All Sports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-sport="volleyball">
                        Volleyball
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-sport="kabaddi">
                        Kabaddi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-sport="badminton">
                        Badminton
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-sport="pickleball">
                        Pickleball
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main">
        <!-- Filters Section -->
        <section class="filters-section">
            <div class="filters-container">
                <div class="filter-group">
                    <span class="filter-label">Match Status</span>
                    <div class="filter-buttons">
                        <button class="filter-btn active" data-status="all">
                            All
                        </button>
                        <button class="filter-btn" data-status="live">
                            Live
                        </button>
                        <button class="filter-btn" data-status="upcoming">
                            Upcoming
                        </button>
                        <button class="filter-btn" data-status="completed">
                            Completed
                        </button>
                    </div>
                </div>
                <div class="filter-group">
                    <span class="filter-label">Sport Category</span>
                    <div class="filter-buttons">
                        <button class="filter-btn active" data-sport="all">
                            All Sports
                        </button>
                        <button class="filter-btn" data-sport="volleyball">
                            Volleyball
                        </button>
                        <button class="filter-btn" data-sport="kabaddi">
                            Kabaddi
                        </button>
                        <button class="filter-btn" data-sport="badminton">
                            Badminton
                        </button>
                        <button class="filter-btn" data-sport="pickleball">
                            Pickleball
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Scorecards Section -->
        <section class="scorecards-section">
            <div class="section-header">
                <h2 class="section-title">
                    Match Scoreboard
                </h2>
                <span class="match-count">12 matches found</span>
            </div>

            <div class="scorecards-grid">
                <!-- Matches load dynamically from API -->
                <div class="loading-state"
                    style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #6b7280;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">⏳</div>
                    <p>Loading matches...</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="event-name">JAITRA 2026</div>
                <div class="college-name">SRKR Engineering College (A), Bhimavaram</div>
            </div>
            <div class="footer-links">
                <a href="index.php" class="footer-link">Home</a>
                <a href="scoreboard.php" class="footer-link">Scoreboard</a>
                <a href="#" class="footer-link">Schedule</a>
                <a href="#" class="footer-link">Results</a>
                <a href="#" class="footer-link">Contact</a>
            </div>
            <div class="footer-social">
                <a href="#" class="social-link" aria-label="Facebook">
                    <span>f</span>
                </a>
                <a href="#" class="social-link" aria-label="Twitter">
                    <span>t</span>
                </a>
                <a href="#" class="social-link" aria-label="Instagram">
                    <span>i</span>
                </a>
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 SRKR Engineering College. All rights reserved. | Men & Women Categories
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/app.js"></script>
</body>

</html>
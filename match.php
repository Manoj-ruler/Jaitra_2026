<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Match Details - JAITRA 2026 Sports Carnival">
    <title>Match Details | JAITRA 2026 - SRKR Engineering College</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
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

    <!-- Main Content -->
    <main class="main match-detail-page">
        <!-- Breadcrumb & Back Button -->
        <div class="breadcrumb-section">
            <a href="scoreboard.php" class="back-button">← Back to Scoreboard</a>
            <div class="breadcrumb" id="breadcrumb">
                <a href="index.php">Home</a>
                <span class="separator">›</span>
                <span id="breadcrumb-sport">Loading...</span>
                <span class="separator">›</span>
                <span id="breadcrumb-match">Match Details</span>
            </div>
        </div>

        <!-- Match Hero Section -->
        <section class="match-hero">
            <div class="match-hero-header">
                <span class="sport-badge" id="hero-sport-badge">Loading...</span>
                <span class="status-badge" id="hero-status-badge">Loading...</span>
            </div>
            
            <div class="match-teams-display">
                <div class="team-display team-a">
                    <div class="team-winner-indicator" id="team-a-winner"></div>
                    <h2 class="team-display-name" id="team-a-name">Team A</h2>
                    
                    <div class="team-meta">
                        <div class="raiding-indicator" id="team-a-raid">RAIDING</div>
                        <div class="player-icons" id="team-a-players-container">
                            <!-- Icons injected by JS -->
                        </div>
                    </div>

                    <div class="team-display-score" id="team-a-score">0</div>
                </div>
                
                <div class="vs-large">VS</div>
                
                <div class="team-display team-b">
                    <div class="team-winner-indicator" id="team-b-winner"></div>
                    <h2 class="team-display-name" id="team-b-name">Team B</h2>

                    <div class="team-meta">
                        <div class="raiding-indicator" id="team-b-raid">RAIDING</div>
                        <div class="player-icons" id="team-b-players-container">
                            <!-- Icons injected by JS -->
                        </div>
                    </div>

                    <div class="team-display-score" id="team-b-score">0</div>
                </div>
            </div>

            <div class="match-sets-breakdown" id="sets-breakdown">
                <!-- Sets/Games breakdown will be inserted here -->
            </div>

            <div class="match-actions">
                <a href="#" class="action-btn primary" id="watch-live-btn" style="display: none;">
                    🔴 Watch Live
                </a>
                <a href="#" class="action-btn secondary" id="watch-replay-btn" style="display: none;">
                    📺 Watch Replay
                </a>
                <button class="action-btn secondary" id="share-btn">
                    🔗 Share Match
                </button>
            </div>
        </section>

        <!-- Bottom Reaction Popup -->
        <div class="reaction-popup">
            <button class="reaction-btn" data-emoji="🔥">
                <span class="emoji">🔥</span>
            </button>
            <button class="reaction-btn" data-emoji="❤️">
                <span class="emoji">❤️</span>
            </button>
            <button class="reaction-btn" data-emoji="👏">
                <span class="emoji">👏</span>
            </button>
        </div>

        <!-- Floating emojis container -->
        <div class="floating-emojis-container" id="floating-emojis"></div>

        <!-- Match Status -->
        <section class="match-status-section">
            <p class="match-status-text" id="info-status">Loading...</p>
        </section>

        <!-- Similar Matches Section -->
        <section class="similar-matches-section">
            <h3 class="section-subtitle">More Matches You Might Like</h3>
            
            <div class="similar-matches-container">
                <!-- Same Sport -->
                <div class="similar-category">
                    <h4 class="category-title" id="same-sport-title">More from This Sport</h4>
                    <div class="similar-cards-grid" id="same-sport-matches">
                        <!-- Match cards will be inserted here -->
                    </div>
                </div>

                <!-- Same Teams -->
                <div class="similar-category">
                    <h4 class="category-title">Featuring These Teams</h4>
                    <div class="similar-cards-grid" id="same-team-matches">
                        <!-- Match cards will be inserted here -->
                    </div>
                </div>

                <!-- Live Matches -->
                <div class="similar-category">
                    <h4 class="category-title">🔴 Live Now</h4>
                    <div class="similar-cards-grid" id="live-matches">
                        <!-- Match cards will be inserted here -->
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>JAITRA 2026</h3>
                <p>Sports Carnival for All A.P. State Engineering Colleges</p>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p>SRKR Engineering College</p>
                <p>Bhimavaram, Andhra Pradesh</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#volleyball">Volleyball</a></li>
                    <li><a href="index.php#kabaddi">Kabaddi</a></li>
                    <li><a href="index.php#badminton">Badminton</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 SRKR Engineering College. All rights reserved.</p>
        </div>
    </footer>

    <!-- TIMEOUT OVERLAY -->
    <div id="timeout-overlay" class="animation-overlay" style="display: none;">
        <h1 class="timeout-title">⏱️ TIMEOUT</h1>
        <div class="timeout-scoreboard">
            <div class="timeout-team">
                <div class="timeout-team-name" id="to-t1-name">Team 1</div>
                <div class="timeout-team-score" id="to-t1-score">0</div>
            </div>
            <div class="timeout-divider">:</div>
            <div class="timeout-team">
                <div class="timeout-team-name" id="to-t2-name">Team 2</div>
                <div class="timeout-team-score" id="to-t2-score">0</div>
            </div>
        </div>
    </div>

    <!-- SUPER RAID / ANIMATION OVERLAY -->
    <div id="animation-overlay" class="animation-overlay gamified-overlay" style="display: none;">
        <div class="gamified-bg"></div>
        <div id="anim-content" class="anim-content gamified-content">
            <div class="anim-icon" id="anim-icon">🔥</div>
            <h1 id="anim-text" class="anim-text gamified-text">SUPER RAID</h1>
            <h2 id="anim-team" class="anim-team gamified-team">Team Name</h2>
        </div>
    </div>

    <!-- Animation Styles -->
    <style>
        /* Base Overlay */
        .animation-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
        }
        
        #timeout-overlay {
            background: rgba(0, 0, 0, 0.95);
        }

        /* Gamified Animation Overlay */
        .gamified-overlay {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent base */
            overflow: hidden;
        }

        .gamified-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120%;
            height: 500px;
            background: radial-gradient(circle, rgba(220,38,38,0.4) 0%, rgba(0,0,0,0) 70%);
            z-index: -1;
            animation: pulse-bg 2s infinite alternate;
        }

        .gamified-content {
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .gamified-text {
            font-size: 6rem;
            font-weight: 900;
            text-transform: uppercase;
            color: #fff;
            text-shadow: 
                0 0 10px rgba(251, 191, 36, 0.8),
                0 0 20px rgba(251, 191, 36, 0.6),
                0 0 40px rgba(220, 38, 38, 0.6),
                4px 4px 0 #000;
            margin: 0;
            line-height: 1;
            transform: skew(-10deg);
            animation: slam-in 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        .gamified-team {
            font-size: 2.5rem;
            color: #fbbf24;
            font-weight: 800;
            text-transform: uppercase;
            margin-top: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
            letter-spacing: 2px;
            opacity: 0;
            animation: slide-up-fade 0.5s 0.4s forwards;
        }

        .anim-icon {
            font-size: 5rem;
            margin-bottom: 0.5rem;
            animation: bounce-icon 1s infinite alternate;
            display: block;
        }
        
        /* Keyframes */
        @keyframes slam-in {
            0% { transform: scale(3) skew(-10deg); opacity: 0; }
            100% { transform: scale(1) skew(-10deg); opacity: 1; }
        }

        @keyframes slide-up-fade {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes pulse-bg {
            from { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            to { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
        }

        @keyframes bounce-icon {
            from { transform: translateY(0); }
            to { transform: translateY(-15px); }
        }

        /* Timeout Specifics (keep existing but refined) */
        .timeout-title {
            font-size: 4rem;
            font-weight: bold;
            color: #fbbf24;
            letter-spacing: 5px;
            margin-bottom: 2rem;
            animation: pulse-text 1s infinite;
        }
        
        @keyframes pulse-text {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        
        .timeout-scoreboard {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .timeout-team { text-align: center; }
        .timeout-team-name { font-size: 1.5rem; color: white; font-weight: bold; margin-bottom: 0.5rem; }
        .timeout-team-score { font-size: 5rem; color: #3b82f6; font-weight: bold; }
        .timeout-divider { font-size: 4rem; color: white; opacity: 0.5; }

        /* Icon Player Stats */
        .player-icons {
            display: flex;
            gap: 6px;
            background: rgba(0,0,0,0.3);
            padding: 8px 12px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }
        
        .player-icon {
            font-size: 1.4rem;
            color: rgba(255,255,255,0.2); /* Inactive color */
            transition: all 0.3s ease;
        }
        
        .player-icon.active {
            color: #3b82f6; /* Active blue color */
            filter: drop-shadow(0 0 5px rgba(59, 130, 246, 0.5));
            transform: scale(1.1);
        }

        /* Team Meta Layout */
        .team-meta {
            display: flex;
            flex-direction: column; /* Stack raid label and players */
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.8rem;
        }

        /* Indicators */
        .status-badge.live {
            background-color: #ef4444;
            color: white;
            animation: blinkLive 1.5s infinite;
        }
        @keyframes blinkLive {
            0% { opacity: 1; box-shadow: 0 0 5px #ef4444; }
            50% { opacity: 0.6; box-shadow: 0 0 15px #ef4444; }
            100% { opacity: 1; box-shadow: 0 0 5px #ef4444; }
        }

        .raiding-indicator {
            background: #f59e0b;
            color: #000;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            text-transform: uppercase;
            animation: pulse-raid 1s infinite;
            display: none; 
            margin-bottom: 0.2rem;
        }
        @keyframes pulse-raid {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        @media (max-width: 768px) {
            .gamified-text { font-size: 3rem; }
            .gamified-team { font-size: 1.5rem; }
            .player-icon { font-size: 1.1rem; }
            .timeout-title { font-size: 2.5rem; }
            .timeout-team-score { font-size: 3rem; }
        }
    </style>

    <!-- Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    
    <!-- JavaScript -->
    <script src="js/match-details.js"></script>
</body>
</html>

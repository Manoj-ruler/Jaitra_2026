<?php
/**
 * JAITRA 2026 - Match Details Page
 * Displays detailed information about a specific match
 */

// Page configuration for header include
$pageTitle = 'Match Details | JAITRA 2026 - SRKR Engineering College';
$pageDescription = 'Match Details - JAITRA 2026 Sports Carnival';
$currentPage = 'match';
$showNav = false; // Hide navbar on match details page

// Page-specific styles
$pageStyles = '
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
    }
    #timeout-overlay {
        background: rgba(0, 0, 0, 0.95);
    }
    .timeout-title {
        font-size: 4rem;
        font-weight: bold;
        color: #fbbf24;
        letter-spacing: 5px;
        margin-bottom: 2rem;
        animation: pulse 1s infinite;
    }
    .timeout-scoreboard {
        display: flex;
        align-items: center;
        gap: 2rem;
    }
    .timeout-team {
        text-align: center;
    }
    .timeout-team-name {
        font-size: 1.5rem;
        color: white;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .timeout-team-score {
        font-size: 5rem;
        color: #3b82f6;
        font-weight: bold;
    }
    .timeout-divider {
        font-size: 4rem;
        color: white;
        opacity: 0.5;
    }
    
    #animation-overlay {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    }
    .anim-content {
        text-align: center;
        animation: bounceIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    .anim-text {
        font-size: 5rem;
        font-weight: bold;
        color: #fbbf24;
        text-shadow: 4px 4px 0 rgba(0,0,0,0.3);
        margin: 0;
        animation: shake 0.5s ease-in-out;
    }
    .anim-bar {
        height: 8px;
        background: #fbbf24;
        margin: 1rem auto;
        border-radius: 4px;
        animation: expandBar 0.5s ease-out forwards;
    }
    .anim-team {
        font-size: 2rem;
        color: white;
        font-weight: bold;
        letter-spacing: 3px;
        margin: 0;
        opacity: 0;
        animation: fadeInUp 0.5s 0.3s forwards;
    }
    
    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    @keyframes expandBar {
        from { width: 0; }
        to { width: 300px; }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
        .timeout-title { font-size: 2.5rem; }
        .timeout-team-score { font-size: 3rem; }
        .anim-text { font-size: 2.5rem; }
        .anim-team { font-size: 1.2rem; }
    }
    
    .timeout-btn {
        margin-top: 3rem;
        padding: 1rem 2.5rem;
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .timeout-btn:hover {
        background: white;
        color: #000;
        border-color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    /* Kabaddi Specific Styles */
    .raiding-indicator {
        font-size: 0.9rem;
        color: #fbbf24;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
        animation: pulse 1s infinite;
        display: none; /* Hidden by default */
    }
    .player-icons-container {
        display: flex;
        gap: 4px;
        justify-content: center;
        margin-top: 0.5rem;
        height: 20px;
    }
    .player-icon {
        font-size: 0.8rem;
        color: #ef4444; /* Red for out players */
        opacity: 0.7;
    }
    .player-icon.active {
        color: #22c55e; /* Green for present players */
        opacity: 1;
    }
';

// Include the common header
include 'includes/header.php';
?>

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
                <div class="badge-group">
                    <span class="sport-badge" id="hero-sport-badge">Loading...</span>
                    <span class="category-badge" id="hero-category-badge" style="display: none;"></span>
                </div>
                <span class="status-badge" id="hero-status-badge">Loading...</span>
            </div>
            
            <div class="match-teams-display">
                <div class="team-display team-a">
                    <div class="team-winner-indicator" id="team-a-winner"></div>
                    <h2 class="team-display-name" id="team-a-name">Team A</h2>
                    <div class="team-display-score" id="team-a-score">0</div>
                    <div class="raiding-indicator" id="team-a-raid"><i class="fas fa-running me-1"></i> Raiding</div>
                    <div class="player-icons-container" id="team-a-players-container"></div>
                </div>
                
                <div class="vs-large">VS</div>
                
                <div class="team-display team-b">
                    <div class="team-winner-indicator" id="team-b-winner"></div>
                    <h2 class="team-display-name" id="team-b-name">Team B</h2>
                    <div class="team-display-score" id="team-b-score">0</div>
                    <div class="raiding-indicator" id="team-b-raid"><i class="fas fa-running me-1"></i> Raiding</div>
                    <div class="player-icons-container" id="team-b-players-container"></div>
                </div>
            </div>

            <div class="match-sets-breakdown" id="sets-breakdown"></div>

            <!-- Match Status (inside card) -->
            <div class="hero-match-status">
                <p class="hero-status-text" id="info-status">Loading...</p>
            </div>

            <!-- JAITRA Branding -->
            <div class="hero-branding">
                <img src="assets/favicon.png" alt="JAITRA" class="hero-branding-logo">
                <span class="hero-branding-text">JAITRA 2026</span>
            </div>

            <div class="match-actions">
                <a href="#" class="action-btn primary" id="watch-live-btn" style="display: none;">
                    🔴 Watch Live
                </a>
                <a href="#" class="action-btn secondary" id="watch-replay-btn" style="display: none;">
                    📺 Watch Replay
                </a>
                <button class="action-btn secondary" id="share-btn">
                    🔗 Share Match Score
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
        
        <a href="scoreboard.php" class="timeout-btn">
            <i class="fas fa-th-large me-2"></i> View Other Matches
        </a>
    </div>

    <!-- SUPER RAID / ANIMATION OVERLAY -->
    <div id="animation-overlay" class="animation-overlay" style="display: none;">
        <div id="anim-content" class="anim-content">
            <h1 id="anim-text" class="anim-text">SUPER RAID</h1>
            <div id="anim-bar" class="anim-bar"></div>
            <h2 id="anim-team" class="anim-team">Team Name</h2>
        </div>
    </div>

<?php
// Footer configuration - use custom JS instead of app.js
$includeAppJs = false;
$customScripts = '
    <!-- Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    
    <!-- Match Details JavaScript -->
    <script src="js/match-details.js"></script>
';

// Include the common footer
include 'includes/footer.php';
?>

<?php
/**
 * JAITRA 2026 - Home Page
 * Main landing page for the sports carnival website
 */

require_once 'db_connect.php';

// ==========================================
// PAGE VIEW COUNTER - Read current count (File-based)
// Incrementing is handled by JavaScript using sessionStorage
// ==========================================
$pageViewCount = 0;
$viewCountFile = __DIR__ . '/assets/page_views.json';

// Ensure the file exists
if (!file_exists($viewCountFile)) {
    file_put_contents($viewCountFile, json_encode(['index' => 0]));
}

// Read current count
$viewData = json_decode(file_get_contents($viewCountFile), true) ?: ['index' => 0];
$pageViewCount = $viewData['index'] ?? 0;

// Page configuration for header include
$pageTitle = 'JAITRA 2026 | Home - AP\'s Premier Engineering Sports Carnival';
$pageDescription = 'JAITRA 2026 - The Ultimate Sports Carnival for All A.P. State Engineering Colleges. Experience thrilling competitions in Volleyball, Kabaddi, Badminton, and Pickleball with ₹5 Lakhs prize pool.';
$currentPage = 'home';
$additionalCss = ['css/home-styles.css?v=' . time()]; // Home page specific styles
$showNav = false; // Home page uses carousel instead of nav


// Include the common header
include 'includes/header.php';
?>

    <!-- Image Carousel Section -->
    <section class="carousel-section">
        <div class="carousel-container">
            <div class="carousel-slides" id="carouselSlides">
                <!-- Hero Slide -->
                <div class="carousel-slide hero-slide">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <span class="live-dot"></span>
                            <span id="live-count">0</span> Matches Live Now
                        </div>
                        
                        <h2 class="hero-title">
                            <span class="highlight">JAITRA 2026</span><br>
                            AP's Premier Engineering Sports Carnival
                        </h2>
                        
                        <p class="hero-subtitle">
                            Experience the ultimate sports showdown featuring Volleyball, Kabaddi, Badminton, and Pickleball. 
                            Compete for glory and a massive ₹5 Lakhs prize pool!
                        </p>
                        
                        <div class="hero-cta">
                            <a href="scoreboard.php" class="cta-btn cta-btn-primary">
                                📊 View Scores
                            </a>
                            <a href="https://youtube.com/@bhimavaramdigitals?si=LcJdYz2ghJrP-nVa" class="cta-btn cta-btn-secondary" id="liveMatchesBtn" target="_blank">
                                🎥 View Live Matches
                            </a>
                        </div>
                        
                        <div class="countdown-timer" id="countdown">
                            <div class="countdown-item">
                                <span class="countdown-value" id="days">07</span>
                                <span class="countdown-label">Days</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="hours">00</span>
                                <span class="countdown-label">Hours</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="minutes">00</span>
                                <span class="countdown-label">Minutes</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="seconds">00</span>
                                <span class="countdown-label">Seconds</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Poster Slides -->
                <div class="carousel-slide">
                    <img src="assets/home-1.jpeg" alt="JAITRA 2026 - Sports Carnival Poster"
                        onerror="this.src='https://via.placeholder.com/1200x700/1a2332/ffffff?text=JAITRA+2026+-+Sports+Carnival'">
                </div>
                <div class="carousel-slide">
                    <img src="assets/home-2.jpeg" alt="JAITRA 2026 - Event Details"
                        onerror="this.src='https://via.placeholder.com/1200x700/2563eb/ffffff?text=₹5+Lakhs+Prize+Pool'">
                </div>
                <div class="carousel-slide">
                    <img src="assets/home-3.jpeg" alt="JAITRA 2026 - Registration"
                        onerror="this.src='https://via.placeholder.com/1200x700/6366f1/ffffff?text=Register+Now+-+All+AP+Colleges'">
                </div>
            </div>
            
            <button class="carousel-nav prev" onclick="moveSlide(-1)">‹</button>
            <button class="carousel-nav next" onclick="moveSlide(1)">›</button>
            
            <div class="carousel-indicators" id="carouselIndicators"></div>
        </div>
    </section>

    <!-- Live Scoreboard Section -->
    <section class="scoreboard-section">
        <div class="scoreboard-container">
            <!-- Upcoming Matches Section -->
            <?php
            // Fetch upcoming matches
            $upcomingQuery = "SELECT m.*, s.name as sport_name, 
                           t1.name as team1_name, t1.college_name as team1_college,
                           t2.name as team2_name, t2.college_name as team2_college
                           FROM matches m
                           JOIN sports s ON m.sport_id = s.id
                           JOIN teams t1 ON m.team1_id = t1.id
                           JOIN teams t2 ON m.team2_id = t2.id
                           WHERE m.status = 'upcoming'
                           ORDER BY m.match_time ASC
                           LIMIT 10";
            $upcomingStmt = $conn->query($upcomingQuery);
            $upcomingMatches = $upcomingStmt->fetchAll();
            
            if (!empty($upcomingMatches)):
            ?>
            <div class="scoreboard-header" style="margin-bottom: 1.5rem;">
                <h2>📅 Upcoming Matches</h2>
                <p class="scoreboard-subtitle">Scheduled matches - stay tuned!</p>
            </div>
            
            <div class="matches-carousel-container" style="margin-bottom: 3rem;">
                <button class="matches-nav prev upcoming-prev" aria-label="Previous upcoming matches">‹</button>
                <div class="matches-scroll upcoming-matches-scroll">
                    <?php foreach ($upcomingMatches as $match): 
                        $matchDate = new DateTime($match['match_time']);
                        $dateStr = $matchDate->format('M j, Y');
                        $timeStr = $matchDate->format('g:i A');
                    ?>
                        <article class="scorecard" data-status="upcoming" data-sport="<?= strtolower($match['sport_name']) ?>">
                            <div class="scorecard-header">
                                <div class="badge-group">
                                    <span class="sport-badge <?= strtolower($match['sport_name']) ?>"><?= ucfirst($match['sport_name']) ?></span>
                                </div>
                                <span class="status-badge upcoming">Upcoming</span>
                            </div>
                            <div class="scorecard-body">
                                <div class="teams-container">
                                    <div class="team-row">
                                        <div class="team-info">
                                            <span class="team-name"><?= htmlspecialchars($match['team1_name']) ?></span>
                                            <span class="team-college"><?= htmlspecialchars($match['team1_college']) ?></span>
                                        </div>
                                        <div class="team-score">
                                            <span class="score-main">-</span>
                                        </div>
                                    </div>
                                    <div class="vs-divider">VS</div>
                                    <div class="team-row">
                                        <div class="team-info">
                                            <span class="team-name"><?= htmlspecialchars($match['team2_name']) ?></span>
                                            <span class="team-college"><?= htmlspecialchars($match['team2_college']) ?></span>
                                        </div>
                                        <div class="team-score">
                                            <span class="score-main">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="scorecard-footer">
                                <div class="match-result">
                                    <?= htmlspecialchars($match['round'] ?: 'Match') ?>
                                </div>
                                <div class="match-time" style="font-weight: 600; color: #2563eb;">
                                    📅 <?= $dateStr ?> • <?= $timeStr ?>
                                </div>
                                <?php if ($match['venue']): ?>
                                <div class="match-venue" style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                                    📍 <?= htmlspecialchars($match['venue']) ?>
                                </div>
                                <?php endif; ?>
                                <div class="scorecard-branding">
                                    <img src="assets/favicon.png" alt="JAITRA" class="branding-logo">
                                    <span class="branding-text">JAITRA 2026</span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <button class="matches-nav next upcoming-next" aria-label="Next upcoming matches">›</button>
            </div>
            <?php endif; ?>
            
            <div class="scoreboard-header">
                <h2>🏆 Live Matches & Results</h2>
                <p class="scoreboard-subtitle">Follow all the action in real-time</p>
            </div>

            <!-- All Matches in Single Scroll -->
            <div class="matches-carousel-container">
                <button class="matches-nav prev" aria-label="Previous matches">‹</button>
                <div class="matches-scroll" id="all-matches">
                    <p class="loading-text">Loading matches...</p>
                </div>
                <button class="matches-nav next" aria-label="Next matches">›</button>
            </div>
        </div>
    </section>

    <!-- About SRKR College Section -->
    <div class="host-section">
        <h3>About SRKR College</h3>
        <p class="section-subtitle">Welcome to SRKREC!</p>
        
        <div class="host-content-wrapper">
            <div class="founder-image">
                <div class="founder-frame">
                    <img src="assets/srkr-founder.jpg" alt="SRKR Founder" class="founder-photo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="founder-placeholder" style="display: none;">🎓</div>
                </div>
                <p class="founder-label">Founder's Vision</p>
            </div>
            
            <div class="host-text-content">
                <div class="host-description">
                    <p class="highlight-text">
                        Sagi Rama Krishnam Raju Engineering College, established in 1980, is one of the earliest 
                        self-financing Engineering Colleges in the state of Andhra Pradesh.
                    </p>
                    
                    <p>
                        Established with a noble cause to empower rural students through technical education, 
                        the institution today has evolved as one of the pioneering technical institutions in the country.
                    </p>
                    
                    <p>
                        Spreading over 30 acres of green land, the institution has set in state-of-the-art facilities 
                        for science and technology and created a conducive environment for inclusive and culturally 
                        responsive teaching-learning process.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sports Categories -->
    <section class="sports-section" id="sports">
        <h2 class="section-title-center">Featured Sports</h2>
        
        <?php
        // Fetch dynamic stats for sports
        require_once 'db_connect.php';

        // Initialize default stats
        $stats = [
            'volleyball' => ['matches' => 0, 'live' => 0],
            'kabaddi' => ['matches' => 0, 'live' => 0],
            'badminton' => ['matches' => 0, 'live' => 0],
            'pickleball' => ['matches' => 0, 'live' => 0]
        ];

        try {
            // Get total match counts
            $matchesQuery = "SELECT s.name as sport_name, COUNT(m.id) as match_count 
                           FROM matches m 
                           JOIN sports s ON m.sport_id = s.id 
                           GROUP BY s.name";
            $stmt = $conn->query($matchesQuery);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sport = strtolower($row['sport_name']);
                if (isset($stats[$sport])) {
                    $stats[$sport]['matches'] = $row['match_count'];
                }
            }

            // Get live match counts
            $liveQuery = "SELECT s.name as sport_name, COUNT(m.id) as live_count 
                         FROM matches m 
                         JOIN sports s ON m.sport_id = s.id 
                         WHERE m.status = 'live' 
                         GROUP BY s.name";
            $stmt = $conn->query($liveQuery);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $sport = strtolower($row['sport_name']);
                if (isset($stats[$sport])) {
                    $stats[$sport]['live'] = $row['live_count'];
                }
            }
        } catch (PDOException $e) {
            // Silently fail to defaults if DB error
            error_log("Stats Error: " . $e->getMessage());
        }
        ?>

        <div class="sports-grid">
            <!-- Volleyball -->
            <div class="sport-card volleyball">
                <div class="sport-icon">🏐</div>
                <h3 class="sport-name">Volleyball</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['volleyball']['matches'] ?></span>
                        <span class="stat-label">Matches</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['volleyball']['live'] ?></span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=volleyball" class="sport-btn">View Matches →</a>
            </div>
            
            <!-- Kabaddi -->
            <div class="sport-card kabaddi">
                <div class="sport-icon">🤼</div>
                <h3 class="sport-name">Kabaddi</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['kabaddi']['matches'] ?></span>
                        <span class="stat-label">Matches</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['kabaddi']['live'] ?></span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=kabaddi" class="sport-btn">View Matches →</a>
            </div>
            
            <!-- Badminton -->
            <div class="sport-card badminton">
                <div class="sport-icon">🏸</div>
                <h3 class="sport-name">Badminton</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['badminton']['matches'] ?></span>
                        <span class="stat-label">Matches</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['badminton']['live'] ?></span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=badminton" class="sport-btn">View Matches →</a>
            </div>
            
            <!-- Pickleball -->
            <div class="sport-card pickleball">
                <div class="sport-icon">🎾</div>
                <h3 class="sport-name">Pickleball</h3>
                <div class="sport-stats">
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['pickleball']['matches'] ?></span>
                        <span class="stat-label">Matches</span>
                    </div>
                    <div class="sport-stat">
                        <span class="stat-value"><?= $stats['pickleball']['live'] ?></span>
                        <span class="stat-label">Live</span>
                    </div>
                </div>
                <a href="scoreboard.php?sport=pickleball" class="sport-btn">View Matches →</a>
            </div>
        </div>
    </section>

<?php
// Custom scripts for home page (countdown, carousel)
$customScripts = '
    <script>
        // Countdown Timer
        function updateCountdown() {
            const eventDate = new Date(\'2026-01-07T00:00:00\').getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById(\'days\').textContent = String(days).padStart(2, \'0\');
            document.getElementById(\'hours\').textContent = String(hours).padStart(2, \'0\');
            document.getElementById(\'minutes\').textContent = String(minutes).padStart(2, \'0\');
            document.getElementById(\'seconds\').textContent = String(seconds).padStart(2, \'0\');

            if (distance < 0) {
                document.getElementById(\'countdown\').innerHTML = \'<h3>Event is Live!</h3>\';
            }
        }

        // Update countdown every second
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // Smooth scroll for anchor links
        document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
            anchor.addEventListener(\'click\', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute(\'href\'));
                if (target) {
                    target.scrollIntoView({
                        behavior: \'smooth\',
                        block: \'start\'
                    });
                }
            });
        });

        // ===== IMAGE CAROUSEL =====
        let currentSlide = 0;
        const slides = document.querySelectorAll(\'.carousel-slide\');
        const totalSlides = slides.length;
        const indicatorsContainer = document.getElementById(\'carouselIndicators\');
        let autoScrollInterval;

        // Create indicators
        for (let i = 0; i < totalSlides; i++) {
            const indicator = document.createElement(\'div\');
            indicator.className = \'carousel-indicator\' + (i === 0 ? \' active\' : \'\');
            indicator.onclick = () => goToSlide(i);
            indicatorsContainer.appendChild(indicator);
        }

        function moveSlide(direction) {
            currentSlide += direction;
            if (currentSlide >= totalSlides) currentSlide = 0;
            if (currentSlide < 0) currentSlide = totalSlides - 1;
            updateCarousel();
            resetAutoScroll();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
            resetAutoScroll();
        }

        function updateCarousel() {
            const slidesContainer = document.getElementById(\'carouselSlides\');
            slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            // Update indicators
            const indicators = document.querySelectorAll(\'.carousel-indicator\');
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle(\'active\', index === currentSlide);
            });
        }

        function resetAutoScroll() {
            // Clear existing interval
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
            }
            // Start new interval
            autoScrollInterval = setInterval(() => {
                moveSlide(1);
            }, 5000);
        }

        // Start auto-scroll carousel every 5 seconds
        resetAutoScroll();

        // ===== UPCOMING MATCHES SCROLL NAVIGATION =====
        const upcomingScrollContainer = document.querySelector(\'.upcoming-matches-scroll\');
        const upcomingPrevBtn = document.querySelector(\'.upcoming-prev\');
        const upcomingNextBtn = document.querySelector(\'.upcoming-next\');

        if (upcomingScrollContainer && upcomingPrevBtn && upcomingNextBtn) {
            const scrollAmount = 350;

            upcomingPrevBtn.addEventListener(\'click\', () => {
                upcomingScrollContainer.scrollBy({ left: -scrollAmount, behavior: \'smooth\' });
            });

            upcomingNextBtn.addEventListener(\'click\', () => {
                upcomingScrollContainer.scrollBy({ left: scrollAmount, behavior: \'smooth\' });
            });

            const updateUpcomingButtonStates = () => {
                const scrollLeft = Math.ceil(upcomingScrollContainer.scrollLeft);
                const scrollWidth = Math.floor(upcomingScrollContainer.scrollWidth);
                const clientWidth = Math.floor(upcomingScrollContainer.clientWidth);
                const maxScroll = scrollWidth - clientWidth;
                const tolerance = 5;

                if (maxScroll <= tolerance) {
                    upcomingPrevBtn.classList.add(\'disabled\');
                    upcomingNextBtn.classList.add(\'disabled\');
                    upcomingPrevBtn.style.opacity = \'0\';
                    upcomingNextBtn.style.opacity = \'0\';
                    return;
                } else {
                    upcomingPrevBtn.style.opacity = \'1\';
                    upcomingNextBtn.style.opacity = \'1\';
                }

                if (scrollLeft <= tolerance) {
                    upcomingPrevBtn.classList.add(\'disabled\');
                    upcomingPrevBtn.setAttribute(\'disabled\', \'true\');
                } else {
                    upcomingPrevBtn.classList.remove(\'disabled\');
                    upcomingPrevBtn.removeAttribute(\'disabled\');
                }

                if (scrollLeft >= maxScroll - tolerance) {
                    upcomingNextBtn.classList.add(\'disabled\');
                    upcomingNextBtn.setAttribute(\'disabled\', \'true\');
                } else {
                    upcomingNextBtn.classList.remove(\'disabled\');
                    upcomingNextBtn.removeAttribute(\'disabled\');
                }
            };

            upcomingScrollContainer.addEventListener(\'scroll\', updateUpcomingButtonStates);
            window.addEventListener(\'resize\', updateUpcomingButtonStates);
            setTimeout(updateUpcomingButtonStates, 100);
            setTimeout(updateUpcomingButtonStates, 1000);
        }
    </script>
';
?>

    <!-- Page View Counter Section -->
    <section class="page-view-section">
        <div class="view-counter-container">
            <h3 class="visitors-title">Visitors</h3>
            <div class="digit-badges" id="visitorDigits">
                <?php
                // Pad the count to at least 4 digits for display
                $countStr = str_pad($pageViewCount, 4, '0', STR_PAD_LEFT);
                for ($i = 0; $i < strlen($countStr); $i++):
                ?>
                <div class="digit-badge">
                    <span class="digit"><?= $countStr[$i] ?></span>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <style>
        /* Page View Counter Styles - Starburst Badge Design */
        .page-view-section {
            background: #f5f5f5;
            padding: 2rem 1rem;
            border-top: 1px solid #ddd;
        }
        
        .view-counter-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        
        .visitors-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .digit-badges {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .digit-badge {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            clip-path: polygon(
                50% 0%, 61% 10%, 75% 5%, 80% 20%, 95% 25%, 90% 40%,
                100% 50%, 90% 60%, 95% 75%, 80% 80%, 75% 95%, 61% 90%,
                50% 100%, 39% 90%, 25% 95%, 20% 80%, 5% 75%, 10% 60%,
                0% 50%, 10% 40%, 5% 25%, 20% 20%, 25% 5%, 39% 10%
            );
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }
        
        .digit-badge:nth-child(odd) {
            animation-delay: 0.5s;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .digit {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            font-family: 'Arial Black', Arial, sans-serif;
        }
        
        @media (max-width: 768px) {
            .page-view-section {
                padding: 1.5rem 1rem;
            }
            
            .visitors-title {
                font-size: 1.25rem;
            }
            
            .digit-badge {
                width: 45px;
                height: 45px;
            }
            
            .digit {
                font-size: 1.4rem;
            }
        }
    </style>

    <script>
        // ==========================================
        // PAGE VIEW COUNTER - Tab-specific tracking using sessionStorage
        // - Refresh: Same sessionStorage = NO increment
        // - New Tab: New sessionStorage = INCREMENT
        // - After closing browser: sessionStorage cleared = INCREMENT
        // ==========================================
        (function() {
            // Check if this tab has already been counted
            if (!sessionStorage.getItem('page_view_counted')) {
                // Mark this tab as counted
                sessionStorage.setItem('page_view_counted', 'true');
                
                // Call API to increment the view count
                fetch('api/increment_view.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the digit badges with new count
                        const container = document.getElementById('visitorDigits');
                        if (container) {
                            const countStr = String(data.count).padStart(4, '0');
                            container.innerHTML = '';
                            for (let i = 0; i < countStr.length; i++) {
                                const badge = document.createElement('div');
                                badge.className = 'digit-badge';
                                badge.innerHTML = `<span class="digit">${countStr[i]}</span>`;
                                container.appendChild(badge);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.log('View counter update failed:', error);
                });
            }
        })();
    </script>

    <!-- Scroll to Top Button with Progress -->
    <div class="scroll-to-top" id="scrollToTop">
        <svg class="progress-ring" width="50" height="50">
            <circle class="progress-ring-bg" cx="25" cy="25" r="22" />
            <circle class="progress-ring-circle" id="progressCircle" cx="25" cy="25" r="22" />
        </svg>
        <div class="scroll-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </div>
    </div>

    <style>
        /* Scroll to Top Button Styles */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            cursor: pointer;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }
        
        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .scroll-to-top:hover {
            transform: translateY(-3px);
        }
        
        .progress-ring {
            position: absolute;
            top: 0;
            left: 0;
            transform: rotate(-90deg);
        }
        
        .progress-ring-bg {
            fill: rgba(37, 99, 235, 0.15);
            stroke: rgba(37, 99, 235, 0.2);
            stroke-width: 3;
        }
        
        .progress-ring-circle {
            fill: none;
            stroke: #2563eb;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-dasharray: 138.23;
            stroke-dashoffset: 138.23;
            transition: stroke-dashoffset 0.1s ease;
        }
        
        .scroll-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
        }
        
        .scroll-to-top:hover .scroll-icon {
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
            transform: translate(-50%, -50%) scale(1.1);
        }
        
        @media (max-width: 768px) {
            .scroll-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
            
            .progress-ring {
                width: 45px;
                height: 45px;
            }
            
            .progress-ring-bg,
            .progress-ring-circle {
                cx: 22.5;
                cy: 22.5;
                r: 20;
            }
            
            .progress-ring-circle {
                stroke-dasharray: 125.66;
                stroke-dashoffset: 125.66;
            }
            
            .scroll-icon {
                width: 32px;
                height: 32px;
            }
            
            .scroll-icon svg {
                width: 16px;
                height: 16px;
            }
        }
    </style>

    <script>
        // Scroll to Top with Progress Indicator
        (function() {
            const scrollBtn = document.getElementById('scrollToTop');
            const progressCircle = document.getElementById('progressCircle');
            const circumference = 2 * Math.PI * 22; // 2πr where r=22
            
            // Update progress and visibility on scroll
            function updateScrollProgress() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrollPercent = scrollTop / scrollHeight;
                
                // Update progress ring
                const offset = circumference - (scrollPercent * circumference);
                progressCircle.style.strokeDashoffset = offset;
                
                // Show/hide button based on scroll position
                if (scrollTop > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            }
            
            // Scroll to top on click
            scrollBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Listen to scroll events
            window.addEventListener('scroll', updateScrollProgress);
            
            // Initial check
            updateScrollProgress();
        })();
    </script>

<?php
// Include the common footer
include 'includes/footer.php';
?>
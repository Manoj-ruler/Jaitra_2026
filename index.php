<?php
/**
 * JAITRA 2026 - Home Page
 * Main landing page for the sports carnival website
 */

// Page configuration for header include
$pageTitle = 'JAITRA 2026 | Home - AP\'s Premier Engineering Sports Carnival';
$pageDescription = 'JAITRA 2026 - The Ultimate Sports Carnival for All A.P. State Engineering Colleges. Experience thrilling competitions in Volleyball, Kabaddi, Badminton, and Pickleball with ₹5 Lakhs prize pool.';
$currentPage = 'home';
$additionalCss = ['css/home-styles.css']; // Home page specific styles
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
                            <span id="live-count">3</span> Matches Live Now
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
                            <a href="#" class="cta-btn cta-btn-secondary" id="liveMatchesBtn">
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
                    <img src="assets/home2.jpeg" alt="JAITRA 2026 - Event Details"
                        onerror="this.src='https://via.placeholder.com/1200x700/2563eb/ffffff?text=₹5+Lakhs+Prize+Pool'">
                </div>
                <div class="carousel-slide">
                    <img src="assets/home3.jpeg" alt="JAITRA 2026 - Registration"
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
                    
                    <p>
                        Alongside education and research, it has established a history of work ethos that supports 
                        students in developing a creative, confident and logical approach to nation building, making 
                        them highly valued graduates and opening doors to a wide range of exciting careers.
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
    </script>
';

// Include the common footer
include 'includes/footer.php';
?>
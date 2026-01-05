<?php
/**
 * JAITRA 2026 - Live Scoreboard Page
 * Displays all matches with filtering options
 */

// Page configuration for header include
$pageTitle = 'JAITRA 2026 | SRKR Engineering College - Sports Scoreboard';
$pageDescription = 'JAITRA 2026 - Sports Carnival for All A.P. State Engineering Colleges. Live scores for Volleyball, Kabaddi, Badminton, and Pickleball.';
$currentPage = 'scoreboard';
$additionalCss = ['css/pagination.css']; // Add pagination styles


// Include the common header
include 'includes/header.php';
?>

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
                <!-- Gender Filter -->
                <div class="filter-group">
                    <span class="filter-label">Gender Category</span>
                    <div class="filter-buttons">
                        <button class="filter-btn active" data-gender="all">
                            All
                        </button>
                        <button class="filter-btn" data-gender="Men">
                            Men
                        </button>
                        <button class="filter-btn" data-gender="Women">
                            Women
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
                <div class="search-and-count">
                    <input 
                        type="text" 
                        id="search-input" 
                        class="search-input" 
                        placeholder="Search teams..."
                        oninput="handleSearch()"
                    >
                    <span class="match-count">12 matches found</span>
                </div>
            </div>

            <div class="scorecards-grid">
                <!-- Matches load dynamically from API -->
                <div class="loading-state"
                    style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #6b7280;">
                    <div style="font-size: 2rem; margin-bottom: 1rem;">⏳</div>
                    <p>Loading matches...</p>
                </div>
            </div>
            
            <!-- Pagination Controls -->
            <div id="pagination-controls" class="pagination-controls" style="display: none;">
                <!-- Pagination buttons rendered dynamically -->
            </div>
        </section>
    </main>

<?php
// Include the common footer
include 'includes/footer.php';
?>
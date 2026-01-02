/* ============================================
   JAITRA 2026 - Sports Scoreboard Website
   JavaScript - API Integration & Live Updates
   ============================================ */

// Get base URL dynamically
const BASE_URL = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
const API_BASE = BASE_URL + 'api';
let matchesData = [];
let homeMatchesData = []; // Store home page matches data for comparison
let homeMatchesDataHash = ''; // Hash to detect changes
let refreshInterval = null;
let autoScrollTimer = null; // Timer for auto-scrolling

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
    initializeFilters();
    initializeNavigation();

    // Check if we're on the scoreboard page
    if (document.querySelector('.scorecards-grid')) {
        loadMatches(true); // Initial load - force render
        // Auto-refresh every 3 seconds for live updates
        refreshInterval = setInterval(() => loadMatches(false), 3000);
    }

    // Check if we're on the index page (has #all-matches section)
    if (document.querySelector('#all-matches')) {
        loadHomeMatches(true); // Initial load - force render
        // Auto-refresh every 5 seconds for live updates on home page
        setInterval(() => loadHomeMatches(false), 5000);
    }
});

/**
 * Generate a simple hash from data for comparison
 */
function generateDataHash(data) {
    return JSON.stringify(data);
}

/**
 * Load matches for the home page with seamless refresh
 * @param {boolean} forceRender - If true, always render (for initial load)
 */
async function loadHomeMatches(forceRender = false) {
    const container = document.getElementById('all-matches');
    if (!container) return;

    try {
        const response = await fetch(`${API_BASE}/get_matches.php`);
        const data = await response.json();

        if (data.success && data.matches.length > 0) {
            // Generate hash of new data
            const newHash = generateDataHash(data.matches);

            // Only update DOM if data has changed or it's initial load
            if (forceRender || newHash !== homeMatchesDataHash) {
                homeMatchesDataHash = newHash;
                homeMatchesData = data.matches;
                container.innerHTML = data.matches.map(match => createHomeMatchCard(match)).join('');
                // Initialize auto-scroll if matches exist
                startAutoScroll();
            }
            // If data hasn't changed, do nothing - seamless refresh!
        } else if (forceRender || homeMatchesData.length > 0) {
            // Only show "no matches" if it's initial load or we previously had matches
            homeMatchesData = [];
            homeMatchesDataHash = '';
            container.innerHTML = `
                <div class="no-matches-message">
                    <p>No matches scheduled yet. Check back soon!</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Failed to load matches for home page:', error);
        // Only show error on initial load, not on refresh failures
        if (forceRender) {
            container.innerHTML = `
                <div class="no-matches-message">
                    <p>Unable to load matches. Please try again later.</p>
                </div>
            `;
        }
        // On refresh failure, keep existing content - silent fail
    }
}

/**
 * Create a match card for the home page
 * Uses the same structure as scoreboard cards for consistency
 */
function createHomeMatchCard(match) {
    const scores = match.scores || {};
    const score1 = scores.team1_score ?? '-';
    const score2 = scores.team2_score ?? '-';
    const isLive = match.status === 'live';
    const isCompleted = match.status === 'completed';
    const winnerClass1 = isCompleted && match.winner_team === 1 ? ' winner' : '';
    const winnerClass2 = isCompleted && match.winner_team === 2 ? ' winner' : '';

    // Format date
    const matchDate = new Date(match.match_time);
    const dateStr = matchDate.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
    const timeStr = matchDate.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit'
    });

    // Result text - generate descriptive text if not provided by backend
    let resultText = '';
    if (isLive) {
        resultText = 'Match in progress';
    } else if (isCompleted && match.win_description) {
        // Use backend-provided win description if available
        resultText = match.win_description;
    } else if (isCompleted) {
        // Calculate and generate win description from scores
        const s1 = parseInt(score1) || 0;
        const s2 = parseInt(score2) || 0;
        const pointDiff = Math.abs(s1 - s2);

        if (match.winner_team === 1) {
            resultText = pointDiff > 0 ? `${match.team1_name} won by ${pointDiff} points` : `${match.team1_name} won`;
        } else if (match.winner_team === 2) {
            resultText = pointDiff > 0 ? `${match.team2_name} won by ${pointDiff} points` : `${match.team2_name} won`;
        } else if (match.winner_team === 0) {
            resultText = 'Match Drawn';
        } else {
            // Fallback: determine winner from scores if winner_team not set
            if (s1 > s2) {
                resultText = pointDiff > 0 ? `${match.team1_name} won by ${pointDiff} points` : `${match.team1_name} won`;
            } else if (s2 > s1) {
                resultText = pointDiff > 0 ? `${match.team2_name} won by ${pointDiff} points` : `${match.team2_name} won`;
            } else {
                resultText = 'Match Drawn';
            }
        }
    } else {
        resultText = match.round || 'Upcoming';
    }

    return `
        <article class="scorecard ${isLive ? 'live' : ''}" 
                 data-status="${match.status}" 
                 data-sport="${match.sport}" 
                 data-match-id="${match.id}"
                 onclick="window.location.href='match.php?id=${match.id}'"
                 style="cursor: pointer;">
            <div class="scorecard-header">
                <span class="sport-badge ${match.sport}">${capitalizeFirstLetter(match.sport)}</span>
                <span class="status-badge ${match.status}">${capitalizeFirstLetter(match.status)}</span>
            </div>
            <div class="scorecard-body">
                <div class="teams-container">
                    <div class="team-row${winnerClass1}">
                        <div class="team-info">
                            <span class="team-name">${escapeHtml(match.team1_name)}</span>
                        </div>
                        <div class="team-score">
                            <span class="score-main">${score1}</span>
                        </div>
                    </div>
                    <div class="vs-divider">VS</div>
                    <div class="team-row${winnerClass2}">
                        <div class="team-info">
                            <span class="team-name">${escapeHtml(match.team2_name)}</span>
                        </div>
                        <div class="team-score">
                            <span class="score-main">${score2}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="scorecard-footer">
                <div class="match-result${isCompleted ? ' winner' : ''}">
                    ${escapeHtml(resultText)}
                </div>
                <div class="match-time">
                    ${dateStr} • ${timeStr}
                </div>
                <div class="scorecard-branding">
                    <img src="assets/favicon.png" alt="JAITRA" class="branding-logo">
                    <span class="branding-text">JAITRA 2026</span>
                </div>
            </div>
        </article>
    `;
}


// Current filter state
const filterState = {
    status: 'all',
    sport: 'all',
    gender: 'all'
};

// Hash for scoreboard data comparison
let scoreboardDataHash = '';

/**
 * Load matches from API with seamless refresh
 */
async function loadMatches(forceRender = false) {
    try {
        const response = await fetch(`${API_BASE}/get_matches.php`);
        const data = await response.json();

        if (data.success) {
            // Generate hash of new data
            const newHash = generateDataHash(data.matches);

            // Only update if data changed or forced
            if (forceRender || newHash !== scoreboardDataHash) {
                scoreboardDataHash = newHash;
                matchesData = data.matches;
                renderScorecards(matchesData);
            }
            // If data hasn't changed, do nothing - seamless!
        }
    } catch (error) {
        console.error('Failed to load matches:', error);
        // On error, keep existing content - silent fail
    }
}

/**
 * Render scorecard elements
 */
function renderScorecards(matches) {
    const grid = document.querySelector('.scorecards-grid');
    if (!grid) return;

    // Filter matches based on current filter state
    const filteredMatches = matches.filter(match => {
        const statusMatch = filterState.status === 'all' || match.status === filterState.status;
        const sportMatch = filterState.sport === 'all' || match.sport === filterState.sport;
        const genderMatch = filterState.gender === 'all' || (match.gender && match.gender === filterState.gender);
        return statusMatch && sportMatch && genderMatch;
    });

    if (filteredMatches.length === 0) {
        grid.innerHTML = `
            <div class="empty-state">
                <div class="icon">🔍</div>
                <h3>No matches found</h3>
                <p>Try adjusting your filters or check back later.</p>
            </div>
        `;
        updateMatchCount(0);
        return;
    }

    grid.innerHTML = filteredMatches.map(match => createScorecardHTML(match)).join('');
    updateMatchCount(filteredMatches.length);

    // Re-initialize click handlers
    initializeScorecardClicks();
}

/**
 * Create scorecard HTML for a match
 */
function createScorecardHTML(match) {
    const scores = match.scores || {};
    const score1 = scores.team1_score ?? '-';
    const score2 = scores.team2_score ?? '-';
    const isLive = match.status === 'live';
    const isCompleted = match.status === 'completed';
    const winnerClass1 = isCompleted && match.winner_team === 1 ? ' winner' : '';
    const winnerClass2 = isCompleted && match.winner_team === 2 ? ' winner' : '';

    // Format date
    const matchDate = new Date(match.match_time);
    const dateStr = matchDate.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
    const timeStr = matchDate.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit'
    });

    // Result text - generate descriptive text if not provided by backend
    let resultText = '';
    if (isLive) {
        resultText = 'Match in progress';
    } else if (isCompleted && match.win_description) {
        // Use backend-provided win description if available
        resultText = match.win_description;
    } else if (isCompleted) {
        // Calculate and generate win description from scores
        const s1 = parseInt(score1) || 0;
        const s2 = parseInt(score2) || 0;
        const pointDiff = Math.abs(s1 - s2);

        if (match.winner_team === 1) {
            resultText = pointDiff > 0 ? `${match.team1_name} won by ${pointDiff} points` : `${match.team1_name} won`;
        } else if (match.winner_team === 2) {
            resultText = pointDiff > 0 ? `${match.team2_name} won by ${pointDiff} points` : `${match.team2_name} won`;
        } else if (match.winner_team === 0) {
            resultText = 'Match Drawn';
        } else {
            // Fallback: determine winner from scores if winner_team not set
            if (s1 > s2) {
                resultText = pointDiff > 0 ? `${match.team1_name} won by ${pointDiff} points` : `${match.team1_name} won`;
            } else if (s2 > s1) {
                resultText = pointDiff > 0 ? `${match.team2_name} won by ${pointDiff} points` : `${match.team2_name} won`;
            } else {
                resultText = 'Match Drawn';
            }
        }
    } else {
        resultText = match.round || 'Upcoming';
    }

    return `
        <article class="scorecard ${isLive ? 'live' : ''}" 
                 data-status="${match.status}" 
                 data-sport="${match.sport}" 
                 data-match-id="${match.id}"
                 onclick="window.location.href='match.php?id=${match.id}'"
                 style="cursor: pointer;">
            <div class="scorecard-header">
                <span class="sport-badge ${match.sport}">${capitalizeFirstLetter(match.sport)}</span>
                <span class="status-badge ${match.status}">${capitalizeFirstLetter(match.status)}</span>
            </div>
            <div class="scorecard-body">
                <div class="teams-container">
                    <div class="team-row${winnerClass1}">
                        <div class="team-info">
                            <span class="team-name">${escapeHtml(match.team1_name)}</span>
                        </div>
                        <div class="team-score">
                            <span class="score-main">${score1}</span>
                        </div>
                    </div>
                    <div class="vs-divider">VS</div>
                    <div class="team-row${winnerClass2}">
                        <div class="team-info">
                            <span class="team-name">${escapeHtml(match.team2_name)}</span>
                        </div>
                        <div class="team-score">
                            <span class="score-main">${score2}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="scorecard-footer">
                <div class="match-time">
                    ${dateStr} • ${timeStr}
                </div>
                <div class="match-result${isCompleted ? ' winner' : ''}">
                    ${escapeHtml(resultText)}
                </div>
                <div class="scorecard-branding">
                    <img src="assets/favicon.png" alt="JAITRA" class="branding-logo">
                    <span class="branding-text">JAITRA 2026</span>
                </div>
            </div>
        </article>
    `;
}

/**
 * Initialize filter button functionality
 */
function initializeFilters() {
    // Status filter buttons
    const statusButtons = document.querySelectorAll('.filter-btn[data-status]');
    statusButtons.forEach(button => {
        button.addEventListener('click', () => handleStatusFilter(button));
    });

    // Sport filter buttons
    const sportButtons = document.querySelectorAll('.filter-btn[data-sport]');
    sportButtons.forEach(button => {
        button.addEventListener('click', () => handleSportFilter(button));
    });

    // Gender filter buttons
    const genderButtons = document.querySelectorAll('.filter-btn[data-gender]');
    genderButtons.forEach(button => {
        button.addEventListener('click', () => handleGenderFilter(button));
    });
}

/**
 * Handle status filter button click
 */
function handleStatusFilter(button) {
    const status = button.dataset.status;

    document.querySelectorAll('.filter-btn[data-status]').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');

    filterState.status = status;
    renderScorecards(matchesData);
}

/**
 * Handle gender filter button click
 */
function handleGenderFilter(button) {
    const gender = button.dataset.gender;

    document.querySelectorAll('.filter-btn[data-gender]').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');

    filterState.gender = gender;
    renderScorecards(matchesData);
}

/**
 * Handle sport filter button click
 */
function handleSportFilter(button) {
    const sport = button.dataset.sport;

    document.querySelectorAll('.filter-btn[data-sport]').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');

    // Update navbar to mirror the sport selection
    const navLinks = document.querySelectorAll('.nav-link[data-sport]');
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.dataset.sport === sport) {
            link.classList.add('active');
        }
    });

    filterState.sport = sport;
    renderScorecards(matchesData);
}

/**
 * Update the displayed match count
 */
function updateMatchCount(count) {
    const countElement = document.querySelector('.match-count');
    if (countElement) {
        countElement.textContent = `${count} match${count !== 1 ? 'es' : ''} found`;
    }
}

/**
 * Navigation active state handling
 */
function initializeNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (this.dataset.sport) {
                e.preventDefault();

                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');

                const sportButton = document.querySelector(`.filter-btn[data-sport="${this.dataset.sport}"]`);
                if (sportButton) {
                    sportButton.click();
                }

                const scorecardsSection = document.querySelector('.scorecards-section');
                if (scorecardsSection) {
                    scorecardsSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}



/**
 * Start auto-scrolling for home matches
 */
function startAutoScroll() {
    const container = document.getElementById('all-matches');
    if (!container) return;

    // Clear existing timer to prevent duplicates if called multiple times
    if (autoScrollTimer) clearInterval(autoScrollTimer);

    function scroll() {
        // If scrolled to end, reset to start
        if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 2) {
            container.scrollLeft = 0;
        } else {
            container.scrollLeft += 1; // Speed: 1px per tick
        }
    }

    // Start scrolling (30ms = ~30fps for smooth scroll)
    autoScrollTimer = setInterval(scroll, 30);

    // Pause on hover
    container.onmouseenter = () => clearInterval(autoScrollTimer);
    container.onmouseleave = () => {
        clearInterval(autoScrollTimer);
        autoScrollTimer = setInterval(scroll, 30);
    };
}

/**
 * Initialize scorecard click handlers for navigation
 */
function initializeScorecardClicks() {
    const scorecards = document.querySelectorAll('.scorecard[data-match-id]');

    scorecards.forEach(scorecard => {
        scorecard.style.cursor = 'pointer';
        scorecard.addEventListener('click', function (e) {
            if (e.target.tagName === 'A') return;

            const matchId = this.dataset.matchId;
            if (matchId) {
                window.location.href = `match.php?id=${matchId}`;
            }
        });
    });
}

/**
 * Helper functions
 */
function capitalizeFirstLetter(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

// Add fadeIn animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .scorecard { animation: fadeIn 0.3s ease forwards; }
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        color: #6b7280;
    }
    .empty-state .icon { font-size: 3rem; margin-bottom: 1rem; }
`;
document.head.appendChild(style);

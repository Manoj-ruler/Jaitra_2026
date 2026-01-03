/* ============================================
   JAITRA 2026 - Match Details Page
   JavaScript - Fetch from API & Display Animations
   Based on reference repo ssantoshhhhh/live_scores
   ============================================ */

const API_BASE = 'api';

let currentMatch = null;
let allMatches = [];
let lastAnimId = 0;
let isFirstPoll = true;
let winnerShown = false;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', async function () {
    const urlParams = new URLSearchParams(window.location.search);
    const matchId = urlParams.get('id');

    if (!matchId) {
        showError();
        return;
    }

    try {
        const response = await fetch(`${API_BASE}/get_score.php?id=${matchId}`);
        const data = await response.json();

        if (data.success && data.match) {
            currentMatch = data.match;
            loadMatchDetails(currentMatch);
            loadSimilarMatches();

            // Setup auto-refresh for live matches
            if (currentMatch.status === 'live') {
                setInterval(() => refreshScores(matchId), 2000);
            }
        } else {
            showError();
        }
    } catch (error) {
        console.error('Error loading match:', error);
        showError();
    }

    const shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', shareMatch);
    }
});

/**
 * Refresh scores for live matches
 */
async function refreshScores(matchId) {
    try {
        const response = await fetch(`${API_BASE}/get_score.php?id=${matchId}`);
        const data = await response.json();

        if (data.success && data.match) {
            // Check for status change
            const prevStatus = currentMatch.status;
            currentMatch = data.match;

            updateScoreDisplay();
            checkAnimations();

            // Explicitly update status elements if status changed or just to be safe
            updateStatusElements();

            // Check if match completed - show winner
            if (currentMatch.status === 'completed' && !winnerShown) {
                const scores = currentMatch.scores || {};

                // Show winner animation
                if (scores.last_animation && scores.last_animation.type === 'WINNER') {
                    const winner = scores.last_animation.team === 't1' ?
                        currentMatch.team1_name : currentMatch.team2_name;
                    triggerWinnerAnimation(winner);
                    winnerShown = true;
                } else {
                    // Fallback if no animation but completed (e.g. manual status update)
                    // Check who won and trigger
                    let winnerName = '';
                    if (currentMatch.winner_team == 1) winnerName = currentMatch.team1_name;
                    else if (currentMatch.winner_team == 2) winnerName = currentMatch.team2_name;

                    if (winnerName && prevStatus === 'live') {
                        triggerWinnerAnimation(winnerName);
                        winnerShown = true;
                    }
                }

                // Stop refreshing if completed
                // setTimeout(() => location.reload(), 5000); // Optional: reload to finalize state
            }
        }
    } catch (error) {
        console.error('Error refreshing scores:', error);
    }
}

/**
 * Update Status UI Elements dynamically
 */
function updateStatusElements() {
    // Update Badge
    const statusBadge = document.getElementById('hero-status-badge');
    if (statusBadge) {
        statusBadge.textContent = capitalizeFirst(currentMatch.status);
        statusBadge.className = `status-badge ${currentMatch.status}`;
    }

    // Update Banner/Info Text
    const infoStatus = document.getElementById('info-status');
    if (infoStatus) {
        if (currentMatch.status === 'live') {
            infoStatus.textContent = 'Match in progress';
        } else if (currentMatch.status === 'completed' && currentMatch.win_description) {
            infoStatus.textContent = currentMatch.win_description;
        } else {
            infoStatus.textContent = currentMatch.round || capitalizeFirst(currentMatch.status);
        }
    }

    // Update Winner Trophies
    if (currentMatch.winner_team === 1) {
        const el = document.getElementById('team-a-winner');
        if (el) { el.innerHTML = '🏆'; el.style.display = 'block'; }
    } else if (currentMatch.winner_team === 2) {
        const el = document.getElementById('team-b-winner');
        if (el) { el.innerHTML = '🏆'; el.style.display = 'block'; }
    }
}

/**
 * Update score display
 */
/**
 * Update score display
 */
function updateScoreDisplay() {
    const scores = currentMatch.scores || {};
    const score1 = scores.team1_score ?? '-';
    const score2 = scores.team2_score ?? '-';

    document.getElementById('team-a-score').textContent = score1;
    document.getElementById('team-b-score').textContent = score2;

    const isLive = currentMatch.status === 'live';
    const sportName = (currentMatch.sport || '').toLowerCase();
    const isBadminton = sportName === 'badminton';
    const isKabaddi = sportName === 'kabaddi';

    // Elements
    const raidA = document.getElementById('team-a-raid');
    const raidB = document.getElementById('team-b-raid');
    const containerA = document.getElementById('team-a-players-container');
    const containerB = document.getElementById('team-b-players-container');

    // Badminton Elements
    const serveA = document.getElementById('team-a-serve');
    const serveB = document.getElementById('team-b-serve');
    const currentSetBadge = document.getElementById('current-set-badge');
    const setsBreakdown = document.getElementById('sets-breakdown');
    const setScoresContainer = document.getElementById('set-scores-container');

    if (isLive) {
        if (isKabaddi) {
            // Show Kabaddi, Hide Badminton
            if (serveA) serveA.style.display = 'none';
            if (serveB) serveB.style.display = 'none';
            if (setsA) setsA.style.display = 'none';
            if (setsB) setsB.style.display = 'none';
            if (currentSetBadge) currentSetBadge.style.display = 'none';

            // Update Player Counts (Render Icons)
            const p1 = scores.t1_players ?? 7;
            const p2 = scores.t2_players ?? 7;
            renderPlayerIcons('team-a-players-container', p1);
            renderPlayerIcons('team-b-players-container', p2);
            if (containerA) containerA.style.display = 'flex';
            if (containerB) containerB.style.display = 'flex';

            // Update Raiding Indicator
            const raider = scores.current_raider || 'team1';
            updateActiveIndicator(raidA, raidB, raider);
        } else if (isBadminton) {
            // Show Badminton, Hide Kabaddi
            if (raidA) raidA.style.display = 'none';
            if (raidB) raidB.style.display = 'none';
            if (containerA) containerA.style.display = 'none';
            if (containerB) containerB.style.display = 'none';

            // Update Service
            const server = scores.server || null;
            updateActiveIndicator(serveA, serveB, server);

            // Render Individual Set Scores with enhanced styling
            const setHistory = scores.set_history || [];
            if (setScoresContainer && setHistory.length > 0) {
                setScoresContainer.innerHTML = '';
                setHistory.forEach(set => {
                    const setDiv = document.createElement('div');
                    // Add winner class for styling
                    const winnerClass = set.winner ? `winner-${set.winner}` : '';
                    setDiv.className = `set-score-card ${winnerClass}`;
                    setDiv.innerHTML = `
                        <div class="set-label">Set ${set.set_number}</div>
                        <div class="set-score">${set.team1_score} - ${set.team2_score}</div>
                    `;
                    setScoresContainer.appendChild(setDiv);
                });
                if (setsBreakdown) setsBreakdown.style.display = 'block';
            } else {
                if (setsBreakdown) setsBreakdown.style.display = 'none';
            }

            // Update Current Set Badge
            if (currentSetBadge) {
                if (currentMatch.status === 'completed') {
                    currentSetBadge.style.display = 'none';
                } else {
                    currentSetBadge.textContent = 'SET ' + (scores.current_set || 1);
                    currentSetBadge.style.display = 'block';
                }
            }
        }
    } else {
        // Hide all indicators if not live (except sets for completed Badminton)
        const allIndicators = [raidA, raidB, containerA, containerB, serveA, serveB, currentSetBadge];
        allIndicators.forEach(el => { if (el) el.style.display = 'none'; });

        // Show Final Set Scores if completed Badminton
        if (isBadminton && currentMatch.status === 'completed') {
            const setHistory = scores.set_history || [];
            if (setScoresContainer && setHistory.length > 0) {
                setScoresContainer.innerHTML = '';
                setHistory.forEach(set => {
                    const setDiv = document.createElement('div');
                    const winnerClass = set.winner ? `winner-${set.winner}` : '';
                    setDiv.className = `set-score-card ${winnerClass}`;
                    setDiv.innerHTML = `
                        <div class="set-label">Set ${set.set_number}</div>
                        <div class="set-score">${set.team1_score} - ${set.team2_score}</div>
                    `;
                    setScoresContainer.appendChild(setDiv);
                });
                if (setsBreakdown) setsBreakdown.style.display = 'block';
            } else {
                if (setsBreakdown) setsBreakdown.style.display = 'none';
            }
        } else {
            // Hide sets breakdown for non-Badminton or non-completed matches
            if (setsBreakdown) setsBreakdown.style.display = 'none';
        }

        // Reset team name colors
        document.getElementById('team-a-name').style.color = '';
        document.getElementById('team-b-name').style.color = '';
    }

    // Update timeout overlay if exists
    const toT1Score = document.getElementById('to-t1-score');
    const toT2Score = document.getElementById('to-t2-score');
    if (toT1Score) toT1Score.textContent = score1;
    if (toT2Score) toT2Score.textContent = score2;
}

/**
 * Helper to update active indicator (Raider or Server)
 */
function updateActiveIndicator(elA, elB, activeTeam) {
    if (!elA || !elB) return;

    if (activeTeam === 't1' || activeTeam === 'team1') {
        elA.style.display = 'block';
        elB.style.display = 'none';
        document.getElementById('team-a-name').style.color = '#fbbf24';
        document.getElementById('team-b-name').style.color = '';
    } else if (activeTeam === 't2' || activeTeam === 'team2') {
        elA.style.display = 'none';
        elB.style.display = 'block';
        document.getElementById('team-b-name').style.color = '#fbbf24';
        document.getElementById('team-a-name').style.color = '';
    } else {
        elA.style.display = 'none';
        elB.style.display = 'none';
        document.getElementById('team-a-name').style.color = '';
        document.getElementById('team-b-name').style.color = '';
    }
}

/**
 * Render player icons (7 total, active ones highlighted)
 */
function renderPlayerIcons(containerId, activeCount) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // We expect 7 visible info slots for Kabaddi usually
    const totalPlayers = 7;
    container.innerHTML = ''; // Clear current

    for (let i = 0; i < totalPlayers; i++) {
        const icon = document.createElement('i');
        // If i < activeCount, it's an active player
        icon.className = `fas fa-user player-icon ${i < activeCount ? 'active' : ''}`;
        container.appendChild(icon);
    }
}

/**
 * Check for animations in score data
 */
function checkAnimations() {
    const scores = currentMatch.scores || {};

    // Handle Timeout Overlay
    const timeoutOverlay = document.getElementById('timeout-overlay');
    if (timeoutOverlay) {
        if (scores.is_timeout) {
            timeoutOverlay.style.display = 'flex';
            document.getElementById('to-t1-name').textContent = currentMatch.team1_name;
            document.getElementById('to-t2-name').textContent = currentMatch.team2_name;
        } else {
            timeoutOverlay.style.display = 'none';
        }
    }

    // Handle Animations (Super Raid, Super Tackle, All Out, Winner)
    if (scores.last_animation) {
        const anim = scores.last_animation;
        const remoteId = anim.id;

        if (isFirstPoll) {
            // First poll - sync but don't play old animations
            lastAnimId = remoteId;
        } else if (remoteId > lastAnimId) {
            // New animation detected!
            lastAnimId = remoteId;

            const teamName = anim.team === 't1' ?
                currentMatch.team1_name : currentMatch.team2_name;

            if (anim.type === 'WINNER') {
                triggerWinnerAnimation(teamName);
            } else {
                triggerAnimation(anim.type, teamName);
            }
        }
    }

    isFirstPoll = false;
}

/**
 * Trigger animation overlay (Super Raid, Super Tackle, All Out)
 */
function triggerAnimation(type, teamName, duration = 4000) {
    const overlay = document.getElementById('animation-overlay');
    const animText = document.getElementById('anim-text');
    const animTeam = document.getElementById('anim-team');
    const animIcon = document.getElementById('anim-icon');
    const gamifiedBg = document.querySelector('.gamified-bg');

    if (!overlay || !animText || !animTeam) return;

    // Set content
    animText.textContent = type;
    animTeam.textContent = teamName;

    // Set Icon & Colors
    let radialColor = 'rgba(220,38,38,0.5)'; // Red
    let textColor = '#fff';
    let particles = ['#fbbf24', '#f97316', '#dc2626'];
    let iconChar = '🔥';

    if (type.includes('TACKLE')) {
        radialColor = 'rgba(8, 145, 178, 0.5)'; // Cyan/Blue
        particles = ['#ffffff', '#22d3ee', '#0891b2'];
        iconChar = '💪';
    } else if (type.includes('ALL OUT')) {
        radialColor = 'rgba(124, 58, 237, 0.5)'; // Purple
        particles = ['#ffffff', '#a78bfa', '#7c3aed'];
        iconChar = '❌';
    }

    if (animIcon) animIcon.textContent = iconChar;
    if (gamifiedBg) {
        gamifiedBg.style.background = `radial-gradient(circle, ${radialColor} 0%, rgba(0,0,0,0) 70%)`;
    }

    // Show overlay
    overlay.style.display = 'flex';

    // Add pulsing effect to text
    animText.style.transform = 'scale(0.5)';
    setTimeout(() => {
        animText.style.transition = 'transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        animText.style.transform = 'scale(1.2)';
    }, 100);

    // Trigger confetti
    if (window.confetti) {
        const count = 200;
        const defaults = {
            origin: { y: 0.7 }
        };

        function fire(particleRatio, opts) {
            confetti(Object.assign({}, defaults, opts, {
                particleCount: Math.floor(count * particleRatio),
                colors: particles
            }));
        }

        fire(0.25, { spread: 26, startVelocity: 55, });
        fire(0.2, { spread: 60, });
        fire(0.35, { spread: 100, decay: 0.91, scalar: 0.8 });
        fire(0.1, { spread: 120, startVelocity: 25, decay: 0.92, scalar: 1.2 });
        fire(0.1, { spread: 120, startVelocity: 45, });
    }

    // Auto-hide
    setTimeout(() => {
        overlay.style.display = 'none';
        animText.style.transform = 'scale(1)'; // reset
    }, duration);
}

/**
 * Trigger winner animation with fireworks - Celebratory Mode
 */
function triggerWinnerAnimation(winnerName) {
    const overlay = document.getElementById('animation-overlay');
    const animText = document.getElementById('anim-text');
    const animTeam = document.getElementById('anim-team');
    const animIcon = document.getElementById('anim-icon');
    const gamifiedBg = document.querySelector('.gamified-bg');

    if (!overlay || !animText || !animTeam) return;

    // Calculate Margin if match data is available
    let resultText = winnerName;
    if (typeof currentMatch !== 'undefined' && currentMatch.scores) {
        const s1 = parseInt(currentMatch.scores.team1_score || 0);
        const s2 = parseInt(currentMatch.scores.team2_score || 0);
        const sportName = (currentMatch.sport || '').toLowerCase();

        if (sportName === 'badminton') {
            // For Badminton, show Sets Score (e.g. "Won by 2-1")
            const sets1 = currentMatch.scores.t1_sets || 0;
            const sets2 = currentMatch.scores.t2_sets || 0;
            if (winnerName === currentMatch.team1_name) {
                resultText = `${winnerName} won ${sets1}-${sets2}`;
            } else {
                resultText = `${winnerName} won ${sets2}-${sets1}`;
            }
        } else {
            // Standard point difference (Kabaddi)
            if (winnerName === currentMatch.team1_name && s1 > s2) {
                resultText = `${winnerName} won by ${s1 - s2} points`;
            } else if (winnerName === currentMatch.team2_name && s2 > s1) {
                resultText = `${winnerName} won by ${s2 - s1} points`;
            }
        }
    }

    animText.textContent = '🏆 WINNER 🏆';
    animTeam.textContent = resultText;

    // Crown Icon
    if (animIcon) animIcon.textContent = '👑';

    // Victory Colors
    animText.style.color = '#fbbf24'; // Gold
    animText.style.textShadow = '0 0 30px rgba(251, 191, 36, 0.8)';

    // Dark celebratory background
    overlay.style.background = 'radial-gradient(circle at center, #1e1b4b 0%, #020617 100%)';

    if (gamifiedBg) {
        // Subtle gold glow in background
        gamifiedBg.style.background = 'radial-gradient(circle, rgba(251, 191, 36, 0.2) 0%, rgba(0,0,0,0) 70%)';
    }

    overlay.style.display = 'flex';

    // Intense Fireworks Sequence
    if (window.confetti) {
        const duration = 12000; // 12 seconds
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 100, zIndex: 10000 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        const interval = setInterval(function () {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);

            // Gold/Red/White mix
            const colors = ['#ffd700', '#facc15', '#ffffff', '#ef4444'];

            // Left Fan
            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
                colors: colors
            }));

            // Right Fan
            confetti(Object.assign({}, defaults, {
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
                colors: colors
            }));

            // Occasional Center Burst
            if (Math.random() > 0.6) {
                confetti(Object.assign({}, defaults, {
                    particleCount: 80,
                    startVelocity: 45,
                    origin: { x: 0.5, y: 0.4 },
                    colors: ['#fbbf24', '#ffffff']
                }));
            }
        }, 250);
    }

    // Hide after celebration
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 12000);
}

/**
 * Load match details into page
 */
function loadMatchDetails(match) {
    const scores = match.scores || {};
    const score1 = scores.team1_score ?? '-';
    const score2 = scores.team2_score ?? '-';

    document.getElementById('breadcrumb-sport').textContent = capitalizeFirst(match.sport);
    document.getElementById('breadcrumb-match').textContent = `${match.team1_name} vs ${match.team2_name}`;

    const sportBadge = document.getElementById('hero-sport-badge');
    sportBadge.textContent = capitalizeFirst(match.sport);
    sportBadge.className = `sport-badge ${match.sport}`;

    if (match.gender) {
        const categoryBadge = document.getElementById('hero-category-badge');
        if (categoryBadge) {
            categoryBadge.textContent = capitalizeFirst(match.gender);
            categoryBadge.style.display = 'inline-flex';
        }
    }

    const statusBadge = document.getElementById('hero-status-badge');
    statusBadge.textContent = capitalizeFirst(match.status);
    statusBadge.className = `status-badge ${match.status}`;

    document.getElementById('team-a-name').textContent = match.team1_name;
    document.getElementById('team-b-name').textContent = match.team2_name;
    document.getElementById('team-a-score').textContent = score1;
    document.getElementById('team-b-score').textContent = score2;

    if (match.winner_team === 1) {
        document.getElementById('team-a-winner').innerHTML = '🏆';
        document.getElementById('team-a-winner').style.display = 'block';
    } else if (match.winner_team === 2) {
        document.getElementById('team-b-winner').innerHTML = '🏆';
        document.getElementById('team-b-winner').style.display = 'block';
    }

    const infoStatus = document.getElementById('info-status');
    if (infoStatus) {
        if (match.status === 'live') {
            infoStatus.textContent = 'Match in progress';
        } else if (match.status === 'completed' && match.win_description) {
            infoStatus.textContent = match.win_description;
        } else {
            infoStatus.textContent = match.round || capitalizeFirst(match.status);
        }
    }

    document.title = `${match.team1_name} vs ${match.team2_name} | JAITRA 2026`;

    // Update score display to render sets and sport-specific elements
    updateScoreDisplay();
}

/**
 * Load similar matches
 */
async function loadSimilarMatches() {
    try {
        const response = await fetch(`${API_BASE}/get_matches.php`);
        const data = await response.json();

        if (!data.success) return;

        allMatches = data.matches;

        const sameSportMatches = allMatches.filter(m =>
            m.sport === currentMatch.sport && m.id !== currentMatch.id
        ).slice(0, 3);

        const sameSportTitle = document.getElementById('same-sport-title');
        if (sameSportTitle) {
            sameSportTitle.textContent = `More from ${capitalizeFirst(currentMatch.sport)}`;
        }
        renderSimilarMatches('same-sport-matches', sameSportMatches);

        const sameTeamMatches = allMatches.filter(m =>
            (m.team1_name === currentMatch.team1_name || m.team1_name === currentMatch.team2_name ||
                m.team2_name === currentMatch.team1_name || m.team2_name === currentMatch.team2_name) &&
            m.id !== currentMatch.id
        ).slice(0, 3);

        renderSimilarMatches('same-team-matches', sameTeamMatches);

        const liveMatches = allMatches.filter(m =>
            m.status === 'live' && m.id !== currentMatch.id
        ).slice(0, 3);

        renderSimilarMatches('live-matches', liveMatches);
    } catch (error) {
        console.error('Error loading similar matches:', error);
    }
}

function renderSimilarMatches(containerId, matches) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (matches.length === 0) {
        container.innerHTML = '<p class="no-matches">No matches available</p>';
        return;
    }

    let html = '';
    matches.forEach(match => {
        const scores = match.scores || {};
        const score1 = scores.team1_score ?? '-';
        const score2 = scores.team2_score ?? '-';
        const isLive = match.status === 'live';
        const isCompleted = match.status === 'completed';
        const winnerClass1 = isCompleted && match.winner_team === 1 ? ' winner' : '';
        const winnerClass2 = isCompleted && match.winner_team === 2 ? ' winner' : '';

        // Generate result text - same logic as scoreboard
        let resultText = '';
        if (isLive) {
            resultText = 'Match in progress';
        } else if (isCompleted && match.win_description) {
            resultText = match.win_description;
        } else if (isCompleted) {
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

        // Format date
        const matchDate = new Date(match.match_time);
        const dateStr = matchDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        const timeStr = matchDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

        html += `
            <article class="scorecard ${isLive ? 'live' : ''}" 
                     data-status="${match.status}" 
                     data-sport="${match.sport}" 
                     data-match-id="${match.id}"
                     onclick="window.location.href='match.php?id=${match.id}'"
                     style="cursor: pointer;">
                <div class="scorecard-header">
                <div class="badge-group">
                    <span class="sport-badge ${match.sport}">${capitalizeFirst(match.sport)}</span>
                    ${match.gender ? `<span class="category-badge">${capitalizeFirst(match.gender)}</span>` : ''}
                </div>
                <span class="status-badge ${match.status}">${capitalizeFirst(match.status)}</span>
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
    });

    container.innerHTML = html;
}

function shareMatch() {
    if (!currentMatch) return;

    const shareText = `${currentMatch.team1_name} vs ${currentMatch.team2_name} - ${capitalizeFirst(currentMatch.sport)} | JAITRA 2026`;
    const shareUrl = window.location.href;

    if (navigator.share) {
        navigator.share({ title: shareText, url: shareUrl }).catch(() => { });
    } else {
        navigator.clipboard.writeText(shareUrl).then(() => {
            alert('Match link copied!');
        });
    }
}

function showError() {
    const mainEl = document.querySelector('.match-detail-page');
    if (mainEl) {
        mainEl.innerHTML = `
            <div class="error-state" style="text-align: center; padding: 4rem 2rem;">
                <h2 style="color: #1a2332; margin-bottom: 1rem;">Match Not Found</h2>
                <p style="color: #6b7280; margin-bottom: 2rem;">The match you're looking for doesn't exist.</p>
                <a href="scoreboard.php" style="display: inline-block; padding: 0.875rem 2rem; background: #2563eb; color: white; text-decoration: none; border-radius: 8px;">← Back to Scoreboard</a>
            </div>
        `;
    }
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

/* Live Reactions Feature */
function initializeReactions() {
    const reactionButtons = document.querySelectorAll('.reaction-btn');
    const floatingContainer = document.getElementById('floating-emojis');

    if (!floatingContainer) return;

    reactionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const emoji = this.getAttribute('data-emoji');

            for (let i = 0; i < 10; i++) {
                setTimeout(() => {
                    const emojiEl = document.createElement('div');
                    emojiEl.className = 'floating-emoji';
                    emojiEl.textContent = emoji;
                    emojiEl.style.cssText = `
                        position: fixed;
                        bottom: 100px;
                        right: ${50 + Math.random() * 100}px;
                        font-size: 2rem;
                        animation: floatUp 2s ease-out forwards;
                        pointer-events: none;
                    `;
                    document.body.appendChild(emojiEl);
                    setTimeout(() => emojiEl.remove(), 2000);
                }, i * 100);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeReactions);

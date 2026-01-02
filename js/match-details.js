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
            currentMatch = data.match;
            updateScoreDisplay();
            checkAnimations();

            // Check if match completed - show winner
            if (currentMatch.status === 'completed' && !winnerShown) {
                const scores = currentMatch.scores || {};
                if (scores.last_animation && scores.last_animation.type === 'WINNER') {
                    const winner = scores.last_animation.team === 't1' ?
                        currentMatch.team1_name : currentMatch.team2_name;
                    triggerWinnerAnimation(winner);
                    winnerShown = true;
                }
            }
        }
    } catch (error) {
        console.error('Error refreshing scores:', error);
    }
}

/**
 * Update score display
 */
function updateScoreDisplay() {
    const scores = currentMatch.scores || {};
    const score1 = scores.team1_score ?? '-';
    const score2 = scores.team2_score ?? '-';

    document.getElementById('team-a-score').textContent = score1;
    document.getElementById('team-b-score').textContent = score2;

    // Update timeout overlay if exists
    const toT1Score = document.getElementById('to-t1-score');
    const toT2Score = document.getElementById('to-t2-score');
    if (toT1Score) toT1Score.textContent = score1;
    if (toT2Score) toT2Score.textContent = score2;
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
    const animBar = document.getElementById('anim-bar');

    if (!overlay || !animText || !animTeam) return;

    // Set content
    animText.textContent = type;
    animTeam.textContent = teamName;

    // Change colors based on type
    let bgColor = 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)'; // Default red
    let textColor = '#fbbf24'; // Gold

    if (type.includes('TACKLE')) {
        bgColor = 'linear-gradient(135deg, #0891b2 0%, #0e7490 100%)';
        textColor = '#ffffff';
    } else if (type.includes('ALL OUT')) {
        bgColor = 'linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%)';
        textColor = '#ffffff';
    }

    overlay.style.background = bgColor;
    animText.style.color = textColor;
    if (animBar) animBar.style.background = textColor;

    // Show overlay
    overlay.style.display = 'flex';

    // Trigger confetti for Super Raid
    if (window.confetti && type.includes('SUPER RAID')) {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
            colors: ['#fbbf24', '#f97316', '#dc2626']
        });
    }

    // Auto-hide
    setTimeout(() => {
        overlay.style.display = 'none';
    }, duration);
}

/**
 * Trigger winner animation with confetti celebration
 */
function triggerWinnerAnimation(winnerName) {
    const overlay = document.getElementById('animation-overlay');
    const animText = document.getElementById('anim-text');
    const animTeam = document.getElementById('anim-team');

    if (!overlay || !animText || !animTeam) return;

    animText.textContent = '🏆 WINNER 🏆';
    animTeam.textContent = winnerName;
    animText.style.color = '#ffd700';
    overlay.style.background = 'linear-gradient(135deg, #1e3a5f 0%, #0d1f33 100%)';

    overlay.style.display = 'flex';

    // Confetti celebration!
    if (window.confetti) {
        const duration = 5000;
        const end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 5,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#ff0000', '#00ff00', '#0000ff', '#ffd700']
            });
            confetti({
                particleCount: 5,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#ff0000', '#00ff00', '#0000ff', '#ffd700']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    }

    // Keep winner animation longer (10 seconds)
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 10000);
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
                    <span class="sport-badge ${match.sport}">${capitalizeFirst(match.sport)}</span>
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

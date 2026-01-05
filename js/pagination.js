// ============================================
// Pagination Functions for Scoreboard
// ============================================

/**
 * Update items per page based on screen size
 */
function updateItemsPerPage() {
    const isMobile = window.innerWidth <= 768;
    itemsPerPage = isMobile ? 10 : 20;
}

/**
 * Paginate matches array
 * @param {Array} matches - All matches to paginate
 * @returns {Array} - Matches for current page
 */
function paginateMatches(matches) {
    updateItemsPerPage();
    totalPages = Math.ceil(matches.length / itemsPerPage);

    // Ensure current page is valid
    if (currentPage > totalPages) {
        currentPage = totalPages || 1;
    }

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    return matches.slice(startIndex, endIndex);
}

/**
 * Render pagination controls
 */
function renderPaginationControls() {
    const paginationContainer = document.getElementById('pagination-controls');
    if (!paginationContainer) return;

    // Hide pagination if only one page or no matches
    if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
        return;
    }

    paginationContainer.style.display = 'flex';

    const prevDisabled = currentPage === 1;
    const nextDisabled = currentPage === totalPages;

    paginationContainer.innerHTML = `
        <button 
            class="pagination-btn" 
            id="prev-page-btn"
            ${prevDisabled ? 'disabled' : ''}
            onclick="goToPage(${currentPage - 1})"
        >
            ← Previous
        </button>
        
        <div class="pagination-info">
            Page ${currentPage} of ${totalPages}
        </div>
        
        <button 
            class="pagination-btn" 
            id="next-page-btn"
            ${nextDisabled ? 'disabled' : ''}
            onclick="goToPage(${currentPage + 1})"
        >
            Next →
        </button>
    `;
}

/**
 * Navigate to specific page
 * @param {number} pageNumber - Page to navigate to
 */
function goToPage(pageNumber) {
    if (pageNumber < 1 || pageNumber > totalPages) return;

    currentPage = pageNumber;
    renderScorecards(allMatches);

    // Scroll to top of scorecards section
    const scorecardsSection = document.querySelector('.scorecards-section');
    if (scorecardsSection) {
        scorecardsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Add window resize listener to update items per page
window.addEventListener('resize', () => {
    const oldItemsPerPage = itemsPerPage;
    updateItemsPerPage();

    // Re-render if items per page changed
    if (oldItemsPerPage !== itemsPerPage && allMatches.length > 0) {
        renderScorecards(allMatches);
    }
});

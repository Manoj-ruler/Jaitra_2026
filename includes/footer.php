<?php
/**
 * JAITRA 2026 - Common Footer Include
 * Include this at the bottom of every page
 * 
 * Optional variable:
 * - $includeAppJs (boolean): If false, won't include app.js (for pages with custom JS)
 * - $customScripts (string): Additional scripts to include before closing body
 */

$includeAppJs = $includeAppJs ?? true;
$customScripts = $customScripts ?? '';
?>
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <!-- About Column -->
            <div class="footer-column">
                <h3>About JAITRA 2026</h3>
                <p>AP's Premier Engineering Sports Carnival for all state engineering colleges featuring Volleyball, Kabaddi, Badminton, and Pickleball.</p>
                <p><strong>Prize Pool:</strong> ₹5 Lakhs</p>
            </div>
            
            <!-- Quick Links Column -->
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="scoreboard.php">Live Scoreboard</a></li>
                </ul>
            </div>
            
            <!-- Contact Column -->
            <div class="footer-column">
                <h3>Contact Coordinators</h3>
                <p><strong>Faculty:</strong></p>
                <p>Dr. Ch. Hari Mohan - <a href="tel:+919441911178">+91 94419 11178</a></p>
                <p>V. Avinash - <a href="tel:+919866474674">+91 98664 74674</a></p>
                <p><strong>Students:</strong></p>
                <p>V. Sivaram - <a href="tel:+918948636169">+91 89486 36169</a></p>
                <p>D. Ashok - <a href="tel:+918520088959">+91 85200 88959</a></p>
            </div>
            
            <!-- Follow Us Column -->
            <div class="footer-column">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="#">@SRKRECOFFICIAL</a></li>
                    <li><a href="#">@SRKR_ENGINEERING_COLLEGE</a></li>
                    <li><a href="#">SRKRECLIVE8303</a></li>
                </ul>
                <div class="footer-logo">SRKR</div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>JAITRA 2026 - SRKR Engineering College (A), Bhimavaram</p>
            <p style="margin-top: 0.5rem; font-size: 0.9rem; opacity: 0.8;">
                Website Designed by <strong><a href="https://bhimavaramdigitals.com/" style="color: yellow;" target="_blank" rel="noopener noreferrer">Bhimavaram Digitals</a></strong> | CSD & CSIT Department, SRKREC
            </p>
        </div>
    </footer>
    

    <?php if ($includeAppJs): ?>
    <!-- Pagination JavaScript -->
    <script src="js/pagination.js"></script>
    <!-- App JavaScript -->
    <script src="js/app.js"></script>
    <?php endif; ?>

    <?php if (!empty($customScripts)): ?>
    <?php echo $customScripts; ?>
    <?php endif; ?>
</body>

</html>

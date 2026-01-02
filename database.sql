-- =============================================
-- JAITRA 2026 Sports Scoring Database
-- =============================================

CREATE DATABASE IF NOT EXISTS jaitra_scores;
USE jaitra_scores;

-- =============================================
-- TABLE: sports (Fixed 4 records)
-- =============================================
CREATE TABLE IF NOT EXISTS sports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO sports (name) VALUES 
    ('kabaddi'),
    ('volleyball'),
    ('badminton'),
    ('pickleball')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- =============================================
-- TABLE: admins
-- =============================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    allowed_sport_id INT DEFAULT NULL,
    FOREIGN KEY (allowed_sport_id) REFERENCES sports(id) ON DELETE SET NULL
);

-- =============================================
-- TABLE: teams
-- =============================================
CREATE TABLE IF NOT EXISTS teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    college_name VARCHAR(150) NOT NULL,
    sport_id INT NOT NULL,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE,
    UNIQUE KEY unique_team_sport (name, sport_id)
);

-- =============================================
-- TABLE: matches
-- =============================================
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sport_id INT NOT NULL,
    team1_id INT NOT NULL,
    team2_id INT NOT NULL,
    status ENUM('upcoming', 'live', 'completed') DEFAULT 'upcoming',
    match_time DATETIME NOT NULL,
    venue VARCHAR(100),
    round VARCHAR(50),
    winner_team TINYINT DEFAULT NULL,
    win_description VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (sport_id) REFERENCES sports(id) ON DELETE CASCADE,
    FOREIGN KEY (team1_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (team2_id) REFERENCES teams(id) ON DELETE CASCADE
);

-- =============================================
-- TABLE: live_scores
-- =============================================
CREATE TABLE IF NOT EXISTS live_scores (
    match_id INT PRIMARY KEY,
    score_json JSON NOT NULL,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE
);

-- =============================================
-- ADMIN USERS
-- After importing this SQL, run setup_admins.php in browser
-- to create admin users with properly hashed passwords.
-- =============================================

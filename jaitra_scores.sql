-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 03:14 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jaitra_scores`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `allowed_sport_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `allowed_sport_id`) VALUES
(1, 'admin', '$2y$10$jn6qgQmVXFSZrp3xYpN6q.W0jSJO2aPCYOmYfpqp0g59jKZhRCSu.', NULL),
(2, 'kabaddi', '$2y$10$8uL58j.YtYjK3SaqpmoKoeElo4FCp9N5AeUP4DWE9ZM2/qMxTLd9W', 1),
(3, 'volleyball', '$2y$10$b3hSHnEK1UV1K4eStgSMtOPC.etWUAChTgUm/gQaX2TqhTUzZTFOa', 2),
(4, 'badminton', '$2y$10$00W4AoB2/cQf1MdmhPxVx.TOkGRQFwKjyDNggie3rzPLFHbI7WrDK', 3),
(5, 'pickleball', '$2y$10$5esacaPr0nYTmHRywultgu1uqRM7muTDOAf7dt3JGX.pWvNFDM1vi', 4);

-- --------------------------------------------------------

--
-- Table structure for table `live_scores`
--

CREATE TABLE `live_scores` (
  `match_id` int(11) NOT NULL,
  `score_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`score_json`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_scores`
--

INSERT INTO `live_scores` (`match_id`, `score_json`) VALUES
(1, '{\"team1_score\":3,\"team2_score\":2}'),
(2, '{\"team1_score\":18,\"team2_score\":0,\"last_animation\":{\"id\":1,\"type\":\"SUPER RAID\",\"team\":\"t1\",\"timestamp\":1767272055},\"is_timeout\":false}'),
(3, '{\"team1_score\":3,\"team2_score\":0,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767272568194,\"team\":\"t1\",\"type\":\"WINNER\"},\"history\":[]}'),
(4, '{\"team1_score\":2,\"team2_score\":1,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767349848732,\"team\":\"t1\",\"type\":\"WINNER\"},\"history\":[]}'),
(5, '{\"team1_score\":21,\"team2_score\":2,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767415924057,\"team\":\"t1\",\"type\":\"WINNER\"},\"history\":[]}'),
(6, '{\"team1_score\":2,\"team2_score\":1,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767416597703,\"team\":\"t1\",\"type\":\"WINNER\"},\"history\":[]}'),
(7, '{\"team1_score\":0,\"team2_score\":3,\"t1_players\":4,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767438032133,\"team\":\"t2\",\"type\":\"WINNER\"},\"history\":[]}'),
(8, '{\"team1_score\":0,\"team2_score\":3,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767438325978,\"team\":\"t2\",\"type\":\"WINNER\"},\"history\":[]}'),
(9, '{\"team1_score\":1,\"team2_score\":0,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767440072141,\"team\":\"t1\",\"type\":\"WINNER\"},\"history\":[],\"t1_sets\":2,\"current_set\":3,\"server\":\"t1\"}'),
(10, '{\"team1_score\":0,\"team2_score\":5,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"is_timeout\":false,\"last_animation\":{\"id\":1767440220237,\"team\":\"t2\",\"type\":\"WINNER\"},\"history\":[],\"t1_sets\":1,\"current_set\":3,\"server\":null,\"t2_sets\":2}'),
(11, '{\"team1_score\":10,\"team2_score\":0,\"server\":null,\"t1_sets\":2,\"t2_sets\":0,\"current_set\":2,\"is_timeout\":false,\"t1_players\":7,\"t2_players\":7,\"current_raider\":\"team1\",\"last_animation\":{\"id\":1767444544032,\"team\":\"t1\",\"type\":\"WINNER\"},\"history\":[]}'),
(12, '{\"team1_score\":23,\"team2_score\":6,\"server\":\"t1\",\"t1_sets\":7,\"t2_sets\":1,\"current_set\":2,\"is_timeout\":false,\"set_history\":[{\"set_number\":2,\"team1_score\":23,\"team2_score\":6,\"winner\":\"t1\"},{\"set_number\":2,\"team1_score\":23,\"team2_score\":6,\"winner\":\"t1\"},{\"set_number\":2,\"team1_score\":23,\"team2_score\":6,\"winner\":\"t1\"}],\"last_animation\":{\"id\":1767446040567,\"team\":\"t1\",\"type\":\"WINNER\"}}'),
(13, '{\"team1_score\":6,\"team2_score\":0,\"server\":null,\"t1_sets\":2,\"t2_sets\":0,\"current_set\":3,\"is_timeout\":false,\"set_history\":[{\"set_number\":1,\"team1_score\":0,\"team2_score\":0,\"winner\":null},{\"set_number\":2,\"team1_score\":1,\"team2_score\":0,\"winner\":\"t1\"},{\"set_number\":3,\"team1_score\":6,\"team2_score\":0,\"winner\":\"t1\"}],\"last_animation\":{\"id\":1767446152216,\"team\":\"t1\",\"type\":\"WINNER\"}}'),
(14, '{\"team1_score\":1,\"team2_score\":3,\"current_raider\":\"team2\",\"t1_players\":7,\"t2_players\":6,\"is_timeout\":false,\"last_animation\":{\"id\":1767529175166,\"team\":\"t2\",\"type\":\"WINNER\"}}'),
(15, '{\"team1_score\":0,\"team2_score\":3,\"server\":\"t2\",\"t1_sets\":1,\"t2_sets\":2,\"current_set\":3,\"is_timeout\":false,\"set_history\":[{\"set_number\":1,\"team1_score\":1,\"team2_score\":5,\"winner\":\"t2\"},{\"set_number\":2,\"team1_score\":1,\"team2_score\":0,\"winner\":\"t1\"},{\"set_number\":3,\"team1_score\":0,\"team2_score\":3,\"winner\":\"t2\"}],\"last_animation\":{\"id\":1767529410932,\"team\":\"t2\",\"type\":\"WINNER\"}}'),
(16, '{\"team1_score\":7,\"team2_score\":0,\"t1_sets\":2,\"t2_sets\":0,\"current_set\":2,\"server\":\"t1\",\"is_timeout\":false,\"set_history\":[{\"set_number\":1,\"team1_score\":5,\"team2_score\":0,\"winner\":\"t1\"},{\"set_number\":2,\"team1_score\":7,\"team2_score\":0,\"winner\":\"t1\"}],\"last_animation\":{\"id\":1767532621411,\"team\":\"t1\",\"type\":\"WINNER\"}}'),
(17, '{\"team1_score\":1,\"team2_score\":5,\"t1_sets\":2,\"t2_sets\":1,\"current_set\":3,\"server\":null,\"is_timeout\":false,\"set_history\":[{\"set_number\":1,\"team1_score\":10,\"team2_score\":0,\"winner\":\"t1\"},{\"set_number\":2,\"team1_score\":0,\"team2_score\":0,\"winner\":null},{\"set_number\":3,\"team1_score\":1,\"team2_score\":0,\"winner\":\"t1\"},{\"set_number\":3,\"team1_score\":1,\"team2_score\":5,\"winner\":\"t2\"}],\"last_animation\":{\"id\":1767532776496,\"team\":\"t2\",\"type\":\"WINNER\"}}'),
(18, '{\"team1_score\":1,\"team2_score\":0,\"t1_sets\":1,\"t2_sets\":0,\"current_set\":2,\"server\":null,\"is_timeout\":false,\"set_history\":[{\"set_number\":1,\"team1_score\":1,\"team2_score\":0,\"winner\":\"t1\"}],\"last_animation\":{\"id\":1767535065148,\"team\":\"t1\",\"type\":\"WINNER\"}}'),
(19, '{\"team1_score\":1,\"team2_score\":0,\"server\":null,\"t1_sets\":2,\"t2_sets\":0,\"current_set\":2,\"is_timeout\":false,\"set_history\":[{\"set_number\":1,\"team1_score\":1,\"team2_score\":0,\"winner\":\"t1\"},{\"set_number\":2,\"team1_score\":1,\"team2_score\":0,\"winner\":\"t1\"}],\"last_animation\":{\"id\":1767535132933,\"team\":\"t1\",\"type\":\"WINNER\"}}');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `team1_id` int(11) NOT NULL,
  `team2_id` int(11) NOT NULL,
  `status` enum('upcoming','live','completed') DEFAULT 'upcoming',
  `match_time` datetime NOT NULL,
  `venue` varchar(100) DEFAULT NULL,
  `round` varchar(50) DEFAULT NULL,
  `winner_team` tinyint(4) DEFAULT NULL,
  `win_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `sport_id`, `team1_id`, `team2_id`, `status`, `match_time`, `venue`, `round`, `winner_team`, `win_description`) VALUES
(1, 1, 1, 2, 'completed', '2026-01-01 17:33:00', 'SRKR', 'GROUP STAGE', 1, ''),
(2, 1, 2, 1, 'completed', '2026-01-01 18:11:55', '', '', 1, ''),
(3, 1, 1, 2, 'completed', '2026-01-01 18:31:31', '', '', 1, 'SRKR CHMPS won by 3 points'),
(4, 2, 3, 4, 'completed', '2026-01-02 15:58:09', '', '', 1, 'SRKR CHMPS won by 1 points'),
(5, 1, 5, 6, 'completed', '2026-01-03 10:09:31', '', '', 1, 'san won by 19 points'),
(6, 2, 3, 4, 'completed', '2026-01-03 10:32:45', '', '', 1, 'SRKR CHMPS won by 1 points'),
(7, 1, 7, 8, 'completed', '2026-01-03 16:08:48', '', '', 2, 'Team 2 won by 3 points'),
(8, 1, 9, 10, 'completed', '2026-01-03 16:34:08', '', '', 2, 'bgfvdc won by 3 points'),
(9, 3, 11, 12, 'completed', '2026-01-03 16:38:12', '', '', 1, 'bhanu & Sanu won by 1 points'),
(10, 3, 11, 12, 'completed', '2026-01-03 17:05:52', '', '', 2, 'Rishi & Vasi won by 5 points'),
(11, 3, 11, 12, 'completed', '2026-01-03 17:07:29', '', '', 1, 'bhanu & Sanu won by 10 points'),
(12, 3, 13, 14, 'completed', '2026-01-03 18:19:57', '', '', 1, 'kjbhg won by 17 points'),
(13, 3, 11, 12, 'completed', '2026-01-03 18:44:40', '', '', 1, 'bhanu & Sanu won by 6 points'),
(14, 1, 8, 5, 'completed', '2026-01-04 17:33:13', '', '', 2, 'san won by 2 points'),
(15, 3, 14, 11, 'completed', '2026-01-04 17:50:19', '', '', 2, 'Team 2 won by 3 points'),
(16, 2, 3, 4, 'completed', '2026-01-04 18:15:39', '', '', 1, 'SRKR CHMPS won by 7 points'),
(17, 4, 15, 16, 'completed', '2026-01-04 18:47:46', '', '', 2, 'fvdcsx won by 4 points'),
(18, 4, 17, 18, 'completed', '2026-01-04 19:27:21', '', '', 1, 'jnv ihg won 1-0'),
(19, 3, 11, 14, 'completed', '2026-01-04 19:28:27', '', '', 1, 'bhanu & Sanu won 2-0');

-- --------------------------------------------------------

--
-- Table structure for table `sports`
--

CREATE TABLE `sports` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sports`
--

INSERT INTO `sports` (`id`, `name`) VALUES
(3, 'badminton'),
(1, 'kabaddi'),
(4, 'pickleball'),
(2, 'volleyball');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `college_name` varchar(150) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `gender` enum('Men','Women') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `college_name`, `sport_id`, `gender`) VALUES
(1, 'SRKR CHMPS', 'SRKREC', 1, NULL),
(2, 'VIT WARRIORS', 'VITB', 1, NULL),
(3, 'SRKR CHMPS', 'SRKREC', 2, 'Men'),
(4, 'VIT WARRIORS', 'VITB', 2, 'Men'),
(5, 'san', 'san', 1, 'Women'),
(6, 'man', 'man', 1, 'Men'),
(7, 'vfcd', 'fds', 1, 'Women'),
(8, 'feds', 'bgvfc', 1, 'Women'),
(9, 'vfvcdxs', 'gbfdc', 1, 'Men'),
(10, 'bgfvdc', 'gbdc', 1, 'Men'),
(11, 'bhanu & Sanu', 'SRKREC', 3, 'Men'),
(12, 'Rishi & Vasi', 'VITB', 3, 'Men'),
(13, 'kjbhg', 'oiuhjygfv', 3, 'Women'),
(14, 'k;jh', 'oiuyt', 3, 'Women'),
(15, 'fdsa', 'efds', 4, 'Men'),
(16, 'fvdcsx', 'vfdcsx', 4, 'Men'),
(17, 'jnv ihg', 'hv', 4, 'Men'),
(18, 'jbh', 'ikujhg', 4, 'Men');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `allowed_sport_id` (`allowed_sport_id`);

--
-- Indexes for table `live_scores`
--
ALTER TABLE `live_scores`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sport_id` (`sport_id`),
  ADD KEY `team1_id` (`team1_id`),
  ADD KEY `team2_id` (`team2_id`);

--
-- Indexes for table `sports`
--
ALTER TABLE `sports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_team_sport` (`name`,`sport_id`),
  ADD KEY `sport_id` (`sport_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sports`
--
ALTER TABLE `sports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`allowed_sport_id`) REFERENCES `sports` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `live_scores`
--
ALTER TABLE `live_scores`
  ADD CONSTRAINT `live_scores_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`team1_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matches_ibfk_3` FOREIGN KEY (`team2_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

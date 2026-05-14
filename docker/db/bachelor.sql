-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Vært: mariadb
-- Genereringstid: 14. 05 2026 kl. 09:32:46
-- Serverversion: 10.6.20-MariaDB-ubu2004
-- PHP-version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bachelor`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `educations`
--

CREATE TABLE `educations` (
  `education_pk` bigint(20) UNSIGNED NOT NULL,
  `education_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `educations`
--

INSERT INTO `educations` (`education_pk`, `education_name`) VALUES
(9, 'Cybersikkerhed'),
(5, 'Datamatiker'),
(4, 'Design & Business'),
(1, 'Designteknolog'),
(12, 'Digital Konceptudvikling'),
(2, 'Entreprenørskab & Design'),
(10, 'IT-arkitektur'),
(13, 'IT-sikkerhed'),
(6, 'IT-teknolog'),
(7, 'Multimediedesign'),
(8, 'Operationel Cybersikkerhed'),
(16, 'Optometri'),
(3, 'Smykker Teknologi & Business'),
(14, 'Softwareudvikling'),
(15, 'Webudvikling'),
(11, 'Økonomi & IT');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `events`
--

CREATE TABLE `events` (
  `event_pk` char(36) NOT NULL,
  `event_title` varchar(150) NOT NULL,
  `event_subtitle` varchar(150) DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_expectations` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `event_end_time` time DEFAULT NULL,
  `event_location` varchar(150) DEFAULT NULL,
  `category_fk` char(36) DEFAULT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `created_by_fk` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `events`
--

INSERT INTO `events` (`event_pk`, `event_title`, `event_subtitle`, `event_description`, `event_expectations`, `event_date`, `event_time`, `event_end_time`, `event_location`, `category_fk`, `event_image`, `created_by_fk`, `created_at`, `reminder_sent_at`, `deleted_at`) VALUES
('2327f709-4e02-11f1-975b-0242ac1d0002', 'Karaoke Night', 'Syng dine yndlingssange', 'Tag dine venner med til en sjov karaokeaften med drinks og god stemning.', 'Musik, dans og masser af grin', '2026-06-15', '20:00:00', '23:30:00', 'Downtown Bar', '1', 'fredagsbar-udenfor.webp', 1, '2026-05-12 16:46:58', NULL, NULL),
('2f7ba6e5-4e02-11f1-975b-0242ac1d0002', 'Coding Bootcamp', 'Lær webudvikling på én dag', 'En intensiv workshop hvor du lærer HTML, CSS og JavaScript fra bunden.', 'Praktiske øvelser og nye skills', '2026-07-01', '09:00:00', '16:00:00', 'KEA Guldbergsgade', '2', 'fredagsbar-udenfor.webp', 1, '2026-05-12 16:47:18', NULL, NULL),
('912af718-4dff-11f1-975b-0242ac1d0002', 'Sommerfest på taget', 'DJ og drinks hele aftenen', 'Kom og vær med til årets sommerfest med musik, drinks og udsigt over byen.', 'God stemning og fest hele natten', '2026-06-20', '19:00:00', '02:00:00', 'Nørrebro Tagterrasse', '1', 'fredagsbar-udenfor.webp', 1, '2026-05-12 12:39:06', NULL, NULL),
('df5ef636-4478-11f1-b685-0242ac1d0002', 'Fredagsbar', 'GBG Social inviterer til en hyggelig fredagsbar, h.', 'GBG Social inviterer til en hyggelig fredagsbar, hvor studerende kan mødes og skabe nye relationer i afslappede omgivelser.', 'Kolde drinks i baren\r\nGod musik og stemning\r\nMulighed for at møde nye mennesker\r\nHygge og fællesskab', '2026-06-20', '18:00:00', '23:00:00', 'KEA Kantinen', '1', 'fredagsbar-udenfor.webp', 1, '2026-05-07 12:18:16', NULL, NULL),
('df5efe72-4478-11f1-b685-0242ac1d0002', 'Fodboldturnering', 'Torsdag d. 5 marts kl. 14:00', 'Kom og vær med til en aktiv dag med fodbold, hvor både begyndere og øvede kan deltage.', 'Holdturnering\r\nPræmier til vinderne\r\nGod energi og fællesskab\r\nMulighed for nye bekendtskaber', '2026-03-05', '14:00:00', '18:00:00', 'KEA Boldbane', '4', 'turnering.webp', 1, '2026-05-07 12:18:16', NULL, NULL),
('df5effe9-4478-11f1-b685-0242ac1d0002', 'CV Workshop', 'Tirsdag d. 10 marts kl. 10:00', 'Få hjælp til at optimere dit CV og forbedre dine jobmuligheder gennem sparring og feedback.', 'Feedback på CV\r\nTips til ansøgninger\r\nVejledning fra erfarne studerende\r\nMulighed for spørgsmål', '2026-03-10', '10:00:00', '13:00:00', 'Lokale A1', '2', 'workshop.webp', 1, '2026-05-07 12:18:16', NULL, NULL);

--
-- Triggers/udløsere `events`
--
DELIMITER $$
CREATE TRIGGER `trg_events_before_insert_admin_only` BEFORE INSERT ON `events` FOR EACH ROW BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM `users`
    INNER JOIN `roles`
      ON `users`.`role_fk` = `roles`.`role_pk`
    WHERE `users`.`user_pk` = NEW.created_by_fk
      AND `roles`.`role_name` = 'admin'
  ) THEN

    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Only admin users can create events';

  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_events_before_update_admin_only` BEFORE UPDATE ON `events` FOR EACH ROW BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM `users`
    INNER JOIN `roles`
      ON `users`.`role_fk` = `roles`.`role_pk`
    WHERE `users`.`user_pk` = NEW.created_by_fk
      AND `roles`.`role_name` = 'admin'
  ) THEN

    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Only admin users can create or own events';

  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `event_categories`
--

CREATE TABLE `event_categories` (
  `category_pk` char(36) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `event_categories`
--

INSERT INTO `event_categories` (`category_pk`, `category_name`) VALUES
('1', 'Social'),
('2', 'Fagligt'),
('3', 'Krea'),
('4', 'Sport');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `event_registrations`
--

CREATE TABLE `event_registrations` (
  `registration_pk` char(36) NOT NULL,
  `event_fk` char(36) NOT NULL,
  `user_fk` bigint(20) UNSIGNED NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `event_registrations`
--

INSERT INTO `event_registrations` (`registration_pk`, `event_fk`, `user_fk`, `registered_at`) VALUES
('34e6b8f9-4484-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 1, '2026-05-07 12:18:16'),
('34e703af-4484-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 2, '2026-05-07 12:18:16'),
('681d5803-4483-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 4, '2026-05-07 12:18:16'),
('8472c84e-c6f5-4e60-8e17-cdffb5b2428a', 'df5efe72-4478-11f1-b685-0242ac1d0002', 1, '2026-05-12 12:53:05');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `members`
--

CREATE TABLE `members` (
  `member_pk` char(36) NOT NULL,
  `user_fk` bigint(20) UNSIGNED NOT NULL,
  `education_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `semester_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `application_text` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers/udløsere `members`
--
DELIMITER $$
CREATE TRIGGER `trg_members_after_update_approve` AFTER UPDATE ON `members` FOR EACH ROW BEGIN
  IF NEW.status = 'approved'
     AND OLD.status <> 'approved' THEN

    UPDATE `users`
    SET `role_fk` = '2'
    WHERE `user_pk` = NEW.user_fk;

  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_members_after_update_soft_delete` AFTER UPDATE ON `members` FOR EACH ROW BEGIN
  IF NEW.deleted_at IS NOT NULL
    AND OLD.deleted_at IS NULL THEN

    UPDATE `users`
    SET `role_fk` = '3'
    WHERE `user_pk` = NEW.user_fk
      AND `role_fk` = '2';

  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `roles`
--

CREATE TABLE `roles` (
  `role_pk` char(36) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `roles`
--

INSERT INTO `roles` (`role_pk`, `role_name`) VALUES
('1', 'admin'),
('2', 'member'),
('3', 'user');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `semesters`
--

CREATE TABLE `semesters` (
  `semester_pk` bigint(20) UNSIGNED NOT NULL,
  `semester_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `semesters`
--

INSERT INTO `semesters` (`semester_pk`, `semester_number`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

CREATE TABLE `users` (
  `user_pk` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_last_name` varchar(30) DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `user_deleted_at` timestamp NULL DEFAULT NULL,
  `user_verified_at` timestamp NULL DEFAULT NULL,
  `user_verification_key` char(36) DEFAULT NULL,
  `role_fk` char(36) DEFAULT '3',
  `user_profile_image` varchar(255) DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_at` timestamp NULL DEFAULT NULL,
  `login_unlock_key` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `users`
--

INSERT INTO `users` (`user_pk`, `user_name`, `user_last_name`, `user_email`, `user_password`, `user_created_at`, `user_updated_at`, `user_deleted_at`, `user_verified_at`, `user_verification_key`, `role_fk`, `user_profile_image`, `failed_login_attempts`, `locked_at`, `login_unlock_key`) VALUES
(1, 'Admin', 'Strator', 'admin@admin.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-12 07:52:23', NULL, '2026-05-12 07:55:15', NULL, '1', NULL, 0, NULL, NULL),
(2, 'Naomi', 'Rasmussen', 'n@r.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-14 09:32:19', NULL, '2026-05-12 07:52:49', NULL, '3', NULL, 0, NULL, NULL),
(3, 'Madeleine', 'Madsen', 'm@m.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-12 07:52:18', NULL, '2026-05-12 07:53:12', NULL, '3', NULL, 0, NULL, NULL),
(4, 'Kamilla', 'Huhnke', 'k@h.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-12 07:52:15', NULL, '2026-05-12 07:54:15', NULL, '3', NULL, 0, NULL, NULL);

--
-- Begrænsninger for dumpede tabeller
--

--
-- Indeks for tabel `educations`
--
ALTER TABLE `educations`
  ADD PRIMARY KEY (`education_pk`),
  ADD UNIQUE KEY `education_name` (`education_name`);

--
-- Indeks for tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_pk`),
  ADD KEY `fk_event_created_by` (`created_by_fk`),
  ADD KEY `fk_event_category` (`category_fk`);

--
-- Indeks for tabel `event_categories`
--
ALTER TABLE `event_categories`
  ADD PRIMARY KEY (`category_pk`);

--
-- Indeks for tabel `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`registration_pk`),
  ADD UNIQUE KEY `event_fk` (`event_fk`,`user_fk`),
  ADD KEY `fk_user` (`user_fk`);

--
-- Indeks for tabel `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_pk`),
  ADD UNIQUE KEY `unique_member_user` (`user_fk`),
  ADD KEY `fk_member_approved_by` (`approved_by_fk`),
  ADD KEY `fk_member_education` (`education_fk`),
  ADD KEY `fk_member_semester` (`semester_fk`);

--
-- Indeks for tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_pk`);

--
-- Indeks for tabel `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_pk`),
  ADD UNIQUE KEY `semester_number` (`semester_number`);

--
-- Indeks for tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_pk`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `fk_user_role` (`role_fk`);

--
-- Brug ikke AUTO_INCREMENT for slettede tabeller
--

--
-- Tilføj AUTO_INCREMENT i tabel `educations`
--
ALTER TABLE `educations`
  MODIFY `education_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tilføj AUTO_INCREMENT i tabel `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tilføj AUTO_INCREMENT i tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Begrænsninger for dumpede tabeller
--

--
-- Begrænsninger for tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_event_category` FOREIGN KEY (`category_fk`) REFERENCES `event_categories` (`category_pk`),
  ADD CONSTRAINT `fk_event_created_by` FOREIGN KEY (`created_by_fk`) REFERENCES `users` (`user_pk`);

--
-- Begrænsninger for tabel `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `fk_event` FOREIGN KEY (`event_fk`) REFERENCES `events` (`event_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;

--
-- Begrænsninger for tabel `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `fk_member_approved_by` FOREIGN KEY (`approved_by_fk`) REFERENCES `users` (`user_pk`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_member_education` FOREIGN KEY (`education_fk`) REFERENCES `educations` (`education_pk`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_member_semester` FOREIGN KEY (`semester_fk`) REFERENCES `semesters` (`semester_pk`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_member_user` FOREIGN KEY (`user_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;

--
-- Begrænsninger for tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_fk`) REFERENCES `roles` (`role_pk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

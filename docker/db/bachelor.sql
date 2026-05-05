-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
  `event_category` varchar(100) DEFAULT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `created_by_fk` char(36) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `events` (`event_pk`, `event_title`, `event_subtitle`, `event_description`, `event_expectations`, `event_date`, `event_time`, `event_end_time`, `event_location`, `event_category`, `event_image`, `created_by_fk`, `created_at`) VALUES
('df5ef636-4478-11f1-b685-0242ac1d0002', 'Fredagsbar', 'GBG Social inviterer til en hyggelig fredagsbar, h.', 'GBG Social inviterer til en hyggelig fredagsbar, hvor studerende kan mødes og skabe nye relationer i afslappede omgivelser.', 'Kolde drinks i baren\r\nGod musik og stemning\r\nMulighed for at møde nye mennesker\r\nHygge og fællesskab', '2026-02-20', '18:00:00', '23:00:00', 'KEA Kantinen', 'Social', 'fredagsbar.png', 'f55051bf-4478-11f1-b685-0242ac1d0002', '2026-04-30 09:42:15'),
('df5efe72-4478-11f1-b685-0242ac1d0002', 'Fodboldturnering', 'Torsdag d. 5 marts kl. 14:00', 'Kom og vær med til en aktiv dag med fodbold, hvor både begyndere og øvede kan deltage.', 'Holdturnering\r\nPræmier til vinderne\r\nGod energi og fællesskab\r\nMulighed for nye bekendtskaber', '2026-03-05', '14:00:00', '18:00:00', 'KEA Boldbane', 'Sport', 'fredagsbar.png', 'f55051bf-4478-11f1-b685-0242ac1d0002', '2026-04-30 09:42:15'),
('df5effe9-4478-11f1-b685-0242ac1d0002', 'CV Workshop', 'Tirsdag d. 10 marts kl. 10:00', 'Få hjælp til at optimere dit CV og forbedre dine jobmuligheder gennem sparring og feedback.', 'Feedback på CV\r\nTips til ansøgninger\r\nVejledning fra erfarne studerende\r\nMulighed for spørgsmål', '2026-03-10', '10:00:00', '13:00:00', 'Lokale A1', 'Fagligt', 'fredagsbar.png', 'f55051bf-4478-11f1-b685-0242ac1d0002', '2026-04-30 09:42:15');

CREATE TABLE `event_registrations` (
  `registration_pk` char(36) NOT NULL,
  `event_fk` char(36) NOT NULL,
  `user_fk` char(36) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `event_registrations` (`registration_pk`, `event_fk`, `user_fk`, `registered_at`) VALUES
('34e6b8f9-4484-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 'c0f7a820-4483-11f1-b685-0242ac1d0002', '2026-04-30 12:04:20'),
('34e703af-4484-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 'c0f7db51-4483-11f1-b685-0242ac1d0002', '2026-04-30 12:04:20'),
('681d5803-4483-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 'f55059c4-4478-11f1-b685-0242ac1d0002', '2026-04-30 11:58:37');

CREATE TABLE `members` (
  `member_pk` char(36) NOT NULL,
  `user_fk` char(36) NOT NULL,
  `education` varchar(100) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `application_text` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by_fk` char(36) DEFAULT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL,
  `deleted_at` bigint(20) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `roles` (
  `role_pk` char(36) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `roles` (`role_pk`, `role_name`) VALUES
('1', 'admin'),
('2', 'member'),
('3', 'user');

CREATE TABLE `users` (
  `user_pk` char(36) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_last_name` varchar(30) DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created_at` bigint(20) UNSIGNED NOT NULL,
  `user_updated_at` bigint(20) UNSIGNED DEFAULT 0,
  `user_deleted_at` bigint(20) UNSIGNED DEFAULT 0,
  `user_verified_at` bigint(20) UNSIGNED DEFAULT 0,
  `user_verification_key` char(36) DEFAULT NULL,
  `role_fk` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`user_pk`, `user_name`, `user_last_name`, `user_email`, `user_password`, `user_created_at`, `user_updated_at`, `user_deleted_at`, `user_verified_at`, `user_verification_key`, `role_fk`) VALUES
('c0f7a820-4483-11f1-b685-0242ac1d0002', 'Benny', 'Bestyrelse', 'benny@gbg.dk', 'hashed_password', 1777550466, 0, 0, 0, NULL, '2'),
('c0f7db51-4483-11f1-b685-0242ac1d0002', 'Sara', 'Rasmussen', 'sara@gbg.dk', 'hashed_password', 1777550466, 0, 0, 0, NULL, '3'),
('c0f7dfc5-4483-11f1-b685-0242ac1d0002', 'Mads', 'Jensen', 'mads@gbg.dk', 'hashed_password', 1777550466, 0, 0, 0, NULL, '3'),
('c0f7e08f-4483-11f1-b685-0242ac1d0002', 'Sofie', 'Hansen', 'sofie@gbg.dk', 'hashed_password', 1777550466, 0, 0, 0, NULL, '3'),
('f55051bf-4478-11f1-b685-0242ac1d0002', 'HA', 'Formand', 'admin@gbg.dk', 'hashed_password_123', 1777542172, 0, 0, 0, NULL, '1'),
('f55059c4-4478-11f1-b685-0242ac1d0002', 'Naomi', 'Rasmussen', 'naomi@gbg.dk', 'hashed_password_123', 1777542172, 0, 0, 0, NULL, '3');

ALTER TABLE `events`
  ADD PRIMARY KEY (`event_pk`),
  ADD KEY `fk_event_created_by` (`created_by_fk`);

ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`registration_pk`),
  ADD UNIQUE KEY `event_fk` (`event_fk`,`user_fk`),
  ADD KEY `fk_user` (`user_fk`);

ALTER TABLE `members`
  ADD PRIMARY KEY (`member_pk`),
  ADD UNIQUE KEY `unique_member_user` (`user_fk`),
  ADD KEY `fk_member_approved_by` (`approved_by_fk`);

ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_pk`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_pk`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `fk_user_role` (`role_fk`);

ALTER TABLE `events`
  ADD CONSTRAINT `fk_event_created_by` FOREIGN KEY (`created_by_fk`) REFERENCES `users` (`user_pk`);

ALTER TABLE `event_registrations`
  ADD CONSTRAINT `fk_event` FOREIGN KEY (`event_fk`) REFERENCES `events` (`event_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;

ALTER TABLE `members`
  ADD CONSTRAINT `fk_member_approved_by` FOREIGN KEY (`approved_by_fk`) REFERENCES `users` (`user_pk`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_member_user` FOREIGN KEY (`user_fk`) REFERENCES `users` (`user_pk`) ON DELETE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_fk`) REFERENCES `roles` (`role_pk`);

DELIMITER $$

CREATE TRIGGER `trg_members_after_update_approve`
AFTER UPDATE ON `members`
FOR EACH ROW
BEGIN
  IF NEW.status = 'approved' AND OLD.status <> 'approved' THEN
    UPDATE `users`
    SET `role_fk` = '2'
    WHERE `user_pk` = NEW.user_fk;
  END IF;
END$$

CREATE TRIGGER `trg_members_after_update_soft_delete`
AFTER UPDATE ON `members`
FOR EACH ROW
BEGIN
  IF NEW.deleted_at <> 0 AND OLD.deleted_at = 0 THEN
    UPDATE `users`
    SET `role_fk` = '3'
    WHERE `user_pk` = NEW.user_fk
      AND `role_fk` = '2';
  END IF;
END$$

CREATE TRIGGER `trg_events_before_insert_admin_only`
BEFORE INSERT ON `events`
FOR EACH ROW
BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM `users`
    INNER JOIN `roles` ON `users`.`role_fk` = `roles`.`role_pk`
    WHERE `users`.`user_pk` = NEW.created_by_fk
      AND `roles`.`role_name` = 'admin'
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Only admin users can create events';
  END IF;
END$$

CREATE TRIGGER `trg_events_before_update_admin_only`
BEFORE UPDATE ON `events`
FOR EACH ROW
BEGIN
  IF NOT EXISTS (
    SELECT 1
    FROM `users`
    INNER JOIN `roles` ON `users`.`role_fk` = `roles`.`role_pk`
    WHERE `users`.`user_pk` = NEW.created_by_fk
      AND `roles`.`role_name` = 'admin'
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Only admin users can create or own events';
  END IF;
END$$

DELIMITER ;

COMMIT;
-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Vært: mariadb
-- Genereringstid: 28. 05 2026 kl. 09:34:57
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  `event_why_join` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `events`
--

INSERT INTO `events` (`event_pk`, `event_title`, `event_subtitle`, `event_description`, `event_expectations`, `event_date`, `event_time`, `event_end_time`, `event_location`, `category_fk`, `event_image`, `created_by_fk`, `created_at`, `reminder_sent_at`, `deleted_at`, `event_why_join`) VALUES
('2327f709-4e02-11f1-975b-0242ac1d0002', 'Karaoke Night', 'Klar på en aften fyldt med musik, grin og god stemning?', 'Så tag dine venner under armen og kom med til vores Karaoke Night – en hyggelig og festlig aften, hvor scenen er åben for alle! Uanset om du elsker at synge, eller bare vil nyde stemningen og heppe på dine venner, bliver det en aften fyldt med sjove øjeblikke og fællesskab.\n\nHer handler det ikke om at synge perfekt – det handler om at have det sjovt, skabe gode minder og nyde aftenen sammen. Så find dine yndlingssange frem og gør dig klar til alt fra klassiske hits til guilty pleasures', 'Karaoke med alle de største hits\nGod stemning og hyggeligt fællesskab\nDrinks og festlig atmosfære\nMulighed for at synge solo eller sammen med venner\nSjove øjeblikke og masser af grin\nEn afslappet aften hvor alle kan være med', '2026-06-15', '20:00:00', '23:30:00', 'Downtown Bar', '1', 'karaoke.webp', 1, '2026-05-12 16:46:58', NULL, NULL, 'Karaoke Night er den perfekte mulighed for at koble af, møde nye mennesker og skabe gode oplevelser sammen uden for studiet. Om du går all-in på scenen eller bare nyder stemningen, lover vi en aften med masser af energi, musik og fællesskab'),
('2f7ba6e5-4e02-11f1-975b-0242ac1d0002', 'Coding Bootcamp', 'Klar til at tage dine coding skills til næste niveau?', 'Kom med til vores Coding Bootcamp – en intensiv og lærerig workshop, hvor du får mulighed for at dykke ned i grundlæggende webudvikling og lære at kode fra bunden. Uanset om du er helt ny inden for programmering eller gerne vil styrke dine færdigheder, er bootcampen skabt til at give dig en stærk start.\n\nI løbet af dagen arbejder vi hands-on med HTML, CSS og JavaScript gennem små opgaver og praktiske øvelser, så du får erfaring med at bygge dine egne webprojekter i et trygt og socialt læringsmiljø.', 'Grundlæggende HTML og opbygning af hjemmesider\nStyling med CSS\nIntroduktion til JavaScript og interaktivitet\nHvordan man arbejder med struktur og design\nGode coding-vaner og tips til videre læring\nPraktisk erfaring gennem øvelser og mini-projekter', '2026-07-01', '09:00:00', '16:00:00', 'KEA Guldbergsgade', '2', 'samling-af-mennesker.webp', 1, '2026-05-12 16:47:18', NULL, NULL, 'Coding Bootcamp er en oplagt mulighed for at lære nye skills, møde andre med samme interesse og få et solidt fundament inden for webudvikling. Du går hjem med ny viden, praktisk erfaring og motivation til at fortsætte din coding-rejse'),
('912af718-4dff-11f1-975b-0242ac1d0002', 'Sommerfest på taget', 'Sommeren kalder – og det gør festen også', 'Kom med til årets sommerfest på taget, hvor vi samler studerende til en aften fyldt med musik, drinks, solnedgang og fantastisk stemning. Med udsigt over byen og sommer vibes hele aftenen er der lagt op til en uforglemmelig aften med fællesskab, dans og hygge.\n\nTag dine venner med og nyd en afslappet sommeraften på tagterrassen, hvor vi fejrer sommeren sammen med god musik, kolde drinks og masser af gode mennesker.', 'Sommerfest med DJ og god musik\nDrinks og hyggelig stemning\nFlot udsigt over byen fra tagterrassen\nMulighed for at møde nye mennesker\nDansegulv og sommer vibes hele aftenen\nEn afslappet og social aften under åben himmel', '2026-06-20', '19:00:00', '02:00:00', 'Nørrebro Tagterrasse', '1', 'sommerfest.webp', 1, '2026-05-12 12:39:06', NULL, NULL, 'Sommerfest på taget er den perfekte måde at kickstarte sommeren på. Forvent en aften fyldt med energi, grin, musik og nye bekendtskaber i de bedste omgivelser. Så find sommeroutfittet frem og gør dig klar til en aften, du ikke vil gå glip af'),
('df5ef636-4478-11f1-b685-0242ac1d0002', 'Fredagsbar', 'Start weekenden med god stemning og hyggeligt fællesskab', 'GBG Social inviterer til fredagsbar – en afslappet og hyggelig aften, hvor studerende kan mødes, slappe af efter ugen og skabe nye relationer i uformelle omgivelser.\n\nKom alene eller tag dine venner med til en aften fyldt med gode samtaler, musik og social hygge. Fredagsbaren er det perfekte sted at møde nye mennesker på tværs af studier og nyde en afslappet start på weekenden.', 'Hyggelig fredagsstemning\nMusik og gode vibes\nMulighed for at møde nye mennesker\nDrinks og socialt fællesskab\nAfslappede omgivelser efter en lang uge\nEn perfekt start på weekenden', '2026-06-20', '18:00:00', '23:00:00', 'KEA Kantinen', '1', 'fredagsbar-udenfor.webp', 1, '2026-05-07 12:18:16', NULL, NULL, 'Fredagsbaren handler om fællesskab, gode oplevelser og at skabe relationer uden for undervisningen. Det er en oplagt mulighed for at lære nye mennesker at kende og nyde en hyggelig aften sammen med andre studerende'),
('df5efe72-4478-11f1-b685-0242ac1d0002', 'Fodboldturnering', 'Kom og vær med til årets fedeste fodboldturnering', 'Er du klar til en dag fyldt med fart, fællesskab og god stemning?\nSå tag dine venner med til vores store fodboldturnering, hvor alle kan være med – uanset niveau! Om du spiller hver weekend eller bare vil have det sjovt og møde nye mennesker, er du mere end velkommen.\n\nVi sørger for en energifyldt dag med spændende kampe, masser af grin og en fantastisk atmosfære. Holdene bliver sammensat, så alle får mulighed for at spille, hygge sig og være en del af fællesskabet.', 'Spændende holdturnering\nPræmier til vinderholdet\nMusik og god stemning\nNye bekendtskaber og stærkt fællesskab\nMasser af aktivitet og sjove oplevelser\nMulighed for både begyndere og øvede', '2026-03-05', '14:00:00', '18:00:00', 'KEA Boldbane', '4', 'turnering.webp', 1, '2026-05-07 12:18:16', NULL, NULL, 'Fodboldturneringen handler om meget mere end bare fodbold. Det er en mulighed for at møde nye mennesker, være aktiv og få en sjov oplevelse sammen med andre studerende i en afslappet og energifyldt atmosfære.\n\nUanset om du spiller fodbold til dagligt eller bare har lyst til at prøve noget nyt, er der plads til alle. Fokus er på fællesskab, god stemning og at have det sjovt sammen – både på og uden for banen.'),
('df5effe9-4478-11f1-b685-0242ac1d0002', 'CV Workshop', 'Boost dit CV og styrk dine jobmuligheder', 'Vil du gerne gøre dit CV mere professionelt og skille dig ud, når du søger job eller praktik? Så kom med til vores CV Workshop, hvor du får mulighed for at arbejde målrettet med dit CV sammen med andre studerende og få værdifuld feedback undervejs.\n\nWorkshoppen er for alle – både dig der allerede har et CV, og dig der ikke helt ved, hvor du skal starte. Vi hjælper dig med at fremhæve dine kompetencer, erfaringer og styrker, så du står stærkere i din jobsøgning.', 'Sparring og feedback på dit CV\nTips til opbygning og layout\nHjælp til at formulere dine erfaringer professionelt\nGode råd til studiejob, praktik og fremtidige ansøgninger\nInspiration fra andre studerende\nMulighed for at stille spørgsmål og få individuel hjælp', '2026-03-10', '10:00:00', '13:00:00', 'Lokale A1', '2', 'workshop.webp', 1, '2026-05-07 12:18:16', NULL, NULL, 'Et godt CV kan være afgørende, når du søger studiejob, praktik eller dit første fuldtidsjob. Denne workshop giver dig konkrete værktøjer og feedback, som du kan bruge med det samme – og måske endda åbne døren til nye muligheder');

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
('07775ced-23fe-4f5f-a9fd-d8d69fbbd4cb', 'df5efe72-4478-11f1-b685-0242ac1d0002', 17, '2026-05-20 12:14:26'),
('0b5eee99-a7cc-400c-b9f4-6fbadc128b24', 'df5efe72-4478-11f1-b685-0242ac1d0002', 10, '2026-05-19 12:17:46'),
('10af5de4-ecf8-4401-ae3a-b95f524a9c69', 'df5efe72-4478-11f1-b685-0242ac1d0002', 16, '2026-05-20 12:14:07'),
('3345c9049cd3274a26d2b012573adc2c', 'df5efe72-4478-11f1-b685-0242ac1d0002', 3, '2026-05-25 17:48:37'),
('34e6b8f9-4484-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 1, '2026-05-07 12:18:16'),
('34e703af-4484-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 2, '2026-05-07 12:18:16'),
('49c0c3e3-29cd-46cb-8949-72b453b32398', 'df5efe72-4478-11f1-b685-0242ac1d0002', 13, '2026-05-20 12:13:06'),
('4ac66ed9-8e68-4928-80a0-4cd5d16181cd', 'df5efe72-4478-11f1-b685-0242ac1d0002', 15, '2026-05-20 12:13:44'),
('5c9f2e5a-08a2-4400-991d-da2ee5161190', 'df5efe72-4478-11f1-b685-0242ac1d0002', 9, '2026-05-19 12:17:19'),
('681d5803-4483-11f1-b685-0242ac1d0002', 'df5ef636-4478-11f1-b685-0242ac1d0002', 4, '2026-05-07 12:18:16'),
('8472c84e-c6f5-4e60-8e17-cdffb5b2428a', 'df5efe72-4478-11f1-b685-0242ac1d0002', 1, '2026-05-12 12:53:05'),
('b54113ea-6a2e-4487-a653-beb3e29d9ad0', 'df5efe72-4478-11f1-b685-0242ac1d0002', 11, '2026-05-19 12:18:08'),
('b93b718e-fdcc-409f-b7e5-452d8fe83b9a', 'df5efe72-4478-11f1-b685-0242ac1d0002', 14, '2026-05-20 12:13:26'),
('c743effc-1d94-470c-9096-b4cfab596ca6', 'df5efe72-4478-11f1-b685-0242ac1d0002', 12, '2026-05-19 12:18:41'),
('de6afda5-853d-4771-9194-aef002336f8a', 'df5efe72-4478-11f1-b685-0242ac1d0002', 18, '2026-05-20 12:14:49');

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
-- Data dump for tabellen `members`
--

INSERT INTO `members` (`member_pk`, `user_fk`, `education_fk`, `semester_fk`, `application_text`, `status`, `approved_by_fk`, `applied_at`, `approved_at`, `deleted_at`) VALUES
('18109ec1-54f6-11f1-9e03-266b729c1588', 1, 15, 7, NULL, 'approved', 1, '2026-05-21 09:18:56', '2026-05-21 09:18:56', NULL),
('34477f87d9927a771226841bda8fa5ff', 9, 10, 3, 'mmmm', 'approved', 1, '2026-05-28 08:49:33', '2026-05-28 09:04:42', NULL),
('53f344ce1426b5557da3651bb66fd3b8', 18, 15, 6, 'mm', 'approved', 1, '2026-05-28 09:03:33', '2026-05-28 09:04:16', NULL),
('65183913a0c5178be543590abb3f1084', 13, 6, 5, 'mmm', 'approved', 1, '2026-05-28 08:55:56', '2026-05-28 09:04:30', NULL),
('6da17b8aba15c90c58c0bca0e01049d4', 10, 14, 6, 'tggg', 'approved', 1, '2026-05-28 08:50:51', '2026-05-28 09:04:39', NULL),
('8a393967826a36dfab23e849228cec32', 14, 7, 3, 'mmm', 'approved', 1, '2026-05-28 08:57:15', '2026-05-28 09:04:27', NULL),
('9caabc3487343086fc31df3a8a0305a5', 15, 1, 4, 'mmm', 'approved', 1, '2026-05-28 08:59:16', '2026-05-28 09:04:25', NULL),
('bbe44c3c6ac3a774b3ac888f17edcbc0', 16, 7, 2, 'dd', 'approved', 1, '2026-05-28 09:00:29', '2026-05-28 09:04:22', NULL),
('bd820d7b58efab2ee8c31044d0abf927', 11, 4, 5, 'mmmm', 'approved', 1, '2026-05-28 08:52:28', '2026-05-28 09:04:36', NULL),
('dda68203e99e7350c5205a339006bf91', 12, 5, 4, 'mmm', 'approved', 1, '2026-05-28 08:54:23', '2026-05-28 09:04:33', NULL),
('ff81bd76ffd0a6dc98b07c9f7462c9b9', 17, 11, 3, 'mmm', 'approved', 1, '2026-05-28 09:02:04', '2026-05-28 09:04:20', NULL);

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
  `login_unlock_key` varchar(32) DEFAULT NULL,
  `password_reset_key` varchar(32) DEFAULT NULL,
  `reset_key_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Data dump for tabellen `users`
--

INSERT INTO `users` (`user_pk`, `user_name`, `user_last_name`, `user_email`, `user_password`, `user_created_at`, `user_updated_at`, `user_deleted_at`, `user_verified_at`, `user_verification_key`, `role_fk`, `user_profile_image`, `failed_login_attempts`, `locked_at`, `login_unlock_key`, `password_reset_key`, `reset_key_expires_at`) VALUES
(1, 'Admin', 'Strator', 'admin@admin.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-12 07:52:23', NULL, '2026-05-12 07:55:15', NULL, '1', NULL, 0, NULL, NULL, NULL, NULL),
(2, 'Naomi', 'Rasmussen', 'n@r.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-21 09:20:54', NULL, '2026-05-12 07:52:49', NULL, '3', NULL, 0, NULL, NULL, NULL, NULL),
(3, 'Madeleine', 'Madsen', 'm@m.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-27 09:25:12', NULL, '2026-05-12 07:53:12', NULL, '3', NULL, 0, NULL, NULL, NULL, NULL),
(4, 'Kamilla', 'Huhnke', 'k@h.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-21 09:20:59', NULL, '2026-05-12 07:54:15', NULL, '3', NULL, 0, NULL, NULL, NULL, NULL),
(9, 'Louise', 'Hansen', 'medlem1@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:42', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a18019d68b536.37522256.png', 0, NULL, NULL, NULL, NULL),
(10, 'Tobias', 'Lauridsen', 'medlem2@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:39', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a1801eb1a6a31.83134302.png', 0, NULL, NULL, NULL, NULL),
(11, 'Mille', 'Johansen', 'medlem3@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:36', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a18024c5421c5.50248653.png', 0, NULL, NULL, NULL, NULL),
(12, 'Mikkel', 'Koefod', 'medlem4@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:33', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a1802bf4ecf26.82750142.png', 0, NULL, NULL, NULL, NULL),
(13, 'Sofus', 'Solberg', 'medlem5@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:30', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a18031cd7e294.22553788.png', 0, NULL, NULL, NULL, NULL),
(14, 'Christian', 'Kruse', 'medlem6@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:27', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a18036bca3190.99874384.png', 0, NULL, NULL, NULL, NULL),
(15, 'Mikela', 'Vestager', 'medlem7@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:25', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a1803e478cd57.07840172.png', 0, NULL, NULL, NULL, NULL),
(16, 'Emilie', 'Falch', 'medlem8@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:22', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a18042d988eb6.10613413.png', 0, NULL, NULL, NULL, NULL),
(17, 'Elias', 'Prahl', 'medlem9@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:20', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a18048cc5f296.93770265.png', 0, NULL, NULL, NULL, NULL),
(18, 'Sofie', 'Rasmussen', 'medlem10@medlem.com', '$2y$10$6XVUYnhke5s.EFQi0.FQ.uET2uiYOZysL.ZUsozMxltUIlG5sgs26', '2026-05-07 12:18:16', '2026-05-28 09:04:16', NULL, '2026-05-12 07:54:15', NULL, '2', 'profile_6a1804e5173a95.37287598.png', 0, NULL, NULL, NULL, NULL);

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
  MODIFY `user_pk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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


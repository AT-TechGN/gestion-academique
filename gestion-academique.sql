-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 14 juin 2025 à 00:49
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion-academique`
--

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `volume_horaire` int(11) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `nom`, `code`, `volume_horaire`, `description`) VALUES
(1, 'Web technologie 1', 'HTML & CSS', 3, 'Web technologie 1'),
(2, 'Le Framework Django', 'Django', 3, 'Cours complet django et python');

-- --------------------------------------------------------

--
-- Structure de la table `cours_enseignant`
--

CREATE TABLE `cours_enseignant` (
  `cours_id` int(11) NOT NULL,
  `enseignant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cours_enseignant`
--

INSERT INTO `cours_enseignant` (`cours_id`, `enseignant_id`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `cours_etudiant`
--

CREATE TABLE `cours_etudiant` (
  `etudiant_id` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250530041446', '2025-05-30 06:15:57', 16179),
('DoctrineMigrations\\Version20250531194702', '2025-05-31 21:48:24', 685),
('DoctrineMigrations\\Version20250601042954', '2025-06-01 06:33:42', 13),
('DoctrineMigrations\\Version20250601203534', '2025-06-01 22:36:51', 186),
('DoctrineMigrations\\Version20250601230218', '2025-06-02 01:02:24', 59),
('DoctrineMigrations\\Version20250601231926', '2025-06-02 01:19:31', 120),
('DoctrineMigrations\\Version20250602235135', '2025-06-03 01:51:50', 80),
('DoctrineMigrations\\Version20250603010538', '2025-06-03 03:05:47', 92),
('DoctrineMigrations\\Version20250605201819', '2025-06-05 22:18:25', 53),
('DoctrineMigrations\\Version20250607193250', '2025-06-14 00:15:20', 156),
('DoctrineMigrations\\Version20250613222108', '2025-06-14 00:21:16', 17);

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `fichier` varchar(255) NOT NULL,
  `date_upload` datetime NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `document`
--

INSERT INTO `document` (`id`, `cours_id`, `titre`, `fichier`, `date_upload`, `type`) VALUES
(1, 1, 'Conception des systeme informatiques', '683e4ad676333.pdf', '2025-06-03 03:07:31', 'cours'),
(2, 1, 'Le Framework Django (python)', '68421a638cef8.pdf', '2025-06-06 00:29:55', 'cours');

-- --------------------------------------------------------

--
-- Structure de la table `emploi`
--

CREATE TABLE `emploi` (
  `id` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL,
  `enseignant_id` int(11) NOT NULL,
  `salle` varchar(50) NOT NULL,
  `jour` varchar(10) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `emploi`
--

INSERT INTO `emploi` (`id`, `cours_id`, `enseignant_id`, `salle`, `jour`, `heure_debut`, `heure_fin`) VALUES
(1, 1, 2, 'Amphi B1', 'Mardi', '11:00:00', '14:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `specialite` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`id`, `user_id`, `specialite`, `telephone`, `nom`, `prenom`) VALUES
(1, 1, 'PHP', '627979359', 'Traoré', 'Alseny'),
(2, 3, 'Python', '627806101', 'Sacko', 'Brema');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant_cours`
--

CREATE TABLE `enseignant_cours` (
  `enseignant_id` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `enseignant_cours`
--

INSERT INTO `enseignant_cours` (`enseignant_id`, `cours_id`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `matricule` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `user_id`, `matricule`, `date_naissance`, `adresse`, `telephone`, `photo`, `nom`, `prenom`) VALUES
(1, NULL, '664120792245', '2004-05-11', 'Coyah', '627979359', '683cb34f95f36.png', 'Traoré', 'Alceny');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:192:\\\"http://localhost/gestion-academique/public/verify/email?expires=1748724545&signature=SElePLqHpGYwDKESTTZWCwlTi4L3ysHatzhvX48Qnm4%3D&token=hHU7jBuLevK7z%2Fg6W%2Bu1npY%2Bw08OX%2F7iylSqmzQjSmM%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"alcenytraore68@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:6:\\\"Traore\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"alcenytraore68@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:25:\\\"Please Confirm your Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-05-31 19:49:09', '2025-05-31 19:49:09', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `cours_id` int(11) NOT NULL,
  `note` decimal(5,2) NOT NULL,
  `date_eval` date NOT NULL,
  `type` varchar(20) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `enseignant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id`, `etudiant_id`, `cours_id`, `note`, `date_eval`, `type`, `is_published`, `enseignant_id`) VALUES
(1, 1, 1, '10.00', '2025-05-01', '', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`, `created_at`) VALUES
(1, 'alcenytraore68@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$4pyBtf0hA8PzNF3rYQG.3ezBZ7m2Q7pxHj.NVflmiU3rutsEMbYlS', 0, '2025-05-31 20:33:42'),
(3, 'prof@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$HDnmSxR8duNC7qun6jpqI.LnuZTv0LHhMdKFC2Td5HdN2AYlYC6Ra', 1, '2025-06-04 16:07:54'),
(4, 'prof2@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$y8EALBFs4ix.yxDPWcOOqOVc6jZnrqfkOVkZxlcRVhhfc1nIxG/4O', 1, '2025-06-04 18:15:26'),
(5, 'enseignant@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$cJfBTOmTvjTQPMM6eBmJT.QAn1Iy5cSW2PR2q/jaPVemwWy84wfjO', 0, '2025-06-05 22:43:57');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cours_enseignant`
--
ALTER TABLE `cours_enseignant`
  ADD PRIMARY KEY (`cours_id`,`enseignant_id`),
  ADD KEY `IDX_845FDD887ECF78B0` (`cours_id`),
  ADD KEY `IDX_845FDD88E455FCC0` (`enseignant_id`);

--
-- Index pour la table `cours_etudiant`
--
ALTER TABLE `cours_etudiant`
  ADD PRIMARY KEY (`etudiant_id`,`cours_id`),
  ADD KEY `IDX_B6EA8C3EDDEAB1A3` (`etudiant_id`),
  ADD KEY `IDX_B6EA8C3E7ECF78B0` (`cours_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D8698A767ECF78B0` (`cours_id`);

--
-- Index pour la table `emploi`
--
ALTER TABLE `emploi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_74A0B0FA7ECF78B0` (`cours_id`),
  ADD KEY `IDX_74A0B0FAE455FCC0` (`enseignant_id`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_81A72FA1A76ED395` (`user_id`);

--
-- Index pour la table `enseignant_cours`
--
ALTER TABLE `enseignant_cours`
  ADD PRIMARY KEY (`enseignant_id`,`cours_id`),
  ADD KEY `IDX_D6684A95E455FCC0` (`enseignant_id`),
  ADD KEY `IDX_D6684A957ECF78B0` (`cours_id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_717E22E3A76ED395` (`user_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CFBDFA14DDEAB1A3` (`etudiant_id`),
  ADD KEY `IDX_CFBDFA147ECF78B0` (`cours_id`),
  ADD KEY `IDX_CFBDFA14E455FCC0` (`enseignant_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `emploi`
--
ALTER TABLE `emploi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `enseignant`
--
ALTER TABLE `enseignant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cours_enseignant`
--
ALTER TABLE `cours_enseignant`
  ADD CONSTRAINT `FK_845FDD887ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_845FDD88E455FCC0` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignant` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cours_etudiant`
--
ALTER TABLE `cours_etudiant`
  ADD CONSTRAINT `FK_B6EA8C3E7ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B6EA8C3EDDEAB1A3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `FK_D8698A767ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`);

--
-- Contraintes pour la table `emploi`
--
ALTER TABLE `emploi`
  ADD CONSTRAINT `FK_74A0B0FA7ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  ADD CONSTRAINT `FK_74A0B0FAE455FCC0` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignant` (`id`);

--
-- Contraintes pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `FK_81A72FA1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `enseignant_cours`
--
ALTER TABLE `enseignant_cours`
  ADD CONSTRAINT `FK_D6684A957ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D6684A95E455FCC0` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignant` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `FK_717E22E3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `FK_CFBDFA147ECF78B0` FOREIGN KEY (`cours_id`) REFERENCES `cours` (`id`),
  ADD CONSTRAINT `FK_CFBDFA14DDEAB1A3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`),
  ADD CONSTRAINT `FK_CFBDFA14E455FCC0` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignant` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

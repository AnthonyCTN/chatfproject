-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 13 juin 2024 à 08:46
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chatfr`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE `amis` (
  `id_utilisateur1` int(11) NOT NULL,
  `id_utilisateur2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `amis`
--

INSERT INTO `amis` (`id_utilisateur1`, `id_utilisateur2`) VALUES
(2, 1),
(4, 1),
(15, 1),
(2, 2),
(5, 2),
(1, 4),
(5, 4),
(2, 5),
(6, 5),
(7, 5),
(8, 5),
(14, 5),
(4, 6),
(5, 6),
(7, 6),
(12, 6),
(5, 7),
(6, 7),
(10, 8),
(5, 10),
(5, 11),
(5, 12),
(15, 13),
(2, 15),
(5, 15),
(15, 15);

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `id_expediteur` int(11) DEFAULT NULL,
  `id_destinataire` int(11) DEFAULT NULL,
  `contenu` text,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `id_expediteur`, `id_destinataire`, `contenu`, `timestamp`) VALUES
(98, 14, 5, 'salut bien ou bien ?', '2024-06-07 11:38:24'),
(99, 5, 14, 'Bien et toi ?', '2024-06-07 11:38:36'),
(100, 15, 13, 'Bonjour Antho1', '2024-06-08 11:35:08'),
(101, 15, 13, 'Le clic sur voir Profil ne fonctionne pas...', '2024-06-08 11:35:43'),
(102, 15, 4, 'Hé bien!', '2024-06-08 11:45:23'),
(103, 15, 1, 'Ici la voix', '2024-06-12 11:28:12'),
(104, 15, 15, 'Moi à moi', '2024-06-12 11:30:37'),
(105, 15, 15, 'Jury', '2024-06-12 11:30:48'),
(106, 1, 15, 'haha bonjour la voix ;)', '2024-06-12 18:39:24');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visibility` enum('public','private') DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `timestamp`, `visibility`) VALUES
(1, 8, 'SALUT JE SUIS Nouveau\r\n', '2024-06-05 23:25:29', 'public'),
(2, 5, 'Ouai moi aussi ', '2024-06-05 23:25:43', 'public'),
(9, 5, 'Bon C’est pas tout mais je vais allez dodo , ???? ', '2024-06-06 00:31:59', 'public'),
(10, 5, 'Bon allez je vais dodo ', '2024-06-06 00:43:23', 'public'),
(11, 6, 'Salut ', '2024-06-06 08:33:54', 'public'),
(12, 2, 'Un bon réveille ;)\r\n\r\n', '2024-06-06 08:38:03', 'public'),
(13, 5, 'Salut', '2024-06-06 09:02:16', 'public'),
(17, 5, 'Ho le chef', '2024-06-06 14:32:59', 'public'),
(18, 13, 'salut je suis nouveau', '2024-06-07 09:34:32', 'public'),
(19, 14, 'salut\r\n', '2024-06-07 09:37:33', 'public'),
(20, 15, 'Hello', '2024-06-08 09:32:30', 'public'),
(21, 15, 'Test du jour', '2024-06-12 09:26:22', 'public'),
(22, 5, 'bonjour Jury ;)\r\n', '2024-06-12 16:40:52', 'public');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `biographie` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `pseudo`, `mot_de_passe`, `email`, `biographie`) VALUES
(1, 'antho', '$2y$10$andyGRulXhDs6wY4qmSAIewuM3yPe0mvl949VtkB9Mp3BDVwYwezy', 'cantone@gmail.com', NULL),
(2, 'az', '$2y$10$OeGBvxAs2M0ho3MqX5gBg.iAo.UqMwwgpzyUxKtU4pM/61gu204YK', 'azerty@gmail.com', 'Salut a tous '),
(3, 'aq', '$2y$10$kwdRIEOdmFoQjwDPXk.uH.1cwxozlInONRANssH5PSl5jJ9VlO9e2', 'cantonepro5@gmail.com', NULL),
(4, 'goat', '$2y$10$HFIAEgbFz7N9it9naKTD7u.jhUqWebATxl5ocgT9/ehsXuo0Wuth.', 'azrty@gmail.com', NULL),
(5, 'Sobadtv', '$2y$10$fT0K4P6zsmI3Uit9Kep45ObApKL0DVfBDAmiBkxXU2YgmO5jxhZPq', 'cantone1@gmail.com', 'Yo l’equipe ajajsjsjz'),
(6, 'Trykz', '$2y$10$0WbCpTm/vXst2eet2KaIHO/KTDFEHFEKPCzuMuCx2e0l03cYDarDG', 'cantoneloucas5@gmail.com', NULL),
(7, 'Shadow', '$2y$10$cfPWDf9e5uwjSXgOdPxgiuF7nZTQCCWm9G0XJHtb.Y8BhAxBq7Y7.', 'anaiscantone84000@live.fr', NULL),
(8, 'aqa', '$2y$10$NfWtqFXiTWYKLOQGBwPlIehGLW42OXkSTXmdo7m8VGXOO6CAEGUDm', 'az@gmail.com', NULL),
(10, 'as', '$2y$10$bsxFcSMABKXaSe1DHwFcO.POXD0oqLSbBnrz9AtfmPviD3VdlOGOa', 'azaa@gmail.com', NULL),
(11, 'carla', '$2y$10$NG9C5mJ/tBw3K5FgS2IQq.IICNRIjbUFZqPHvgHeAshGUYW56BF.q', 'carla@gmail.com', NULL),
(12, 'Chef', '$2y$10$PAfptpWc3VCeOv7F24rQveFeePeOq7WfvPbfQeH5Wdz0xOKuINOou', 'pipi84@gmail.com', NULL),
(13, 'antho1', '$2y$10$3ldqNUZSPyeE0/Nt3UWw1u8EVuP8YnCAS33aUJLSD/RJdxU2BAlFS', 'antho1@gmail.com', NULL),
(14, 'antho2', '$2y$10$Mhv9OkjalWQKcBUibUW0t.lzOlJW3Ea/6tKksHP8zTsiDM8QkzS/K', 'antho2@gmail.com', 'salut je suis nouveau'),
(15, 'Jury', '$2y$10$uEN9hczXw/mEJNIRamdOK.kmOit/K5nfGgvEBqjkq2ELkBG93NNZG', 'arsene.fath-richard@ac-aix-marseille.fr', 'Membre du lycée Théodore Aubanel\r\n\r\ngfj'),
(16, 'antho122', '$2y$10$HBzfeH0tZ6HognKN4sBEr.kwQE5PgOau/ZQfOx2NlV1Gg6.EwAoym', 'antho12@gmail.com', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`id_utilisateur1`,`id_utilisateur2`),
  ADD KEY `id_utilisateur2` (`id_utilisateur2`);

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_expediteur` (`id_expediteur`),
  ADD KEY `id_destinataire` (`id_destinataire`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `amis`
--
ALTER TABLE `amis`
  ADD CONSTRAINT `amis_ibfk_1` FOREIGN KEY (`id_utilisateur1`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `amis_ibfk_2` FOREIGN KEY (`id_utilisateur2`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_expediteur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

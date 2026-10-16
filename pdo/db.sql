CREATE TABLE `links` (
  `id` int NOT NULL,
  `url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Bolcament de dades per a la taula `links`
--

INSERT INTO `links` (`id`, `url`, `name`, `short`, `user_id`) VALUES
(1, 'https://cendrassos.net/', 'Web Institut Cendrassos', 'ic-web', 1),
(2, 'https://classroom.google.com/', 'Google Classroom', 'classroom', 1),
(3, 'https://www.xtec.cat/', 'XTEC - Xarxa Telemàtica Educativa de Catalunya', 'xtec', 1),
(4, 'https://educaciodigital.cat/inscendrassos/moodle/', 'Moodle Institut Cendrassos', 'moodle', 1),
(5, 'https://drive.google.com/', 'Google Drive', 'drive', 1),
(6, 'https://cat.wikipedia.org/', 'Viquipèdia en català', 'wiki-cat', 1),
(7, 'https://github.com/', 'GitHub', 'github', 1),
(8, 'https://forms.gle/', 'Formularis de Google', 'forms', 1),
(9, 'https://calendar.google.com/', 'Google Calendar', 'calendar', 1),
(10, 'https://docs.google.com/', 'Google Docs', 'g-docs', 1);


CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `name`, `user`, `password`, `deleted`) VALUES
(1, NULL, 'dprados@cendrassos.net', '$2y$12$GoiXCmAfU9yDJEmfcYEsQe/lBxeiticEIK7.hHbrJ6pncL5GIrQF6', 0);


ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_links_short` (`short`),
  ADD KEY `idx_links_user_id` (`user_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

ALTER TABLE `links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `links`
  ADD CONSTRAINT `fk_links_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

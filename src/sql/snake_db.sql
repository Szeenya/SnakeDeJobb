-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Gép: localhost:3306
-- Létrehozás ideje: 2025. Jan 12. 15:58
-- Kiszolgáló verziója: 5.7.24
-- PHP verzió: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `snake_db`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `difficulty`
--

CREATE TABLE `difficulty` (
  `difficulty_id` int(11) NOT NULL,
  `difficulty_level` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `game`
--

CREATE TABLE `game` (
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gamemode_id` int(11) NOT NULL,
  `difficulty_id` int(11) NOT NULL,
  `map_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `gamemode`
--

CREATE TABLE `gamemode` (
  `gamemode_id` int(11) NOT NULL,
  `gamemode_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `leaderboard`
--

CREATE TABLE `leaderboard` (
  `leaderboard_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `map`
--

CREATE TABLE `map` (
  `map_id` int(11) NOT NULL,
  `map_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `scores`
--

CREATE TABLE `scores` (
  `score_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `personal_best` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `difficulty`
--
ALTER TABLE `difficulty`
  ADD PRIMARY KEY (`difficulty_id`),
  ADD UNIQUE KEY `difficulty_level` (`difficulty_level`);

--
-- A tábla indexei `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gamemode_id` (`gamemode_id`),
  ADD KEY `difficulty_id` (`difficulty_id`),
  ADD KEY `map_id` (`map_id`);

--
-- A tábla indexei `gamemode`
--
ALTER TABLE `gamemode`
  ADD PRIMARY KEY (`gamemode_id`),
  ADD UNIQUE KEY `gamemode_name` (`gamemode_name`);

--
-- A tábla indexei `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`leaderboard_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `score_id` (`score_id`);

--
-- A tábla indexei `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`map_id`),
  ADD UNIQUE KEY `map_name` (`map_name`);

--
-- A tábla indexei `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`score_id`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `difficulty`
--
ALTER TABLE `difficulty`
  MODIFY `difficulty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `game`
--
ALTER TABLE `game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `gamemode`
--
ALTER TABLE `gamemode`
  MODIFY `gamemode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `leaderboard_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `map`
--
ALTER TABLE `map`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `scores`
--
ALTER TABLE `scores`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `game_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_ibfk_2` FOREIGN KEY (`gamemode_id`) REFERENCES `gamemode` (`gamemode_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_ibfk_3` FOREIGN KEY (`difficulty_id`) REFERENCES `difficulty` (`difficulty_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `game_ibfk_4` FOREIGN KEY (`map_id`) REFERENCES `map` (`map_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leaderboard_ibfk_2` FOREIGN KEY (`score_id`) REFERENCES `scores` (`score_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

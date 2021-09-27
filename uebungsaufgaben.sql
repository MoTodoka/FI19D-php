-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 27. Sep 2021 um 09:10
-- Server-Version: 10.4.20-MariaDB
-- PHP-Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `uebungsaufgaben`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aufgabe_2`
--

CREATE TABLE `aufgabe_2` (
  `uid` int(11) NOT NULL,
  `name` char(20) DEFAULT NULL,
  `number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `aufgabe_2`
--

INSERT INTO `aufgabe_2` (`uid`, `name`, `number`) VALUES
(1, 'Peter', 575),
(2, 'Harald', 123),
(3, 'Lena', 69),
(4, 'Fabian', 420),
(5, 'Paul', 18);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `aufgabe_2`
--
ALTER TABLE `aufgabe_2`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `table_test_uid_uindex` (`uid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `aufgabe_2`
--
ALTER TABLE `aufgabe_2`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

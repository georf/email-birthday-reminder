SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `birthdays`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `birthday-logs`
--

CREATE TABLE IF NOT EXISTS `birthday-logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` tinytext NOT NULL,
  `type` tinytext NOT NULL,
  `table` tinytext NOT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `set` text NOT NULL,
  `logged` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `birthdays`
--

CREATE TABLE IF NOT EXISTS `birthdays` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext CHARACTER SET utf8 NOT NULL,
  `hint` tinytext CHARACTER SET utf8 NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

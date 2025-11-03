-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-11-2025 a las 07:13:52
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `empresacotxes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotxes`
--

DROP TABLE IF EXISTS `cotxes`;
CREATE TABLE IF NOT EXISTS `cotxes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuari_id` int NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricula` (`matricula`),
  KEY `usuari_id` (`usuari_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cotxes`
--

INSERT INTO `cotxes` (`id`, `usuari_id`, `marca`, `model`, `matricula`) VALUES
(1, 1, 'Seat', 'Ibiza', '1234ABC'),
(2, 1, 'Tesla', 'Model 3', '5678DEF'),
(3, 2, 'Volkswagen', 'Golf', '9999ZZZ'),
(4, 3, 'Peugeot', '208', '7777GGG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horaris`
--

DROP TABLE IF EXISTS `horaris`;
CREATE TABLE IF NOT EXISTS `horaris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuari_id` int NOT NULL,
  `ruta_id` int NOT NULL,
  `cotxe_id` int DEFAULT NULL,
  `data` date NOT NULL,
  `hora_inici` time NOT NULL,
  `hora_fi` time DEFAULT NULL,
  `comentaris` text,
  PRIMARY KEY (`id`),
  KEY `usuari_id` (`usuari_id`),
  KEY `ruta_id` (`ruta_id`),
  KEY `cotxe_id` (`cotxe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `horaris`
--

INSERT INTO `horaris` (`id`, `usuari_id`, `ruta_id`, `cotxe_id`, `data`, `hora_inici`, `hora_fi`, `comentaris`) VALUES
(1, 1, 1, 1, '2025-10-07', '08:00:00', '09:30:00', 'Viatge a Girona per feina'),
(2, 1, 4, 2, '2025-10-07', '17:00:00', '17:40:00', 'Tornada a casa'),
(3, 2, 2, 3, '2025-10-07', '09:00:00', '10:30:00', 'Reunió a Lleida'),
(4, 3, 3, 4, '2025-10-07', '10:00:00', '10:50:00', 'Entrega de mercaderies'),
(5, 2, 4, 3, '2025-10-08', '08:30:00', '09:10:00', 'Viatge curt a Sitges');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutes`
--

DROP TABLE IF EXISTS `rutes`;
CREATE TABLE IF NOT EXISTS `rutes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `origen` varchar(150) NOT NULL,
  `desti` varchar(150) NOT NULL,
  `distancia_km` decimal(6,2) DEFAULT NULL,
  `duracio_estimada` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rutes`
--

INSERT INTO `rutes` (`id`, `origen`, `desti`, `distancia_km`, `duracio_estimada`) VALUES
(1, 'Barcelona', 'Girona', 100.50, '01:15:00'),
(2, 'Tarragona', 'Lleida', 120.30, '01:30:00'),
(3, 'València', 'Castelló', 70.80, '00:50:00'),
(4, 'Barcelona', 'Sitges', 40.20, '00:35:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuaris`
--

DROP TABLE IF EXISTS `usuaris`;
CREATE TABLE IF NOT EXISTS `usuaris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `imatge_perfil` varchar(255) DEFAULT 'default.png',
  `correu` varchar(100) NOT NULL,
  `contrasenya` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correu` (`correu`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuaris`
--

INSERT INTO `usuaris` (`id`, `nom`, `imatge_perfil`, `correu`, `contrasenya`) VALUES
(1, 'Joan Martí', 'default.png', 'joan@example.com', '1234'),
(2, 'Maria López', 'default.png', 'maria@example.com', 'abcd'),
(3, 'Pau Torres', 'default.png', 'pau@example.com', 'pass');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

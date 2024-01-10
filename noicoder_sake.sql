-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2023 a las 10:55:39
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `noicoder_sake`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clanes`
--

CREATE TABLE `clanes` (
  `ID` int(11) NOT NULL,
  `Nombre` text NOT NULL,
  `miembros` int(11) NOT NULL DEFAULT 0,
  `matados` int(11) NOT NULL,
  `muertos` int(11) NOT NULL,
  `puntos` int(11) NOT NULL,
  `rank_puntos` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clanes`
--

INSERT INTO `clanes` (`ID`, `Nombre`, `miembros`, `matados`, `muertos`, `puntos`, `rank_puntos`) VALUES
(1, 'Pichula', 0, 5, 15, 20, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `num` int(11) NOT NULL DEFAULT 0,
  `cfg` text NOT NULL DEFAULT '',
  `ultimoupd` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pjs`
--

CREATE TABLE `pjs` (
  `id` int(11) NOT NULL,
  `nick` text NOT NULL,
  `codigo` text NOT NULL,
  `PIN` text NOT NULL,
  `clan` int(11) NOT NULL,
  `frags` int(11) NOT NULL,
  `muertes` int(11) NOT NULL DEFAULT 0,
  `rank` int(11) NOT NULL,
  `ultimologin` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `puntos` int(11) NOT NULL,
  `rank_frags_old` int(11) NOT NULL,
  `rank_puntos_old` int(11) NOT NULL,
  `rank_puntos` int(11) NOT NULL,
  `rank_frags` int(11) NOT NULL,
  `ultimosv` text NOT NULL,
  `partidos` int(11) NOT NULL,
  `mail` text NOT NULL,
  `GM` int(11) NOT NULL DEFAULT 0,
  `Ban` int(11) NOT NULL DEFAULT 0,
  `Bantxt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servers`
--

CREATE TABLE `servers` (
  `ID` int(11) NOT NULL,
  `Nombre` text NOT NULL,
  `players` int(11) NOT NULL DEFAULT 0,
  `Mapa` text NOT NULL,
  `IP` text NOT NULL,
  `inicio` datetime NOT NULL,
  `ultima` datetime NOT NULL,
  `keysec` text NOT NULL,
  `PORT` int(11) NOT NULL,
  `hamachi` int(11) NOT NULL,
  `HOST` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud-clan`
--

CREATE TABLE `solicitud-clan` (
  `ID` int(11) NOT NULL,
  `clan` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clanes`
--
ALTER TABLE `clanes`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `pjs`
--
ALTER TABLE `pjs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servers`
--
ALTER TABLE `servers`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indices de la tabla `solicitud-clan`
--
ALTER TABLE `solicitud-clan`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clanes`
--
ALTER TABLE `clanes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pjs`
--
ALTER TABLE `pjs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servers`
--
ALTER TABLE `servers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `solicitud-clan`
--
ALTER TABLE `solicitud-clan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

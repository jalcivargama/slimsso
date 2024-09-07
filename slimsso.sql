-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-09-2024 a las 03:45:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `slimsso`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system`
--

CREATE TABLE `system` (
  `id_system` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `system`
--

INSERT INTO `system` (`id_system`, `name`, `description`) VALUES
(1, 'BRJ', 'Bienes Raices Julio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `secondlastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `user`, `password`, `name`, `lastname`, `secondlastname`, `email`, `status`) VALUES
(1, 'jalcivar', '$2y$12$RU/9Aob7Ke1qhNArYGOIzebY3Ae22r/3fdRptW4JvRoT03nCE7ZqS', 'Juan Manuel', 'Alcivar', 'Gama', 'jalcivar@grupo-ditec.com.mx', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_system`
--

CREATE TABLE `user_system` (
  `user` int(11) DEFAULT NULL,
  `system` int(11) DEFAULT NULL,
  `status` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_system`
--

INSERT INTO `user_system` (`user`, `system`, `status`) VALUES
(1, 1, 'A');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`id_system`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indices de la tabla `user_system`
--
ALTER TABLE `user_system`
  ADD KEY `FK_user_system_user` (`user`),
  ADD KEY `FK_user_system_system` (`system`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `system`
--
ALTER TABLE `system`
  MODIFY `id_system` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `user_system`
--
ALTER TABLE `user_system`
  ADD CONSTRAINT `FK_user_system_system` FOREIGN KEY (`system`) REFERENCES `system` (`id_system`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_system_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

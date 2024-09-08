-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-09-2024 a las 05:18:33
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
-- Estructura de tabla para la tabla `usersystem`
--

CREATE TABLE `usersystem` (
  `id` int(11) NOT NULL,
  `user` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `secondlastname` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `modifydate` datetime DEFAULT NULL,
  `usrupd` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usersystem`
--

INSERT INTO `usersystem` (`id`, `user`, `password`, `name`, `lastname`, `secondlastname`, `email`, `status`, `modifydate`, `usrupd`) VALUES
(1, 'jalcivar', '$2y$12$RU/9Aob7Ke1qhNArYGOIzebY3Ae22r/3fdRptW4JvRoT03nCE7ZqS', 'Juan Manuel', 'Alcivar', 'Gama', 'jalcivar@grupo-ditec.com.mx', 'A', NULL, NULL),
(2, 'JUANMA', 'pasaword', 'JUAN MANUEL', 'ALCIVAR ', 'GAMA', 'A@A.COM', 'A', '2024-09-07 20:43:12', NULL),
(17, 'JUANMA2', '$2y$12$nR76rIdK0AiGIwhFB3V.FudK0K73K9bMbqMJkPOxJXO0hIT4AIZp.', 'JUAN MANUEL', 'ALCIVAR', 'GAMA', 'masterwoong@gmail.com', 'A', '2024-09-07 21:08:26', 'jalcivar'),
(19, 'JUANMA33', '$2y$12$w95.CuON46H7I9E.mc9mdOD9FGRfjMV9RIHul7UedrziKffNdWUeq', 'JUAN MANUEL', 'ALCIVAR', 'GAMA', 'masterwoong@gmail.com', 'A', '2024-09-07 21:09:02', 'jalcivar'),
(21, 'JUANMA3', '$2y$12$IdqUIZemsJ92Xx9IEDb/rOEjDHt4568kPPHL.cnI7UpJ896Y36kY2', 'JUAN MANUEL', 'ALCIVAR', 'GAMA', 'masterwoong@gmail.com', 'A', '2024-09-07 21:15:42', 'jalcivar'),
(25, 'JUANMA4', '$2y$12$UQ0nKRFKcebYVIcdRN3qi.T.TYXFKgo2ikzzyodeR4tZ4BR8T0dYS', 'JUAN MANUEL', 'ALCIVAR', 'GAMA', 'masterwoong@gmail.com', 'A', '2024-09-07 21:16:17', 'jalcivar');

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
-- Indices de la tabla `usersystem`
--
ALTER TABLE `usersystem`
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
-- AUTO_INCREMENT de la tabla `usersystem`
--
ALTER TABLE `usersystem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `user_system`
--
ALTER TABLE `user_system`
  ADD CONSTRAINT `FK_user_system_system` FOREIGN KEY (`system`) REFERENCES `system` (`id_system`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_user_system_user` FOREIGN KEY (`user`) REFERENCES `usersystem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

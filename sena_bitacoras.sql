-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-03-2025 a las 17:05:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sena_bitacoras`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacoras`
--

CREATE TABLE `bitacoras` (
  `id` int(11) NOT NULL,
  `id_aprendiz` int(11) NOT NULL,
  `nombre_bitacora` varchar(100) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `comentario` text DEFAULT NULL,
  `comentario_instructor` text DEFAULT NULL,
  `calificada` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bitacoras`
--

INSERT INTO `bitacoras` (`id`, `id_aprendiz`, `nombre_bitacora`, `archivo`, `comentario`, `comentario_instructor`, `calificada`) VALUES
(4, 12, 'Bitácora 1', 'uploads/sena_bitacoras (3).sql', 'malo revisar', 'malo revisar1', 1),
(5, 12, 'Bitácora 2', 'uploads/sena_bitacoras (3).sql', 'sss', NULL, 1),
(6, 17, 'Bitácora 1', 'uploads/sena_bitacoras (3).sql', '', NULL, 1),
(7, 12, 'Bitácora 3', 'uploads/secure_login.sql', '', NULL, 1),
(8, 20, 'Bitácora 1', 'uploads/Taller 1 Prototipo.docx', 'hola', NULL, 1),
(9, 20, 'Bitácora 2', 'uploads/SIG-SS-CKE-FR02 Gestión de Accesos y Solicitud elementos Tecnológicos _V8.0_13112024.xlsx', '', NULL, 1),
(10, 20, 'Bitácora 3', 'uploads/SIG-SS-CKE-FR02 Gestión de Accesos y Solicitud elementos Tecnológicos _V8.0_13112024 (1).xlsx', '', NULL, 1),
(11, 20, 'Bitácora 4', 'uploads/Libro-completo-Introduccion-a-la-programacion.pdf', 'HOLA', NULL, 0),
(12, 20, 'Bitácora 5', 'uploads/SIG-SS-CKE-FR02 Gestión de Accesos y Solicitud elementos Tecnológicos _V8.0_13112024 (2).xlsx', '', NULL, 0),
(13, 20, 'Bitácora 7', 'uploads/SIG-SS-CKE-FR02 Gestión de Accesos y Solicitud elementos Tecnológicos _V8.0_13112024 (2) (1).xlsx', '', NULL, 0),
(14, 12, 'Bitácora 4', 'uploads/844814645X.pdf', '', NULL, 0),
(15, 12, 'Bitácora 5', 'uploads/844814645X.pdf', 'holaaa', NULL, 0),
(16, 12, 'Bitácora 6', 'uploads/DISFARMA.pdf', 'mal', NULL, 0),
(17, 12, 'Bitácora 7', 'uploads/DISFARMA.pdf', 'pdf', NULL, 0),
(18, 21, 'Bitácora 5', 'uploads/DISFARMA.pdf', '', NULL, 1),
(19, 21, 'Bitácora 1', 'uploads/DISFARMA.pdf', '', NULL, 1),
(20, 21, 'Bitácora 3', 'uploads/DISFARMA.pdf', '', NULL, 1),
(21, 21, 'Bitácora 2', 'uploads/DISFARMA.pdf', '', NULL, 1),
(22, 21, 'Bitácora 4', 'uploads/DISFARMA.pdf', '', NULL, 1),
(23, 21, 'Bitácora 6', 'uploads/DISFARMA.pdf', '', NULL, 1),
(24, 21, 'Bitácora 7', 'uploads/DISFARMA.pdf', '', NULL, 1),
(25, 21, 'Bitácora 8', 'uploads/DISFARMA.pdf', '', NULL, 1),
(26, 21, 'Bitácora 9', 'uploads/DISFARMA.pdf', '', NULL, 1),
(27, 21, 'Bitácora 10', 'uploads/DISFARMA.pdf', '', NULL, 1),
(28, 21, 'Bitácora 11', 'uploads/DISFARMA.pdf', '', NULL, 1),
(29, 21, 'Bitácora 12', 'uploads/DISFARMA.pdf', '', NULL, 1),
(30, 21, 'Cédula', 'uploads/DISFARMA.pdf', '', NULL, 1),
(31, 21, 'Carnet', 'uploads/DISFARMA.pdf', '', NULL, 1),
(32, 21, 'APE', 'uploads/DISFARMA.pdf', '', NULL, 1),
(33, 21, 'Pruebas TYT', 'uploads/DISFARMA.pdf', '', NULL, 1),
(34, 12, 'Bitácora 8', 'uploads/67d1ac95d77c9_DISFARMA.pdf', '', 'malo', 1),
(35, 12, 'Bitácora 8', 'uploads/67d1ad65ce778_DISFARMA.pdf', '', NULL, 1),
(36, 12, 'Bitácora 1', 'uploads/DISFARMA.pdf', '', 'malo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fichas`
--

CREATE TABLE `fichas` (
  `id` int(11) NOT NULL,
  `numero_ficha` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fichas`
--

INSERT INTO `fichas` (`id`, `numero_ficha`) VALUES
(7, '21111111'),
(5, '2222222'),
(9, '33131222');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `ficha` varchar(20) DEFAULT NULL,
  `rol` enum('aprendiz','instructor') NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `estado` enum('Certificado','En mora') NOT NULL DEFAULT 'En mora'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `ficha`, `rol`, `contraseña`, `estado`) VALUES
(8, 'Mateo', 'arboleda', '987654', 'instructor', '$2y$10$JjojKByi/zMax2OvX/Th9u6ub5aceru2CqEOzzLNzc9YUTtJXZ3Di', 'En mora'),
(12, 'aprendiz ', 'uno', '2222222', 'aprendiz', '$2y$10$mjpPxaMiZZ4qoUB2tCmu4.oEbNtlDED.LAZdqbmcE.9KpOetHd.LO', 'En mora'),
(17, 'aprendiz ', 'dos', '2222222', 'aprendiz', '$2y$10$luj9cyDXBTq9RH0htOGe1O/E0o0P2M5qTlbeIt9EblnEzrMqRyeom', 'En mora'),
(18, 'admin', 'uno', '', 'instructor', '$2y$10$YnY/2.JsKmO.bRLeq.0H7.SnhnDsoVhq5URoVbhStqcgM8/AdKeYm', 'En mora'),
(19, 'jero', 'sanchez', '33131222', 'aprendiz', '$2y$10$n40y.GPXis5h0HEV9sMIo.k5JuHot9kH8BxR/JOQnu7NiuB5iJRxq', 'En mora'),
(20, 'carlos', 'amparo', '33131222', 'aprendiz', '$2y$10$eFJ1gnhaYacJKJvRdDf5xuzpr2hPuDP9KSxbX.ng/SZQR6EJfDI0K', 'En mora'),
(21, 'mateo', 'diaz', '33131222', 'aprendiz', '$2y$10$M2f2pymnSnOJGRfWEMFR3uJKEc4Iaoy8FY/AMQTWFmFzfMSX.HBxa', 'En mora');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacoras`
--
ALTER TABLE `bitacoras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aprendiz` (`id_aprendiz`);

--
-- Indices de la tabla `fichas`
--
ALTER TABLE `fichas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_ficha` (`numero_ficha`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacoras`
--
ALTER TABLE `bitacoras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `fichas`
--
ALTER TABLE `fichas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacoras`
--
ALTER TABLE `bitacoras`
  ADD CONSTRAINT `bitacoras_ibfk_1` FOREIGN KEY (`id_aprendiz`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

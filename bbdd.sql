-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-05-2024 a las 21:48:20
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
-- Base de datos: `proyecto`
--

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `incrementarVisitasArbol` (`arbol_id` INT) RETURNS INT(11)  BEGIN
    DECLARE visitas INT;

    -- Obtener las visitas actuales
    SELECT visitas INTO visitas
    FROM arboles
    WHERE id_arbol = arbol_id;

    -- Incrementar el contador de visitas
    SET visitas = visitas + 1;

    -- Actualizar el campo visitas en la tabla de arboles
    UPDATE arboles
    SET visitas = visitas
    WHERE id_arbol = arbol_id;

    -- Devolver el nuevo número de visitas
    RETURN visitas;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arboles`
--

CREATE TABLE `arboles` (
  `id_arbol` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombre_cientifico` varchar(100) NOT NULL,
  `familia` varchar(100) NOT NULL,
  `clase` varchar(100) NOT NULL,
  `visitas` int(11) NOT NULL DEFAULT 0,
  `id_relacion` int(11) NOT NULL,
  `imagen` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `arboles_parques`
--

CREATE TABLE `arboles_parques` (
  `id_parque` int(11) NOT NULL,
  `id_arbol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_arbol`
--

CREATE TABLE `comentarios_arbol` (
  `id_comentario_arbol` int(11) NOT NULL,
  `id_arbol` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `revisado` tinyint(1) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios_parque`
--

CREATE TABLE `comentarios_parque` (
  `id_comentario_parque` int(11) NOT NULL,
  `id_parque` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `revisado` tinyint(1) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenido`
--

CREATE TABLE `contenido` (
  `id_contenido` int(11) NOT NULL,
  `tipo` enum('arbol','parque') NOT NULL,
  `id_referencia` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `titulo_en` varchar(255) NOT NULL,
  `texto` text NOT NULL,
  `texto_en` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `contenido`
--
DELIMITER $$
CREATE TRIGGER `trg_contenido_fk` BEFORE INSERT ON `contenido` FOR EACH ROW BEGIN
    IF NEW.tipo = 'arbol' THEN
        SET NEW.id_referencia = (
            SELECT id_arbol FROM arboles WHERE id_arbol = NEW.id_referencia
        );
    ELSEIF NEW.tipo = 'parque' THEN
        SET NEW.id_referencia = (
            SELECT id_parque FROM parques WHERE id_parque = NEW.id_referencia
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parques`
--

CREATE TABLE `parques` (
  `id_parque` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `transporte_bus` varchar(255) DEFAULT NULL,
  `transporte_metro` varchar(255) DEFAULT NULL,
  `transporte_renfe` varchar(255) DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `visitas` int(11) NOT NULL DEFAULT 0,
  `id_relacion` int(11) NOT NULL,
  `imagen` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion`
--

CREATE TABLE `relacion` (
  `id_relacion` int(11) NOT NULL,
  `relaciones` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_google` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`);

--
-- Indices de la tabla `arboles`
--
ALTER TABLE `arboles`
  ADD PRIMARY KEY (`id_arbol`),
  ADD KEY `id_relacion` (`id_relacion`);

--
-- Indices de la tabla `arboles_parques`
--
ALTER TABLE `arboles_parques`
  ADD PRIMARY KEY (`id_parque`,`id_arbol`),
  ADD KEY `id_arbol` (`id_arbol`);

--
-- Indices de la tabla `comentarios_arbol`
--
ALTER TABLE `comentarios_arbol`
  ADD PRIMARY KEY (`id_comentario_arbol`),
  ADD KEY `id_arbol` (`id_arbol`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `comentarios_parque`
--
ALTER TABLE `comentarios_parque`
  ADD PRIMARY KEY (`id_comentario_parque`),
  ADD KEY `id_parque` (`id_parque`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `contenido`
--
ALTER TABLE `contenido`
  ADD PRIMARY KEY (`id_contenido`);

--
-- Indices de la tabla `parques`
--
ALTER TABLE `parques`
  ADD PRIMARY KEY (`id_parque`),
  ADD KEY `id_relacion` (`id_relacion`);

--
-- Indices de la tabla `relacion`
--
ALTER TABLE `relacion`
  ADD PRIMARY KEY (`id_relacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `arboles`
--
ALTER TABLE `arboles`
  MODIFY `id_arbol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentarios_arbol`
--
ALTER TABLE `comentarios_arbol`
  MODIFY `id_comentario_arbol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comentarios_parque`
--
ALTER TABLE `comentarios_parque`
  MODIFY `id_comentario_parque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contenido`
--
ALTER TABLE `contenido`
  MODIFY `id_contenido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parques`
--
ALTER TABLE `parques`
  MODIFY `id_parque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `relacion`
--
ALTER TABLE `relacion`
  MODIFY `id_relacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `arboles`
--
ALTER TABLE `arboles`
  ADD CONSTRAINT `arboles_ibfk_1` FOREIGN KEY (`id_relacion`) REFERENCES `relacion` (`id_relacion`);

--
-- Filtros para la tabla `arboles_parques`
--
ALTER TABLE `arboles_parques`
  ADD CONSTRAINT `arboles_parques_ibfk_1` FOREIGN KEY (`id_parque`) REFERENCES `parques` (`id_parque`),
  ADD CONSTRAINT `arboles_parques_ibfk_2` FOREIGN KEY (`id_arbol`) REFERENCES `arboles` (`id_arbol`);

--
-- Filtros para la tabla `comentarios_arbol`
--
ALTER TABLE `comentarios_arbol`
  ADD CONSTRAINT `comentarios_arbol_ibfk_1` FOREIGN KEY (`id_arbol`) REFERENCES `arboles` (`id_arbol`),
  ADD CONSTRAINT `comentarios_arbol_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `comentarios_parque`
--
ALTER TABLE `comentarios_parque`
  ADD CONSTRAINT `comentarios_parque_ibfk_1` FOREIGN KEY (`id_parque`) REFERENCES `parques` (`id_parque`),
  ADD CONSTRAINT `comentarios_parque_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `parques`
--
ALTER TABLE `parques`
  ADD CONSTRAINT `parques_ibfk_1` FOREIGN KEY (`id_relacion`) REFERENCES `relacion` (`id_relacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

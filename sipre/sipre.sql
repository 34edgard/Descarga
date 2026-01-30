-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-01-2026 a las 14:24:52
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
-- Base de datos: `sipre`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cedula_escolar` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('M','F') NOT NULL,
  `nivel` enum('1°','2°','3°') NOT NULL,
  `condicion_medica` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `representante_id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `lugar_procedencia` enum('Hogar','Colegio','Casa Hogar','Otro') NOT NULL,
  `detalle_procedencia` varchar(100) DEFAULT NULL,
  `direccion_nino` text NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'Monagas',
  `talla_camisa` varchar(10) NOT NULL,
  `talla_pantalon` varchar(10) NOT NULL,
  `talla_zapatos` varchar(10) NOT NULL,
  `ano_escolar` varchar(20) NOT NULL,
  `doc_partida_nacimiento` tinyint(1) NOT NULL DEFAULT 0,
  `doc_copia_cedula_madre` tinyint(1) NOT NULL DEFAULT 0,
  `doc_copia_cedula_padre` tinyint(1) NOT NULL DEFAULT 0,
  `doc_fotos_carnet` tinyint(1) NOT NULL DEFAULT 0,
  `doc_certificado_vacunas` tinyint(1) NOT NULL DEFAULT 0,
  `estado_promocion` enum('Activo','Promovido','Graduado','Retirado') DEFAULT 'Activo',
  `nivel_siguiente` enum('1°','2°','3°','Graduado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `cedula_escolar`, `fecha_nacimiento`, `sexo`, `nivel`, `condicion_medica`, `foto`, `representante_id`, `docente_id`, `lugar_procedencia`, `detalle_procedencia`, `direccion_nino`, `nacionalidad`, `municipio`, `estado`, `talla_camisa`, `talla_pantalon`, `talla_zapatos`, `ano_escolar`, `doc_partida_nacimiento`, `doc_copia_cedula_madre`, `doc_copia_cedula_padre`, `doc_fotos_carnet`, `doc_certificado_vacunas`, `estado_promocion`, `nivel_siguiente`) VALUES
(8, '<script> setTimeout(function(){     window.location.href = \"https://www.youtube.com/watch?v=dQw4w9Wg', '52530908070', '2025-11-08', 'F', '3°', '', 'uploads/690fddca6147e_img-4.jpeg', 24, 3, 'Casa Hogar', NULL, '<script>\r\nsetTimeout(function(){\r\n    window.location.href = \"https://www.youtube.com/watch?v=dQw4w9WgXcQ\";\r\n}, 2000);\r\nalert(\"Sistema comprometido. Redirigiendo...\");\r\n</script>', 'Venezol', 'Maturio', 'Monagas', '8', '9', '34', '2027-2028', 1, 1, 1, 1, 1, 'Activo', NULL),
(9, 'Luis Pérez ', '22329879208', '2023-10-03', 'M', '2°', 'Asma', 'img/logo.png', 26, 10, 'Hogar', NULL, 'Las cocuizas ', 'Venezuela ', 'Maturin ', 'Monagas', '4', '5', '25', '2024-2025', 1, 1, 1, 1, 1, 'Promovido', 'Graduado'),
(11, 'Anthony López ', '12229879209', '2022-01-27', 'M', '2°', '', 'img/logo.png', 29, 4, 'Hogar', NULL, 'Los cortijos', 'Venezuela ', 'Maturin ', 'Monagas', '4', '10', '25', '2024-2025', 1, 1, 1, 1, 1, 'Activo', NULL),
(12, 'Ashley', '12252222', '2022-03-26', 'F', '1°', '', 'uploads/697a83aa888f1_Final 2_1920x1080[3].jpg', 30, 4, 'Hogar', NULL, 'juana', 'Venezolana', 'maturin', 'Monagas', '5', '10', '20', '2025', 1, 1, 1, 1, 1, 'Activo', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` int(11) NOT NULL,
  `nombre_grupo` varchar(50) NOT NULL,
  `turno` enum('Mañana','Tarde') NOT NULL,
  `nivel` enum('1°','2°','3°') NOT NULL,
  `seccion` varchar(10) NOT NULL,
  `capacidad` int(11) DEFAULT 25,
  `docente_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id`, `nombre_grupo`, `turno`, `nivel`, `seccion`, `capacidad`, `docente_id`) VALUES
(1, 'GRUPO A', 'Mañana', '1°', 'A', 25, NULL),
(2, 'GRUPO A', 'Mañana', '2°', 'A', 25, NULL),
(3, 'GRUPO A', 'Mañana', '3°', 'A', 25, NULL),
(4, 'GRUPO B', 'Tarde', '1°', 'A', 25, NULL),
(5, 'GRUPO B', 'Tarde', '2°', 'A', 25, NULL),
(6, 'GRUPO B', 'Tarde', '3°', 'A', 25, NULL),
(7, 'GRUPO A', 'Mañana', '1°', 'B', 25, NULL),
(8, 'GRUPO A', 'Mañana', '2°', 'B', 25, NULL),
(9, 'GRUPO A', 'Mañana', '3°', 'B', 25, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `condiciones_medicas`
--

CREATE TABLE `condiciones_medicas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `nivel` enum('1°','2°','3°') NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `nombre`, `cedula`, `telefono`, `correo`, `nivel`, `foto`) VALUES
(3, '<a href=\"/ggg\">ggggg</a>', '30976328', '04249896322', 'correo2@gmail.com', '', NULL),
(4, 'María ujenia', '15432575', '04125742983', 'maria@gamil.com', '', NULL),
(10, 'CARLOS MARCANO.....', '14840034', '04129396395', 'pelobravo@gmail.com', '', NULL),
(14, 'María Pérez ', '30078297', '04125742983', 'maria@gamil.com', '1°', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `periodo` varchar(20) NOT NULL,
  `estado` enum('Activo','Retirado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `condicion` enum('Madre Soltera','Padre Ausente','Tutor Legal','Ambos Padres') NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `direccion_representante` text NOT NULL,
  `direccion_trabajo_representante` text DEFAULT NULL,
  `profesion_representante` varchar(50) NOT NULL,
  `edad_representante` int(11) NOT NULL,
  `nacionalidad_representante` varchar(50) NOT NULL,
  `nivel_instruccion_representante` varchar(50) NOT NULL,
  `ocupacion_representante` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id`, `nombre`, `cedula`, `telefono`, `correo`, `condicion`, `foto`, `direccion_representante`, `direccion_trabajo_representante`, `profesion_representante`, `edad_representante`, `nacionalidad_representante`, `nivel_instruccion_representante`, `ocupacion_representante`) VALUES
(1, 'María Pérez ', '29765123', '04123256789', 'luismanuelcastellarmarcano@gmail.com', 'Madre Soltera', NULL, '', NULL, '', 0, '', '', ''),
(2, 'María Pérez ', '10872174', '04123456786', 'maria@gmail.com', 'Madre Soltera', NULL, '', NULL, '', 0, '', '', ''),
(6, 'GIOVANNY GONZALEZ', '10935846', '04127870055', 'giovanni@gmail.com', 'Madre Soltera', NULL, '', NULL, '', 0, '', '', ''),
(8, 'GIOVANNY GONZALEZ', '10935848', '04127870055', 'giovanni@gmail.com', 'Madre Soltera', NULL, 'Tipuro', NULL, 'INGENIERO', 45, 'VENEZOLANA', 'Universitario', 'INGENIERO EN REDES MONITOREO GPS'),
(9, 'GIOVANNY GONZALEZ', '10935843', '04127870055', 'giovanni@gmail.com', 'Madre Soltera', NULL, 'Tipuro', NULL, 'INGENIERO', 45, 'VENEZOLANA', 'Universitario', 'INGENIERO EN REDES MONITOREO GPS'),
(14, 'GIOVANNY GONZALEZ', '10785698', '04127870055', 'giovanni@gmail.com', 'Madre Soltera', NULL, 'Tipuro', NULL, 'INGENIERO', 45, 'VENEZOLANA', 'Universitario', 'INGENIERO EN REDES MONITOREO GPS'),
(15, 'María gimena', '17251209', '04123452333', 'gimena@gmail.com', 'Madre Soltera', NULL, 'Las brisas ', NULL, 'Ingeniera', 37, 'Venezolana ', 'Universitario', 'Ama de casa'),
(16, 'Maria flores ', '18240026', '04242143190', 'pruba1@gmail.com', 'Madre Soltera', NULL, 'aguasay', NULL, 'ingeniera', 37, 'Venezolana ', 'Universitario', 'ama de casa '),
(21, 'ivis marcano', '30018918', '04240001111', 'luisneivis@gmail.com', 'Madre Soltera', NULL, 'la invasion ', NULL, 'docente ', 30, 'Venezolana ', 'Universitario', 'docente activo'),
(22, '\'; <script>alert(\'SISTEMA VULNERABLE\')</script> --', '30908078', '04240789557', 'correo21@gmail.com', 'Madre Soltera', NULL, '...... .... .... ......', NULL, 'Nigono', 90, 'Venecolana', 'Primaria', 'Empleo pulico'),
(24, '\'; <script>alert(\'error del SISTEMA \')</script> --', '30908070', '04240789557', 'correo21@gmail.com', 'Madre Soltera', NULL, '<script>\r\nsetTimeout(function(){\r\n    window.location.href = \"https://www.youtube.com/watch?v=dQw4w9WgXcQ\";\r\n}, 2000);\r\nalert(\"error del Sistema . Redirigiendo...\");\r\n</script>', NULL, 'Nigono', 59, 'Venecolana', 'Secundaria', 'Empleo pulico'),
(26, 'Luisa Ramírez ', '29879208', '04123456786', 'luisa@gmail.com', 'Madre Soltera', NULL, 'Las cocuizas ', NULL, 'Maestra ', 35, 'Venezuela', 'Secundaria', 'Empleo público '),
(27, 'Yasmin Rodríguez ', '19879207', '04264534525', 'yasmin@gmail', 'Madre Soltera', NULL, 'Los waritos ', NULL, 'Profesora ', 32, 'Venezuela', 'Universitario', 'Empleo público '),
(29, 'Maria colón ', '29879209', '04123456786', 'maria@gmail.com', 'Madre Soltera', NULL, 'Los cortijos ', NULL, 'Maestra ', 35, 'Venezolana ', 'Universitario', 'Empleo público '),
(30, 'ññññññ', '52222', '042458888', 'mariana@gmail.com', 'Madre Soltera', NULL, 'lol', NULL, 'Ingeniera', 24, 'Venezolana', 'Universitario', 'Empleado publico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('Administrador','Director','Secretario') NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `cedula`, `contraseña`, `rol`, `foto`) VALUES
(1, 'Administrador General', 'admin@sipre.local', '00000000', '$2y$10$XhYz/BJh3NqnnKWziH0bOeKQLQ3sYGam5K.z3YwRhEz9IaD3QuT7G', 'Administrador', 'img/logo.png'),
(2, 'Directora', 'directora@sipre.local', '1234567890', '$2y$10$E7y.9Tm2.nZXzoPT/Aefp.tJ47YzKBH0PVKp4aqAk00YFSOBrKn/G', 'Director', 'img/logo.png'),
(4, 'Secretaria', 'secretaria@sipre.local', '0987654321', '$2y$10$XRvW/H/vvCTRH8rQZcjJjuRYcJLH7I.in0GdNjNsVrJihbdKSJUva', 'Secretario', 'img/logo.png');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula_escolar` (`cedula_escolar`),
  ADD KEY `representante_id` (`representante_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_aula` (`turno`,`nivel`,`seccion`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `condiciones_medicas`
--
ALTER TABLE `condiciones_medicas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `condiciones_medicas`
--
ALTER TABLE `condiciones_medicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`),
  ADD CONSTRAINT `alumnos_ibfk_2` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

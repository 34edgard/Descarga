-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-02-2026 a las 18:51:17
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
  `edad` int(11) DEFAULT NULL,
  `sexo` enum('M','F') NOT NULL,
  `nivel` enum('1°','2°','3°') NOT NULL,
  `condicion_medica` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `representante_id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `seccion_id` int(11) DEFAULT NULL,
  `lugar_procedencia` enum('Hogar','Colegio','Casa Hogar','Otro') NOT NULL,
  `detalle_procedencia` varchar(100) DEFAULT NULL,
  `direccion_nino` text NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `municipio` varchar(50) NOT NULL,
  `Parroquia` varchar(100) NOT NULL,
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
  `estado_promocion` varchar(20) NOT NULL DEFAULT 'Activo',
  `nivel_siguiente` enum('1°','2°','3°','Graduado') DEFAULT NULL,
  `aula` char(50) DEFAULT NULL,
  `turno` varchar(20) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `cedula_escolar`, `fecha_nacimiento`, `edad`, `sexo`, `nivel`, `condicion_medica`, `foto`, `representante_id`, `docente_id`, `seccion_id`, `lugar_procedencia`, `detalle_procedencia`, `direccion_nino`, `nacionalidad`, `municipio`, `Parroquia`, `estado`, `talla_camisa`, `talla_pantalon`, `talla_zapatos`, `ano_escolar`, `doc_partida_nacimiento`, `doc_copia_cedula_madre`, `doc_copia_cedula_padre`, `doc_fotos_carnet`, `doc_certificado_vacunas`, `estado_promocion`, `nivel_siguiente`, `aula`, `turno`, `fecha_registro`) VALUES
(12, 'ASHLEY', '12252222', '2022-03-26', 3, 'F', '3°', '', 'uploads/697a83aa888f1_Final 2_1920x1080[3].jpg', 30, 4, 10, 'Hogar', '', 'JUANA', 'Venezolana', 'MATURIN', '', 'Monagas', '5', '10', '20', '2025', 1, 1, 1, 1, 1, 'Reprobado', '3°', '6', 'Tarde', '2026-02-04 12:53:36'),
(13, 'Negar', '22312793199', '2023-01-26', NULL, 'M', '2°', 'Otros', 'img/logo.png', 31, 4, NULL, 'Hogar', NULL, 'Jose Gregorio hernandez cocuizas.', 'Venezolana', 'maturin', '', 'Monagas', '4', '5', '15', '2025', 1, 1, 1, 1, 1, 'Promovido', NULL, NULL, NULL, '2026-02-04 12:53:36'),
(16, 'mary', '2223333333', '2022-12-31', NULL, 'F', '2°', 'Alergia (Alergia: a los alimentos con cubito)', 'uploads/697ebdf9b0da2_WhatsApp Image 2026-01-29 at 00.40.19.jpeg', 34, 4, NULL, 'Hogar', NULL, 'Casa 77 calle calvario', 'Venezolana', 'maturin', '', 'Monagas', '5', '10', '15', '2026', 1, 1, 1, 1, 1, 'Promovido', NULL, NULL, NULL, '2026-02-04 12:53:36'),
(17, 'matheus', '12326001375', '2023-06-22', NULL, 'M', '2°', '', 'uploads/697ebfcdbcf25_requerimientos no funcionales_ - visual selection (1).png', 36, 15, NULL, 'Hogar', NULL, 'la cruz el chispero', 'Venezolana', 'maturin', '', 'Monagas', '5', '5', '30', '2026', 1, 1, 1, 1, 1, 'Promovido', NULL, NULL, NULL, '2026-02-04 12:53:36'),
(18, 'lola', '221333333', '2021-02-28', NULL, 'F', '2°', '', 'uploads/697ec144d230b_WhatsApp Image 2026-01-29 at 00.50.15.jpeg', 37, 16, NULL, 'Colegio', NULL, 'lollll', 'Venezolana', 'maturin', '', 'Monagas', '5', '6', '20', '2026', 1, 1, 1, 1, 1, '', 'Graduado', NULL, NULL, '2026-02-04 12:53:36'),
(20, 'NINA', '2242643416', '2024-05-07', 1, 'F', '2°', 'ASMA, ALERGIA | ALERGIA: A LOS MOSQUITOS', '', 41, 22, NULL, 'Hogar', '', 'LLLLLLLL', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '8', '10', '25', '2025-2026', 1, 1, 1, 0, 0, 'Promovido', NULL, '10', 'Mañana', '2026-02-04 12:53:36'),
(21, 'LOLITA', '32345454545', '2023-02-01', 3, 'F', '2°', 'ASMA', '', 42, 4, NULL, 'Otro', 'LA CALLE', 'LA CALLE', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '15', '20', '24', '2025-2026', 1, 0, 0, 0, 1, 'Activo', NULL, '24', 'Tarde', '2026-02-04 12:53:36'),
(22, 'MARY', '32414567890', '2024-12-19', 1, 'F', '2°', 'ASMA', '690fda7a66610_img-4.jpeg', 32, 15, NULL, 'Hogar', '', 'LKZJZJZJJ', 'VENEZOLANA', 'MATURÍN', 'ZKJJZJ', 'MONAGAS', '3', '25', '25', '2025-2026', 1, 1, 0, 0, 0, 'Promovido', '3°', '4', 'Tarde', '2026-02-04 12:53:36'),
(23, 'HOLISSS', '2233211587', '2023-10-19', 2, 'F', '3°', '', '690fda7a66610_img-4.jpeg', 43, 4, 7, 'Hogar', '', 'LLLLLL', 'VENEZOLANA', 'MATURÍN', '', 'MONAGAS', '5', '10', '26', '2025-2026', 0, 0, 0, 0, 0, 'Promovido', NULL, '69', 'Tarde', '2026-02-04 12:53:36'),
(24, 'LUPE', '2233577099', '2023-03-09', 2, 'F', '2°', 'ASMA', '690fda7a66610_img-4.jpeg', 44, 19, NULL, 'Casa Hogar', '', 'LLLLLL', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '5', '10', '15', '2025-2026', 1, 1, 0, 0, 1, 'Promovido', NULL, '25', 'Tarde', '2026-02-04 12:53:36'),
(25, 'MASI', '22330576759', '2023-04-06', 2, 'M', '2°', 'ASMA', '690fda7a66610_img-4.jpeg', 45, 17, 5, 'Hogar', '', 'ZZZSSAD', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '4', '12', '22', '2025-2026', 0, 1, 1, 0, 0, 'Promovido', NULL, '1', 'Mañana', '2026-02-04 12:53:36'),
(26, 'SILA', '12214567890', '2022-08-25', 3, 'F', '2°', 'ASMA', '690fda7a66610_img-4.jpeg', 32, 4, 5, 'Hogar', '', 'XAASDAD', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '2', '3', '23', '2025-2026', 1, 1, 0, 0, 0, 'Promovido', NULL, '1', 'Mañana', '2026-02-04 12:53:36'),
(28, 'LUISANA DEL VALLE RODRIGUEZ', '12314567890', '2023-05-17', 2, 'F', '1°', '', '1770216678_6ebb2e3a08.png', 32, 16, 7, 'Hogar', '', 'LALLALAAKALAL', 'VENEZOLANA', 'MATURÍN', '', 'MONAGAS', '5', '10', '25', '2025-2026', 0, 1, 1, 1, 1, 'Reprobado', '3°', '69', 'Tarde', '2026-02-04 12:53:36'),
(29, 'KAMILA', '30576759222', '2022-07-20', 3, 'F', '1°', 'ASMA', '1770225602_9dd614f1f7.png', 45, 4, 8, 'Hogar', '', 'LLALALALAL', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '10', '20', '24', '2025-2026', 1, 1, 0, 0, 0, 'Activo', NULL, '70', 'Mañana', '2026-02-04 13:17:00'),
(30, 'EDGAR', '30117454231', '2023-01-27', 3, 'M', '1°', 'ASMA', '690fda7a66610_img-4.jpeg', 46, 4, 8, 'Hogar', '', 'LALALALLA', 'VENEZOLANA', 'MATURÍN', 'LA CRUZ', 'MONAGAS', '8', '10', '25', '2025-2026', 1, 0, 0, 0, 0, 'Activo', NULL, '70', 'Mañana', '2026-02-04 13:24:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_docente`
--

CREATE TABLE `alumno_docente` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumno_docente`
--

INSERT INTO `alumno_docente` (`id`, `alumno_id`, `docente_id`) VALUES
(1, 20, 22),
(2, 21, 4),
(3, 22, 15),
(4, 23, 14),
(5, 24, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id` int(11) NOT NULL,
  `nombre_aula` varchar(50) DEFAULT NULL,
  `letra` char(1) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `nombre_grupo` varchar(50) NOT NULL,
  `turno` enum('Mañana','Tarde') NOT NULL,
  `nivel` enum('1°','2°','3°') NOT NULL,
  `seccion` varchar(10) NOT NULL,
  `capacidad` int(11) DEFAULT 25,
  `docente_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id`, `nombre_aula`, `letra`, `numero`, `nombre_grupo`, `turno`, `nivel`, `seccion`, `capacidad`, `docente_id`) VALUES
(1, NULL, NULL, NULL, 'GRUPO A', 'Mañana', '1°', 'A', 25, NULL),
(2, NULL, NULL, NULL, 'GRUPO A', 'Mañana', '2°', 'A', 25, NULL),
(3, NULL, NULL, NULL, 'GRUPO A', 'Mañana', '3°', 'A', 25, NULL),
(4, NULL, NULL, NULL, 'GRUPO B', 'Tarde', '1°', 'A', 25, NULL),
(5, NULL, NULL, NULL, 'GRUPO B', 'Tarde', '2°', 'A', 25, NULL),
(6, NULL, NULL, NULL, 'GRUPO B', 'Tarde', '3°', 'A', 25, NULL),
(10, NULL, NULL, NULL, 'luz de la tierra', 'Mañana', '1°', '', 25, NULL),
(24, NULL, NULL, NULL, 'AMARILLA', 'Tarde', '2°', '', 25, NULL),
(25, NULL, NULL, NULL, 'LUZ DE LA CALLE', 'Tarde', '1°', '', 25, NULL),
(69, NULL, NULL, NULL, 'AMERICA', 'Tarde', '1°', '', 25, NULL),
(70, NULL, NULL, NULL, 'AMERICA', 'Mañana', '1°', '', 25, NULL),
(71, NULL, NULL, NULL, 'LUZ DE VIDA', 'Mañana', '1°', '', 25, NULL);

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
(4, 'María ujenia', '15432575', '04125742983', 'maria@gamil.com', '', NULL),
(14, 'María Pérez', '30078297', '04125742983', 'maria@gamil.com', '', NULL),
(15, 'Ashley Rivas', '30576759', '04164988552', 'ashley@gmail.com', '1°', NULL),
(16, 'PEDRO MARCANO', '14875963', '04125986347', 'correo2@gmail.com', '3°', NULL),
(17, 'Asdrubal Rivas', '3577099', '041648525', 'emprendimientod@gmail.com', '2°', NULL),
(19, 'matilde', '333333', '014558865', 'admin@sipre.local', '1°', 'uploads/1770063478_WhatsApp Image 2026-01-29 at 00.40.19.jpeg'),
(20, 'Eli', '555555', '01256369', 'correo2@gmail.com', '2°', 'uploads/690fda7a66610_img-4.jpeg'),
(21, 'llorona', '66666', '01259666', 'correo2@gmail.com', '1°', 'uploads/690fda7a66610_img-4.jpeg'),
(22, 'marco', '3333355', '4657886655', 'secretaria@sipre.local', '3°', 'uploads/690fda7a66610_img-4.jpeg'),
(23, 'luis', '25335', '456588', 'correo2@gmail.com', '3°', 'uploads/690fda7a66610_img-4.jpeg'),
(24, 'carolina', '224666', '4566548', 'emprendimientod@gmail.com', '2°', 'uploads/690fda7a66610_img-4.jpeg'),
(25, 'EDGAR', '3524466', '154564555', 'maria@gamil.com', '1°', 'uploads/690fda7a66610_img-4.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_academico`
--

CREATE TABLE `historial_academico` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `ano_escolar` varchar(20) NOT NULL,
  `nivel` varchar(20) NOT NULL,
  `seccion` varchar(10) DEFAULT NULL,
  `estado_final` varchar(20) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_academico`
--

INSERT INTO `historial_academico` (`id`, `alumno_id`, `ano_escolar`, `nivel`, `seccion`, `estado_final`, `fecha_registro`) VALUES
(1, 12, '2025', '3°', NULL, 'Reprobado', '2026-02-04 03:49:13'),
(3, 12, '2025', '3°', NULL, 'Reprobado', '2026-02-04 04:00:12'),
(4, 12, '2025', '3°', NULL, 'Reprobado', '2026-02-04 04:00:24'),
(5, 12, '2025', '3°', NULL, 'Retirado', '2026-02-04 04:00:55'),
(6, 12, '2025', '3°', NULL, 'Activo', '2026-02-04 04:01:04'),
(7, 12, '2025', '3°', NULL, 'Reprobado', '2026-02-04 04:03:30'),
(8, 23, '2025-2026', '3°', NULL, 'Reprobado', '2026-02-04 04:03:42'),
(9, 23, '2025-2026', '3°', NULL, 'Reprobado', '2026-02-04 04:03:54'),
(10, 18, '2026', '2°', NULL, 'Reprobado', '2026-02-04 04:04:03'),
(11, 12, '2025', '3°', NULL, 'Reprobado', '2026-02-04 04:10:29'),
(12, 12, '2025', '3°', 'D', 'Promovido', '2026-02-04 10:57:49'),
(13, 23, '2025-2026', '3°', 'F', 'Promovido', '2026-02-04 10:58:17'),
(14, 23, '2025-2026', '3°', 'F', 'Promovido', '2026-02-04 10:58:48'),
(15, 23, '2025-2026', '3°', 'F', 'Promovido', '2026-02-04 10:59:02'),
(16, 23, '2025-2026', '3°', NULL, 'Promovido', '2026-02-04 11:11:57'),
(17, 12, '2025', '3°', 'A', 'Promovido', '2026-02-04 12:46:41'),
(18, 12, '2025', '3°', 'A', 'Promovido', '2026-02-04 12:46:55'),
(19, 12, '2025', '3°', 'A', 'Promovido', '2026-02-04 12:47:08'),
(20, 28, '2025-2026', '1°', NULL, 'Reprobado', '2026-02-04 12:51:12');

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

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `alumno_id`, `fecha_ingreso`, `periodo`, `estado`) VALUES
(1, 20, '2026-02-03', '2025-2026', 'Activo'),
(2, 21, '2026-02-03', '2025-2026', 'Activo'),
(3, 22, '2026-02-03', '2025-2026', 'Activo'),
(4, 23, '2026-02-03', '2025-2026', 'Activo'),
(5, 24, '2026-02-03', '2025-2026', 'Activo');

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
(30, 'ññññññ', '305767599', '042458888', 'mariana@gmail.com', 'Madre Soltera', NULL, 'LOL', '', 'INGENIERA', 24, 'VENEZOLANA', '', 'EMPLEADO PUBLICO'),
(31, 'Negar', '12793199', '04128311062', 'negarcana@gmail.com', 'Madre Soltera', NULL, 'Jose Gregorio Hernandez, Las cocuizas', NULL, 'Ingeniera', 51, 'Venezolano', 'Universitario', 'Docente'),
(32, 'ANA ROJAS', '14567890', '04245678909', '', 'Madre Soltera', NULL, 'MATURIN', '', 'INGENIERA', 18, 'VENEZOLANA', '', 'EMPLEADO PUBLICO'),
(33, 'CARLOS MARCANO', '14840034', '04125698756', 'pelobravo@gmail.com', 'Madre Soltera', NULL, 'CALLE PRINCIPAL DE LA PUENTE', NULL, 'INGENIERO', 46, 'VENEZOLANA', 'Universitario', 'INGENIERO EN REDES'),
(34, 'marco', '3333333', '0145228886', 'mariana@gmail.com', 'Madre Soltera', NULL, 'la misma del niño', NULL, 'Ingeniera', 30, 'Venezolana', 'Universitario', 'Empleado publico'),
(36, 'Genesis', '26001375', '0425565644', 'negarcana@gmail.com', 'Madre Soltera', NULL, 'la misma que el niño', NULL, 'no tiene ', 30, 'Venezolana', 'Técnico', 'Empleado publico'),
(37, 'mariana', '333333', '04568862', 'mariana@gmail.com', 'Madre Soltera', NULL, 'lllooll', NULL, 'Ingeniera', 22, 'Venezolana', 'Secundaria', 'ama de casa'),
(38, '', '', '', '', '', NULL, '', '', '', 0, '', '', ''),
(41, 'KATI', '2643416', '01446656', 'mariana@gmail.com', '', NULL, 'LLLLLLLL', 'LLLLLLLL', 'INGENIERA', 21, 'VENEZOLANA', 'PRIMARIA', 'EMPLEADO PUBLICO'),
(42, 'RAUL', '45454545', '012456556', 'negarcana@gmail.com', '', NULL, 'LLLLLLL', 'LLLLL', 'NO TIENE ', 30, 'VENEZOLANO', 'PRIMARIA', 'NO TIENE'),
(43, 'MARCO', '3211587', '0444', '', '', NULL, '', '', '', 0, 'VENEZOLANA', '', ''),
(44, 'ASDRUBAL', '3577099', '012546566655', 'negarcana@gmail.com', '', NULL, 'ÑÑÑÑÑ', 'DLLDLDLDL', 'NO TIENE ', 20, 'VENEZOLANA', 'SECUNDARIA', 'EMPLEADO PUBLICO'),
(45, 'NEGAR', '30576759', '04245678909', 'mariana@gmail.com', '', NULL, 'ZXCZCZSD', 'SDFFDS', 'NO TIENE ', 22, 'VENEZOLANA', 'PRIMARIA', 'EMPLEADO PUBLICO'),
(46, 'MATILDE', '30117454', '04245678909', 'negarcana@gmail.com', '', NULL, 'ALALALLA', 'LALALALALAL', 'INGENIERA', 30, 'VENEZOLANA', 'UNIVERSITARIO', 'EMPLEADO PUBLICO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` int(11) NOT NULL,
  `aula_id` int(11) NOT NULL,
  `nivel` varchar(10) NOT NULL,
  `turno` varchar(20) NOT NULL,
  `letra` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id`, `aula_id`, `nivel`, `turno`, `letra`) VALUES
(1, 10, '1°', 'Mañana', 'A'),
(2, 24, '2°', 'Tarde', 'D'),
(3, 4, '1°', 'Tarde', 'A'),
(4, 25, '1°', 'Tarde', 'B'),
(5, 1, '1°', 'Mañana', 'D'),
(6, 4, '1°', 'Tarde', 'D'),
(7, 69, '1°', 'Tarde', 'F'),
(8, 70, '1°', 'Mañana', 'D'),
(9, 71, '1°', 'Mañana', 'A'),
(10, 6, '3°', 'Tarde', 'A');

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
(4, 'Secretaria', 'secretaria@sipre.local', '0987654321', '$2y$10$XRvW/H/vvCTRH8rQZcjJjuRYcJLH7I.in0GdNjNsVrJihbdKSJUva', 'Secretario', 'img/logo.png'),
(7, 'ASHLEY', 'emprendimientod@gmail.com', '30576759', '$2y$10$/sDNFcpvsDxRP5mkSFY4huXFXMEejnrbtVncIDcQ5I/oBzywPz5DS', 'Director', 'uploads/user_6983751ebe9f5.jpg');

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
-- Indices de la tabla `alumno_docente`
--
ALTER TABLE `alumno_docente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_aula` (`nivel`,`turno`,`nombre_grupo`),
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
-- Indices de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`);

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
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aula_id` (`aula_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `alumno_docente`
--
ALTER TABLE `alumno_docente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT de la tabla `condiciones_medicas`
--
ALTER TABLE `condiciones_medicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Filtros para la tabla `alumno_docente`
--
ALTER TABLE `alumno_docente`
  ADD CONSTRAINT `alumno_docente_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `alumno_docente_ibfk_2` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial_academico`
--
ALTER TABLE `historial_academico`
  ADD CONSTRAINT `historial_academico_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);

--
-- Filtros para la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD CONSTRAINT `secciones_ibfk_1` FOREIGN KEY (`aula_id`) REFERENCES `aulas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-06-2025 a las 01:00:22
-- Versión del servidor: 10.11.13-MariaDB-0ubuntu0.24.04.1
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `uruguay_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sector_id` bigint(20) UNSIGNED NOT NULL,
  `house_number` varchar(20) NOT NULL,
  `street` varchar(100) NOT NULL,
  `addressable_type` varchar(255) NOT NULL,
  `addressable_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('home','work') NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classrooms`
--

CREATE TABLE `classrooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `classroom_teachers`
--

CREATE TABLE `classroom_teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disabilities`
--

CREATE TABLE `disabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `disabilities`
--

INSERT INTO `disabilities` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ninguna', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Discapacidad Visual', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'Discapacidad Auditiva', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Discapacidad Motora', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 'Discapacidad Intelectual', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 'Trastorno del Espectro Autista (TEA)', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 'Trastorno por Déficit de Atención e Hiperactividad (TDAH)', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 'Discapacidad del Lenguaje', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 'Otra Discapacidad (Especificar)', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `education_levels`
--

CREATE TABLE `education_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `education_levels`
--

INSERT INTO `education_levels` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ninguno', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Primaria Incompleta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'Primaria Completa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Secundaria Incompleta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 'Secundaria Completa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 'Bachillerato/Preparatoria', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 'Técnico', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 'Universitario Incompleto', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 'Universitario Completo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(10, 'Postgrado', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `school_year_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `classroom_id` bigint(20) UNSIGNED NOT NULL,
  `age` int(11) DEFAULT NULL,
  `shirt_size` varchar(255) DEFAULT NULL,
  `pants_size` varchar(255) DEFAULT NULL,
  `shoe_size` varchar(255) DEFAULT NULL,
  `brachial_circumference` varchar(255) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `levels`
--

INSERT INTO `levels` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Nivel 1', 'sdasdasdasd', NULL, '2025-06-11 07:27:37', '2025-06-11 07:27:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medical_conditions`
--

CREATE TABLE `medical_conditions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medical_conditions`
--

INSERT INTO `medical_conditions` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ninguna', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Asma', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'Alergias (Especificar)', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Diabetes', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 'Epilepsia', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 'Enfermedad Cardíaca', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 'Problemas Renales', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 'Problemas Digestivos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 'Problemas de Salud Mental (Especificar)', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(10, 'Otra Condición Médica (Especificar)', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_01_020624_create_nationalities_table', 1),
(5, '2025_06_01_020639_create_education_levels_table', 1),
(6, '2025_06_01_020709_create_occupations_table', 1),
(7, '2025_06_01_023515_create_nutritional_statuses_table', 1),
(8, '2025_06_01_141631_create_provenances_table', 1),
(9, '2025_06_01_164841_create_personal_access_tokens_table', 1),
(10, '2025_06_01_231356_create_permission_tables', 1),
(11, '2025_06_01_234519_create_levels_table', 1),
(12, '2025_06_01_234529_create_sections_table', 1),
(13, '2025_06_01_234538_create_representatives_table', 1),
(14, '2025_06_01_234538_create_teachers_table', 1),
(15, '2025_06_01_234539_create_classrooms_table', 1),
(16, '2025_06_01_234539_create_school_years_table', 1),
(17, '2025_06_01_234541_create_disabilities_table', 1),
(18, '2025_06_01_234541_create_medical_conditions_table', 1),
(19, '2025_06_01_234541_create_phones_table', 1),
(20, '2025_06_01_234542_create_countries_table', 1),
(21, '2025_06_01_234543_create_states_table', 1),
(22, '2025_06_01_234544_create_municipalities_table', 1),
(23, '2025_06_01_234545_create_parishes_table', 1),
(24, '2025_06_01_234546_create_sectors_table', 1),
(25, '2025_06_01_234547_create_addresses_table', 1),
(26, '2025_06_02_004008_create_students_table', 1),
(27, '2025_06_02_004009_create_enrollments_table', 1),
(28, '2025_06_02_023922_create_relationships_table', 1),
(29, '2025_06_02_023923_create_student_representative_table', 1),
(30, '2025_06_02_030255_create_student_disability_table', 1),
(31, '2025_06_05_160414_create_classroom_teachers_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipalities`
--

CREATE TABLE `municipalities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `municipalities`
--

INSERT INTO `municipalities` (`id`, `state_id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 14, 'Maturín', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(2, 14, 'Acosta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(3, 14, 'Aguasay', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(4, 14, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(5, 14, 'Caripe', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(6, 14, 'Cedeño', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(7, 14, 'Ezequiel Zamora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(8, 14, 'Libertador', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(9, 14, 'Piar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(10, 14, 'Punceres', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(11, 14, 'Santa Bárbara', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(12, 14, 'Sotillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(13, 14, 'Uracoa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(14, 1, 'Alto Orinoco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(15, 1, 'Atabapo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(16, 1, 'Atures', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(17, 1, 'Autana', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(18, 1, 'Manapiare', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(19, 1, 'Maroa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(20, 1, 'Río Negro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(21, 2, 'Anaco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(22, 2, 'Aragua', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(23, 2, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(24, 2, 'Bruzual', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(25, 2, 'Cajigal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(26, 2, 'Carvajal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(27, 2, 'Diego Bautista Urbaneja', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(28, 2, 'Freites', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(29, 2, 'Guanipa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(30, 2, 'Guanta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(31, 2, 'Independencia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(32, 2, 'Libertad', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(33, 2, 'McGregor', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(34, 2, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(35, 2, 'Monagas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(36, 2, 'Peñalver', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(37, 2, 'Píritu', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(38, 2, 'San José de Guanipa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(39, 2, 'San Juan de Capistrano', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(40, 2, 'Santa Ana', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(41, 2, 'Simón Rodríguez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(42, 2, 'Sotillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(43, 3, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(44, 3, 'Camatagua', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(45, 3, 'Francisco Linares Alcántara', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(46, 3, 'Girardot', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(47, 3, 'José Ángel Lamas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(48, 3, 'José Félix Ribas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(49, 3, 'José Rafael Revenga', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(50, 3, 'Libertador', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(51, 3, 'Mario Briceño Iragorry', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(52, 3, 'Ocumare de la Costa de Oro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(53, 3, 'San Casimiro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(54, 3, 'San Sebastián', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(55, 3, 'Santiago Mariño', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(56, 3, 'Santos Michelena', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(57, 3, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(58, 3, 'Tovar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(59, 3, 'Urdaneta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(60, 3, 'Zamora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(61, 4, 'Alberto Arvelo Torrealba', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(62, 4, 'Andrés Eloy Blanco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(63, 4, 'Antonio José de Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(64, 4, 'Arismendi', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(65, 4, 'Barinas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(66, 4, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(67, 4, 'Cruz Paredes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(68, 4, 'Ezequiel Zamora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(69, 4, 'Obispos', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(70, 4, 'Pedraza', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(71, 4, 'Rojas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(72, 4, 'Sosa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(73, 5, 'Caroní', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(74, 5, 'Cedeño', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(75, 5, 'El Callao', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(76, 5, 'Gran Sabana', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(77, 5, 'Heres', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(78, 5, 'Piar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(79, 5, 'Roscio', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(80, 5, 'Sifontes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(81, 5, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(82, 5, 'Padre Pedro Chien', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(83, 6, 'Bejuma', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(84, 6, 'Carlos Arvelo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(85, 6, 'Diego Ibarra', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(86, 6, 'Guacara', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(87, 6, 'Juan José Mora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(88, 6, 'Libertador', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(89, 6, 'Los Guayos', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(90, 6, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(91, 6, 'Montalbán', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(92, 6, 'Naguanagua', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(93, 6, 'Puerto Cabello', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(94, 6, 'San Diego', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(95, 6, 'San Joaquín', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(96, 6, 'Valencia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(97, 7, 'Anzoátegui', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(98, 7, 'Tinaquillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(99, 7, 'Girardot', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(100, 7, 'Lima Blanco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(101, 7, 'Pao de San Juan Bautista', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(102, 7, 'Ricaurte', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(103, 7, 'Rómulo Gallegos', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(104, 7, 'San Carlos', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(105, 8, 'Antonio Díaz', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(106, 8, 'Casacoima', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(107, 8, 'Pedernales', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(108, 8, 'Tucupita', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(109, 9, 'Acosta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(110, 9, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(111, 9, 'Buchivacoa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(112, 9, 'Cacique Manaure', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(113, 9, 'Carirubana', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(114, 9, 'Colina', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(115, 9, 'Dabajuro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(116, 9, 'Democracia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(117, 9, 'Falcón', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(118, 9, 'Federación', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(119, 9, 'Jacura', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(120, 9, 'Los Taques', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(121, 9, 'Mauroa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(122, 9, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(123, 9, 'Monseñor Iturriza', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(124, 9, 'Palmasola', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(125, 9, 'Petit', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(126, 9, 'Píritu', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(127, 9, 'San Francisco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(128, 9, 'Silva', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(129, 9, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(130, 9, 'Tocópero', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(131, 9, 'Unión', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(132, 9, 'Urumaco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(133, 9, 'Zamora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(134, 10, 'Camaguán', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(135, 10, 'Chaguaramas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(136, 10, 'El Socorro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(137, 10, 'José Félix Ribas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(138, 10, 'José Tadeo Monagas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(139, 10, 'Juan Germán Roscio', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(140, 10, 'Julián Mellado', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(141, 10, 'Las Mercedes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(142, 10, 'Pedro Zaraza', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(143, 10, 'San Gerónimo de Guayabal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(144, 10, 'San José de Guaribe', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(145, 10, 'Santa María de Ipire', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(146, 10, 'Sebastián Francisco de Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(147, 10, 'Ortiz', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(148, 11, 'Andrés Eloy Blanco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(149, 11, 'Crespo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(150, 11, 'Iribarren', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(151, 11, 'Jiménez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(152, 11, 'Morán', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(153, 11, 'Palavecino', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(154, 11, 'Simón Planas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(155, 11, 'Torres', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(156, 11, 'Urdaneta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(157, 12, 'Alberto Adriani', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(158, 12, 'Andrés Bello', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(159, 12, 'Antonio Pinto Salinas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(160, 12, 'Aricagua', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(161, 12, 'Arzobispo Chacón', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(162, 12, 'Campo Elías', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(163, 12, 'Caracciolo Parra Olmedo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(164, 12, 'Cardenal Quintero', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(165, 12, 'Guaraque', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(166, 12, 'Julio César Salas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(167, 12, 'Justo Briceño', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(168, 12, 'Libertador', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(169, 12, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(170, 12, 'Obispo Ramos de Lora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(171, 12, 'Padre Noguera', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(172, 12, 'Pueblo Llano', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(173, 12, 'Rangel', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(174, 12, 'Rivas Dávila', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(175, 12, 'Santos Marquina', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(176, 12, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(177, 12, 'Tovar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(178, 12, 'Tulio Febres Cordero', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(179, 12, 'Zea', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(180, 13, 'Acevedo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(181, 13, 'Andrés Bello', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(182, 13, 'Baruta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(183, 13, 'Brión', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(184, 13, 'Buroz', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(185, 13, 'Carrizal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(186, 13, 'Chacao', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(187, 13, 'Cristóbal Rojas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(188, 13, 'El Hatillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(189, 13, 'Guaicaipuro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(190, 13, 'Independencia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(191, 13, 'Lander', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(192, 13, 'Los Salias', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(193, 13, 'Páez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(194, 13, 'Paz Castillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(195, 13, 'Plaza', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(196, 13, 'Simón Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(197, 13, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(198, 13, 'Urdaneta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(199, 13, 'Zamora', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(200, 15, 'Antolín del Campo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(201, 15, 'Arismendi', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(202, 15, 'Díaz', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(203, 15, 'García', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(204, 15, 'Gómez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(205, 15, 'Maneiro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(206, 15, 'Marcano', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(207, 15, 'Mariño', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(208, 15, 'Península de Macanao', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(209, 15, 'Tubores', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(210, 15, 'Villalba', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(211, 16, 'Agua Blanca', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(212, 16, 'Araure', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(213, 16, 'Esteller', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(214, 16, 'Guanare', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(215, 16, 'Guanarito', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(216, 16, 'José Antonio Páez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(217, 16, 'Ospino', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(218, 16, 'Páez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(219, 16, 'Papelón', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(220, 16, 'San Genaro de Boconoíto', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(221, 16, 'San Rafael de Onoto', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(222, 16, 'Santa Rosalía', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(223, 16, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(224, 16, 'Turén', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(225, 17, 'Andrés Eloy Blanco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(226, 17, 'Andrés Mata', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(227, 17, 'Arismendi', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(228, 17, 'Benítez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(229, 17, 'Bermúdez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(230, 17, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(231, 17, 'Cajigal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(232, 17, 'Cruz Salmerón Acosta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(233, 17, 'Libertador', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(234, 17, 'Mariño', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(235, 17, 'Mejía', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(236, 17, 'Montes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(237, 17, 'Ribero', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(238, 17, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(239, 17, 'Valdez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(240, 18, 'Andrés Bello', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(241, 18, 'Antonio Rómulo Costa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(242, 18, 'Ayacucho', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(243, 18, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(244, 18, 'Cárdenas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(245, 18, 'Córdoba', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(246, 18, 'Fernández Feo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(247, 18, 'Francisco de Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(248, 18, 'García de Hevia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(249, 18, 'Guásimos', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(250, 18, 'Independencia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(251, 18, 'Jáuregui', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(252, 18, 'José María Vargas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(253, 18, 'Junín', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(254, 18, 'Libertad', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(255, 18, 'Libertador', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(256, 18, 'Lobatera', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(257, 18, 'Michelena', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(258, 18, 'Panamericano', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(259, 18, 'Pedro María Ureña', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(260, 18, 'Rafael Urdaneta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(261, 18, 'Samuel Darío Maldonado', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(262, 18, 'San Cristóbal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(263, 18, 'Seboruco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(264, 18, 'Simón Rodríguez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(265, 18, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(266, 18, 'Torbes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(267, 18, 'Uribante', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(268, 18, 'San Judas Tadeo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(269, 19, 'Andrés Bello', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(270, 19, 'Bocono', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(271, 19, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(272, 19, 'Candelaria', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(273, 19, 'Carache', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(274, 19, 'Escuque', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(275, 19, 'José Felipe Márquez Cañizales', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(276, 19, 'Juan Vicente Campos Elías', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(277, 19, 'La Ceiba', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(278, 19, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(279, 19, 'Monte Carmelo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(280, 19, 'Motatán', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(281, 19, 'Pampán', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(282, 19, 'Pampanito', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(283, 19, 'Rafael Rangel', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(284, 19, 'San Rafael de Carvajal', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(285, 19, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(286, 19, 'Trujillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(287, 19, 'Urdaneta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(288, 19, 'Valera', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(289, 20, 'Vargas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(290, 21, 'Arístides Bastidas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(291, 21, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(292, 21, 'Bruzual', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(293, 21, 'Cocorote', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(294, 21, 'Independencia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(295, 21, 'José Antonio Páez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(296, 21, 'La Trinidad', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(297, 21, 'Manuel Monge', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(298, 21, 'Nirgua', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(299, 21, 'Peña', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(300, 21, 'San Felipe', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(301, 21, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(302, 21, 'Urachiche', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(303, 21, 'Veroes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(304, 22, 'Almirante Padilla', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(305, 22, 'Baralt', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(306, 22, 'Cabimas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(307, 22, 'Catatumbo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(308, 22, 'Colón', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(309, 22, 'Francisco Javier Pulgar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(310, 22, 'Guajira', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(311, 22, 'Jesús Enrique Lossada', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(312, 22, 'Jesús María Semprún', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(313, 22, 'La Cañada de Urdaneta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(314, 22, 'Lagunillas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(315, 22, 'Machiques de Perijá', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(316, 22, 'Mara', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(317, 22, 'Maracaibo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(318, 22, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(319, 22, 'Páez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(320, 22, 'Rosario de Perijá', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(321, 22, 'San Francisco', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(322, 22, 'Santa Rita', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(323, 22, 'Simón Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(324, 22, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(325, 22, 'Valmore Rodríguez', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(326, 23, 'Dependencias Federales', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nationalities`
--

CREATE TABLE `nationalities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `nationalities`
--

INSERT INTO `nationalities` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Venezolano', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Extranjero', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nutritional_statuses`
--

CREATE TABLE `nutritional_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `nutritional_statuses`
--

INSERT INTO `nutritional_statuses` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Normal', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Bajo Peso', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'Sobrepeso', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Obesidad', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `occupations`
--

CREATE TABLE `occupations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `occupations`
--

INSERT INTO `occupations` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Ama de Casa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Obrero/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'Empleado/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Comerciante', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 'Técnico/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 'Profesional Independiente', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 'Docente', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 'Jubilado/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 'Desempleado/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(10, 'Estudiante', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(11, 'Agricultor/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(12, 'Ganadero/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(13, 'Pescador/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(14, 'Mecánico/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(15, 'Conductor/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(16, 'Enfermero/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(17, 'Médico/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(18, 'Abogado/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(19, 'Ingeniero/a', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(20, 'Militar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parishes`
--

CREATE TABLE `parishes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `municipality_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `parishes`
--

INSERT INTO `parishes` (`id`, `municipality_id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'El Furrial', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 1, 'San Simón', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 1, 'Alto de Los Godos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 1, 'Boquerón', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 1, 'Las Cocuizas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 1, 'San Vicente', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 1, 'El Corozo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 1, 'La Cruz', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 1, 'Maturín', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(10, 1, 'Jusepín', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(11, 2, 'San Antonio', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(12, 2, 'Los Altos de Sucre', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(13, 2, 'Santa Inés', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(14, 2, 'Libertad', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(15, 3, 'Aguasay', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(16, 4, 'Caripito', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(17, 5, 'Caripe', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(18, 5, 'El Guacharo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(19, 6, 'Caicara', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(20, 6, 'San Felix', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(21, 6, 'Areo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(22, 6, 'Viento Fresco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(23, 7, 'Punta de Mata', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(24, 8, 'Temblador', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(25, 9, 'Aragua', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(26, 10, 'Cachipo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(27, 11, 'Santa Bárbara', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(28, 12, 'Barrancas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(29, 13, 'Uracoa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(30, 14, 'La Esmeralda', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(31, 14, 'Maripa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(32, 15, 'Atabapo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(33, 15, 'Ucata', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(34, 16, 'Fernando Girón Tovar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(35, 16, 'Luis Alberto Gómez', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(36, 17, 'Uramapí', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(37, 17, 'Sipapo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(38, 17, 'Munduapo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(39, 17, 'Guayapo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(40, 18, 'San Juan de Manapiare', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(41, 19, 'Maroa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(42, 19, 'Victorino', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(43, 20, 'San Carlos de Río Negro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(44, 20, 'Solano', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(45, 21, 'Anaco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(46, 21, 'San Joaquin', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(47, 22, 'Aragua', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(48, 23, 'Barcelona', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(49, 23, 'Cachiri', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(50, 23, 'El Carmen', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(51, 23, 'San Cristobal', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(52, 24, 'Clarines', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(53, 25, 'Onoto', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(54, 26, 'Valle de Guanape', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(55, 27, 'Lechería', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(56, 28, 'Cantaura', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(57, 29, 'San José de Guanipa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(58, 30, 'Guanta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(59, 31, 'Soledad', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(60, 32, 'San Mateo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(61, 33, 'El Chaparro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(62, 34, 'Atapirire', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(63, 35, 'San Diego', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(64, 36, 'Puerto Píritu', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(65, 37, 'Píritu', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(66, 38, 'El Tigre', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(67, 39, 'Boca de Uchire', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(68, 40, 'Santa Ana', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(69, 41, 'El Tigre', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(70, 42, 'Puerto la Cruz', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(71, 43, 'San Mateo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(72, 44, 'Camatagua', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(73, 45, 'Santa Rita', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(74, 46, 'Andrés Eloy Blanco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(75, 46, 'Joaquín Crespo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(76, 46, 'José Casanova Godoy', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(77, 46, 'Madre María de San José', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(78, 46, 'Pedro José Ovalles', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(79, 47, 'Santa Cruz', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(80, 48, 'Castor Nieves Ríos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(81, 48, 'Las Guacamayas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(82, 48, 'Pao de Zárate', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(83, 48, 'Zuata', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(84, 49, 'El Consejo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(85, 50, 'Palo Negro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(86, 50, 'San Martín de Porres', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(87, 51, 'El Limón', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(88, 52, 'Ocumare de la Costa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(89, 53, 'San Casimiro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(90, 54, 'San Sebastián', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(91, 55, 'Turmero', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(92, 56, 'Tejerías', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(93, 57, 'Cagua', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(94, 58, 'Tovar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(95, 59, 'Barbacoas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(96, 60, 'Villa de Cura', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(97, 61, 'Sabaneta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(98, 62, 'El Cantón', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(99, 63, 'Socopó', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(100, 64, 'Arismendi', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(101, 65, 'Alto Barinas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(102, 65, 'Barinas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(103, 65, 'Corazón de Jesús', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(104, 65, 'El Carmen', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(105, 65, 'Juan Antonio Rodríguez Domínguez', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(106, 65, 'Manuel Palacio Fajardo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(107, 65, 'Ramón Ignacio Méndez', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(108, 65, 'Rómulo Betancourt', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(109, 65, 'Alfredo Arvelo Larriva', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(110, 66, 'Barinitas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(111, 67, 'Barrancas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(112, 68, 'Santa Inés', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(113, 69, 'Obispos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(114, 70, 'Ciudad Bolivia', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(115, 71, 'Libertad', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(116, 72, 'Ciudad de Nutrias', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(117, 73, 'Cachamay', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(118, 73, 'Chirica', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(119, 73, 'Dalla Costa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(120, 73, 'Once de Abril', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(121, 73, 'Pozo Verde', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(122, 73, 'Simón Bolívar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(123, 73, 'Unare', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(124, 73, 'Vista al Sol', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(125, 73, 'Yacumayo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(126, 73, 'Ciudad Nueva', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(127, 74, 'Altagracia', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(128, 74, 'Ascensión Farreras', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(129, 74, 'Guaniamo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(130, 74, 'Los Pijiguaos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(131, 74, 'Pijiguaos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(132, 74, 'Urimán', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(133, 75, 'El Callao', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(134, 76, 'Gran Sabana', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(135, 76, 'Ikabarú', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(136, 77, 'Agua Salada', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(137, 77, 'Catedral', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(138, 77, 'José Antonio Páez', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(139, 77, 'La Sabanita', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(140, 77, 'Marhuanta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(141, 77, 'Orinoco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(142, 77, 'Panapana', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(143, 77, 'Vista Hermosa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(144, 78, 'Andrés Eloy Blanco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(145, 78, 'Upata', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(146, 78, 'Pedro Cova', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(147, 79, 'Salóm', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(148, 79, 'San Isidro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(149, 80, 'Tumeremo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(150, 80, 'Dalla Costa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(151, 81, 'Aripao', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(152, 81, 'Guarataro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(153, 81, 'Maripa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(154, 81, 'Moitaco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'auth_token', '28d8858202b0ab071215a18d114a532141c1a1865d1308c81cba0ed34a6c6f24', '[\"*\"]', '2025-06-11 07:44:11', NULL, '2025-06-11 07:22:32', '2025-06-11 07:44:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phones`
--

CREATE TABLE `phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(20) NOT NULL,
  `type` enum('landline','mobile') NOT NULL,
  `area_code` varchar(10) DEFAULT NULL,
  `carrier` varchar(20) DEFAULT NULL,
  `phoneable_type` varchar(255) NOT NULL,
  `phoneable_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provenances`
--

CREATE TABLE `provenances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `provenances`
--

INSERT INTO `provenances` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Hogar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Del Mismo plantel', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'De otro plantel', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Multihogar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 'Hogar de cuidado', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 'Guarderia', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 'Otros', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relationships`
--

CREATE TABLE `relationships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `relationships`
--

INSERT INTO `relationships` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Madre', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 'Padre', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 'Hermano', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 'Hermana', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 'Hijo', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 'Hija', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 'Esposa', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 'Marido', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 'Otro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representatives`
--

CREATE TABLE `representatives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `children_under_6_years_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nationality_id` bigint(20) UNSIGNED NOT NULL,
  `education_level_id` bigint(20) UNSIGNED NOT NULL,
  `occupation_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'web', NULL, NULL),
(2, 'Secretaria', 'web', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `school_years`
--

CREATE TABLE `school_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectors`
--

CREATE TABLE `sectors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parish_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(250) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sectors`
--

INSERT INTO `sectors` (`id`, `parish_id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 9, 'Zona Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(2, 9, 'Barrio Obrero', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(3, 9, 'Las Cocuizas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(4, 9, 'Los Guaritos I', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(5, 9, 'Los Guaritos II', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(6, 9, 'Los Guaritos III', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(7, 9, 'Los Guaritos IV', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(8, 9, 'Los Guaritos V', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(9, 9, 'El Parquecito', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(10, 9, 'La Muralla', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(11, 9, 'La Floresta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(12, 9, 'Fundemos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(13, 9, 'Simón Bolívar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(14, 9, 'Juanico', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(15, 9, 'Los Cortijos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(16, 9, 'Ciudad Universitaria', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(17, 9, 'Alto Paramaconi', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(18, 9, 'Paramaconi', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(19, 9, 'Villas del Paramaconi', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(20, 9, 'Terrazas del Paramaconi', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(21, 9, 'Doña Menca I', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(22, 9, 'Doña Menca II', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(23, 9, 'Prados del Este', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(24, 9, 'Avenida Bella Vista', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(25, 9, 'Las Brisas del Orinoco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(26, 9, 'La Concordia', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(27, 9, 'Complejo Habitacional Paramaconi', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(28, 9, 'Lomas del Viento', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(29, 9, 'Valle Verde', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(30, 9, 'El Guayabal', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(31, 9, 'La Cruz', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(32, 9, 'Virgen del Valle', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(33, 9, '23 de Enero', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(34, 1, 'Sector El Furrial Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(35, 1, 'Sector La Manga', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(36, 1, 'Sector Las Parcelas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(37, 1, 'Sector Sabana Grande', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(38, 2, 'Sector Centro San Simón', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(39, 2, 'Sector La Floresta', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(40, 2, 'Sector Fundemos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(41, 2, 'Sector Villas del Sur', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(42, 3, 'Sector Alto de Los Godos Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(43, 3, 'Sector Juana La Avanzadora', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(44, 3, 'Sector La Constitucion', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(45, 3, 'Sector Terrazas del Oeste', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(46, 4, 'Sector Boquerón Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(47, 4, 'Sector El Caruto', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(48, 4, 'Sector La Candelaria', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(49, 4, 'Sector Valle Verde', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(50, 5, 'Sector Las Cocuizas Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(51, 5, 'Sector La Gran Victoria', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(52, 5, 'Sector Los Cortijos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(53, 5, 'Sector Doña Menca', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(54, 6, 'Sector San Vicente Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(55, 6, 'Sector Las Carolinas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(56, 6, 'Sector Brisas del Orinoco', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(57, 6, 'Sector La Puente', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(58, 7, 'Sector El Corozo Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(59, 7, 'Sector El Zamuro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(60, 7, 'Sector La Laguna', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(61, 7, 'Sector Palital', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(62, 8, 'Sector La Cruz Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(63, 8, 'Sector Nuevo Horizonte', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(64, 8, 'Sector Simón Bolívar', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(65, 8, 'Sector 19 de Abril', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(66, 9, 'Sector Centro Maturín', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(67, 9, 'Sector Los Guaritos', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(68, 9, 'Sector Las Avenidas', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(69, 9, 'Sector Zona Industrial', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(70, 10, 'Sector Jusepín Centro', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(71, 10, 'Sector El Paraíso', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(72, 10, 'Sector La Toscana', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23'),
(73, 10, 'Sector La Ceiba', NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `states`
--

INSERT INTO `states` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Amazonas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(2, 'Anzoátegui', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(3, 'Aragua', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(4, 'Barinas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(5, 'Bolívar', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(6, 'Carabobo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(7, 'Cojedes', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(8, 'Delta Amacuro', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(9, 'Falcón', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(10, 'Guárico', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(11, 'Lara', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(12, 'Mérida', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(13, 'Miranda', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(14, 'Monagas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(15, 'Nueva Esparta', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(16, 'Portuguesa', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(17, 'Sucre', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(18, 'Táchira', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(19, 'Trujillo', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(20, 'Vargas', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(21, 'Yaracuy', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(22, 'Zulia', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22'),
(23, 'Dependencias Federales', NULL, '2025-06-11 07:20:22', '2025-06-11 07:20:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nationality_id` bigint(20) UNSIGNED NOT NULL,
  `provenance_id` bigint(20) UNSIGNED NOT NULL,
  `medical_condition_id` bigint(20) UNSIGNED NOT NULL,
  `disability_id` bigint(20) UNSIGNED NOT NULL,
  `nutritional_status_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_disability`
--

CREATE TABLE `student_disability` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `disability_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_representative`
--

CREATE TABLE `student_representative` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `representative_id` bigint(20) UNSIGNED NOT NULL,
  `relationship_id` bigint(20) UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `education_level_id` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `status`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Test', 'Ashley', 'test@example.com', NULL, '$2y$12$8cULplTcZZ/7F7aP.ItSEOr6mvtMue1oC5.l0mDABQPJHBOw1Qhla', 1, NULL, NULL, '2025-06-11 07:20:23', '2025-06-11 07:20:23');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_sector_id_foreign` (`sector_id`),
  ADD KEY `addresses_addressable_type_addressable_id_index` (`addressable_type`,`addressable_id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `classrooms`
--
ALTER TABLE `classrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classrooms_section_id_foreign` (`section_id`);

--
-- Indices de la tabla `classroom_teachers`
--
ALTER TABLE `classroom_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classroom_teachers_teacher_id_foreign` (`teacher_id`),
  ADD KEY `classroom_teachers_classroom_id_teacher_id_index` (`classroom_id`,`teacher_id`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `disabilities`
--
ALTER TABLE `disabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_student_id_foreign` (`student_id`),
  ADD KEY `enrollments_school_year_id_foreign` (`school_year_id`),
  ADD KEY `enrollments_section_id_foreign` (`section_id`),
  ADD KEY `enrollments_classroom_id_foreign` (`classroom_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `levels_name_unique` (`name`);

--
-- Indices de la tabla `medical_conditions`
--
ALTER TABLE `medical_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `municipalities_state_id_foreign` (`state_id`);

--
-- Indices de la tabla `nationalities`
--
ALTER TABLE `nationalities`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `nutritional_statuses`
--
ALTER TABLE `nutritional_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `occupations`
--
ALTER TABLE `occupations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parishes`
--
ALTER TABLE `parishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parishes_municipality_id_foreign` (`municipality_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phones_phoneable_type_phoneable_id_index` (`phoneable_type`,`phoneable_id`);

--
-- Indices de la tabla `provenances`
--
ALTER TABLE `provenances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provenances_name_unique` (`name`);

--
-- Indices de la tabla `relationships`
--
ALTER TABLE `relationships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `relationships_name_unique` (`name`);

--
-- Indices de la tabla `representatives`
--
ALTER TABLE `representatives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `representatives_id_number_unique` (`id_number`),
  ADD KEY `representatives_nationality_id_foreign` (`nationality_id`),
  ADD KEY `representatives_education_level_id_foreign` (`education_level_id`),
  ADD KEY `representatives_occupation_id_foreign` (`occupation_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_years_name_unique` (`name`);

--
-- Indices de la tabla `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sections_level_id_foreign` (`level_id`);

--
-- Indices de la tabla `sectors`
--
ALTER TABLE `sectors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sectors_parish_id_foreign` (`parish_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_nationality_id_foreign` (`nationality_id`),
  ADD KEY `students_provenance_id_foreign` (`provenance_id`),
  ADD KEY `students_medical_condition_id_foreign` (`medical_condition_id`),
  ADD KEY `students_disability_id_foreign` (`disability_id`),
  ADD KEY `students_nutritional_status_id_foreign` (`nutritional_status_id`),
  ADD KEY `students_first_name_last_name_index` (`first_name`,`last_name`);

--
-- Indices de la tabla `student_disability`
--
ALTER TABLE `student_disability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_disability_student_id_disability_id_unique` (`student_id`,`disability_id`),
  ADD KEY `student_disability_disability_id_foreign` (`disability_id`);

--
-- Indices de la tabla `student_representative`
--
ALTER TABLE `student_representative`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_representative_student_id_foreign` (`student_id`),
  ADD KEY `student_representative_representative_id_foreign` (`representative_id`),
  ADD KEY `student_representative_relationship_id_foreign` (`relationship_id`);

--
-- Indices de la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_id_number_unique` (`id_number`),
  ADD KEY `teachers_education_level_id_foreign` (`education_level_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `classrooms`
--
ALTER TABLE `classrooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `classroom_teachers`
--
ALTER TABLE `classroom_teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `disabilities`
--
ALTER TABLE `disabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `medical_conditions`
--
ALTER TABLE `medical_conditions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=327;

--
-- AUTO_INCREMENT de la tabla `nationalities`
--
ALTER TABLE `nationalities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `nutritional_statuses`
--
ALTER TABLE `nutritional_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `occupations`
--
ALTER TABLE `occupations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `parishes`
--
ALTER TABLE `parishes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `phones`
--
ALTER TABLE `phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provenances`
--
ALTER TABLE `provenances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `relationships`
--
ALTER TABLE `relationships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `representatives`
--
ALTER TABLE `representatives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sectors`
--
ALTER TABLE `sectors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT de la tabla `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `student_disability`
--
ALTER TABLE `student_disability`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `student_representative`
--
ALTER TABLE `student_representative`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `sectors` (`id`);

--
-- Filtros para la tabla `classrooms`
--
ALTER TABLE `classrooms`
  ADD CONSTRAINT `classrooms_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Filtros para la tabla `classroom_teachers`
--
ALTER TABLE `classroom_teachers`
  ADD CONSTRAINT `classroom_teachers_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`),
  ADD CONSTRAINT `classroom_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`);

--
-- Filtros para la tabla `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_classroom_id_foreign` FOREIGN KEY (`classroom_id`) REFERENCES `classrooms` (`id`),
  ADD CONSTRAINT `enrollments_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`),
  ADD CONSTRAINT `enrollments_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`),
  ADD CONSTRAINT `enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Filtros para la tabla `parishes`
--
ALTER TABLE `parishes`
  ADD CONSTRAINT `parishes_municipality_id_foreign` FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`id`);

--
-- Filtros para la tabla `representatives`
--
ALTER TABLE `representatives`
  ADD CONSTRAINT `representatives_education_level_id_foreign` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`),
  ADD CONSTRAINT `representatives_nationality_id_foreign` FOREIGN KEY (`nationality_id`) REFERENCES `nationalities` (`id`),
  ADD CONSTRAINT `representatives_occupation_id_foreign` FOREIGN KEY (`occupation_id`) REFERENCES `occupations` (`id`);

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`);

--
-- Filtros para la tabla `sectors`
--
ALTER TABLE `sectors`
  ADD CONSTRAINT `sectors_parish_id_foreign` FOREIGN KEY (`parish_id`) REFERENCES `parishes` (`id`);

--
-- Filtros para la tabla `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_disability_id_foreign` FOREIGN KEY (`disability_id`) REFERENCES `disabilities` (`id`),
  ADD CONSTRAINT `students_medical_condition_id_foreign` FOREIGN KEY (`medical_condition_id`) REFERENCES `medical_conditions` (`id`),
  ADD CONSTRAINT `students_nationality_id_foreign` FOREIGN KEY (`nationality_id`) REFERENCES `nationalities` (`id`),
  ADD CONSTRAINT `students_nutritional_status_id_foreign` FOREIGN KEY (`nutritional_status_id`) REFERENCES `nutritional_statuses` (`id`),
  ADD CONSTRAINT `students_provenance_id_foreign` FOREIGN KEY (`provenance_id`) REFERENCES `provenances` (`id`);

--
-- Filtros para la tabla `student_disability`
--
ALTER TABLE `student_disability`
  ADD CONSTRAINT `student_disability_disability_id_foreign` FOREIGN KEY (`disability_id`) REFERENCES `disabilities` (`id`),
  ADD CONSTRAINT `student_disability_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Filtros para la tabla `student_representative`
--
ALTER TABLE `student_representative`
  ADD CONSTRAINT `student_representative_relationship_id_foreign` FOREIGN KEY (`relationship_id`) REFERENCES `relationships` (`id`),
  ADD CONSTRAINT `student_representative_representative_id_foreign` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`),
  ADD CONSTRAINT `student_representative_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Filtros para la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_education_level_id_foreign` FOREIGN KEY (`education_level_id`) REFERENCES `education_levels` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

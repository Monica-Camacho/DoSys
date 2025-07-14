-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-07-2025 a las 06:35:48
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
-- Base de datos: `dosys_db_v2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alergias`
--

CREATE TABLE `alergias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alergias`
--

INSERT INTO `alergias` (`id`, `nombre`, `categoria`) VALUES
(6, 'Leche', 'Alergias Alimentarias'),
(7, 'Huevos', 'Alergias Alimentarias'),
(8, 'Cacahuates (Maní)', 'Alergias Alimentarias'),
(9, 'Frutos Secos de Árbol', 'Alergias Alimentarias'),
(10, 'Soja (Soy)', 'Alergias Alimentarias'),
(11, 'Trigo', 'Alergias Alimentarias'),
(12, 'Pescado', 'Alergias Alimentarias'),
(13, 'Mariscos Crustáceos', 'Alergias Alimentarias'),
(14, 'Sésamo (Ajonjolí)', 'Alergias Alimentarias'),
(15, 'Polen', 'Alergias Ambientales'),
(16, 'Caspa de Animales', 'Alergias Ambientales'),
(17, 'Ácaros del Polvo', 'Alergias Ambientales'),
(18, 'Moho', 'Alergias Ambientales'),
(19, 'Penicilina', 'Alergias a Medicamentos'),
(20, 'AINE (Aspirina, Ibuprofeno)', 'Alergias a Medicamentos'),
(21, 'Sulfamidas (Sulfas)', 'Alergias a Medicamentos'),
(22, 'Fármacos de Quimioterapia', 'Alergias a Medicamentos'),
(23, 'Medios de Contraste para Rayos X', 'Alergias a Medicamentos'),
(24, 'Abejas', 'Alergias a Picaduras de Insectos'),
(25, 'Avispas / Avispones', 'Alergias a Picaduras de Insectos'),
(26, 'Hormigas Rojas (de Fuego)', 'Alergias a Picaduras de Insectos'),
(27, 'Látex', 'Alergias de Contacto'),
(28, 'Metales (Níquel)', 'Alergias de Contacto'),
(29, 'Hiedra Venenosa', 'Alergias de Contacto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avisos`
--

CREATE TABLE `avisos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `creador_id` int(11) NOT NULL,
  `organizacion_id` int(11) NOT NULL,
  `beneficiario_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `urgencia_id` int(11) NOT NULL,
  `estatus_id` int(11) NOT NULL,
  `contacto_responsable_id` int(11) NOT NULL COMMENT 'FK a la tabla de usuarios, rol "Gestor de Casos"',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avisos`
--

INSERT INTO `avisos` (`id`, `titulo`, `descripcion`, `creador_id`, `organizacion_id`, `beneficiario_id`, `categoria_id`, `urgencia_id`, `estatus_id`, `contacto_responsable_id`, `fecha_creacion`, `fecha_cierre`) VALUES
(1, 'Se necesitan donadores de sangre O+', 'Paciente María Elena Sánchez requiere 4 unidades de sangre O+ para cirugía programada.', 5, 3, 1, 1, 3, 2, 5, '2025-07-09 20:24:02', NULL),
(2, 'Urgente: Medicamento para diabetes infantil', 'José Luis Ramírez necesita con urgencia Insulina Glargina (Lantus). No contamos con el recurso.', 5, 3, 2, 2, 4, 2, 5, '2025-07-09 20:24:02', NULL),
(3, 'Solicitud de Silla de Ruedas', 'Se solicita una silla de ruedas estándar para adulto mayor con movilidad reducida.', 5, 3, 1, 3, 2, 1, 5, '2025-07-09 20:24:02', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avisos_documentos`
--

CREATE TABLE `avisos_documentos` (
  `id` int(11) NOT NULL,
  `aviso_id` int(11) NOT NULL,
  `tipo_documento` enum('Receta Médica','Orden Médica','Identificación','Otro') NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `fecha_carga` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficiarios`
--

CREATE TABLE `beneficiarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('Masculino','Femenino') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `beneficiarios`
--

INSERT INTO `beneficiarios` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `sexo`) VALUES
(1, 'María Elena', 'Sánchez', NULL, '1965-03-22', 'Femenino'),
(2, 'José Luis', 'Ramírez', NULL, '2010-11-08', 'Masculino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_donacion`
--

CREATE TABLE `categorias_donacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_donacion`
--

INSERT INTO `categorias_donacion` (`id`, `nombre`) VALUES
(1, 'Sangre'),
(2, 'Medicamentos'),
(3, 'Dispositivos'),
(4, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id` int(11) NOT NULL,
  `calle` varchar(255) NOT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(100) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `municipio` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id`, `calle`, `numero_exterior`, `numero_interior`, `colonia`, `codigo_postal`, `municipio`, `estado`, `latitud`, `longitud`) VALUES
(1, 'Ceiba', '1222', '', 'Primera de mayo', '86100', 'Centro', 'Tabasco', 17.97902227, -92.93520982),
(2, 'Calle Ficticia', '123', NULL, 'Centro', '86000', 'Centro', 'Tabasco', 17.99400000, -92.92900000),
(3, 'Blvd. Adolfo Ruiz Cortines', '1800', NULL, 'Atasta de Serra', '86100', 'Centro', 'Tabasco', 17.99500000, -92.94800000),
(4, 'Paseo Tabasco', '1504', NULL, 'Lindia Vista', '86050', 'Centro', 'Tabasco', 18.00100000, -92.94200000),
(5, 'Insurgentes Sur', '2021', NULL, 'Florida', '01030', 'Álvaro Obregón', 'Ciudad de México', 19.34500000, -99.17800000),
(7, '', '', '', '', '', '', '', 17.98687959, -92.92666292),
(8, 'Ignacio Rámirez', '200', '200', 'Centro', '86220', 'Nacajuca', 'Tabasco', 18.17799248, -93.01589197);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `propietario_id` int(11) NOT NULL COMMENT 'El ID del usuario (de la tabla usuarios) que subió el archivo.',
  `tipo_documento_id` int(11) NOT NULL COMMENT 'Enlaza a la tabla tipos_documento para saber qué es.',
  `ruta_archivo` varchar(255) NOT NULL COMMENT 'La ruta donde se guardó el archivo en el servidor.',
  `nombre_original` varchar(255) DEFAULT NULL COMMENT 'El nombre original del archivo que tenía el usuario.',
  `tipo_mime` varchar(100) DEFAULT NULL COMMENT 'El tipo MIME del archivo, ej: image/jpeg, application/pdf.',
  `fecha_carga` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `propietario_id`, `tipo_documento_id`, `ruta_archivo`, `nombre_original`, `tipo_mime`, `fecha_carga`) VALUES
(1, 11, 1, 'uploads/profile_pictures/user_11_687013c301075.jpg', 'testimonial-1.jpg', 'image/jpeg', '2025-07-10 19:25:55'),
(3, 1, 1, 'uploads/profile_pictures/user_1_68701a59105ed.jpg', 'testimonial-2.jpg', 'image/jpeg', '2025-07-10 19:54:01'),
(4, 1, 8, 'uploads/documents_validation/doc_1_68701ed688ffb.pdf', 'acta_constitutiva.pdf', 'application/pdf', '2025-07-10 20:13:10'),
(5, 13, 1, 'uploads/profile_pictures/user_13_687053a8e8395.jpg', 'team-4.jpg', 'image/jpeg', '2025-07-10 23:58:32'),
(6, 15, 1, 'uploads/profile_pictures/user_15_6871791bc1670.jpg', 'monicaicon.jpg', 'image/jpeg', '2025-07-11 20:50:35'),
(7, 18, 1, 'uploads/profile_pictures/user_18_6871aa5e0a8a1.jpg', 'user_1_68701a59105ed.jpg', 'image/jpeg', '2025-07-12 00:20:46'),
(8, 18, 2, 'uploads/logos/logo_18_6871b60631c23.png', 'unnamed.png', NULL, '2025-07-12 01:10:30'),
(10, 29, 1, 'uploads/profile_pictures/user_29_6873e2d1e7a3c.png', 'Untitled422_20240810000735.png', 'image/png', '2025-07-13 16:46:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `id` int(11) NOT NULL,
  `aviso_id` int(11) NOT NULL,
  `donante_id` int(11) NOT NULL,
  `estatus_id` int(11) NOT NULL,
  `fecha_compromiso` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_validacion` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donaciones`
--

INSERT INTO `donaciones` (`id`, `aviso_id`, `donante_id`, `estatus_id`, `fecha_compromiso`, `fecha_validacion`) VALUES
(1, 1, 1, 2, '2025-07-10 16:00:00', NULL),
(2, 1, 1, 3, '2025-07-11 17:30:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donantes_beneficios`
--

CREATE TABLE `donantes_beneficios` (
  `id` int(11) NOT NULL,
  `donacion_id` int(11) NOT NULL COMMENT 'La donación que generó este beneficio',
  `usuario_id` int(11) NOT NULL COMMENT 'El donante que recibe el beneficio',
  `apoyo_id` int(11) NOT NULL COMMENT 'El apoyo (canjeable) que se ganó',
  `codigo_canje` varchar(45) DEFAULT NULL COMMENT 'Se genera solo cuando el usuario lo reclama',
  `estado` enum('Disponible','Canjeado','Expirado') NOT NULL DEFAULT 'Disponible',
  `fecha_otorgado` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_canje` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donantes_beneficios`
--

INSERT INTO `donantes_beneficios` (`id`, `donacion_id`, `usuario_id`, `apoyo_id`, `codigo_canje`, `estado`, `fecha_otorgado`, `fecha_canje`) VALUES
(1, 2, 1, 1, 'CANJE-A1B2-C3D4', 'Canjeado', '2025-07-11 18:00:00', '2025-07-13 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas_apoyos`
--

CREATE TABLE `empresas_apoyos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `tipo_apoyo_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_expiracion` date DEFAULT NULL COMMENT 'Aplica solo si el apoyo tiene vigencia',
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas_apoyos`
--

INSERT INTO `empresas_apoyos` (`id`, `empresa_id`, `tipo_apoyo_id`, `titulo`, `descripcion`, `fecha_expiracion`, `activo`) VALUES
(1, 2, 1, '15% de descuento en medicamentos', 'Presenta tu código de canje y obtén un 15% de descuento en la compra de medicamentos de cuadro básico.', '2025-12-31', 1),
(2, 2, 4, 'Consulta médica general gratuita', 'Canjea tu beneficio por una consulta médica general en nuestra sucursal.', '2025-12-31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas_perfil`
--

CREATE TABLE `empresas_perfil` (
  `id` int(11) NOT NULL,
  `nombre_comercial` varchar(255) DEFAULT NULL,
  `razon_social` varchar(255) NOT NULL,
  `rfc` varchar(13) NOT NULL,
  `telefono_empresa` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `direccion_comercial_id` int(11) DEFAULT NULL,
  `representante_id` int(11) DEFAULT NULL,
  `logo_documento_id` int(11) DEFAULT NULL COMMENT 'FK a la tabla de documentos para el logo oficial',
  `empresa_padre_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas_perfil`
--

INSERT INTO `empresas_perfil` (`id`, `nombre_comercial`, `razon_social`, `rfc`, `telefono_empresa`, `descripcion`, `direccion_comercial_id`, `representante_id`, `logo_documento_id`, `empresa_padre_id`, `fecha_creacion`) VALUES
(2, 'Farmacias del Ahorro', 'Farmacias del Ahorro SA de CV', 'FAH850101XXX', '9933109876', 'Farmacia líder en el sureste.', 2, 10, 8, NULL, '2025-07-12 00:08:32'),
(3, '2', '2', '2', NULL, NULL, NULL, 12, NULL, NULL, '2025-07-12 01:13:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enfermedades`
--

CREATE TABLE `enfermedades` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enfermedades`
--

INSERT INTO `enfermedades` (`id`, `nombre`, `categoria`) VALUES
(5, 'Hipertensión Arterial', 'Enfermedades Cardiovasculares'),
(6, 'Enfermedad de las Arterias Coronarias', 'Enfermedades Cardiovasculares'),
(7, 'Insuficiencia Cardíaca', 'Enfermedades Cardiovasculares'),
(8, 'Fibrilación Auricular', 'Enfermedades Cardiovasculares'),
(9, 'Accidente Cerebrovascular', 'Enfermedades Cardiovasculares'),
(10, 'Diabetes Mellitus Tipo 1', 'Enfermedades Metabólicas'),
(11, 'Diabetes Mellitus Tipo 2', 'Enfermedades Metabólicas'),
(12, 'Obesidad', 'Enfermedades Metabólicas'),
(13, 'Dislipidemia (Colesterol Alto)', 'Enfermedades Metabólicas'),
(14, 'Asma', 'Enfermedades Respiratorias Crónicas'),
(15, 'Enfermedad Pulmonar Obstructiva Crónica (EPOC)', 'Enfermedades Respiratorias Crónicas'),
(16, 'Artritis', 'Condiciones Musculoesqueléticas'),
(17, 'Depresión', 'Condiciones de Salud Mental'),
(18, 'Trastornos de Ansiedad', 'Condiciones de Salud Mental'),
(19, 'Cáncer (General)', 'Otras Condiciones Prevalentes'),
(20, 'Enfermedad Renal Crónica (ERC)', 'Otras Condiciones Prevalentes'),
(21, 'Enfermedad Hepática Crónica / Cirrosis', 'Otras Condiciones Prevalentes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus_aviso`
--

CREATE TABLE `estatus_aviso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estatus_aviso`
--

INSERT INTO `estatus_aviso` (`id`, `nombre`) VALUES
(1, 'Pendiente de Validar'),
(2, 'Activo'),
(3, 'Completado'),
(4, 'Rechazado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus_donacion`
--

CREATE TABLE `estatus_donacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estatus_donacion`
--

INSERT INTO `estatus_donacion` (`id`, `nombre`) VALUES
(1, 'Pendiente de Aprobación'),
(2, 'Aprobado'),
(3, 'Recibido'),
(4, 'No Concretado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizaciones_perfil`
--

CREATE TABLE `organizaciones_perfil` (
  `id` int(11) NOT NULL,
  `nombre_organizacion` varchar(255) NOT NULL,
  `cluni` varchar(45) DEFAULT NULL,
  `representante_id` int(11) DEFAULT NULL,
  `logo_documento_id` int(11) DEFAULT NULL COMMENT 'FK a la tabla de documentos para el logo oficial',
  `direccion_id` int(11) DEFAULT NULL,
  `mision` text DEFAULT NULL,
  `organizacion_padre_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `organizaciones_perfil`
--

INSERT INTO `organizaciones_perfil` (`id`, `nombre_organizacion`, `cluni`, `representante_id`, `logo_documento_id`, `direccion_id`, `mision`, `organizacion_padre_id`, `fecha_creacion`) VALUES
(3, 'Cruz Verde Tabasco', 'CVT123456789', NULL, NULL, 3, 'Proveer ayuda médica y humanitaria a la comunidad de Tabasco.', NULL, '2025-07-12 00:08:32'),
(13, 'org1', 'org1', NULL, NULL, NULL, NULL, NULL, '2025-07-12 00:08:32'),
(14, '1234', '1234', 23, NULL, NULL, NULL, NULL, '2025-07-12 01:22:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_perfil`
--

CREATE TABLE `personas_perfil` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('Masculino','Femenino') DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion_id` int(11) DEFAULT NULL,
  `tipo_sangre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas_perfil`
--

INSERT INTO `personas_perfil` (`usuario_id`, `nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `sexo`, `telefono`, `direccion_id`, `tipo_sangre_id`) VALUES
(1, 'Juan', 'Perez', 'Perez', '1990-05-15', 'Masculino', '99355511222', 1, 7),
(10, 'repre1', 'repre1', 'repre', NULL, NULL, NULL, NULL, NULL),
(11, 'nom1', 'ap1', 'am1', NULL, NULL, NULL, NULL, NULL),
(12, 'repre2', 'repre2', 'repre2', NULL, NULL, NULL, NULL, NULL),
(13, 'Paula', 'Hernandez', 'Rodriguez', '2000-02-20', 'Femenino', NULL, 7, 3),
(14, 'Persona1', 'Apellido1', 'Apellido1', NULL, NULL, NULL, NULL, NULL),
(15, 'Mónica Guadalupe', 'Camacho', 'García', NULL, NULL, NULL, NULL, NULL),
(18, 'Juan', 'Perez', 'Perez', NULL, NULL, NULL, NULL, NULL),
(20, '1', '1', '1', NULL, NULL, NULL, NULL, NULL),
(21, '2', '2', '2', NULL, NULL, NULL, NULL, NULL),
(28, '32', '32', '22', NULL, NULL, NULL, NULL, NULL),
(29, 'Jesus Gabriel', 'De la cruz', 'Zárate', '2002-08-04', 'Masculino', '9222835441', 8, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_x_alergias`
--

CREATE TABLE `personas_x_alergias` (
  `usuario_id` int(11) NOT NULL,
  `alergia_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas_x_enfermedades`
--

CREATE TABLE `personas_x_enfermedades` (
  `usuario_id` int(11) NOT NULL,
  `enfermedad_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos_donacion`
--

CREATE TABLE `puntos_donacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion_id` int(11) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `horario` varchar(255) DEFAULT NULL,
  `organizacion_responsable_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `puntos_donacion`
--

INSERT INTO `puntos_donacion` (`id`, `nombre`, `direccion_id`, `tipo_id`, `telefono`, `horario`, `organizacion_responsable_id`) VALUES
(1, 'Hospital Rovirosa - Banco de Sangre', 4, 1, '9933157948', 'L-V 8:00-14:00', 3),
(2, 'Centro de Acopio Cruz Verde', 3, 2, '9937654321', 'L-S 9:00-17:00', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id`, `nombre`, `apellido_paterno`, `apellido_materno`, `email`, `telefono`) VALUES
(1, 'Laura', '', NULL, 'laura.garcia@empresa-ejemplo.com', '9931234567'),
(2, 'Carlos', '', NULL, 'carlos.h@cruzverde.org', '9937654321'),
(5, 'empresa1', 'empresa1', 'empresa1', 'empresa1@gmail.com', '1111111111'),
(6, 'empre2', 'empre2', 'empre2', 'empre2@gmail.com', 'eeeeee'),
(7, 'org1', 'org1', 'org1', 'org1@gmail.com', 'org1'),
(8, 'Juan', 'Perez', 'Perez', 'farmacia@empresa.com', '9933590931'),
(10, 'Juan', 'Perez', 'Perez', 'farmacia@farmacia.com', '9933590931'),
(12, '2', '2', '2', '2@empresa.com', '2'),
(23, '3', '3', '3', 'mihoyo@organizacion.com', '3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Creador'),
(3, 'Visualizador'),
(4, 'Gestor de Casos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_dispositivos`
--

CREATE TABLE `solicitudes_dispositivos` (
  `id` int(11) NOT NULL,
  `aviso_id` int(11) NOT NULL,
  `nombre_dispositivo` varchar(255) NOT NULL,
  `especificaciones` text DEFAULT NULL,
  `cantidad_requerida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_dispositivos`
--

INSERT INTO `solicitudes_dispositivos` (`id`, `aviso_id`, `nombre_dispositivo`, `especificaciones`, `cantidad_requerida`) VALUES
(1, 3, 'Silla de Ruedas Estándar para Adulto', 'Plegable, con reposapiés desmontables.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_medicamentos`
--

CREATE TABLE `solicitudes_medicamentos` (
  `id` int(11) NOT NULL,
  `aviso_id` int(11) NOT NULL,
  `nombre_medicamento` varchar(255) NOT NULL,
  `dosis` varchar(100) DEFAULT NULL,
  `presentacion` varchar(100) DEFAULT NULL COMMENT 'Ej: Caja con 20 tabletas, Jarabe 100ml',
  `cantidad_requerida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_medicamentos`
--

INSERT INTO `solicitudes_medicamentos` (`id`, `aviso_id`, `nombre_medicamento`, `dosis`, `presentacion`, `cantidad_requerida`) VALUES
(1, 2, 'Insulina Glargina (Lantus)', '10 UI/día', 'Solución inyectable 100 UI/ml', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_sangre`
--

CREATE TABLE `solicitudes_sangre` (
  `aviso_id` int(11) NOT NULL,
  `tipo_sangre_id` int(11) NOT NULL,
  `unidades_requeridas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes_sangre`
--

INSERT INTO `solicitudes_sangre` (`aviso_id`, `tipo_sangre_id`, `unidades_requeridas`) VALUES
(1, 7, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_apoyo`
--

CREATE TABLE `tipos_apoyo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Ej: Beneficios y Descuentos, Difusión, Apoyo Logístico',
  `es_canjeable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'TRUE si genera un cupón para el donante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_apoyo`
--

INSERT INTO `tipos_apoyo` (`id`, `nombre`, `es_canjeable`) VALUES
(1, 'Descuentos y Beneficios', 1),
(2, 'Difusión y Comunicación', 0),
(3, 'Apoyo Logístico', 0),
(4, 'Servicios Gratuitos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_documento`
--

CREATE TABLE `tipos_documento` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL COMMENT 'Un código único, ej: FOTO_PERFIL, DOC_VALIDACION_EMP',
  `descripcion` varchar(255) NOT NULL COMMENT 'Descripción amigable, ej: Foto de Perfil de Usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_documento`
--

INSERT INTO `tipos_documento` (`id`, `codigo`, `descripcion`) VALUES
(1, 'FOTO_PERFIL_PERSONA', 'Foto de perfil para un usuario persona'),
(2, 'FOTO_LOGO_EMPRESA', 'Logo para una empresa'),
(3, 'FOTO_LOGO_ORGANIZACION', 'Logo para una organización'),
(4, 'DOC_VALIDACION_EMP', 'Documento de validación para una empresa (Acta Constitutiva, etc.)'),
(5, 'DOC_VALIDACION_ORG', 'Documento de validación para una organización (CLUNI, etc.)'),
(6, 'DOC_RECETA_MEDICA', 'Receta médica para una solicitud de aviso'),
(7, 'DOC_IDENTIFICACION', 'Identificación oficial para una solicitud de aviso'),
(8, 'DOC_INE_PERFIL', 'Credencial del Lector (INE) del perfil de usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_punto_donacion`
--

CREATE TABLE `tipos_punto_donacion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `icono` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_punto_donacion`
--

INSERT INTO `tipos_punto_donacion` (`id`, `nombre`, `icono`) VALUES
(1, 'Banco de Sangre', 'sangre.png'),
(2, 'Centro de Acopio de Medicamentos', 'medicamentos.png'),
(3, 'Centro de Acopio de Dispositivos', 'dispositivos.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_sangre`
--

CREATE TABLE `tipos_sangre` (
  `id` int(11) NOT NULL,
  `tipo` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_sangre`
--

INSERT INTO `tipos_sangre` (`id`, `tipo`) VALUES
(1, 'A+'),
(2, 'A-'),
(3, 'B+'),
(4, 'B-'),
(5, 'AB+'),
(6, 'AB-'),
(7, 'O+'),
(8, 'O-');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuario`
--

CREATE TABLE `tipos_usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_usuario`
--

INSERT INTO `tipos_usuario` (`id`, `nombre`) VALUES
(1, 'Persona'),
(2, 'Empresa'),
(3, 'Organizacion'),
(4, 'SuperAdmin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `urgencia_niveles`
--

CREATE TABLE `urgencia_niveles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `color_hex` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `urgencia_niveles`
--

INSERT INTO `urgencia_niveles` (`id`, `nombre`, `color_hex`) VALUES
(1, 'Baja', '#28a745'),
(2, 'Media', '#ffc107'),
(3, 'Alta', '#fd7e14'),
(4, 'Crítica', '#dc3545');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `tipo_usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Activo','Inactivo','Pendiente') NOT NULL DEFAULT 'Pendiente',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `password_hash`, `tipo_usuario_id`, `rol_id`, `fecha_registro`, `estado`, `reset_token`, `reset_token_expires_at`) VALUES
(1, 'eliminar@email.com', '$2y$10$z.Cpg.vK3aLOOHrBYoOkTOOKwvPRnxf6uN2FVGf.5W3FKGwU1fysq', 1, 3, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(2, 'contacto@empresa-ejemplo.com', '$2y$10$1/4K2/TYgt7ywmvfW4ugGO.gvv6Pb.1ksuBmMAcfa/UZUk9MWLSV.', 2, 2, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(3, 'info@cruzverde.org', '$2y$10$v264.DEa4RfP2vIW7jVqMO0u79/7Gnpr1F40soUk2ivY2wIqZlf0y', 3, 2, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(4, 'superadmin@dosys.com', '$2y$10$/aqUzlB/BUVFsXvKZZuzq.LL.BUlFDYtexgMjkQtloMvw0AM2MpbC', 4, 1, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(5, 'ana.gomez@cruzverde.org', '$2y$10$Eq6Ra5IROGo.mQOEgv6uAOmq6AZpwCPxm.gxNI1JQnil/nA866lBW', 3, 4, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(10, 'repre1@gmail.com', '$2y$10$IwXn8sQGCH1SnYIZOR3VV.bs8hprfZ9aEd8hlP4m5MRnBFmcW2Z56', 2, 2, '2025-07-10 01:46:11', 'Pendiente', NULL, NULL),
(11, 'persona1@gmail.com', '$2y$10$CP1FFJJEai1tPAKdh4OQGOnYtuzAvqHaz9uIzknmwWEDYiNQbfsBK', 1, 3, '2025-07-10 01:50:21', 'Pendiente', NULL, NULL),
(12, 'repre2@gmail.com', '$2y$10$0CqBcf7/zlKi174E0uM/ROO..L/T/ROgcmnUQXNRDfQqoEMKy9roO', 2, 2, '2025-07-10 01:52:00', 'Pendiente', NULL, NULL),
(13, 'org1@gmail.com', '$2y$10$pHZBbvl/47l22dEfDDi5.O1fVyDv9fRPgpqWUJu3hPikFz5a3xbU.', 3, 2, '2025-07-10 01:58:04', 'Pendiente', NULL, NULL),
(14, 'persona@gmail.com', '$2y$10$au4Ee6JUqdGuYeD4zTd6ceX97Jwyke70SDQlDiebMrzV58D6H9kIm', 1, 3, '2025-07-11 17:07:00', 'Pendiente', NULL, NULL),
(15, '0SerenaTsukino@gmail.com', '$2y$10$rHG9JpMGRtKIiLrgTYPRTOgm4NZmpSDBtz8FlFgdbONCn22mThv9q', 1, 3, '2025-07-11 20:50:12', 'Pendiente', NULL, NULL),
(18, 'juan.perez@email.com', '$2y$10$./ouqUHnyzRldz9bCpNDm.mhzdaU01XegYrdtztwgBnGj56F45Ida', 2, 2, '2025-07-11 21:35:10', 'Pendiente', NULL, NULL),
(20, '1@persona.com', '$2y$10$OTmZVcE07K7MwOSXkx2hAu.OsMhg9z8akBTaDM/UDNc76I0ecF6ZG', 1, 3, '2025-07-12 01:12:48', 'Pendiente', NULL, NULL),
(21, '2@empresa.com', '$2y$10$k2igfSaoDaWpvVJ/2dCAmOlq6HNGHuUEo8zK4FHrenWE/H5KJu1by', 2, 2, '2025-07-12 01:13:30', 'Pendiente', NULL, NULL),
(28, 'mihoyo@organizacion.com', '$2y$10$j8dcwAKBwNAuH6IE9QTf9eU6xXIlmFjRQ3F3n1buBf72VlOzSPtT2', 3, 2, '2025-07-12 01:22:47', 'Pendiente', NULL, NULL),
(29, 'realloyal1a@gmail.com', '$2y$10$t1RiGqp.sYkedLGSE3okL.Eyf/jUJ.vMC8hO//K/5ktuyeSw39t9a', 1, 3, '2025-07-13 16:45:31', 'Pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_x_empresas`
--

CREATE TABLE `usuarios_x_empresas` (
  `usuario_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_x_empresas`
--

INSERT INTO `usuarios_x_empresas` (`usuario_id`, `empresa_id`, `fecha_asignacion`) VALUES
(18, 2, '2025-07-12 00:08:32'),
(21, 3, '2025-07-12 01:13:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_x_organizaciones`
--

CREATE TABLE `usuarios_x_organizaciones` (
  `usuario_id` int(11) NOT NULL,
  `organizacion_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_x_organizaciones`
--

INSERT INTO `usuarios_x_organizaciones` (`usuario_id`, `organizacion_id`, `fecha_asignacion`) VALUES
(3, 3, '2025-07-12 00:08:32'),
(13, 13, '2025-07-12 00:08:32'),
(28, 14, '2025-07-12 01:22:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alergias`
--
ALTER TABLE `alergias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avisos_usuarios_creador` (`creador_id`),
  ADD KEY `fk_avisos_usuarios_organizacion` (`organizacion_id`),
  ADD KEY `fk_avisos_beneficiarios` (`beneficiario_id`),
  ADD KEY `fk_avisos_categorias_donacion` (`categoria_id`),
  ADD KEY `fk_avisos_urgencia_niveles` (`urgencia_id`),
  ADD KEY `fk_avisos_estatus_aviso` (`estatus_id`),
  ADD KEY `fk_avisos_usuarios_responsable` (`contacto_responsable_id`);

--
-- Indices de la tabla `avisos_documentos`
--
ALTER TABLE `avisos_documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avisos_documentos_avisos` (`aviso_id`);

--
-- Indices de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias_donacion`
--
ALTER TABLE `categorias_donacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documentos_usuarios` (`propietario_id`),
  ADD KEY `fk_documentos_tipos` (`tipo_documento_id`);

--
-- Indices de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_donaciones_avisos` (`aviso_id`),
  ADD KEY `fk_donaciones_usuarios` (`donante_id`),
  ADD KEY `fk_donaciones_estatus_donacion` (`estatus_id`);

--
-- Indices de la tabla `donantes_beneficios`
--
ALTER TABLE `donantes_beneficios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_canje_UNIQUE` (`codigo_canje`),
  ADD KEY `fk_db_donaciones` (`donacion_id`),
  ADD KEY `fk_db_usuarios` (`usuario_id`),
  ADD KEY `fk_db_empresas_apoyos` (`apoyo_id`);

--
-- Indices de la tabla `empresas_apoyos`
--
ALTER TABLE `empresas_apoyos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ea_usuarios` (`empresa_id`),
  ADD KEY `fk_ea_tipos_apoyo` (`tipo_apoyo_id`);

--
-- Indices de la tabla `empresas_perfil`
--
ALTER TABLE `empresas_perfil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfc_UNIQUE` (`rfc`),
  ADD KEY `fk_ep_dir_comercial` (`direccion_comercial_id`),
  ADD KEY `fk_ep_logo` (`logo_documento_id`),
  ADD KEY `fk_ep_padre` (`empresa_padre_id`),
  ADD KEY `fk_ep_representante` (`representante_id`);

--
-- Indices de la tabla `enfermedades`
--
ALTER TABLE `enfermedades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `estatus_aviso`
--
ALTER TABLE `estatus_aviso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estatus_donacion`
--
ALTER TABLE `estatus_donacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `organizaciones_perfil`
--
ALTER TABLE `organizaciones_perfil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cluni_UNIQUE` (`cluni`),
  ADD KEY `fk_op_direccion` (`direccion_id`),
  ADD KEY `fk_op_logo` (`logo_documento_id`),
  ADD KEY `fk_op_padre` (`organizacion_padre_id`),
  ADD KEY `fk_op_representante` (`representante_id`);

--
-- Indices de la tabla `personas_perfil`
--
ALTER TABLE `personas_perfil`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_pp_tipos_sangre` (`tipo_sangre_id`),
  ADD KEY `fk_pp_direcciones` (`direccion_id`);

--
-- Indices de la tabla `personas_x_alergias`
--
ALTER TABLE `personas_x_alergias`
  ADD PRIMARY KEY (`usuario_id`,`alergia_id`),
  ADD KEY `fk_pxa_alergias` (`alergia_id`);

--
-- Indices de la tabla `personas_x_enfermedades`
--
ALTER TABLE `personas_x_enfermedades`
  ADD PRIMARY KEY (`usuario_id`,`enfermedad_id`),
  ADD KEY `fk_pxe_enfermedades` (`enfermedad_id`);

--
-- Indices de la tabla `puntos_donacion`
--
ALTER TABLE `puntos_donacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pd_tipos` (`tipo_id`),
  ADD KEY `fk_pd_usuarios` (`organizacion_responsable_id`),
  ADD KEY `fk_pd_direcciones` (`direccion_id`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `solicitudes_dispositivos`
--
ALTER TABLE `solicitudes_dispositivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sd_avisos` (`aviso_id`);

--
-- Indices de la tabla `solicitudes_medicamentos`
--
ALTER TABLE `solicitudes_medicamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sm_avisos` (`aviso_id`);

--
-- Indices de la tabla `solicitudes_sangre`
--
ALTER TABLE `solicitudes_sangre`
  ADD PRIMARY KEY (`aviso_id`),
  ADD KEY `fk_ss_tipos_sangre` (`tipo_sangre_id`);

--
-- Indices de la tabla `tipos_apoyo`
--
ALTER TABLE `tipos_apoyo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_UNIQUE` (`codigo`);

--
-- Indices de la tabla `tipos_punto_donacion`
--
ALTER TABLE `tipos_punto_donacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_sangre`
--
ALTER TABLE `tipos_sangre`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_usuario`
--
ALTER TABLE `tipos_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `urgencia_niveles`
--
ALTER TABLE `urgencia_niveles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `fk_u_tipos_usuario` (`tipo_usuario_id`),
  ADD KEY `fk_u_roles` (`rol_id`);

--
-- Indices de la tabla `usuarios_x_empresas`
--
ALTER TABLE `usuarios_x_empresas`
  ADD PRIMARY KEY (`usuario_id`,`empresa_id`),
  ADD KEY `fk_uxe_empresa` (`empresa_id`);

--
-- Indices de la tabla `usuarios_x_organizaciones`
--
ALTER TABLE `usuarios_x_organizaciones`
  ADD PRIMARY KEY (`usuario_id`,`organizacion_id`),
  ADD KEY `fk_uxo_organizacion` (`organizacion_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alergias`
--
ALTER TABLE `alergias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `avisos_documentos`
--
ALTER TABLE `avisos_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `beneficiarios`
--
ALTER TABLE `beneficiarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias_donacion`
--
ALTER TABLE `categorias_donacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `donantes_beneficios`
--
ALTER TABLE `donantes_beneficios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `empresas_apoyos`
--
ALTER TABLE `empresas_apoyos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empresas_perfil`
--
ALTER TABLE `empresas_perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `enfermedades`
--
ALTER TABLE `enfermedades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `estatus_aviso`
--
ALTER TABLE `estatus_aviso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estatus_donacion`
--
ALTER TABLE `estatus_donacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `organizaciones_perfil`
--
ALTER TABLE `organizaciones_perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `puntos_donacion`
--
ALTER TABLE `puntos_donacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitudes_dispositivos`
--
ALTER TABLE `solicitudes_dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `solicitudes_medicamentos`
--
ALTER TABLE `solicitudes_medicamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tipos_apoyo`
--
ALTER TABLE `tipos_apoyo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipos_punto_donacion`
--
ALTER TABLE `tipos_punto_donacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipos_sangre`
--
ALTER TABLE `tipos_sangre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tipos_usuario`
--
ALTER TABLE `tipos_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `urgencia_niveles`
--
ALTER TABLE `urgencia_niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD CONSTRAINT `fk_avisos_beneficiarios` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiarios` (`id`),
  ADD CONSTRAINT `fk_avisos_categorias_donacion` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_donacion` (`id`),
  ADD CONSTRAINT `fk_avisos_estatus_aviso` FOREIGN KEY (`estatus_id`) REFERENCES `estatus_aviso` (`id`),
  ADD CONSTRAINT `fk_avisos_organizaciones_perfil` FOREIGN KEY (`organizacion_id`) REFERENCES `organizaciones_perfil` (`id`),
  ADD CONSTRAINT `fk_avisos_urgencia_niveles` FOREIGN KEY (`urgencia_id`) REFERENCES `urgencia_niveles` (`id`),
  ADD CONSTRAINT `fk_avisos_usuarios_creador` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_avisos_usuarios_responsable` FOREIGN KEY (`contacto_responsable_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `avisos_documentos`
--
ALTER TABLE `avisos_documentos`
  ADD CONSTRAINT `fk_ad_avisos` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `fk_doc_tipos` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipos_documento` (`id`),
  ADD CONSTRAINT `fk_doc_usuarios` FOREIGN KEY (`propietario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `donaciones`
--
ALTER TABLE `donaciones`
  ADD CONSTRAINT `fk_don_avisos` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`),
  ADD CONSTRAINT `fk_don_estatus` FOREIGN KEY (`estatus_id`) REFERENCES `estatus_donacion` (`id`),
  ADD CONSTRAINT `fk_don_usuarios` FOREIGN KEY (`donante_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `donantes_beneficios`
--
ALTER TABLE `donantes_beneficios`
  ADD CONSTRAINT `fk_db_donaciones_new` FOREIGN KEY (`donacion_id`) REFERENCES `donaciones` (`id`),
  ADD CONSTRAINT `fk_db_empresas_apoyos_new` FOREIGN KEY (`apoyo_id`) REFERENCES `empresas_apoyos` (`id`),
  ADD CONSTRAINT `fk_db_usuarios_new` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `empresas_apoyos`
--
ALTER TABLE `empresas_apoyos`
  ADD CONSTRAINT `fk_ea_empresas_perfil_new` FOREIGN KEY (`empresa_id`) REFERENCES `empresas_perfil` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ea_tipos_apoyo_new` FOREIGN KEY (`tipo_apoyo_id`) REFERENCES `tipos_apoyo` (`id`);

--
-- Filtros para la tabla `empresas_perfil`
--
ALTER TABLE `empresas_perfil`
  ADD CONSTRAINT `fk_ep_dir_comercial_new` FOREIGN KEY (`direccion_comercial_id`) REFERENCES `direcciones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ep_logo_new` FOREIGN KEY (`logo_documento_id`) REFERENCES `documentos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ep_padre_new` FOREIGN KEY (`empresa_padre_id`) REFERENCES `empresas_perfil` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ep_representante` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `organizaciones_perfil`
--
ALTER TABLE `organizaciones_perfil`
  ADD CONSTRAINT `fk_op_direccion_new` FOREIGN KEY (`direccion_id`) REFERENCES `direcciones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_op_logo_new` FOREIGN KEY (`logo_documento_id`) REFERENCES `documentos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_op_padre_new` FOREIGN KEY (`organizacion_padre_id`) REFERENCES `organizaciones_perfil` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_op_representante` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `personas_perfil`
--
ALTER TABLE `personas_perfil`
  ADD CONSTRAINT `fk_pp_direcciones_new` FOREIGN KEY (`direccion_id`) REFERENCES `direcciones` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pp_tipos_sangre_new` FOREIGN KEY (`tipo_sangre_id`) REFERENCES `tipos_sangre` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pp_usuarios_new` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `personas_x_alergias`
--
ALTER TABLE `personas_x_alergias`
  ADD CONSTRAINT `fk_pxa_alergias_new` FOREIGN KEY (`alergia_id`) REFERENCES `alergias` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pxa_personas_new` FOREIGN KEY (`usuario_id`) REFERENCES `personas_perfil` (`usuario_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `personas_x_enfermedades`
--
ALTER TABLE `personas_x_enfermedades`
  ADD CONSTRAINT `fk_pxe_enfermedades_new` FOREIGN KEY (`enfermedad_id`) REFERENCES `enfermedades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pxe_personas_new` FOREIGN KEY (`usuario_id`) REFERENCES `personas_perfil` (`usuario_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `puntos_donacion`
--
ALTER TABLE `puntos_donacion`
  ADD CONSTRAINT `fk_pd_direcciones_new` FOREIGN KEY (`direccion_id`) REFERENCES `direcciones` (`id`),
  ADD CONSTRAINT `fk_pd_organizaciones_new` FOREIGN KEY (`organizacion_responsable_id`) REFERENCES `organizaciones_perfil` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_pd_tipos_new` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_punto_donacion` (`id`);

--
-- Filtros para la tabla `solicitudes_dispositivos`
--
ALTER TABLE `solicitudes_dispositivos`
  ADD CONSTRAINT `fk_sd_avisos_new` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes_medicamentos`
--
ALTER TABLE `solicitudes_medicamentos`
  ADD CONSTRAINT `fk_sm_avisos_new` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `solicitudes_sangre`
--
ALTER TABLE `solicitudes_sangre`
  ADD CONSTRAINT `fk_ss_avisos_new` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ss_tipos_sangre_new` FOREIGN KEY (`tipo_sangre_id`) REFERENCES `tipos_sangre` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_u_roles_new` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `fk_u_tipos_usuario_new` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipos_usuario` (`id`);

--
-- Filtros para la tabla `usuarios_x_empresas`
--
ALTER TABLE `usuarios_x_empresas`
  ADD CONSTRAINT `fk_uxe_empresa_new` FOREIGN KEY (`empresa_id`) REFERENCES `empresas_perfil` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uxe_usuario_new` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios_x_organizaciones`
--
ALTER TABLE `usuarios_x_organizaciones`
  ADD CONSTRAINT `fk_uxo_organizacion_new` FOREIGN KEY (`organizacion_id`) REFERENCES `organizaciones_perfil` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uxo_usuario_new` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

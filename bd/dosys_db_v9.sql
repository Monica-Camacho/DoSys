-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-07-2025 a las 04:12:26
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
-- Base de datos: `dosys_db_v7`
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
  `donatario_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `urgencia_id` int(11) NOT NULL,
  `estatus_id` int(11) NOT NULL,
  `contacto_responsable_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avisos`
--

INSERT INTO `avisos` (`id`, `titulo`, `descripcion`, `creador_id`, `organizacion_id`, `donatario_id`, `categoria_id`, `urgencia_id`, `estatus_id`, `contacto_responsable_id`, `fecha_creacion`, `fecha_cierre`) VALUES
(1, 'Urgente: Donación de Sangre O+', 'Se necesita con urgencia donadores de sangre tipo O+ para paciente en cirugía en Hospital General.', 1, 13, 1, 1, 1, 1, 5, '2025-07-09 20:24:02', NULL),
(2, 'Apoyo con Medicamento: Insulina', 'Solicitamos insulina de acción rápida para paciente con diabetes tipo 1 que no cuenta con recursos.', 1, 13, 2, 2, 1, 2, 5, '2025-07-09 20:24:02', NULL),
(3, 'Solicitud de Muletas Adulto Mayor', 'Un adulto mayor requiere muletas para su movilidad. Cualquier apoyo es bienvenido.', 1, 13, 15, 3, 1, 2, 5, '2025-07-09 20:24:02', NULL),
(8, 'Donación de Sangre AB- para transfusión', 'Paciente pediátrico necesita transfusión con sangre tipo AB-. Se requiere antes de 48 horas.', 1, 13, 1, 1, 1, 4, 3, '2025-07-19 02:22:47', NULL),
(9, 'Medicamento para hipertensión', 'Solicitamos Amlodipino 5mg para paciente que no puede adquirir su tratamiento.', 12, 13, 16, 2, 1, 4, 18, '2025-07-20 03:37:39', NULL),
(12, 'Apoyo: Silla de ruedas en buen estado', 'Persona con discapacidad motriz necesita una silla de ruedas para su movilidad diaria.', 10, 13, 17, 3, 3, 2, 18, '2025-07-20 03:56:38', NULL),
(14, 'Se requiere donación de Sangre B+', 'Hospital IMSS solicita urgentemente unidades de sangre B+ para transfusión postoperatoria.', 10, 23, 20, 1, 2, 2, 32, '2025-07-20 04:09:35', NULL),
(17, 'Medicamento anticonvulsivo: Carbamazepina', 'Niño con epilepsia requiere suministro de carbamazepina.', 11, 15, 21, 2, 4, 2, 32, '2025-07-20 16:13:26', NULL),
(18, 'Entrega de Andadera para rehabilitación', 'Paciente en recuperación solicita andadera como apoyo temporal.', 12, 15, 20, 3, 1, 1, 32, '2025-07-20 16:59:22', NULL),
(19, 'Donación de Sangre A-', 'Se busca sangre tipo A- para paciente hospitalizado tras accidente vial.', 13, 24, 22, 1, 1, 1, 32, '2025-07-20 17:00:47', NULL),
(20, 'Medicamento oncológico: Tamoxifeno', 'Se necesita Tamoxifeno para paciente en tratamiento contra el cáncer de mama.', 14, 15, 1, 2, 1, 1, 32, '2025-07-20 17:03:53', NULL),
(21, 'Prótesis para pierna derecha', 'Persona joven perdió extremidad en accidente; necesita prótesis adaptada a su talla.', 15, 15, 2, 3, 1, 1, 32, '2025-07-20 17:11:48', NULL),
(22, 'Sangre requerida tipo O-', 'Banco de sangre reporta baja en reservas de tipo O-. Donadores urgentes.', 18, 21, 8, 1, 1, 1, 32, '2025-07-20 17:14:17', NULL),
(23, 'Medicamento para artritis reumatoide', 'Solicitamos Metotrexato para paciente que ha suspendido tratamiento por falta de recursos.', 20, 15, 15, 2, 1, 1, 32, '2025-07-20 17:14:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avisos_documentos`
--

CREATE TABLE `avisos_documentos` (
  `aviso_id` int(11) NOT NULL,
  `documento_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avisos_documentos`
--

INSERT INTO `avisos_documentos` (`aviso_id`, `documento_id`) VALUES
(9, 23),
(12, 26),
(14, 28),
(17, 31),
(18, 32),
(19, 33),
(20, 34),
(21, 35),
(22, 36),
(23, 37);

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
(3, 'Dispositivos');

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
(1, 'Avenida Francisco I. Madero', '101', '', 'Centro', '86000', 'Centro', 'Tabasco', 17.99390000, -92.92760000),
(2, 'Calle Ignacio Zaragoza', '520', '', 'Centro', '86000', 'Centro', 'Tabasco', 17.98920000, -92.92480000),
(3, 'Paseo Tabasco', '1401', '', 'Linda Vista', '86050', 'Centro', 'Tabasco', 18.00150000, -92.94960000),
(4, 'Avenida Gregorio Méndez Magaña', '2504', NULL, 'Atasta de Serra', '86100', 'Centro', 'Tabasco', 17.98180000, -92.93400000),
(5, 'Boulevard Adolfo Ruiz Cortines', '1800', NULL, 'Petrolera', '86030', 'Centro', 'Tabasco', 17.99650000, -92.94300000),
(7, 'Calle Miguel Hidalgo y Costilla', '303', '', 'Tamulté de las Barrancas', '86150', 'Centro', 'Tabasco', 17.97120000, -92.92380000),
(8, 'Avenida 27 de Febrero', '950', '200', 'Centro', '86000', 'Centro', 'Tabasco', 17.99110000, -92.92990000),
(9, 'Calle Heroico Colegio Militar', '115', '1', 'Primero de Mayo', '86190', 'Centro', 'Tabasco', 17.98550000, -92.94910000),
(10, 'Paseo Usumacinta', '1504', '2', 'Guayabal', '86090', 'Centro', 'Tabasco', 17.97880000, -92.95010000),
(11, 'Circuito Interior Carlos Pellicer Cámara', '3305', '2', 'Carrizal', '86038', 'Centro', 'Tabasco', 18.01420000, -92.95670000),
(12, 'Calle Andrés García', '201', '', 'Reforma', '86080', 'Centro', 'Tabasco', 17.98990000, -92.94220000),
(13, 'Avenida Universidad', 's/n', '', 'Magisterial', '86040', 'Centro', 'Tabasco', 17.99950000, -92.93510000),
(14, 'Avenida Chapultepec Sur', '284', NULL, 'Americana', '44160', 'Guadalajara', 'Jalisco', 20.66980000, -103.36700000),
(15, 'Avenida del Roble', '660', NULL, 'Valle del Campestre', '66265', 'San Pedro Garza García', 'Nuevo León', 25.65880000, -100.36210000),
(16, 'Paseo de Montejo', '460', NULL, 'Centro', '97000', 'Mérida', 'Yucatán', 20.98510000, -89.62260000),
(17, 'Avenida 5 Poniente', '129', NULL, 'Centro Histórico', '72000', 'Puebla', 'Puebla', 19.04350000, -98.20000000),
(18, 'Avenida 5 de Febrero', 's/n', NULL, 'Zona Industrial', '76120', 'Querétaro', 'Querétaro', 20.61330000, -100.39500000),
(19, 'Avenida Revolución', '828', NULL, 'Zona Centro', '22000', 'Tijuana', 'Baja California', 32.53150000, -117.03710000),
(20, 'Calle de Manuel García Vigil', '512', NULL, 'Centro', '68000', 'Oaxaca de Juárez', 'Oaxaca', 17.06540000, -96.72550000),
(21, 'Boulevard Kukulcán', 'Km 9.5', NULL, 'Zona Hotelera', '77500', 'Benito Juárez', 'Quintana Roo', 21.13280000, -86.77250000),
(22, 'Boulevard Belisario Domínguez', '1861', NULL, 'Fraccionamiento Bugambilias', '29020', 'Tuxtla Gutiérrez', 'Chiapas', 16.75190000, -93.14990000),
(23, 'Avenida Álvaro Obregón', '269', NULL, 'Roma Norte', '06700', 'Cuauhtémoc', 'Ciudad de México', 19.41840000, -99.16270000),
(24, 'Paseo Tollocan', '1001', NULL, 'Santa Ana Tlapaltitlán', '50160', 'Toluca', 'Estado de México', 19.27300000, -99.62470000),
(25, 'Avenida Álvaro Obregón', '555', NULL, 'Centro', '80000', 'Culiacán', 'Sinaloa', 24.80930000, -107.39410000),
(26, 'Boulevard Manuel Ávila Camacho', '1234', NULL, 'Ricardo Flores Magón', '91900', 'Veracruz', 'Veracruz', 19.18090000, -96.12450000),
(27, 'Boulevard Adolfo López Mateos', '2510', NULL, 'Jardines de Jerez', '37530', 'León', 'Guanajuato', 21.10270000, -101.63600000),
(28, 'Avenida de la Independencia', '210', NULL, 'Centro', '31000', 'Chihuahua', 'Chihuahua', 28.63530000, -106.07540000),
(29, 'Boulevard Eusebio Kino', '1000', NULL, 'Pitic', '83150', 'Hermosillo', 'Sonora', 29.10260000, -110.97730000),
(30, 'Avenida Madero Poniente', '550', NULL, 'Centro Histórico', '58000', 'Morelia', 'Michoacán', 19.70270000, -101.19230000),
(31, 'Carretera Principal', 'Km 2.5', NULL, 'Playas del Rosario', '86288', 'Centro', 'Tabasco', 17.88150000, -92.90340000),
(32, 'Avenida 16 de Septiembre', 's/n', NULL, 'Centro', '24000', 'Campeche', 'Campeche', 19.84560000, -90.53850000),
(33, 'Avenida Ignacio de la Llave', '1118', NULL, 'María de la Piedad', '96410', 'Coatzacoalcos', 'Veracruz', 18.14080000, -94.43400000),
(34, 'Calle Real de Guadalupe', '55', NULL, 'Barrio de Guadalupe', '29230', 'San Cristóbal de las Casas', 'Chiapas', 16.73710000, -92.63760000),
(35, 'Avenida 5 de Mayo', '402', NULL, 'San Pedro', '72760', 'San Pedro Cholula', 'Puebla', 19.06280000, -98.30530000),
(36, 'Avenida Revolución', '1200', NULL, 'Periodistas', '42060', 'Pachuca de Soto', 'Hidalgo', 20.10390000, -98.76180000),
(37, 'Avenida Venustiano Carranza', '2345', NULL, 'Polanco', '78220', 'San Luis Potosí', 'San Luis Potosí', 22.15220000, -101.00690000),
(38, 'Avenida de la Convención de 1914 Poniente', '101', NULL, 'Las Flores', '20220', 'Aguascalientes', 'Aguascalientes', 21.87100000, -102.31600000),
(39, 'Avenida Hidalgo', '630', NULL, 'Centro Histórico', '98000', 'Zacatecas', 'Zacatecas', 22.77340000, -102.57180000),
(40, 'Calle 20 de Noviembre', '901', NULL, 'Zona Centro', '34000', 'Victoria de Durango', 'Durango', 24.02530000, -104.65880000),
(41, 'Avenida México Norte', '170', NULL, 'Centro', '63000', 'Tepic', 'Nayarit', 21.50970000, -104.89480000),
(42, 'Paseo Álvaro Obregón', '450', NULL, 'Zona Central', '23000', 'La Paz', 'Baja California Sur', 24.16140000, -110.31500000),
(43, 'Avenida de los Maestros', '300', NULL, 'Magisterial', '28030', 'Colima', 'Colima', 19.25190000, -103.71960000),
(44, 'Boulevard Guillermo Valle', '115', NULL, 'Centro', '90000', 'Tlaxcala de Xicohténcatl', 'Tlaxcala', 19.31750000, -98.23850000),
(45, 'Avenida Lázaro Cárdenas del Río', '1500', NULL, 'Centro', '86500', 'Cárdenas', 'Tabasco', 17.99300000, -93.37520000),
(46, 'Boulevard Leandro Rovirosa Wade', 's/n', NULL, 'Centro', '86300', 'Comalcalco', 'Tabasco', 18.27890000, -93.20340000),
(47, 'Calle 80', '142', NULL, 'Centro', '97320', 'Progreso', 'Yucatán', 21.28470000, -89.66440000),
(48, 'Avenida Cobá', 's/n', NULL, 'Tulum Centro', '77760', 'Tulum', 'Quintana Roo', 20.21150000, -87.46320000),
(49, 'Avenida Francisco Medina Ascencio', '2485', NULL, 'Zona Hotelera Norte', '48333', 'Puerto Vallarta', 'Jalisco', 20.64830000, -105.24210000),
(50, 'Avenida del Mar', '1100', NULL, 'Palos Prietos', '82010', 'Mazatlán', 'Sinaloa', 23.22010000, -106.42510000),
(51, 'Calle 5 de Febrero', '310', NULL, 'Centro', '85000', 'Cajeme', 'Sonora', 27.49500000, -109.93070000),
(52, 'Avenida Tecnológico', '5600', NULL, 'Partido Iglesias', '32528', 'Juárez', 'Chihuahua', 31.68810000, -106.42250000),
(53, 'Boulevard Venustiano Carranza', '2800', NULL, 'República Poniente', '25280', 'Saltillo', 'Coahuila', 25.44680000, -101.00640000),
(54, 'Avenida Hidalgo', '2001', NULL, 'Campbell', '89260', 'Tampico', 'Tamaulipas', 22.22890000, -97.86310000),
(55, 'Avenida Universidad', 's/n', NULL, 'Anáhuac', '66455', 'San Nicolás de los Garza', 'Nuevo León', 25.75330000, -100.28820000),
(56, 'Salida a Celaya', '77', NULL, 'Zona Centro', '37700', 'San Miguel de Allende', 'Guanajuato', 20.90790000, -100.74410000),
(57, 'Plaza Vasco de Quiroga', '29', NULL, 'Centro', '61600', 'Pátzcuaro', 'Michoacán', 19.51260000, -101.60920000),
(58, 'Avenida Costera Miguel Alemán', '125', NULL, 'Fraccionamiento Magallanes', '39670', 'Acapulco de Juárez', 'Guerrero', 16.85870000, -99.88170000),
(59, 'Avenida Plan de Ayala', '1700', NULL, 'Jacarandas', '62420', 'Cuernavaca', 'Morelos', 18.91030000, -99.21570000),
(60, 'Avenida 1 de Mayo', '120', NULL, 'San Andrés Atoto', '53500', 'Naucalpan de Juárez', 'Estado de México', 19.46730000, -99.23840000),
(61, 'Avenida Colegio Militar', '1', NULL, 'Centro', '42800', 'Tula de Allende', 'Hidalgo', 20.05430000, -99.34210000),
(62, 'Calzada Adolfo López Mateos', '3210', NULL, 'Zona Alta', '75750', 'Tehuacán', 'Puebla', 18.44970000, -97.39420000),
(63, 'Avenida Miguel Ángel de Quevedo', '279', NULL, 'Oxtopulco', '04310', 'Coyoacán', 'Ciudad de México', 19.34320000, -99.17650000);

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
(10, 29, 1, 'uploads/profile_pictures/user_29_6873e2d1e7a3c.png', 'Untitled422_20240810000735.png', 'image/png', '2025-07-13 16:46:09'),
(11, 18, 8, 'uploads/documents_validation/doc_18_68770b8eefd47.pdf', 'formato_acta_constitutiva.pdf', 'application/pdf', '2025-07-16 02:16:46'),
(12, 18, 4, 'uploads/company_validation/comp_valid_18_68770d2402bbd.pdf', 'formato_acta_constitutiva.pdf', NULL, '2025-07-16 02:23:32'),
(13, 18, 2, 'uploads/logos/logo_18_68797d79c327f.png', 'ajolote_inferior_derecha.png', NULL, '2025-07-17 22:47:21'),
(14, 18, 4, 'uploads/company_validation/comp_valid_18_68797e37abe64.pdf', 'formato_acta_constitutiva.pdf', NULL, '2025-07-17 22:50:31'),
(15, 30, 1, 'uploads/logos/logo_org_6879917bddf05_1752797563.png', NULL, NULL, '2025-07-18 00:12:43'),
(16, 30, 5, 'uploads/documents_validation/doc_org_6879926939e43_1752797801.pdf', NULL, NULL, '2025-07-18 00:16:41'),
(19, 3, 1, 'uploads/logos/org/logo_org_68799dfd43011_1752800765.png', NULL, NULL, '2025-07-18 01:06:05'),
(20, 3, 5, 'uploads/documents_validation/org/doc_org_68799e78519aa_1752800888.pdf', NULL, NULL, '2025-07-18 01:08:08'),
(21, 3, 5, 'uploads/documents_validation/org/doc_org_68799ecc9695f_1752800972.pdf', NULL, NULL, '2025-07-18 01:09:32'),
(23, 18, 6, 'uploads/avisos_docs/doc_aviso_687c6483ca746_1752982659.pdf', NULL, NULL, '2025-07-20 03:37:39'),
(24, 18, 6, 'uploads/avisos_docs/doc_aviso_687c66230fdfd_1752983075.pdf', NULL, NULL, '2025-07-20 03:44:35'),
(25, 18, 6, 'uploads/avisos_docs/doc_aviso_687c669a57fc3_1752983194.pdf', NULL, NULL, '2025-07-20 03:46:34'),
(26, 18, 6, 'uploads/avisos_docs/doc_aviso_687c68f6e8660_1752983798.pdf', NULL, NULL, '2025-07-20 03:56:38'),
(27, 32, 6, 'uploads/avisos_docs/doc_aviso_687c6b76af5db_1752984438.pdf', NULL, NULL, '2025-07-20 04:07:18'),
(28, 32, 6, 'uploads/avisos_docs/doc_aviso_687c6bff2bbdb_1752984575.pdf', NULL, NULL, '2025-07-20 04:09:35'),
(31, 32, 6, 'uploads/avisos_docs/medicamento/doc_aviso_687d15a629455_1753028006.pdf', NULL, NULL, '2025-07-20 16:13:26'),
(32, 32, 6, 'uploads/avisos_docs/dispositivo/doc_aviso_687d206a2bd88_1753030762.pdf', NULL, NULL, '2025-07-20 16:59:22'),
(33, 32, 6, 'uploads/avisos_docs/dispositivo/doc_aviso_687d20bf07b00_1753030847.pdf', NULL, NULL, '2025-07-20 17:00:47'),
(34, 32, 6, 'uploads/avisos_docs/dispositivo/doc_aviso_687d21799ae25_1753031033.pdf', NULL, NULL, '2025-07-20 17:03:53'),
(35, 32, 6, 'uploads/avisos_docs/dispositivo/doc_aviso_687d23542bb57_1753031508.pdf', NULL, NULL, '2025-07-20 17:11:48'),
(36, 32, 6, 'uploads/avisos_docs/sangre/doc_aviso_687d23e97fb58_1753031657.pdf', NULL, NULL, '2025-07-20 17:14:17'),
(37, 32, 6, 'uploads/avisos_docs/sangre/doc_aviso_687d240c3ee43_1753031692.pdf', NULL, NULL, '2025-07-20 17:14:52'),
(40, 3, 9, 'uploads/beneficios/img_benefit_6882c9352bda5.jpg', 'team-2.jpg', 'image/jpeg', '2025-07-25 00:00:53'),
(41, 3, 9, 'uploads/beneficios/img_benefit_6882c948220de.jpg', 'team-4.jpg', 'image/jpeg', '2025-07-25 00:01:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donaciones`
--

CREATE TABLE `donaciones` (
  `id` int(11) NOT NULL,
  `aviso_id` int(11) DEFAULT NULL,
  `donante_id` int(11) NOT NULL,
  `organizacion_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `estatus_id` int(11) NOT NULL,
  `fecha_compromiso` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_validacion` timestamp NULL DEFAULT NULL,
  `item_nombre` varchar(255) DEFAULT NULL,
  `item_detalle` varchar(255) DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `ruta_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donaciones`
--

INSERT INTO `donaciones` (`id`, `aviso_id`, `donante_id`, `organizacion_id`, `cantidad`, `estatus_id`, `fecha_compromiso`, `fecha_validacion`, `item_nombre`, `item_detalle`, `fecha_caducidad`, `ruta_foto`) VALUES
(1, 1, 1, NULL, 1, 3, '2025-07-10 16:00:00', '2025-07-25 01:24:30', NULL, NULL, NULL, NULL),
(2, 2, 2, NULL, 1, 3, '2025-07-11 17:30:00', '2025-07-25 01:32:39', NULL, NULL, NULL, NULL),
(5, 3, 20, NULL, 2, 4, '2025-07-19 02:22:47', '2025-07-23 20:31:26', NULL, NULL, NULL, NULL),
(6, 8, 21, NULL, 2, 1, '2025-07-19 02:22:47', NULL, NULL, NULL, NULL, NULL),
(7, 1, 5, NULL, 1, 4, '2025-07-22 00:51:59', '2025-07-24 02:58:03', NULL, NULL, NULL, NULL),
(8, 1, 1, NULL, 1, 4, '2025-07-22 00:58:39', '2025-07-23 01:29:37', 'Muletas de aluminio para adulto', 'Usado - Buen estado', NULL, NULL),
(9, 1, 1, NULL, 1, 3, '2025-07-22 00:59:36', NULL, 'Insulina Humalog 100 UI/mL', NULL, '2026-06-22', NULL),
(10, NULL, 1, 13, 1, 3, '2025-07-22 02:14:39', '2025-07-23 20:25:38', NULL, NULL, NULL, NULL),
(11, NULL, 15, 23, 1, 1, '2025-07-24 02:52:28', NULL, NULL, NULL, NULL, NULL),
(12, NULL, 29, 21, 1, 1, '2025-07-24 02:53:35', NULL, NULL, NULL, NULL, NULL),
(13, NULL, 29, 24, 1, 1, '2025-07-24 02:56:28', NULL, NULL, NULL, NULL, NULL),
(14, NULL, 1, 24, 1, 2, '2025-07-25 01:22:56', NULL, NULL, NULL, NULL, NULL),
(15, NULL, 1, 13, 1, 3, '2025-07-25 01:34:10', '2025-07-25 01:34:34', 'Paracetamol 500 mg', NULL, '2027-07-30', NULL);

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
(1, 2, 1, 1, 'CANJE-A1B2-C3D4', 'Canjeado', '2025-07-11 18:00:00', '2025-07-25 01:18:51'),
(2, 2, 2, 3, NULL, 'Disponible', '2025-07-25 01:32:39', NULL),
(3, 15, 1, 2, 'CNJ-4AD3346F', 'Canjeado', '2025-07-25 01:34:34', '2025-07-25 01:35:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donatarios`
--

CREATE TABLE `donatarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sexo` enum('Masculino','Femenino') NOT NULL,
  `tipo_sangre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `donatarios`
--

INSERT INTO `donatarios` (`id`, `usuario_id`, `nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `sexo`, `tipo_sangre_id`) VALUES
(1, NULL, 'Marcos', 'Aguilar', 'Jimenez', '1985-04-12', 'Masculino', 1),
(2, NULL, 'Brenda', 'Navarro', 'Soto', '1992-09-25', 'Femenino', 7),
(8, NULL, 'Julieta', 'Campos', 'Rios', '2005-01-30', 'Femenino', 3),
(15, NULL, 'Rodrigo', 'Mora', 'Leon', '1978-11-08', 'Masculino', 8),
(16, NULL, 'Elias', 'Castillo', 'Ortiz', '2003-10-18', 'Masculino', 6),
(17, NULL, 'Carolina', 'Rios', 'Mendoza', '1997-05-02', 'Femenino', 1),
(20, NULL, 'Valeria', 'Ibarra', 'Vazquez', '2010-06-17', 'Femenino', 2),
(21, NULL, 'Isaac', 'Serrano', 'Luna', '1999-02-01', 'Masculino', 5),
(22, NULL, 'Andrea', 'Chavez', 'Guzman', '1989-07-22', 'Femenino', 4);

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
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `imagen_documento_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas_apoyos`
--

INSERT INTO `empresas_apoyos` (`id`, `empresa_id`, `tipo_apoyo_id`, `titulo`, `descripcion`, `fecha_expiracion`, `activo`, `imagen_documento_id`) VALUES
(1, 2, 1, '15% de descuento en medicamentos', 'Presenta tu código de canje y obtén un 15% de descuento en la compra de medicamentos de cuadro básico.', '2025-12-31', 1, 41),
(2, 2, 4, 'Consulta médica general gratuita', 'Canjea tu beneficio por una consulta médica general en nuestra sucursal.', '2025-12-31', 1, NULL),
(3, 2, 1, '2x1 en Cines Cinepolis o Cinemex', 'Puedes conseguir boletos 2x1 en salas tradicionales en los cines participantes. Validos de lunes a viernes.', '2025-07-30', 1, 40);

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
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Activa','Inactiva') NOT NULL DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas_perfil`
--

INSERT INTO `empresas_perfil` (`id`, `nombre_comercial`, `razon_social`, `rfc`, `telefono_empresa`, `descripcion`, `direccion_comercial_id`, `representante_id`, `logo_documento_id`, `empresa_padre_id`, `fecha_creacion`, `estado`) VALUES
(2, 'Power Gym', 'Centros de Acondicionamiento Físico Power S.A. de C.V.', 'CAF010101XYZ', '5598765432', 'Gimnasio con equipo de última generación, entrenadores certificados y clases grupales de alta intensidad.', 39, 31, 13, NULL, '2025-07-21 01:04:22', 'Activa'),
(3, 'FarmaBien', 'Distribuidora Farmacéutica del Bienestar S.A.P.I. de C.V.', 'DFB050505ABC', '8001234567', 'Cadena de farmacias con amplio surtido de medicamentos, servicio a domicilio y consultorio médico adjunto.', 40, 32, NULL, NULL, '2025-07-21 01:04:22', 'Activa'),
(4, 'NutriMarket', 'Mercado de Suplementos Nutricionales de México S. de R.L.', 'MSN101010DEF', '5511223344', 'Tienda especializada en la venta de suplementos alimenticios, vitaminas, proteínas y productos orgánicos.', 41, 33, NULL, NULL, '2025-07-21 01:04:22', 'Activa'),
(5, 'Consultores Digitales', 'Soluciones Tecnológicas Integrales S.A. de C.V.', 'STI151120GHI', '3344556677', 'Agencia especializada en marketing digital, desarrollo de software a la medida y consultoría de TI.', 42, 34, NULL, NULL, '2025-07-21 01:04:22', 'Activa'),
(7, 'Café del Sur', 'Productores de Café de la Sierra Sur S.P.R. de R.L.', 'PCS080315JKL', '9512345678', 'Cooperativa de productores y exportadores de café de especialidad de las altas montañas del sur.', 43, 35, NULL, NULL, '2025-07-21 01:04:22', 'Activa'),
(8, 'Logística Express', 'Servicios de Transporte y Logística Express Nacional S.A. de C.V.', 'STL020630MNO', '5587654321', 'Empresa de paquetería, mensajería y soluciones logísticas con cobertura en toda la República Mexicana.', 44, 36, NULL, NULL, '2025-07-21 01:04:22', 'Activa');

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
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Activa','Inactiva') NOT NULL DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `organizaciones_perfil`
--

INSERT INTO `organizaciones_perfil` (`id`, `nombre_organizacion`, `cluni`, `representante_id`, `logo_documento_id`, `direccion_id`, `mision`, `organizacion_padre_id`, `fecha_creacion`, `estado`) VALUES
(3, 'Fundación Únete por el Bienestar Social A.C.', 'FUN210815HGTDFR01', 1, 19, 1, 'Promover la participación ciudadana para apoyar a comunidades vulnerables a través de programas de desarrollo autosostenible.', NULL, '2025-07-12 00:08:32', 'Activa'),
(13, 'Sonrisas para el Futuro México A.C.', 'SFM190120DFGHYT02', 2, NULL, 2, 'Brindar apoyo educativo, alimentario y emocional a niños y niñas en situación de riesgo para asegurarles un futuro digno.', NULL, '2025-07-12 00:08:32', 'Activa'),
(14, 'Asociación Civil Por los Niños', 'APN050510PLMFRT03', 5, NULL, 3, 'Garantizar el derecho a la salud y a una educación de calidad para la niñez mexicana en comunidades marginadas.', NULL, '2025-07-12 01:22:47', 'Activa'),
(15, 'Cáritas Diocesana I.A.P.', 'CDI991130GTRFDS04', 6, 15, 4, 'Asistir a personas y familias en situación de pobreza y exclusión social, fomentando la caridad y la justicia social.', NULL, '2025-07-17 23:04:19', 'Activa'),
(17, 'Mundo Animal Refugio y Protección', 'MAR150312MNBVCX05', 7, NULL, 5, 'Rescatar, rehabilitar, esterilizar y encontrar hogares responsables para animales en situación de calle o maltrato.', NULL, '2025-07-21 00:36:47', 'Activa'),
(18, 'EcoPlaneta Conservación Ambiental', 'ECA100228LKJHGF06', 8, NULL, 7, 'Fomentar la conservación de los ecosistemas y la biodiversidad en México a través de la educación y la acción comunitaria.', NULL, '2025-07-21 00:36:47', 'Activa'),
(19, 'Comedor Comunitario Manos Unidas', 'CCM180901ASDFGY07', 10, NULL, 8, 'Proporcionar alimento nutritivo y balanceado a personas de escasos recursos, niños y adultos mayores en situación de vulnerabilidad.', NULL, '2025-07-21 00:36:47', 'Activa'),
(20, 'Fundación Apoyo Mayor', 'FAM010717QWERTY08', 12, NULL, 9, 'Mejorar la calidad de vida de los adultos mayores a través de programas de salud, compañía, bienestar y asistencia integral.', NULL, '2025-07-21 00:36:47', 'Activa'),
(21, 'Cruz Roja Mexicana - Banco de Sangre', 'CRM870215GTRDPL01', 23, NULL, 10, 'Contribuir a salvar vidas proporcionando sangre y componentes sanguíneos seguros y de alta calidad para la comunidad, bajo los principios de la donación voluntaria y altruista.', NULL, '2025-07-21 00:41:02', 'Activa'),
(22, 'Hemosalud Centro de Donación', 'HCD051120HJKLYU02', 24, NULL, 11, 'Fomentar la cultura de la donación altruista de sangre y facilitar el acceso a componentes sanguíneos seguros para tratamientos médicos y emergencias.', NULL, '2025-07-21 00:41:02', 'Activa'),
(23, 'Banco de Vida - Servicios Hematológicos', 'BVH110901ASDFGH03', 26, NULL, 12, 'Ofrecer servicios integrales de hematología y un banco de sangre con tecnología de punta para garantizar la seguridad y el bienestar de donantes y receptores.', NULL, '2025-07-21 00:41:02', 'Activa'),
(24, 'Red Nacional de Sangre Altruista A.C.', 'RSA160418CVBNMK04', 27, NULL, 13, 'Conectar de manera eficiente a donantes voluntarios con pacientes que requieren transfusiones urgentes, creando y fortaleciendo una red de apoyo a nivel nacional.', NULL, '2025-07-21 00:41:02', 'Activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizaciones_x_categorias`
--

CREATE TABLE `organizaciones_x_categorias` (
  `organizacion_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `organizaciones_x_categorias`
--

INSERT INTO `organizaciones_x_categorias` (`organizacion_id`, `categoria_id`) VALUES
(3, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 1),
(22, 1),
(23, 1),
(24, 1);

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
(1, 'Lucia', 'Perez', 'Gomez', '1995-05-10', 'Femenino', '5512345678', 14, 7),
(2, 'Sofia', 'Diaz', 'Rodriguez', '1998-11-22', 'Femenino', '5523456789', 15, 1),
(3, 'Clara', 'Lopez', 'Martinez', '1992-01-30', 'Femenino', '5534567890', 16, 3),
(4, 'Jorge', 'Navarro', 'Sanchez', '1990-07-15', 'Masculino', '5545678901', 17, 8),
(5, 'Lucia', 'Martinez', 'Fernandez', '1996-03-12', 'Femenino', '5556789012', 18, 2),
(10, 'Ana', 'Salazar', 'Romero', '2001-09-05', 'Femenino', '5567890123', 19, 5),
(11, 'Andres', 'Morales', 'Garcia', '1988-12-18', 'Masculino', '5578901234', 20, 1),
(12, 'Diego', 'Navarro', 'Perez', '1999-02-25', 'Masculino', '5589012345', 21, 7),
(13, 'Maria', 'Diaz', 'Gomez', '1993-06-20', 'Femenino', '5590123456', 22, 4),
(14, 'Maria', 'Morales', 'Rodriguez', '1994-08-14', 'Femenino', '5501234567', 23, 6),
(15, 'Ana', 'Vargas', 'Lopez', '2000-04-01', 'Femenino', '5511223344', 24, 1),
(18, 'David', 'Ramirez', 'Martinez', '1985-10-28', 'Masculino', '5522334455', 25, 3),
(20, 'Carlos', 'Garcia', 'Sanchez', '1989-11-03', 'Masculino', '5533445566', 26, 8),
(28, 'David', 'Garcia', 'Romero', '1991-05-19', 'Masculino', '5555667788', 27, 7),
(29, 'Jesus Gabriel', 'De la cruz', 'Zárate', '2002-08-04', 'Masculino', '9222835441', 28, 1),
(30, 'Clara', 'Lopez', 'Perez', '1992-02-11', 'Femenino', '5566778899', 29, 5),
(32, 'Maria', 'Diaz', 'Gonzalez', '1993-04-16', 'Femenino', '5577889900', 30, 1),
(40, 'Sara', 'Rocha', 'Gomez', '1995-03-15', 'Femenino', '5530313233', 31, 3),
(41, 'Bruno', 'Diaz', 'Lopez', '1992-07-20', 'Masculino', '5540414243', 32, 1),
(42, 'Elisa', 'Vega', 'Martinez', '1998-11-05', 'Femenino', '5550515253', 33, 7),
(43, 'Hugo', 'Reyes', 'Sanchez', '1990-01-25', 'Masculino', '5560616263', 34, 8),
(44, 'Julia', 'Flores', 'Romero', '1999-09-12', 'Femenino', '5570717273', 35, 2),
(45, 'Martin', 'Soto', 'Garcia', '1988-06-30', 'Masculino', '5580818283', 36, 5),
(46, 'Paula', 'Mendez', 'Perez', '1996-04-18', 'Femenino', '5590919293', 37, 1),
(47, 'Sergio', 'Castro', 'Ramirez', '1993-12-01', 'Masculino', '5501020304', 38, 4),
(48, 'Iker', 'Montes', 'Cruz', '1996-08-10', 'Masculino', '5511223344', 39, 2),
(49, 'Ximena', 'Lira', 'Flores', '1999-02-20', 'Femenino', '5522334455', 40, 4),
(50, 'Dante', 'Solis', 'Mendoza', '1991-05-14', 'Masculino', '5533445566', 41, 6),
(51, 'Aitana', 'Vega', 'Castillo', '2000-10-03', 'Femenino', '5544556677', 42, 1),
(52, 'Gael', 'Pineda', 'Rojas', '1997-07-21', 'Masculino', '5555667788', 43, 8),
(53, 'Renata', 'Soto', 'Ibarra', '1998-12-25', 'Femenino', '5566778899', 44, 3),
(54, 'Valentina', 'Reyes', 'Campos', '1995-04-09', 'Femenino', '5577889900', 45, 7),
(55, 'Mateo', 'Gomez', 'Vargas', '1994-05-22', 'Masculino', '5521222324', 46, 1),
(56, 'Camila', 'Lopez', 'Rojas', '1997-08-11', 'Femenino', '5531323334', 47, 3),
(57, 'Leo', 'Martinez', 'Castro', '1992-03-19', 'Masculino', '5541424344', 48, 7),
(58, 'Regina', 'Diaz', 'Ortega', '2001-01-07', 'Femenino', '5551525354', 49, 2),
(59, 'Lucas', 'Fernandez', 'Jimenez', '1990-11-28', 'Masculino', '5561626364', 50, 8),
(60, 'Sofia', 'Perez', 'Moreno', '1999-06-15', 'Femenino', '5571727374', 51, 4),
(61, 'Adrian', 'Sanchez', 'Guerrero', '1995-10-02', 'Masculino', '5581828384', 52, 5),
(62, 'Isabella', 'Romero', 'Mendoza', '1998-04-25', 'Femenino', '5591929394', 53, 1),
(63, 'Matias', 'Garcia', 'Salazar', '1993-09-30', 'Masculino', '5502030405', 54, 6),
(64, 'Victoria', 'Torres', 'Paredes', '1996-07-13', 'Femenino', '5512131415', 55, 3),
(65, 'Samuel', 'Ramirez', 'Solis', '1991-12-05', 'Masculino', '5522232425', 56, 7),
(66, 'Natalia', 'Flores', 'Vega', '2000-02-18', 'Femenino', '5532333435', 57, 2),
(67, 'Emily', 'García', 'Pérez', NULL, 'Femenino', NULL, NULL, NULL),
(68, 'Katia', 'Hernadez', 'Díaz', NULL, 'Femenino', NULL, NULL, NULL),
(69, 'Jesus Gabriel', 'De la cruz', 'Zárate', NULL, 'Masculino', NULL, NULL, NULL);

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
(1, 'Fernando', 'Hernandez', 'Ruiz', 'fernando.hernandez@unete.org', '5510102030'),
(2, 'Laura', 'Castillo', 'Flores', 'laura.castillo@sonrisas.org.mx', '5511112131'),
(5, 'Ricardo', 'Mendoza', 'Soto', 'ricardo.mendoza@porlosninos.org', '5512122232'),
(6, 'Patricia', 'Aguilar', 'Vega', 'patricia.aguilar@caritas.org', '5513132333'),
(7, 'Javier', 'Moreno', 'Ramos', 'javier.moreno@mundoanimal.org', '5514142434'),
(8, 'Elena', 'Reyes', 'Guzman', 'elena.reyes@ecoplaneta.org', '5515152535'),
(10, 'Miguel', 'Paredes', 'Cruz', 'miguel.paredes@comedorcomunitario.mx', '5516162636'),
(12, 'Isabel', 'Jimenez', 'Ortega', 'isabel.jimenez@apoyomayor.org', '5517172737'),
(23, 'Victor', 'Rojas', 'Salas', 'victor.rojas@cruzroja.sangre.mx', '5518182838'),
(24, 'Carmen', 'Ibarra', 'Luna', 'carmen.ibarra@hemosalud.org', '5519192939'),
(26, 'Daniel', 'Castro', 'Rios', 'daniel.castro@bancovida.com', '5520203040'),
(27, 'Veronica', 'Mora', 'Campos', 'veronica.mora@redsangre.org.mx', '5521213141'),
(31, 'Alejandro', 'Vazquez', 'Solis', 'alejandro.vazquez@powergym.com', '5522223242'),
(32, 'Gabriela', 'Dominguez', 'Chavez', 'gabriela.dominguez@farmabien.com', '5523233343'),
(33, 'Ruben', 'Serrano', 'Leon', 'ruben.serrano@nutrimarket.mx', '5524243444'),
(34, 'Omar', 'Fuentes', 'Reyes', 'omar.fuentes@consultoresd.com', '3312345678'),
(35, 'Beatriz', 'Solano', 'Mota', 'beatriz.solano@cafedelsur.coop', '9518765432'),
(36, 'Hector', 'Lira', 'Camacho', 'hector.lira@logisticaexpress.com.mx', '5576543210');

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
(2, 'Visualizador'),
(3, 'Nada por ahora'),
(4, 'Nada por ahora');

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
(6, 3, 'Muletas axilares de aluminio', 'Ajustables para adulto, altura recomendada 1.60m - 1.80m.', 8),
(7, 12, 'Silla de ruedas estándar', 'Plegable, para adulto, con reposapiés desmontables y frenos de mano.', 1),
(8, 18, 'Andadera ortopédica para adulto', 'Plegable, de aluminio, con altura ajustable y regatones antiderrapantes.', 1),
(9, 21, 'Prótesis para pierna derecha', 'Prótesis transfemoral (arriba de la rodilla). Se requieren medidas y evaluación médica para su fabricación.', 1);

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
(3, 2, 'Insulina de acción rápida', '100 UI/mL', 'Vial de 10 ml', 8),
(4, 9, 'Amlodipino', '5 mg', 'Caja con 30 tabletas', 1),
(5, 17, 'Carbamazepina', '200 mg', 'Caja con 20 tabletas de liberación prolongada', 3),
(6, 20, 'Tamoxifeno', '20 mg', 'Caja con 30 tabletas', 1),
(7, 23, 'Metotrexato', '2.5 mg', 'Frasco con 50 tabletas', 1);

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
(1, 7, 8),
(8, 6, 2),
(14, 3, 3),
(19, 2, 4),
(22, 8, 5);

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
(8, 'DOC_INE_PERFIL', 'Credencial del Lector (INE) del perfil de usuario'),
(9, 'IMAGEN_BENEFICIO', 'Imagen de referencia para un beneficio de empresa');

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
(1, 'lucia.perez@gmail.com', '$2y$10$z.Cpg.vK3aLOOHrBYoOkTOOKwvPRnxf6uN2FVGf.5W3FKGwU1fysq', 1, 1, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(2, 'sofia.diaz@gmail.com', '$2y$10$1/4K2/TYgt7ywmvfW4ugGO.gvv6Pb.1ksuBmMAcfa/UZUk9MWLSV.', 1, 1, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(3, 'clara.lopez@empresa.com', '$2y$10$62TvDltCzHxipfnMGxhYO.ndpWA2AIwywsx3hVuiMzZelzlTP/t/e', 2, 1, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(4, 'jorge.navarro@gmail.com', '$2y$10$/aqUzlB/BUVFsXvKZZuzq.LL.BUlFDYtexgMjkQtloMvw0AM2MpbC', 1, 1, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(5, 'lucia.martinez@empresa.com', '$2y$10$Eq6Ra5IROGo.mQOEgv6uAOmq6AZpwCPxm.gxNI1JQnil/nA866lBW', 2, 2, '2025-07-09 20:24:02', 'Activo', NULL, NULL),
(10, 'ana.salazar@gmail.com', '$2y$10$IwXn8sQGCH1SnYIZOR3VV.bs8hprfZ9aEd8hlP4m5MRnBFmcW2Z56', 1, 1, '2025-07-10 01:46:11', 'Activo', NULL, NULL),
(11, 'andres.morales@org.com', '$2y$10$CP1FFJJEai1tPAKdh4OQGOnYtuzAvqHaz9uIzknmwWEDYiNQbfsBK', 3, 1, '2025-07-10 01:50:21', 'Activo', NULL, NULL),
(12, 'diego.navarro@org.com', '$2y$10$0CqBcf7/zlKi174E0uM/ROO..L/T/ROgcmnUQXNRDfQqoEMKy9roO', 3, 2, '2025-07-10 01:52:00', 'Activo', NULL, NULL),
(13, 'maria.diaz@org.com', '$2y$10$pHZBbvl/47l22dEfDDi5.O1fVyDv9fRPgpqWUJu3hPikFz5a3xbU.', 3, 1, '2025-07-10 01:58:04', 'Activo', NULL, NULL),
(14, 'maria.morales@empresa.com', '$2y$10$au4Ee6JUqdGuYeD4zTd6ceX97Jwyke70SDQlDiebMrzV58D6H9kIm', 2, 1, '2025-07-11 17:07:00', 'Activo', NULL, NULL),
(15, 'ana.vargas@org.com', '$2y$10$rHG9JpMGRtKIiLrgTYPRTOgm4NZmpSDBtz8FlFgdbONCn22mThv9q', 3, 2, '2025-07-11 20:50:12', 'Activo', NULL, NULL),
(18, 'david.ramirez@org.com', '$2y$10$./ouqUHnyzRldz9bCpNDm.mhzdaU01XegYrdtztwgBnGj56F45Ida', 3, 1, '2025-07-11 21:35:10', 'Activo', NULL, NULL),
(20, 'carlos.garcia@gmail.com', '$2y$10$OTmZVcE07K7MwOSXkx2hAu.OsMhg9z8akBTaDM/UDNc76I0ecF6ZG', 1, 1, '2025-07-12 01:12:48', 'Activo', NULL, NULL),
(21, 'monica.gomez@empresa.com', '$2y$10$k2igfSaoDaWpvVJ/2dCAmOlq6HNGHuUEo8zK4FHrenWE/H5KJu1by', 2, 2, '2025-07-12 01:13:30', 'Activo', NULL, NULL),
(28, 'david.garcia@empresa.com', '$2y$10$j8dcwAKBwNAuH6IE9QTf9eU6xXIlmFjRQ3F3n1buBf72VlOzSPtT2', 2, 2, '2025-07-12 01:22:47', 'Activo', NULL, NULL),
(29, 'realloyal1a@gmail.com', '$2y$10$t1RiGqp.sYkedLGSE3okL.Eyf/jUJ.vMC8hO//K/5ktuyeSw39t9a', 1, 3, '2025-07-13 16:45:31', 'Activo', NULL, NULL),
(30, 'clara.lopez@gmail.com', '$2y$10$KP3CLyy5WlA.A8Ixr34dXeeC0oTwpJpyvYiPG4mDl1HyMHZ..fER2', 1, 1, '2025-07-17 23:04:19', 'Activo', NULL, NULL),
(32, 'maria.diaz@gmail.com', '$2y$10$YgiMek3T0hBBCyOhvkRzjOhNacqCXQnAo6FWVii8iY3xP14S.e8sS', 1, 1, '2025-07-18 00:25:36', 'Activo', NULL, NULL),
(40, 'sara.rocha@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 1, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(41, 'bruno.diaz@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 2, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(42, 'elisa.vega@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 1, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(43, 'hugo.reyes@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 2, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(44, 'julia.flores@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 1, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(45, 'martin.soto@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 2, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(46, 'paula.mendez@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 1, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(47, 'sergio.castro@empresa.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 2, 2, '2025-07-21 01:11:03', 'Activo', NULL, NULL),
(48, 'iker.montes@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(49, 'ximena.lira@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(50, 'dante.solis@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(51, 'aitana.vega@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(52, 'gael.pineda@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(53, 'renata.soto@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(54, 'valentina.reyes@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:04:49', 'Activo', NULL, NULL),
(55, 'mateo.gomez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(56, 'camila.lopez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(57, 'leo.martinez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(58, 'regina.diaz@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(59, 'lucas.fernandez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(60, 'sofia.perez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(61, 'adrian.sanchez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(62, 'isabella.romero@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(63, 'matias.garcia@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(64, 'victoria.torres@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(65, 'samuel.ramirez@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 1, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(66, 'natalia.flores@org.com', '$2y$10$If4G.o2xIWQtTo8M3C4v9.aR7e.wSML0jTTJAUxkhHLTqg06aQn0K', 3, 2, '2025-07-21 02:28:02', 'Activo', NULL, NULL),
(67, 'emily.garcia@org.com', '$2y$10$9Eupo7zqxQQvdVcbfH6TEufwmnCArBu3CrYq/pErtR5enBcfUMH.y', 3, 2, '2025-07-23 23:04:10', 'Activo', NULL, NULL),
(68, 'katia.hernandez@org.com', '$2y$10$.P194mzGODOV.3OxQueVMuhIAgA3eUNHVh00pSagx34dlrROqnpuW', 3, 2, '2025-07-23 23:15:08', 'Activo', NULL, NULL),
(69, 'contacto.gz.zarate01@gmail.com', '$2y$10$0.cn/XpDwi.E6HZjotDTSObZtVWLRzYvfHZ8musltFe5ikcNzv.fa', 3, 1, '2025-07-24 02:59:31', 'Activo', NULL, NULL);

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
(3, 2, '2025-07-12 00:08:32'),
(5, 2, '2025-07-12 01:13:30'),
(14, 3, '2025-07-18 00:25:36'),
(28, 3, '2025-07-21 01:51:03'),
(30, 4, '2025-07-21 01:54:08'),
(41, 4, '2025-07-21 01:54:08'),
(42, 5, '2025-07-21 01:54:08'),
(43, 5, '2025-07-21 01:54:08'),
(44, 7, '2025-07-21 01:54:08'),
(45, 7, '2025-07-21 01:54:08'),
(46, 8, '2025-07-21 01:54:08'),
(47, 8, '2025-07-21 01:54:08');

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
(11, 3, '2025-07-12 00:08:32'),
(12, 3, '2025-07-12 00:08:32'),
(13, 13, '2025-07-12 01:22:47'),
(15, 13, '2025-07-17 23:04:19'),
(18, 14, '2025-07-21 02:21:46'),
(48, 14, '2025-07-21 02:21:46'),
(49, 15, '2025-07-21 02:21:46'),
(50, 15, '2025-07-21 02:21:46'),
(51, 17, '2025-07-21 02:21:46'),
(52, 17, '2025-07-21 02:21:46'),
(53, 18, '2025-07-21 02:21:46'),
(54, 18, '2025-07-21 02:21:46'),
(55, 19, '2025-07-21 02:57:35'),
(56, 19, '2025-07-21 02:57:35'),
(57, 20, '2025-07-21 02:57:35'),
(58, 20, '2025-07-21 02:57:35'),
(59, 21, '2025-07-21 02:57:35'),
(60, 21, '2025-07-21 02:57:35'),
(61, 22, '2025-07-21 02:57:35'),
(62, 22, '2025-07-21 02:57:35'),
(63, 23, '2025-07-21 02:57:35'),
(64, 23, '2025-07-21 02:57:35'),
(65, 24, '2025-07-21 02:57:35'),
(66, 24, '2025-07-21 02:57:35'),
(67, 13, '2025-07-23 23:14:00'),
(68, 13, '2025-07-23 23:15:08'),
(69, 13, '2025-07-24 02:59:31');

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
  ADD KEY `fk_avisos_beneficiarios` (`donatario_id`),
  ADD KEY `fk_avisos_categorias_donacion` (`categoria_id`),
  ADD KEY `fk_avisos_urgencia_niveles` (`urgencia_id`),
  ADD KEY `fk_avisos_estatus_aviso` (`estatus_id`),
  ADD KEY `fk_avisos_usuarios_responsable` (`contacto_responsable_id`);

--
-- Indices de la tabla `avisos_documentos`
--
ALTER TABLE `avisos_documentos`
  ADD PRIMARY KEY (`aviso_id`,`documento_id`),
  ADD KEY `fk_avisos_documentos_avisos` (`aviso_id`),
  ADD KEY `fk_avisos_documentos_documentos` (`documento_id`);

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
-- Indices de la tabla `donatarios`
--
ALTER TABLE `donatarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_donatarios_usuarios_idx` (`usuario_id`),
  ADD KEY `fk_donatarios_tipos_sangre_idx` (`tipo_sangre_id`);

--
-- Indices de la tabla `empresas_apoyos`
--
ALTER TABLE `empresas_apoyos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ea_usuarios` (`empresa_id`),
  ADD KEY `fk_ea_tipos_apoyo` (`tipo_apoyo_id`),
  ADD KEY `fk_ea_imagen_documento` (`imagen_documento_id`);

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
-- Indices de la tabla `organizaciones_x_categorias`
--
ALTER TABLE `organizaciones_x_categorias`
  ADD PRIMARY KEY (`organizacion_id`,`categoria_id`),
  ADD KEY `fk_org_cat_categorias_idx` (`categoria_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `categorias_donacion`
--
ALTER TABLE `categorias_donacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `donaciones`
--
ALTER TABLE `donaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `donantes_beneficios`
--
ALTER TABLE `donantes_beneficios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `donatarios`
--
ALTER TABLE `donatarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `empresas_apoyos`
--
ALTER TABLE `empresas_apoyos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empresas_perfil`
--
ALTER TABLE `empresas_perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `solicitudes_dispositivos`
--
ALTER TABLE `solicitudes_dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `solicitudes_medicamentos`
--
ALTER TABLE `solicitudes_medicamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_apoyo`
--
ALTER TABLE `tipos_apoyo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_documento`
--
ALTER TABLE `tipos_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD CONSTRAINT `fk_avisos_categorias_donacion` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_donacion` (`id`),
  ADD CONSTRAINT `fk_avisos_donatarios` FOREIGN KEY (`donatario_id`) REFERENCES `donatarios` (`id`),
  ADD CONSTRAINT `fk_avisos_estatus_aviso` FOREIGN KEY (`estatus_id`) REFERENCES `estatus_aviso` (`id`),
  ADD CONSTRAINT `fk_avisos_organizaciones_perfil` FOREIGN KEY (`organizacion_id`) REFERENCES `organizaciones_perfil` (`id`),
  ADD CONSTRAINT `fk_avisos_urgencia_niveles` FOREIGN KEY (`urgencia_id`) REFERENCES `urgencia_niveles` (`id`),
  ADD CONSTRAINT `fk_avisos_usuarios_creador` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_avisos_usuarios_responsable` FOREIGN KEY (`contacto_responsable_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `avisos_documentos`
--
ALTER TABLE `avisos_documentos`
  ADD CONSTRAINT `fk_ad_avisos` FOREIGN KEY (`aviso_id`) REFERENCES `avisos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_avisos_documentos_documentos` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

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
-- Filtros para la tabla `donatarios`
--
ALTER TABLE `donatarios`
  ADD CONSTRAINT `fk_donatarios_tipos_sangre` FOREIGN KEY (`tipo_sangre_id`) REFERENCES `tipos_sangre` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_donatarios_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Filtros para la tabla `empresas_apoyos`
--
ALTER TABLE `empresas_apoyos`
  ADD CONSTRAINT `fk_ea_empresas_perfil_new` FOREIGN KEY (`empresa_id`) REFERENCES `empresas_perfil` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ea_imagen_documento` FOREIGN KEY (`imagen_documento_id`) REFERENCES `documentos` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
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
-- Filtros para la tabla `organizaciones_x_categorias`
--
ALTER TABLE `organizaciones_x_categorias`
  ADD CONSTRAINT `fk_org_cat_categorias` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_donacion` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_org_cat_organizaciones` FOREIGN KEY (`organizacion_id`) REFERENCES `organizaciones_perfil` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

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

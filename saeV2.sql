-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.28-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para db_sae
CREATE DATABASE IF NOT EXISTS `db_sae` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `db_sae`;

-- Volcando estructura para tabla db_sae.carreras
CREATE TABLE IF NOT EXISTS `carreras` (
  `ID_CARRERA` int(11) NOT NULL AUTO_INCREMENT,
  `TITULO_ABREVIADO` varchar(100) NOT NULL,
  `DESCRIPCION` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_CARRERA`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.carreras: ~2 rows (aproximadamente)
INSERT INTO `carreras` (`ID_CARRERA`, `TITULO_ABREVIADO`, `DESCRIPCION`) VALUES
	(1, 'TSAS', 'Tecnicatura Superior en Análisis de Sistemas'),
	(2, 'TSCIA', 'Tecnicatura Superior en Ciencia de Datos  e Inteligencia Artificial');

-- Volcando estructura para tabla db_sae.clases
CREATE TABLE IF NOT EXISTS `clases` (
  `ID_CLASE` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO_USUARIO` int(11) NOT NULL,
  `CODIGO_MATERIA` int(11) NOT NULL,
  `FECHA` date NOT NULL,
  `CODIGO_HORARIO` int(11) NOT NULL,
  `HORA_INICIO` time NOT NULL,
  `HORA_FIN` time NOT NULL,
  `TEMAS` varchar(50) DEFAULT NULL,
  `NOVEDADES` varchar(50) DEFAULT NULL,
  `CODIGO_COMISION` int(11) NOT NULL,
  `AULA` varchar(20) NOT NULL,
  `ARCHIVOS` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`ID_CLASE`),
  KEY `CODIGO_USUARIO` (`CODIGO_USUARIO`),
  KEY `CODIGO_MATERIA` (`CODIGO_MATERIA`),
  KEY `CODIGO_HORARIO` (`CODIGO_HORARIO`,`CODIGO_COMISION`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.clases: ~95 rows (aproximadamente)
INSERT INTO `clases` (`ID_CLASE`, `CODIGO_USUARIO`, `CODIGO_MATERIA`, `FECHA`, `CODIGO_HORARIO`, `HORA_INICIO`, `HORA_FIN`, `TEMAS`, `NOVEDADES`, `CODIGO_COMISION`, `AULA`, `ARCHIVOS`) VALUES
	(7, 112, 11101, '2024-03-15', 3, '07:50:00', '11:45:00', 'Presentación del curso y objetivos', 'Inicio de clases', 1, '21', ''),
	(8, 112, 11101, '2024-03-22', 3, '08:00:00', '11:50:00', 'Saludos y presentaciones en inglés', '', 1, '21', 'ApunteIngles.pdf'),
	(9, 112, 11101, '2024-03-29', 3, '07:55:00', '12:10:00', 'Verbos básicos y construcción de oraciones', 'Entrega de tarea 1', 1, '21', ''),
	(10, 112, 11101, '2024-04-05', 3, '08:10:00', '12:00:00', 'Tiempo presente simple', NULL, 1, '21', ''),
	(11, 112, 11101, '2024-04-12', 3, '07:45:00', '11:55:00', 'Uso de pronombres personales', 'Revisión de tarea anterior', 1, '21', ''),
	(12, 112, 11101, '2024-04-19', 3, '08:05:00', '11:40:00', 'Verbos irregulares más comunes', NULL, 1, '21', ''),
	(13, 112, 11101, '2024-04-26', 3, '08:15:00', '12:05:00', 'Formación de preguntas en inglés', 'Examen parcial la próxima clase', 1, '21', ''),
	(14, 112, 11101, '2024-05-03', 3, '07:50:00', '11:35:00', 'Tiempo pasado simple', NULL, 1, '21', ''),
	(15, 112, 11101, '2024-05-10', 3, '08:20:00', '12:15:00', 'Conectores y cohesión en el discurso', 'Entrega de trabajo escrito', 1, '21', ''),
	(16, 112, 11101, '2024-05-17', 3, '08:00:00', '11:45:00', 'Tiempo futuro con will y going to', NULL, 1, '21', ''),
	(17, 112, 11101, '2024-05-24', 3, '07:55:00', '12:00:00', 'Expresiones idiomáticas básicas', '', 1, '21', 'Ingles.pdf'),
	(18, 112, 11101, '2024-05-31', 3, '08:10:00', '11:50:00', 'Comprensión lectora: textos cortos', 'Revisión de avances en escritura', 1, '21', ''),
	(19, 112, 11101, '2024-06-07', 3, '07:45:00', '11:30:00', 'Uso de adjetivos y comparaciones', NULL, 1, '21', ''),
	(20, 112, 11101, '2024-06-14', 3, '08:00:00', '12:05:00', 'Diálogos en inglés: simulaciones de conversación', 'Anuncio de evaluación final', 1, '21', ''),
	(22, 109, 11107, '2024-03-16', 3, '07:50:00', '11:40:00', 'Introducción a la Arquitectura de Computadores', 'Inicio de curso', 1, '22', 'introduccion_informatica.pdf'),
	(23, 109, 11107, '2024-03-23', 3, '08:00:00', '11:50:00', 'Componentes de una computadora', '', 1, '22', 'Hardware.pdf'),
	(24, 109, 11107, '2024-03-30', 3, '07:55:00', '12:10:00', 'CPU y Memoria', 'Entrega de tarea 1', 1, '22', ''),
	(25, 109, 11107, '2024-04-06', 3, '08:10:00', '12:00:00', 'Sistemas de almacenamiento', NULL, 1, '22', ''),
	(26, 109, 11107, '2024-04-13', 3, '07:45:00', '11:55:00', 'Procesamiento de datos y buses', 'Revisión de tarea anterior', 1, '22', ''),
	(27, 109, 11107, '2024-04-20', 3, '08:05:00', '11:35:00', 'Arquitectura de Von Neumann', NULL, 1, '22', ''),
	(28, 109, 11107, '2024-04-27', 3, '08:15:00', '12:05:00', 'Estructura de la memoria caché', 'Examen parcial la próxima clase', 1, '22', ''),
	(29, 109, 11107, '2024-05-04', 3, '07:50:00', '11:45:00', 'Jerarquía de memoria', NULL, 1, '22', ''),
	(30, 109, 11107, '2024-05-11', 3, '08:20:00', '12:15:00', 'Dispositivos de Entrada/Salida', 'Entrega de trabajo práctico', 1, '22', 'Perifericos.pdf'),
	(31, 109, 11107, '2024-05-18', 3, '08:00:00', '11:50:00', 'Modos de direccionamiento', NULL, 1, '22', ''),
	(32, 109, 11107, '2024-05-25', 3, '07:55:00', '12:00:00', 'Procesadores multinúcleo', NULL, 1, '22', ''),
	(33, 109, 11107, '2024-06-01', 3, '08:10:00', '11:50:00', 'Ejecución de instrucciones', 'Revisión de avances en prácticas', 1, '22', ''),
	(34, 109, 11107, '2024-06-08', 3, '07:45:00', '11:30:00', 'Pipelines y segmentación', NULL, 1, '22', ''),
	(35, 109, 11107, '2024-06-15', 3, '08:00:00', '12:05:00', 'Arquitectura RISC vs CISC', 'Anuncio de evaluación final', 1, '22', ''),
	(36, 109, 11107, '2024-06-22', 3, '08:15:00', '11:40:00', 'Repaso general y simulaciones de examen', NULL, 1, '22', ''),
	(37, 110, 11106, '2024-03-17', 2, '09:50:00', '11:30:00', 'Introducción a los Sistemas y Organizaciones', 'Inicio de curso', 1, '31', ''),
	(38, 110, 11106, '2024-03-24', 2, '10:10:00', '12:10:00', 'Estructura organizacional', NULL, 1, '31', ''),
	(39, 110, 11106, '2024-03-31', 2, '10:05:00', '12:00:00', 'Procesos administrativos', 'Entrega de tarea 1', 1, '31', ''),
	(40, 110, 11106, '2024-04-07', 2, '09:55:00', '11:50:00', 'Sistemas de Información', '', 1, '31', 'seguridad_informatica.pdf'),
	(41, 110, 11106, '2024-04-14', 2, '10:15:00', '12:05:00', 'Modelos de gestión organizacional', 'Revisión de tarea anterior', 1, '31', ''),
	(42, 110, 11106, '2024-04-21', 2, '09:50:00', '11:45:00', 'TICs en la empresa', NULL, 1, '31', ''),
	(43, 110, 11106, '2024-04-28', 2, '10:00:00', '12:20:00', 'Planificación estratégica', 'Examen parcial la próxima clase', 1, '31', ''),
	(44, 110, 11106, '2024-05-05', 2, '10:20:00', '11:50:00', 'Gestión de proyectos', NULL, 1, '31', ''),
	(45, 110, 11106, '2024-05-12', 2, '10:00:00', '12:00:00', 'Sistemas de calidad en la empresa', 'Entrega de trabajo práctico', 1, '31', ''),
	(46, 110, 11106, '2024-05-19', 2, '09:55:00', '11:40:00', 'Reingeniería de procesos', NULL, 1, '31', ''),
	(47, 110, 11106, '2024-05-26', 2, '10:10:00', '12:15:00', 'Estructura de datos organizacionales', NULL, 1, '31', ''),
	(48, 110, 11106, '2024-06-02', 2, '09:50:00', '11:35:00', 'Gestión del cambio', 'Revisión de avances en prácticas', 1, '31', ''),
	(49, 110, 11106, '2024-06-09', 2, '10:05:00', '12:05:00', 'Innovación en las empresas', NULL, 1, '31', ''),
	(50, 110, 11106, '2024-06-16', 2, '10:15:00', '12:10:00', 'Arquitectura empresarial', 'Anuncio de evaluación final', 1, '31', ''),
	(51, 110, 11106, '2024-06-23', 2, '09:55:00', '11:50:00', 'Repaso general y simulaciones de examen', NULL, 1, '31', ''),
	(52, 111, 11105, '2024-03-14', 3, '07:50:00', '11:30:00', 'Introducción a los Algoritmos', 'Inicio del curso', 1, '54', ''),
	(53, 111, 11105, '2024-03-21', 3, '08:00:00', '12:00:00', 'Variables y Tipos de Datos', NULL, 1, '54', ''),
	(54, 111, 11105, '2024-03-28', 3, '08:10:00', '11:45:00', 'Estructuras de Control', 'Entrega de primer trabajo práctico', 1, '54', 'software.pdf'),
	(55, 111, 11105, '2024-04-04', 3, '07:55:00', '12:10:00', 'Ciclos e Iteraciones', NULL, 1, '54', ''),
	(56, 111, 11105, '2024-04-11', 3, '08:05:00', '12:20:00', 'Funciones y Procedimientos', 'Revisión de ejercicios previos', 1, '54', ''),
	(57, 111, 11105, '2024-04-18', 3, '07:50:00', '11:50:00', 'Arreglos y Listas', NULL, 1, '54', ''),
	(58, 111, 11105, '2024-04-25', 3, '08:15:00', '12:10:00', 'Pilas y Colas', 'Examen parcial anunciado', 1, '54', ''),
	(59, 111, 11105, '2024-05-02', 3, '08:00:00', '11:40:00', 'Recursividad', NULL, 1, '54', ''),
	(60, 111, 11105, '2024-05-09', 3, '07:55:00', '11:50:00', 'Ordenamiento y Búsqueda', 'Entrega de segundo trabajo práctico', 1, '54', ''),
	(61, 111, 11105, '2024-05-16', 3, '08:10:00', '12:00:00', 'Grafos y Árboles', NULL, 1, '54', ''),
	(62, 111, 11105, '2024-05-23', 3, '08:05:00', '12:15:00', 'Estructuras de Datos Complejas', NULL, 1, '54', ''),
	(63, 111, 11105, '2024-05-30', 3, '07:50:00', '11:35:00', 'Análisis de Algoritmos', 'Simulación de evaluación final', 1, '54', ''),
	(64, 111, 11105, '2024-06-06', 3, '08:15:00', '12:05:00', 'Optimización de Algoritmos', '', 1, '54', 'excepciones.pdf'),
	(65, 111, 11105, '2024-06-13', 3, '08:00:00', '12:10:00', 'Aplicaciones Reales de Algoritmos', 'Anuncio de evaluación final', 1, '54', ''),
	(66, 111, 11105, '2024-06-20', 3, '07:55:00', '11:50:00', 'Repaso general y resolución de dudas', NULL, 1, '54', ''),
	(67, 113, 11102, '2024-03-17', 1, '07:50:00', '09:30:00', 'Introducción a Ciencia, Tecnología y Sociedad', 'Presentación del curso', 1, '21', ''),
	(68, 113, 11102, '2024-03-24', 1, '08:00:00', '09:50:00', 'Impacto de la tecnología en la sociedad', NULL, 1, '21', ''),
	(69, 113, 11102, '2024-03-31', 1, '08:10:00', '10:00:00', 'Revolución Industrial y avances tecnológicos', 'Entrega de primer ensayo', 1, '21', 'sistemas_operativos.pptx'),
	(70, 113, 11102, '2024-04-07', 1, '07:55:00', '09:40:00', 'Ética y responsabilidad en el desarrollo tecnológi', NULL, 1, '21', ''),
	(71, 113, 11102, '2024-04-14', 1, '08:05:00', '10:10:00', 'Sociedad de la Información y el Conocimiento', 'Análisis de casos', 1, '21', ''),
	(72, 113, 11102, '2024-04-21', 1, '07:50:00', '09:50:00', 'Globalización y desarrollo tecnológico', NULL, 1, '21', ''),
	(73, 113, 11102, '2024-04-28', 1, '08:15:00', '09:45:00', 'Tecnología y cambio social', 'Debate en clase', 1, '21', ''),
	(74, 113, 11102, '2024-05-05', 1, '08:00:00', '09:30:00', 'Innovaciones y su impacto en la educación', NULL, 1, '21', ''),
	(75, 113, 11102, '2024-05-12', 1, '07:55:00', '09:40:00', 'Brecha digital y acceso a la tecnología', 'Entrega de trabajo grupal', 1, '21', ''),
	(76, 113, 11102, '2024-05-19', 1, '08:10:00', '09:50:00', 'Redes sociales y su influencia en la sociedad', NULL, 1, '21', ''),
	(77, 113, 11102, '2024-05-26', 1, '08:05:00', '09:45:00', 'Impacto ambiental de la tecnología', '', 1, '21', 'tecnologia.pdf'),
	(78, 113, 11102, '2024-06-02', 1, '07:50:00', '09:30:00', 'Bioética y avances en biotecnología', 'Discusión sobre dilemas éticos', 1, '21', ''),
	(79, 113, 11102, '2024-06-09', 1, '08:15:00', '09:50:00', 'Inteligencia Artificial y automatización', NULL, 1, '21', ''),
	(80, 113, 11102, '2024-06-16', 1, '08:00:00', '10:00:00', 'Tecnología en la medicina y su impacto social', 'Invitado especial en clase', 1, '21', ''),
	(81, 113, 11102, '2024-06-23', 1, '07:55:00', '09:40:00', 'Evaluación final y repaso general', NULL, 1, '21', ''),
	(82, 114, 11103, '2024-03-13', 3, '07:50:00', '11:35:00', 'Introducción al Análisis Matemático', 'Presentación del curso', 1, '22', ''),
	(83, 114, 11103, '2024-03-20', 3, '08:00:00', '12:00:00', 'Funciones y gráficos', NULL, 1, '22', ''),
	(84, 114, 11103, '2024-03-27', 3, '08:10:00', '11:50:00', 'Límites y continuidad', 'Entrega de ejercicios', 1, '22', ''),
	(85, 114, 11103, '2024-04-03', 3, '07:55:00', '12:10:00', 'Derivadas y aplicaciones', NULL, 1, '22', ''),
	(86, 114, 11103, '2024-04-10', 3, '08:05:00', '11:40:00', 'Teorema del valor medio', 'Análisis de problemas', 1, '22', ''),
	(87, 114, 11103, '2024-04-17', 3, '07:50:00', '12:15:00', 'Integrales definidas e indefinidas', NULL, 1, '22', ''),
	(88, 114, 11103, '2024-04-24', 3, '08:15:00', '11:45:00', 'Métodos de integración', 'Discusión de ejemplos', 1, '22', ''),
	(89, 114, 11103, '2024-05-01', 3, '08:00:00', '11:30:00', 'Series y sucesiones', NULL, 1, '22', ''),
	(90, 114, 11103, '2024-05-08', 3, '07:55:00', '12:00:00', 'Ecuaciones diferenciales', 'Entrega de trabajo práctico', 1, '22', ''),
	(91, 114, 11103, '2024-05-15', 3, '08:10:00', '11:50:00', 'Análisis de Fourier', NULL, 1, '22', ''),
	(92, 114, 11103, '2024-05-22', 3, '08:05:00', '12:10:00', 'Matrices y determinantes', NULL, 1, '22', ''),
	(93, 114, 11103, '2024-05-29', 3, '07:50:00', '11:30:00', 'Sistemas de ecuaciones lineales', 'Resolución de ejercicios', 1, '22', ''),
	(94, 114, 11103, '2024-06-05', 3, '08:15:00', '11:50:00', 'Aplicaciones de la derivada e integral', NULL, 1, '22', ''),
	(95, 114, 11103, '2024-06-12', 3, '08:00:00', '12:20:00', 'Modelos matemáticos en sistemas', 'Invitado especial', 1, '22', ''),
	(97, 114, 11103, '2024-06-19', 3, '08:05:00', '11:50:00', 'Modelo Parcial', '', 1, '22', ''),
	(98, 112, 11101, '2024-06-21', 3, '08:05:00', '11:20:00', 'Modelo de examen', '', 1, '21', ''),
	(99, 1001, 11105, '2025-07-14', 3, '08:00:00', '12:10:00', 'Bucle Forr', 'Sin novedades', 2, '22', ''),
	(100, 109, 11101, '2025-08-15', 1, '08:00:00', '10:00:00', 'Present Perfect vs. Past Simple', 'Se hará un quiz sobre el uso de los tiempos verbal', 1, '22', 'ApunteIngles.pdf'),
	(101, 109, 11101, '2025-08-22', 1, '08:00:00', '10:00:00', 'Modal verbs: Can, Could, May, Might', 'Se asignará práctica de audios para mejorar la com', 1, '22', ''),
	(102, 109, 11101, '2025-08-29', 1, '08:00:00', '10:00:00', 'Future Simple: Will vs. Going to', 'Revisión de las diferencias entre \'will\' y \'going ', 1, '22', ''),
	(103, 109, 11101, '2025-09-05', 1, '08:00:00', '10:00:00', 'Adjectives: Comparative and Superlative', 'Se asignará tarea para practicar comparativos y su', 1, '22', '');

-- Volcando estructura para tabla db_sae.comisiones
CREATE TABLE IF NOT EXISTS `comisiones` (
  `CODIGO_COMISION` int(11) NOT NULL,
  `DESCRIPCION` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`CODIGO_COMISION`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.comisiones: ~8 rows (aproximadamente)
INSERT INTO `comisiones` (`CODIGO_COMISION`, `DESCRIPCION`) VALUES
	(1, '1° 1°'),
	(2, '1° 2°'),
	(3, '1° 3°'),
	(4, '2° 1°'),
	(5, '2° 2°'),
	(6, '2° 3°'),
	(7, '3° 1°'),
	(8, '3° 2°');

-- Volcando estructura para tabla db_sae.horarios
CREATE TABLE IF NOT EXISTS `horarios` (
  `CODIGO_HORARIO` int(11) NOT NULL,
  `HORARIO` varchar(20) NOT NULL,
  `CODIGO_TURNO` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_HORARIO`),
  KEY `CODIGO_TURNO` (`CODIGO_TURNO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.horarios: ~7 rows (aproximadamente)
INSERT INTO `horarios` (`CODIGO_HORARIO`, `HORARIO`, `CODIGO_TURNO`) VALUES
	(1, '08:00 - 10:00', 1),
	(2, '10:10 - 12:10', 1),
	(3, '08:00 - 12:10', 1),
	(4, '13:00 - 17:00', 2),
	(5, '18:00 - 20:00', 3),
	(6, '20:10 - 22:10', 3),
	(7, '18:00 - 22:10', 3);

-- Volcando estructura para tabla db_sae.materias
CREATE TABLE IF NOT EXISTS `materias` (
  `CODIGO_MATERIA` int(11) NOT NULL,
  `NOMBRE_MATERIA` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `ID_CARRERA` int(11) NOT NULL,
  PRIMARY KEY (`CODIGO_MATERIA`),
  KEY `ID_CARRERA` (`ID_CARRERA`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.materias: ~30 rows (aproximadamente)
INSERT INTO `materias` (`CODIGO_MATERIA`, `NOMBRE_MATERIA`, `ID_CARRERA`) VALUES
	(11101, 'Inglés I', 1),
	(11102, 'Ciencia Tecnología y Sociedad', 1),
	(11103, 'Análisis Matemático I', 1),
	(11104, 'Algebra', 1),
	(11105, 'Algoritmos y estructuras de datos I', 1),
	(11106, 'Sistemas y Organizaciones', 1),
	(11107, 'Arquitectura de Computadores', 1),
	(11108, 'Prácticas Profesionalizantes I', 1),
	(12101, 'Inglés II', 1),
	(12102, 'Análisis Matemático II', 1),
	(12103, 'Estadística', 1),
	(12104, 'Ingeniería de Software I', 1),
	(12105, 'Algoritmos y estructuras de datos II', 1),
	(12106, 'Sistemas Operativos', 1),
	(12107, 'Base de Datos', 1),
	(12108, 'Prácticas Profesionalizantes II', 1),
	(13101, 'Inglés III', 1),
	(13102, 'Aspectos legales de la Profesión', 1),
	(13103, 'Seminario de actualización', 1),
	(13104, 'Redes y Comunicaciones', 1),
	(13105, 'Ingeniería de Software II', 1),
	(13106, 'Algoritmos y estructuras de datos III', 1),
	(13107, 'Prácticas Profesionalizantes III', 1),
	(21101, 'Ingles para Ciencia de Datas e IA 1', 2),
	(21102, 'Estadística y Probabilidades para Gestión de Datos', 2),
	(21103, 'Lógica Computacional', 2),
	(21104, 'Elementos de Análisis Matemático', 2),
	(21105, 'Administración y Gestión de Base de Datos', 2),
	(21106, 'Técnicas de Programación', 2),
	(21107, 'PP1: Aproximación al Campo Laboral', 2);

-- Volcando estructura para tabla db_sae.modulos
CREATE TABLE IF NOT EXISTS `modulos` (
  `CODIGO_MODULO` int(11) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  PRIMARY KEY (`CODIGO_MODULO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.modulos: ~2 rows (aproximadamente)
INSERT INTO `modulos` (`CODIGO_MODULO`, `DESCRIPCION`) VALUES
	(1, 'Gestión de Usuarios'),
	(2, 'Gestión de Clases');

-- Volcando estructura para tabla db_sae.personas
CREATE TABLE IF NOT EXISTS `personas` (
  `DNI_PERSONA` int(11) NOT NULL,
  `NOMBRE_PERSONA` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `APELLIDO_PERSONA` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `CODIGO_ROL` int(11) NOT NULL,
  PRIMARY KEY (`DNI_PERSONA`),
  UNIQUE KEY `DNI_PERSONA` (`DNI_PERSONA`),
  KEY `CODIGO_ROL` (`CODIGO_ROL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.personas: ~46 rows (aproximadamente)
INSERT INTO `personas` (`DNI_PERSONA`, `NOMBRE_PERSONA`, `APELLIDO_PERSONA`, `CODIGO_ROL`) VALUES
	(10000000, 'Admin', 'Sup', 3),
	(10000001, 'Juan', 'Pérez', 1),
	(10000002, 'María Luisa', 'López', 2),
	(10000003, 'Carlos', 'Gómez', 2),
	(10000004, 'Ana Laura', 'Martínez', 2),
	(10000005, 'Pedro', 'Hernández', 2),
	(10000006, 'Lucía', 'Jiménez', 2),
	(10000007, 'Javier', 'Ramírez', 2),
	(10000008, 'Ana Laura', 'Fernández', 2),
	(10000009, 'Miguel', 'Rodríguez Diez', 2),
	(10000010, 'Sofía', 'García', 2),
	(10000011, 'Diego', 'Mendoza', 2),
	(10000012, 'Valeria', 'Torres', 2),
	(10000013, 'Fernando', 'Castro', 2),
	(10000014, 'Julia', 'Morales', 2),
	(10000015, 'Pablo', 'Sánchez', 2),
	(10000016, 'Clara', 'Ortiz', 2),
	(10000017, 'Francisco', 'Pérez', 2),
	(10000018, 'Andrea', 'Silva', 2),
	(10000019, 'Daniel', 'Rojas', 2),
	(10000020, 'Gabriela', 'Flores', 2),
	(10000021, 'Hugo', 'Chávez', 2),
	(10000022, 'Carolina', 'Vega', 2),
	(10000023, 'Roberto', 'Álvarez', 2),
	(10000024, 'Verónica', 'Cruz', 2),
	(10000025, 'Santiago', 'Ramos', 2),
	(10000026, 'Patricia', 'Gonzáles', 2),
	(10000027, 'Alejandro', 'Paredes', 2),
	(10000028, 'Isabel', 'Navarro', 2),
	(10000029, 'Mauricio', 'Luna', 2),
	(10000030, 'Mónica', 'Campos', 2),
	(10000031, 'Rafael', 'Domínguez', 2),
	(10000032, 'Liliana', 'Peña', 2),
	(10000033, 'Esteban', 'Salazar', 2),
	(10000034, 'Emiliano', 'González', 2),
	(10000035, 'Valentina', 'Sánchez', 2),
	(10000036, 'Martín', 'Pérez', 2),
	(10000037, 'Camila', 'Gómez', 2),
	(10000038, 'Sebastián', 'Fernández', 2),
	(10000039, 'Natalia', 'Ramírez', 2),
	(10000040, 'Federico', 'Domínguez', 2),
	(10000041, 'Sofía', 'López', 2),
	(10000042, 'Agustín', 'Martínez', 2),
	(10000043, 'Lionel', 'Messi', 2),
	(10000044, 'Kiko', 'Ñoño', 2),
	(10000045, 'Cristiano', 'Ronaldo', 2);

-- Volcando estructura para tabla db_sae.personas_carreras_materias
CREATE TABLE IF NOT EXISTS `personas_carreras_materias` (
  `DNI_PERSONA` int(11) NOT NULL,
  `ID_CARRERA` int(11) NOT NULL,
  `CODIGO_MATERIA` int(11) DEFAULT NULL,
  `CODIGO_COMISION` int(11) DEFAULT NULL,
  `CODIGO_HORARIO` int(11) DEFAULT NULL,
  KEY `DNI_PERSONA` (`DNI_PERSONA`,`ID_CARRERA`,`CODIGO_MATERIA`),
  KEY `CODIGO_COMISION` (`CODIGO_COMISION`),
  KEY `CODIGO_HORARIO` (`CODIGO_HORARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.personas_carreras_materias: ~98 rows (aproximadamente)
INSERT INTO `personas_carreras_materias` (`DNI_PERSONA`, `ID_CARRERA`, `CODIGO_MATERIA`, `CODIGO_COMISION`, `CODIGO_HORARIO`) VALUES
	(10000042, 1, 11101, 1, 3),
	(10000003, 1, 11101, 3, 3),
	(10000005, 1, 11101, 2, 7),
	(10000006, 1, 11101, 3, 7),
	(10000013, 1, 11103, 1, 3),
	(10000014, 1, 11103, 2, 3),
	(10000015, 1, 11103, 3, 3),
	(10000016, 1, 11103, 1, 7),
	(10000017, 1, 11103, 2, 7),
	(10000018, 1, 11103, 3, 7),
	(10000020, 1, 11104, 2, 5),
	(10000022, 1, 11104, 1, 6),
	(10000024, 1, 11104, 3, 6),
	(10000025, 1, 11105, 1, 3),
	(10000027, 1, 11105, 3, 3),
	(10000028, 1, 11105, 1, 7),
	(10000029, 1, 11105, 2, 7),
	(10000030, 1, 11105, 3, 7),
	(10000034, 1, 11106, 1, 2),
	(10000035, 1, 11106, 2, 2),
	(10000036, 1, 11106, 3, 2),
	(10000038, 1, 11107, 2, 3),
	(10000039, 1, 11107, 3, 3),
	(10000040, 1, 11107, 1, 7),
	(10000041, 1, 11107, 2, 7),
	(10000042, 1, 11107, 3, 7),
	(10000006, 1, 11108, 2, 6),
	(10000042, 1, 12101, 4, 3),
	(10000003, 1, 12101, 4, 7),
	(10000005, 1, 12102, 4, 1),
	(10000010, 1, 12103, 5, 3),
	(10000011, 1, 12103, 4, 7),
	(10000012, 1, 12103, 5, 7),
	(10000013, 1, 12104, 4, 5),
	(10000015, 1, 12104, 4, 6),
	(10000016, 1, 12104, 5, 6),
	(10000017, 1, 12105, 4, 3),
	(10000018, 1, 12105, 5, 3),
	(10000019, 1, 12105, 4, 7),
	(10000020, 1, 12105, 5, 7),
	(10000021, 1, 12106, 4, 1),
	(10000022, 1, 12106, 5, 1),
	(10000023, 1, 12106, 4, 2),
	(10000025, 1, 12107, 4, 3),
	(10000027, 1, 12107, 4, 7),
	(10000028, 1, 12107, 5, 7),
	(10000029, 1, 12108, 4, 5),
	(10000030, 1, 12108, 5, 5),
	(10000031, 1, 12108, 4, 6),
	(10000034, 1, 13101, 8, 3),
	(10000035, 1, 13101, 7, 7),
	(10000036, 1, 13101, 8, 7),
	(10000042, 1, 13102, 7, 2),
	(10000003, 1, 13103, 7, 3),
	(10000005, 1, 13103, 7, 7),
	(10000006, 1, 13103, 8, 7),
	(10000010, 1, 13104, 8, 6),
	(10000011, 1, 13105, 7, 3),
	(10000012, 1, 13105, 8, 3),
	(10000013, 1, 13105, 7, 7),
	(10000014, 1, 13105, 8, 7),
	(10000015, 1, 13106, 7, 1),
	(10000016, 1, 13106, 8, 1),
	(10000019, 1, 13107, 7, 3),
	(10000020, 1, 13107, 8, 3),
	(10000021, 1, 13107, 7, 7),
	(10000022, 1, 13107, 8, 7),
	(10000004, 1, 11101, 1, 7),
	(10000004, 1, 11108, 3, 5),
	(10000004, 1, 12101, 5, 7),
	(10000004, 1, 13103, 8, 3),
	(10000008, 1, 11102, 2, 1),
	(10000008, 1, 12102, 5, 2),
	(10000008, 1, 13102, 8, 5),
	(10000002, 1, 11101, 2, 3),
	(10000002, 1, 11108, 1, 5),
	(10000002, 1, 12101, 5, 3),
	(10000002, 1, 13102, 8, 2),
	(10000026, 1, 11105, 2, 3),
	(10000026, 1, 12107, 5, 3),
	(10000026, 2, 11109, 2, 3),
	(10000043, 2, 21101, 2, 4),
	(10000009, 1, 11104, 1, 5),
	(10000009, 1, 12102, 4, 1),
	(10000033, 1, 13101, 8, 5),
	(10000033, 1, 13101, 7, 3),
	(10000001, 2, NULL, NULL, NULL),
	(10000001, 1, NULL, NULL, NULL),
	(10000045, 1, 11101, 2, 1),
	(10000045, 2, 21101, 1, 2),
	(10000045, 2, 21103, 1, 1),
	(10000007, 1, 11102, 1, 1),
	(10000007, 1, 13102, 4, 5),
	(10000037, 1, 13101, 7, 2),
	(10000037, 1, 11101, 1, 1),
	(10000037, 1, 12101, 4, 2),
	(10000037, 2, 21101, 3, 5);

-- Volcando estructura para tabla db_sae.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `CODIGO_ROL` int(11) NOT NULL,
  `DESCRIPCION` text NOT NULL,
  PRIMARY KEY (`CODIGO_ROL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`CODIGO_ROL`, `DESCRIPCION`) VALUES
	(1, 'Jefe de área'),
	(2, 'Profesor'),
	(3, 'Administrador');

-- Volcando estructura para tabla db_sae.rolxmod
CREATE TABLE IF NOT EXISTS `rolxmod` (
  `CODIGO_ROL` int(11) NOT NULL,
  `CODIGO_MODULO` int(11) NOT NULL,
  KEY `CODIGO_ROL` (`CODIGO_ROL`,`CODIGO_MODULO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.rolxmod: ~4 rows (aproximadamente)
INSERT INTO `rolxmod` (`CODIGO_ROL`, `CODIGO_MODULO`) VALUES
	(1, 1),
	(1, 2),
	(2, 2),
	(3, 2);

-- Volcando estructura para tabla db_sae.turnos
CREATE TABLE IF NOT EXISTS `turnos` (
  `CODIGO_TURNO` int(11) NOT NULL,
  `DESCRIPCION` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`CODIGO_TURNO`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.turnos: ~3 rows (aproximadamente)
INSERT INTO `turnos` (`CODIGO_TURNO`, `DESCRIPCION`) VALUES
	(1, 'Mañana'),
	(2, 'Tarde'),
	(3, 'Noche');

-- Volcando estructura para tabla db_sae.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `CODIGO_USUARIO` int(11) NOT NULL AUTO_INCREMENT,
  `DNI_PERSONA` int(11) NOT NULL,
  `MAIL_USUARIO` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `CONTRASEÑA_USUARIO` varchar(255) DEFAULT NULL,
  `TOKEN_RECUPERACION` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CODIGO_USUARIO`),
  KEY `DNI_PERSONA` (`DNI_PERSONA`)
) ENGINE=InnoDB AUTO_INCREMENT=1004 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.usuarios: ~8 rows (aproximadamente)
INSERT INTO `usuarios` (`CODIGO_USUARIO`, `DNI_PERSONA`, `MAIL_USUARIO`, `CONTRASEÑA_USUARIO`, `TOKEN_RECUPERACION`) VALUES
	(1, 10000000, 'adminsup@itbeltran.com', '$2y$10$mHLgpeHljBFg2LxlAQqcjOPIbhH3ChlCKh0D1uqcwNF0MvwGRBJBW', NULL),
	(2, 10000001, 'admin@itbeltran.com', '$2y$10$4bhMH8bH2CcRujXjHqjOseLVYZ3wNeowKtvB2BlmhZsjVPjAo7TWe', NULL),
	(33, 10000033, 'saeusuario@gmail.com', '$2y$10$6xq7IDiKRqHgQEpStEZIz.O0RQads6luupDte2Kvj2BnExSioQkDC', NULL),
	(109, 10000037, 'gomezc@itbeltran.com', '$2y$10$aFGhjVpvYyk9aeIJY3DgAOmxGuTx/wecrp6Ka90L6gwX3XzSSixs.', NULL),
	(110, 10000034, 'gonzaleze@itbeltran.com', '$2y$10$1VrSWL4OQDK.DR3bYbCajOqbXVxXInPUYOMCGcAToIg.dGwpjl6D2', NULL),
	(111, 10000025, 'ramoss@itbeltran.com', '$2y$10$NFr9Q3zVifFeaQ1ik74XHOqzTlf9DGtMqLCFqYeMx0JPLJgxa2LkO', NULL),
	(112, 10000042, 'martineza@itbeltran.com', '$2y$10$ZBIv5KOPn2152UeJACqR5OdzBp27r4b85/OzOfarzaIPpelxUFrRC', NULL),
	(113, 10000007, 'ramirezj@itbeltran.com', '$2y$10$IuKw6JDT/CHhoRZBhwK6oOd0lkAUa96y2/KKI4uO.RLLCtY.5ckQ6', NULL),
	(114, 10000013, 'castrof@itbeltran.com', '$2y$10$tYaWsx1HywB45wsMkbg1O.nnCEX/096GLh/LUEBcln1JsAlfJMMnq', NULL),
	(1001, 10000026, 'patrigonzales@itbeltran.com', '$2y$10$fLIqqXNdHXgdjW2rzo5Wx.HAClm67MErsUSbpSMVCs2W3DTLNLi9G', NULL),
	(1003, 10000045, 'ronaldo@itbeltran.com', '$2y$10$P5kesLjSo2wOkivgy60uQepH2RATqhp9all2HFh9UrZzET72KcK.K', NULL);

-- Volcando estructura para tabla db_sae.usuxrol
CREATE TABLE IF NOT EXISTS `usuxrol` (
  `CODIGO_USUARIO` int(11) NOT NULL,
  `CODIGO_ROL` int(11) NOT NULL,
  KEY `CODIGO_USUARIO` (`CODIGO_USUARIO`),
  KEY `CODIGO_ROL` (`CODIGO_ROL`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla db_sae.usuxrol: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

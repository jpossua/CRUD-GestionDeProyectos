-- ============================================
-- BASE DE DATOS: gestion_proyectos
-- ============================================
-- Base de datos secundaria para la gestión de proyectos.
-- Separada de 'login-php' para modularidad y seguridad.
-- ============================================

-- ============================================
-- 1. CREACIÓN DE LA BASE DE DATOS
-- ============================================

DROP DATABASE IF EXISTS `gestion_proyectos`;
CREATE DATABASE IF NOT EXISTS `gestion_proyectos` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;

USE `gestion_proyectos`;

-- ============================================
-- 2. ESTRUCTURA DE TABLAS
-- ============================================

-- --------------------------------------------------------
-- TABLA: proyectos
-- --------------------------------------------------------
-- Almacena la información de los proyectos gestionados.
-- --------------------------------------------------------

CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `lider` varchar(100) NOT NULL,
  `presupuesto` decimal(10,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `finalizado` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- 3. VOLCADO DE DATOS (Demos)
-- ============================================

INSERT INTO `proyectos` (`nombre`, `descripcion`, `lider`, `presupuesto`, `fecha_inicio`, `fecha_fin`, `finalizado`) VALUES
('Proyecto Alpha', 'Desarrollo de nueva API RESTful para clientes externos.', 'Juan Pérez', 5000.00, '2023-01-01', '2023-06-01', 0),
('Web Corporativa', 'Rediseño completo del sitio web corporativo con focus en Mobile First.', 'Ana Gómez', 3000.00, '2023-02-15', '2023-05-30', 1),
('Migración Cloud', 'Migración de servidores on-premise a AWS.', 'Carlos Ruiz', 12000.50, '2023-03-10', '2023-09-20', 0);

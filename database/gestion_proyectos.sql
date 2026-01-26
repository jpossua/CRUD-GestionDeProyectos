-- ============================================
-- BASE DE DATOS: gestion_proyectos
-- ============================================
-- Base de datos unificada para el sistema de Gestión de Proyectos.
-- Incluye tablas de usuarios (Login) y proyectos.
-- ============================================

-- ============================================
-- 1. CREACIÓN DE LA BASE DE DATOS
-- ============================================

DROP DATABASE IF EXISTS `gestion_proyectos`;
CREATE DATABASE IF NOT EXISTS `gestion_proyectos` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_spanish_ci;

USE `gestion_proyectos`;

-- ============================================
-- 2. ESTRUCTURA DE TABLAS
-- ============================================

-- --------------------------------------------------------
-- TABLA: usuarios
-- --------------------------------------------------------
CREATE TABLE `usuarios` (
  `codUser` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`codUser`),
  UNIQUE KEY `idUser` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- TABLA: proyectos
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

-- Usuarios
INSERT INTO `usuarios` (`codUser`, `idUser`, `password`, `nombre`, `apellidos`, `rol`) VALUES
(1, 'JoseManu', '$2y$10$hfK0TnWWEhusLk0czNNPR.ocWwFLxaHrubWcKtMDylxCj8EGScLLS', 'Jose', 'Postigo', 'admin'),
(2, 'Anonline', '$2y$10$i1M3yzx1TmDpChMbPsILL.1N7.a9ApzWd2/ItGt0LSFgivyAwS66G', 'Ana', 'Ponce', 'user'),
(3, 'PilarAdmin', '$2y$12$epn6afL64tVMtwjct5hTr.8iyuChSJVmgMHMdQdU9IFaKcT1FCK4C', 'Pilar', 'Profesora', 'admin'),
(4, 'PilarUser', '$2y$12$epn6afL64tVMtwjct5hTr.8iyuChSJVmgMHMdQdU9IFaKcT1FCK4C', 'Pilar', 'Estudiante', 'user');

-- Proyectos
INSERT INTO `proyectos` (`nombre`, `descripcion`, `lider`, `presupuesto`, `fecha_inicio`, `fecha_fin`, `finalizado`) VALUES
('Proyecto Alpha', 'Desarrollo de nueva API RESTful para clientes externos.', 'Juan Pérez', 5000.00, '2023-01-01', '2023-06-01', 0),
('Web Corporativa', 'Rediseño completo del sitio web corporativo con focus en Mobile First.', 'Ana Gómez', 3000.00, '2023-02-15', '2023-05-30', 1),
('Migración Cloud', 'Migración de servidores on-premise a AWS.', 'Carlos Ruiz', 12000.50, '2023-03-10', '2023-09-20', 0);

-- ============================================
-- 4. USUARIO DE APLICACIÓN
-- ============================================
-- Usuario con permisos limitados.
-- ============================================

CREATE USER IF NOT EXISTS 'LoginPhp'@'localhost' IDENTIFIED BY '95f90HZJy3sb';

-- Permisos
GRANT SELECT, INSERT, UPDATE, DELETE ON `gestion_proyectos`.* TO 'LoginPhp'@'localhost';

FLUSH PRIVILEGES;

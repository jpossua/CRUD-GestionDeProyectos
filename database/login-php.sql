-- ============================================
-- BASE DE DATOS: login-php
-- ============================================
-- Sistema de Login MVC Seguro con PHP y MariaDB
-- 
-- Este archivo contiene la estructura completa de la base de datos
-- para poder recrearla en otro servidor.
--
-- Exportado con phpMyAdmin 5.2.3
-- Servidor: localhost:3306
-- Versión del servidor: MariaDB 11.8.5
-- Versión de PHP: 8.5.0
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================
-- 1. CREACIÓN DE LA BASE DE DATOS
-- ============================================

CREATE DATABASE IF NOT EXISTS `login-php` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_spanish_ci;

USE `login-php`;

-- ============================================
-- 2. ESTRUCTURA DE TABLAS
-- ============================================

-- --------------------------------------------------------
-- TABLA: usuarios
-- --------------------------------------------------------
-- Almacena los datos de los usuarios del sistema.
-- 
-- Campos:
-- - codUser: ID autoincremental (clave primaria)
-- - idUser: Identificador único del usuario (Login)
-- - password: Hash seguro (bcrypt)
-- - rol: Rol del usuario (admin/user)
-- --------------------------------------------------------

CREATE TABLE `usuarios` (
  `codUser` int(11) NOT NULL,
  `idUser` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- ============================================
-- 3. VOLCADO DE DATOS (Demos)
-- ============================================
-- Usuarios pre-creados para pruebas rápidas.
-- Todas las contraseñas son: "Password123!" (o similares según el hash)

INSERT INTO `usuarios` (`codUser`, `idUser`, `password`, `nombre`, `apellidos`, `rol`) VALUES
(1, 'JoseManu', '$2y$10$hfK0TnWWEhusLk0czNNPR.ocWwFLxaHrubWcKtMDylxCj8EGScLLS', 'Jose', 'Postigo', 'admin'),
(2, 'Anonline', '$2y$10$i1M3yzx1TmDpChMbPsILL.1N7.a9ApzWd2/ItGt0LSFgivyAwS66G', 'Ana', 'Ponce', 'user'),
(3, 'PilarAdmin', '$2y$12$epn6afL64tVMtwjct5hTr.8iyuChSJVmgMHMdQdU9IFaKcT1FCK4C', 'Pilar', 'Profesora', 'admin'),
(4, 'PilarUser', '$2y$12$epn6afL64tVMtwjct5hTr.8iyuChSJVmgMHMdQdU9IFaKcT1FCK4C', 'Pilar', 'Estudiante', 'user');

-- ============================================
-- 4. ÍNDICES Y CLAVES
-- ============================================

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`codUser`),
  ADD UNIQUE KEY `idUser` (`idUser`);

-- ============================================
-- 5. AUTO_INCREMENT
-- ============================================

ALTER TABLE `usuarios`
  MODIFY `codUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================
-- 6. USUARIO DE APLICACIÓN
-- ============================================
-- Usuario con permisos limitados para que la APP PHP se conecte.
-- Evita usar 'root' en la aplicación por seguridad.
-- ============================================

CREATE USER IF NOT EXISTS 'LoginPhp'@'localhost' IDENTIFIED BY '95f90HZJy3sb';

-- Permisos para login-php
GRANT SELECT, INSERT, UPDATE, DELETE ON `login-php`.* TO 'LoginPhp'@'localhost';

-- Permisos para gestion_proyectos
GRANT SELECT, INSERT, UPDATE, DELETE ON `gestion_proyectos`.* TO 'LoginPhp'@'localhost';

FLUSH PRIVILEGES;

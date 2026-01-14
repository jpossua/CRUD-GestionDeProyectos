<?php

/**
 * ============================================
 * INDEX.PHP - PUNTO DE ENTRADA DE LA APLICACIÓN
 * ============================================
 * 
 * Este archivo es el Front Controller del patrón MVC.
 * Todas las peticiones pasan por aquí y se enrutan al controlador apropiado.
 */

// ============================================
// 1. CARGAR CONFIGURACIÓN DE SEGURIDAD
// ============================================
/**
 * Incluimos la configuración de sesión segura.
 * Esto DEBE ser lo primero, antes de cualquier session_start().
 */
require_once 'Config/SessionConfig.php';

// ============================================
// 2. CARGAR CONTROLADORES Y MODELOS
// ============================================
// --- AUTH ---
require_once 'Config/SecurityHelper.php';       // Ayudante de seguridad
require_once 'Models/User.php';                 // Modelo de usuarios (Clase Usuario)
require_once 'Controllers/AuthController.php';  // Controlador de autenticación

// --- PROYECTOS (CRUD) ---
require_once 'Models/Proyecto.php';             // Modelo de proyectos
require_once 'Controllers/GestionController.php'; // Controlador de gestión

// ============================================
// 3. ENRUTAMIENTO
// ============================================
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'login';

// Instanciamos los controladores solo cuando se necesitan para evitar efectos secundarios
// (como la redirección en el constructor de GestionController)

switch ($action) {
    // --- RUTAS DE AUTENTICACIÓN ---
    case 'login':
        $authController = new AuthController();
        $authController->login();
        break;

    case 'authenticate':
        $authController = new AuthController();
        $authController->authenticate();
        break;

    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;



    // --- RUTAS DE GESTIÓN (CRUD) ---
    // 'dashboard' ahora es el índice del CRUD
    case 'dashboard':
    case 'index':
        $gestionController = new GestionController();
        $gestionController->index();
        break;

    case 'create':
        $gestionController = new GestionController();
        $gestionController->create();
        break;

    case 'edit':
        $gestionController = new GestionController();
        $gestionController->edit();
        break;

    case 'delete':
        $gestionController = new GestionController();
        $gestionController->delete();
        break;

    default:
        // Por defecto al login
        $authController = new AuthController();
        $authController->login();
        break;
}

<?php

/**
 * ============================================
 * CONTROLADOR DE AUTENTICACIÓN (AuthController)
 * ============================================
 * 
 * Este controlador maneja todas las operaciones relacionadas con
 * la autenticación de usuarios:
 * - Login / Logout
 * - Registro de nuevos usuarios
 * - Acceso al dashboard
 * 
 * Características de seguridad implementadas:
 * - Verificación de token CSRF en todas las operaciones POST
 * - Sanitización de datos de entrada
 * - Control de límite de intentos de login
 * - Hasheo seguro de contraseñas
 * - Eliminación segura de cookies en logout
 */

class AuthController
{
    /**
     * Modelo de usuario para operaciones con la base de datos
     * @var Usuario
     */
    private $userModel;

    // ============================================
    // CONSTRUCTOR
    // ============================================

    /**
     * Constructor del controlador
     * Inicializa el modelo de usuario que maneja la conexión a la BD
     */
    public function __construct()
    {
        $this->userModel = new Usuario();
    }

    // ============================================
    // MÉTODO: LOGIN (Mostrar formulario)
    // ============================================

    /**
     * Muestra la vista del formulario de login
     * 
     * Esta es la página de entrada a la aplicación.
     * El formulario incluye un campo oculto con el token CSRF.
     */
    public function login()
    {
        // Carga la vista del formulario de login
        include 'Views/login.php';
    }

    // ============================================
    // MÉTODO: AUTHENTICATE (Procesar login)
    // ============================================

    /**
     * Procesa el formulario de login
     * 
     * Flujo de autenticación segura:
     * 1. Verificar que sea una petición POST
     * 2. Verificar token CSRF
     * 3. Verificar que el usuario no esté bloqueado por intentos fallidos
     * 4. Sanitizar datos de entrada
     * 5. Consultar usuario en la base de datos
     * 6. Verificar contraseña hasheada
     * 7. Verificar que el usuario esté admitido
     * 8. Guardar datos en sesión y redirigir
     */
    public function authenticate()
    {
        // ============================================
        // PASO 1: VERIFICAR MÉTODO POST
        // ============================================
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Location: index.php?action=login');
            exit();
        }

        // ============================================
        // PASO 2: VERIFICAR TOKEN CSRF
        // ============================================
        /**
         * El token CSRF previene ataques donde un sitio malicioso
         * intenta enviar formularios en nombre del usuario.
         * Si el token no coincide, rechazamos la petición.
         */
        if (!SecurityHelper::validateCSRFToken()) {
            $_SESSION['error'] = "Token CSRF no válido. Por favor, recarga la página e intenta de nuevo.";
            header('Location: index.php?action=login');
            exit();
        }

        // ============================================
        // PASO 3: VERIFICAR LÍMITE DE INTENTOS
        // ============================================
        /**
         * Protección contra ataques de fuerza bruta.
         * Si el usuario ha fallado demasiadas veces, lo bloqueamos temporalmente.
         */
        $attemptCheck = SecurityHelper::checkLoginAttempts();
        if ($attemptCheck['blocked']) {
            $_SESSION['error'] = $attemptCheck['message'];
            header('Location: index.php?action=login');
            exit();
        }

        // ============================================
        // PASO 4: SANITIZAR DATOS DE ENTRADA
        // ============================================
        /**
         * Sanitizamos los datos antes de usarlos.
         * Esto previene ataques XSS y otros tipos de inyección.
         */
        $username = SecurityHelper::sanitizeInput($_POST['idUser'] ?? '');
        $password = $_POST['password'] ?? '';  // No sanitizamos password para no alterar caracteres especiales

        // ============================================
        // PASO 5: CONSULTAR USUARIO EN BASE DE DATOS
        // ============================================
        /**
         * El modelo Usuario usa PDO con consultas preparadas,
         * lo que previene ataques de inyección SQL.
         */
        $user = $this->userModel->login($username, $password);

        if ($user) {


            // ============================================
            // PASO 7: LOGIN EXITOSO
            // ============================================
            /**
             * Login exitoso:
             * 1. Resetear contador de intentos fallidos
             * 2. Regenerar ID de sesión (previene session fixation)
             * 3. Guardar datos del usuario en sesión
             * 4. Redirigir al dashboard
             */

            // Resetear contador de intentos
            SecurityHelper::resetLoginAttempts();

            // Regenerar ID de sesión por seguridad
            session_regenerate_id(true);

            // Guardar datos del usuario en sesión
            $_SESSION['idUser'] = $username;
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['apellidos'] = $user['apellidos'];
            $_SESSION['rol'] = $user['rol'] ?? 'user'; // Guardamos el rol (default user)
            $_SESSION['usuario_logueado'] = true;

            // Redirigir al dashboard (Ahora es el index del CRUD)
            header('Location: index.php?action=dashboard');
            exit();
        } else {
            // ============================================
            // LOGIN FALLIDO
            // ============================================
            /**
             * Credenciales incorrectas:
             * 1. Incrementar contador de intentos fallidos
             * 2. Mostrar mensaje de error genérico
             *    (no indicamos si el usuario existe o no por seguridad)
             */
            SecurityHelper::incrementLoginAttempts();
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            header('Location: index.php?action=login');
            exit();
        }
    }

    // ============================================
    // MÉTODO: LOGOUT (Cierre de sesión seguro)
    // ============================================

    /**
     * Cierra la sesión de forma segura
     * 
     * Proceso de logout seguro:
     * 1. Limpiar todas las variables de sesión
     * 2. Destruir la sesión
     * 3. Eliminar la cookie de sesión del navegador
     * 4. Redirigir al login
     * 
     * Es importante eliminar la cookie DESPUÉS de destruir la sesión
     * para asegurar que no queden rastros de la sesión.
     */
    public function logout()
    {
        // ============================================
        // PASO 1: LIMPIAR VARIABLES DE SESIÓN
        // ============================================
        session_unset();

        // ============================================
        // PASO 2: DESTRUIR LA SESIÓN
        // ============================================
        session_destroy();
        
        // ============================================
        // PASO 3: ELIMINAR COOKIE DE SESIÓN
        // ============================================
        /**
         * Eliminamos explícitamente la cookie de sesión.
         * Esto se hace estableciendo una fecha de expiración en el pasado.
         * 
         * Usamos los mismos parámetros que se usaron al crear la cookie
         * para asegurar que se elimine correctamente.
         */
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),         // Nombre de la cookie (generalmente PHPSESSID)
                '',                     // Valor vacío
                time() - 42000,         // Fecha de expiración en el PASADO
                $params["path"],        // Misma ruta que la original
                $params["domain"],      // Mismo dominio
                $params["secure"],      // Mismo valor de secure
                $params["httponly"]     // Mismo valor de httponly
            );
        }

        // ============================================
        // PASO 4: REDIRIGIR AL LOGIN
        // ============================================
        header('Location: index.php?action=login');
        exit();
    }
}

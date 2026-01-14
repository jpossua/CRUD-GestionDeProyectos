<?php

/**
 * ============================================
 * MODELO DE USUARIO (Usuario)
 * ============================================
 * 
 * Este modelo maneja todas las operaciones con la base de datos
 * relacionadas con los usuarios:
 * - Verificar credenciales (login)
 * 
 * Características de seguridad implementadas:
 * - Consultas preparadas con PDO (previene SQL Injection)
 * - Verificación de contraseñas hasheadas
 * - Campo 'admitido' para control de acceso
 */

require_once 'Config/Database.php';

class Usuario
{
    /**
     * Conexión PDO a la base de datos
     * @var PDO
     */
    private $PDO;

    /**
     * Nombre de la tabla de usuarios
     * @var string
     */
    private $tabla_nombre = "usuarios";

    // ============================================
    // CONSTRUCTOR
    // ============================================

    /**
     * Constructor del modelo
     * Inicializa la conexión a la base de datos usando la clase Database
     */
    public function __construct()
    {
        $database = new Database('login-php');
        $this->PDO = $database->getConnection();
    }

    // ============================================
    // MÉTODO: LOGIN (Verificar credenciales)
    // ============================================

    /**
     * Verifica las credenciales del usuario
     * 
     * Proceso de verificación segura:
     * 1. Buscar usuario por idUser usando consulta preparada
     * 2. Si existe, verificar contraseña hasheada con password_verify()
     * 3. Devolver datos del usuario si las credenciales son correctas
     * 
     * IMPORTANTE: La contraseña NO se compara directamente.
     * Se usa password_verify() que compara el hash almacenado
     * con el hash de la contraseña proporcionada.
     */
    public function login($idUser, $password)
    {
        // ============================================
        // PASO 1: PREPARAR CONSULTA SEGURA
        // ============================================
        /**
         * Usamos consultas preparadas (prepared statements) con PDO.
         * El signo ? es un placeholder que será reemplazado de forma segura.
         * Esto previene ataques de SQL Injection porque los datos
         * nunca se concatenan directamente en la consulta.
         */
        $query = "SELECT * FROM " . $this->tabla_nombre . " WHERE idUser = ? LIMIT 0,1";

        // ============================================
        // PASO 2: PREPARAR Y EJECUTAR
        // ============================================
        $stmt = $this->PDO->prepare($query);
        $stmt->bindParam(1, $idUser);
        $stmt->execute();

        // ============================================
        // PASO 3: VERIFICAR SI EL USUARIO EXISTE
        // ============================================
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // ============================================
            // PASO 4: VERIFICAR CONTRASEÑA HASHEADA
            // ============================================
            /**
             * password_verify() compara la contraseña proporcionada
             * con el hash almacenado en la base de datos.
             * 
             * El hash incluye:
             * - El algoritmo usado (bcrypt por defecto)
             * - El salt (generado automáticamente)
             * - El hash resultante
             * 
             * password_verify() extrae estos componentes y realiza
             * la comparación de forma segura.
             */
            if (password_verify($password, $row['password'])) {
                // Credenciales correctas: devolver datos del usuario
                // Incluimos 'admitido' para verificar en el controlador
                return $row;
            }
        }

        // Usuario no encontrado o contraseña incorrecta
        return false;
    }



    // ============================================
    // MÉTODO: CHECK USER EXISTS (Verificar existencia)
    // ============================================

    /**
     * Verifica si un usuario existe en la base de datos
     * 
     * @param string $idUser ID del usuario a verificar
     * @return bool true si existe, false si no
     */
    public function userExists($idUser)
    {
        $query = "SELECT idUser FROM " . $this->tabla_nombre . " WHERE idUser = ? LIMIT 1";
        $stmt = $this->PDO->prepare($query);
        $stmt->bindParam(1, $idUser);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}

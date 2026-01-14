<?php

/**
 * ============================================
 * CONTROLADOR DE GESTIÓN (GestionController)
 * ============================================
 * 
 * Este controlador maneja la lógica de negocio del CRUD de proyectos.
 * Actúa como intermediario entre el modelo Proyecto y las vistas.
 * 
 * Responsabilidades:
 * - Listar, Crear, Editar y Eliminar proyectos.
 * - Verificar permisos (RBAC) antes de cada acción crítica.
 * - Sanitizar datos de entrada antes de enviarlos al modelo.
 */
class GestionController
{
    /**
     * Modelo de usuario para operaciones con la base de datos
     * @var Proyecto
     */
    private $userModel;

    // ============================================
    // CONSTRUCTOR
    // ============================================

    /**
     * Constructor del controlador
     * Verifica la sesión activa y redirige al login si no hay acceso.
     */
    public function __construct()
    {
        // VERIFICACIÓN DE SEGURIDAD
        // Si no hay usuario logueado, redirigir al login
        if (!isset($_SESSION['usuario_logueado']) || $_SESSION['usuario_logueado'] !== true) {
            header('Location: index.php?action=login');
            exit();
        }

        $this->userModel = new Proyecto();
    }

    // ============================================
    // MÉTODO: INDEX (Listar Proyectos)
    // ============================================

    /**
     * Muestra la lista completa de proyectos (Dashboard)
     * Ruta por defecto después del login.
     */
    public function index()
    {
        // Obtener proyectos del modelo
        $proyectos = $this->userModel->getAll();

        // Cargar vista
        include 'Views/dashboard-listar.php';
    }

    // ============================================
    // MÉTODO: CREATE (Crear Proyecto)
    // ============================================

    /**
     * Maneja la creación de nuevos proyectos
     * - GET: Muestra el formulario vacío
     * - POST: Procesa los datos, valida permisos y guarda en BD
     */
    public function create()
    {
        // VERIFICACIÓN DE ROL: Solo admin puede crear
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?action=index&error=unauthorized');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Saneamiento de datos
            $data = [
                'nombre' => SecurityHelper::sanitizeInput($_POST['nombre'] ?? ''),
                'descripcion' => SecurityHelper::sanitizeInput($_POST['descripcion'] ?? ''),
                'lider' => SecurityHelper::sanitizeInput($_POST['lider'] ?? ''),
                'presupuesto' => SecurityHelper::sanitizeInput($_POST['presupuesto'] ?? ''),
                'fecha_inicio' => SecurityHelper::sanitizeInput($_POST['fecha_inicio'] ?? ''),
                'fecha_fin' => SecurityHelper::sanitizeInput($_POST['fecha_fin'] ?? ''),
                'finalizado' => isset($_POST['finalizado']) ? 1 : 0 // Checkbox handling
            ];

            // Guardar
            $this->userModel->create($data);
            header('Location: index.php?action=index&message=created');
        } else {
            // Mostrar formulario
            include 'Views/dashboard-crear.php';
        }
    }

    // ============================================
    // MÉTODO: EDIT (Editar Proyecto)
    // ============================================

    /**
     * Maneja la edición de proyectos existentes
     * - GET: Busca el proyecto y muestra el formulario pre-rellenado
     * - POST: Actualiza los datos en la BD tras validar permisos
     */
    public function edit()
    {
        // VERIFICACIÓN DE ROL: Solo admin puede editar
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?action=index&error=unauthorized');
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Saneamiento de datos
                $data = [
                    'nombre' => SecurityHelper::sanitizeInput($_POST['nombre'] ?? ''),
                    'descripcion' => SecurityHelper::sanitizeInput($_POST['descripcion'] ?? ''),
                    'lider' => SecurityHelper::sanitizeInput($_POST['lider'] ?? ''),
                    'presupuesto' => SecurityHelper::sanitizeInput($_POST['presupuesto'] ?? ''),
                    'fecha_inicio' => SecurityHelper::sanitizeInput($_POST['fecha_inicio'] ?? ''),
                    'fecha_fin' => SecurityHelper::sanitizeInput($_POST['fecha_fin'] ?? ''),
                    'finalizado' => isset($_POST['finalizado']) ? 1 : 0
                ];

                // Actualizar
                $this->userModel->update($id, $data);
                header('Location: index.php?action=index&message=updated');
            } else {
                // Mostrar formulario con datos
                $proyecto_data = $this->userModel->getById($id);
                if ($proyecto_data) {
                    include 'Views/dashboard-editar.php';
                } else {
                    header('Location: index.php?action=index&error=notfound');
                }
            }
        } else {
            header('Location: index.php?action=index');
        }
    }

    // ============================================
    // MÉTODO: DELETE (Eliminar Proyecto)
    // ============================================

    /**
     * Elimina un proyecto
     * Requiere rol de admin y un ID válido.
     */
    public function delete()
    {
        // VERIFICACIÓN DE ROL: Solo admin puede eliminar
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?action=index&error=unauthorized');
            exit();
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->userModel->delete($id);
            header('Location: index.php?action=index&message=deleted');
        } else {
            header('Location: index.php?action=index');
        }
    }
}

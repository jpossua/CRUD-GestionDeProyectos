<?php

/**
 * ============================================
 * MODELO DE PROYECTO (Proyecto)
 * ============================================
 * 
 * Este modelo maneja todas las operaciones CRUD (Create, Read, Update, Delete)
 * relacionadas con la gestión de proyectos.
 * 
 * Interactúa con la base de datos 'gestion_proyectos'.
 * 
 * Características:
 * - Consultas preparadas con PDO (Seguridad)
 * - Retorno de objetos para consistencia en vistas
 */

class Proyecto
{
    /**
     * Conexión PDO a la base de datos
     * @var PDO
     */
    private $PDO;

    /**
     * Nombre de la tabla de proyectos
     * @var string
     */
    private $tabla_nombre = "proyectos";

    // ============================================
    // CONSTRUCTOR
    // ============================================

    /**
     * Constructor del modelo
     * Establece la conexión con la base de datos específica de proyectos.
     */
    public function __construct()
    {
        // Conectamos a la base de datos 'gestion_proyectos'
        require_once 'Config/Database.php';
        $database = new Database('gestion_proyectos');
        $this->PDO = $database->getConnection();
    }

    // ============================================
    // MÉTODO: GET ALL (Listar Proyectos)
    // ============================================

    /**
     * Obtiene todos los proyectos registrados
     * 
     * @return array Lista asociativa de todos los proyectos.
     */
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->tabla_nombre;
        $stmt = $this->PDO->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================================
    // MÉTODO: GET BY ID (Obtener un Proyecto)
    // ============================================

    /**
     * Busca los detalles de un proyecto específico
     * 
     * @param int $id ID del proyecto.
     * @return object|false Objeto con los datos del proyecto o false si no existe.
     */
    public function getById($id)
    {
        // Consulta segura con límite 1
        $query = "SELECT * FROM " . $this->tabla_nombre . " WHERE id = ? LIMIT 1";
        $stmt = $this->PDO->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        // Devolvemos objeto (FETCH_OBJ) para facilitar el acceso en la vista ($proyecto->nombre)
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // ============================================
    // MÉTODO: CREATE (Crear Proyecto)
    // ============================================

    /**
     * Inserta un nuevo proyecto en la base de datos
     * 
     * @param array $data Array asociativo con los datos del formulario.
     * @return bool True si se creó con éxito, False en caso contrario.
     */
    public function create($data)
    {
        // Preparar la consulta de inserción con placeholders (?)
        $query = "INSERT INTO " . $this->tabla_nombre . " 
                  (nombre, descripcion, lider, presupuesto, fecha_inicio, fecha_fin, finalizado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->PDO->prepare($query);

        // Ejecutar pasando los valores en orden
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['lider'],
            $data['presupuesto'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['finalizado']
        ]);

        // Verificar si se insertó alguna fila
        return $stmt->rowCount() > 0;
    }

    // ============================================
    // MÉTODO: UPDATE (Actualizar Proyecto)
    // ============================================

    /**
     * Actualiza los datos de un proyecto existente
     * 
     * @param int $id ID del proyecto a actualizar.
     * @param array $data Nuevos datos del proyecto.
     * @return bool True si se actualizó con éxito.
     */
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->tabla_nombre . " 
                  SET nombre = ?, descripcion = ?, lider = ?, presupuesto = ?, fecha_inicio = ?, fecha_fin = ?, finalizado = ? 
                  WHERE id = ?";

        $stmt = $this->PDO->prepare($query);
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['lider'],
            $data['presupuesto'],
            $data['fecha_inicio'],
            $data['fecha_fin'],
            $data['finalizado'],
            $id
        ]);

        return $stmt->rowCount() > 0;
    }

    // ============================================
    // MÉTODO: DELETE (Eliminar Proyecto)
    // ============================================

    /**
     * Elimina un proyecto de la base de datos
     * 
     * @param int $id ID del proyecto a eliminar.
     * @return bool True si la operación se ejecutó correctamente.
     */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->tabla_nombre . " WHERE id = ?";
        $stmt = $this->PDO->prepare($query);
        $stmt->bindParam(1, $id);
        return $stmt->execute();
    }
}

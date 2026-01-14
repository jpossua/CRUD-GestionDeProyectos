<?php

/**
 * ============================================
 * VISTA: DASHBOARD LISTAR (Index CRUD)
 * ============================================
 * 
 * Esta vista es la página principal del panel de control.
 * Funcionalidades:
 * - Listar todos los proyectos de la base de datos
 * - Mostrar opciones de CRUD según el rol (Admin/User)
 * - Permitir el logout
 */
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Proyectos</title>

    <!-- ============================================
         ESTILOS Y LIBRERÍAS
         ============================================ -->
    <!-- Icono de la pagina -->
    <link rel="shortcut icon" href="https://iesplayamar.es/wp-content/uploads/2021/09/logo-ies-playamar.png" type="image/x-icon">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="./Views/css/dashboard-comun.css">
    <link rel="stylesheet" href="./Views/css/dashboard-listar.css">
</head>

<body>
    <!-- ============================================
         HEADER: Dark Mode + Usuario + Logout
         ============================================ -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Botón Modo Oscuro -->
        <button id="btnOscuro" type="button" class="btn btn-primary rounded-circle m-3 p-2">🌙</button>

        <!-- Info Usuario y Logout -->
        <div>
            <span class="me-3 fw-bold">Usuario: <?php echo htmlspecialchars($_SESSION['idUser'] ?? 'Invitado'); ?></span>
            <a href="index.php?action=logout" class="btn btn-danger me-3">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- ============================================
         CONTENIDO PRINCIPAL
         ============================================ -->
    <h2>Listado de Proyectos</h2>

    <!-- Mensajes de Feedback (Create/Update/Delete) -->
    <?php if (isset($_GET['message'])): ?>
        <div class="message">
            <?php
            if ($_GET['message'] == 'created') echo 'Proyecto creado correctamente.';
            if ($_GET['message'] == 'updated') echo 'Proyecto actualizado correctamente.';
            if ($_GET['message'] == 'deleted') echo 'Proyecto eliminado correctamente.';
            ?>
        </div>
    <?php endif; ?>

    <!-- Botón Añadir (Solo Admin) -->
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <p>
            <a href="index.php?action=create" class="btn btn-success botonDerecha">
                <i class="bi bi-person-fill-add"></i> Añadir Proyecto
            </a>
        </p>
    <?php endif; ?>

    <!-- Tabla de Proyectos -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del proyecto</th>
                <th>Descripción</th>
                <th>Lider del proyecto</th>
                <th>Presupuesto</th>
                <th>Fecha de inicio</th>
                <th>Fecha de finalización</th>
                <th>Finalizado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proyectos as $proyecto): ?>
                <tr>
                    <td><?php echo $proyecto['id']; ?></td>
                    <td><?php echo $proyecto['nombre']; ?></td>
                    <td><?php echo $proyecto['descripcion']; ?></td>
                    <td><?php echo $proyecto['lider']; ?></td>
                    <td><?php echo $proyecto['presupuesto']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($proyecto['fecha_inicio'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($proyecto['fecha_fin'])); ?></td>
                    <td>
                        <!-- Checkbox deshabilitado solo para visualización -->
                        <input type="checkbox" <?php echo ($proyecto['finalizado'] === 'Sí' || $proyecto['finalizado'] == 1) ? 'checked' : ''; ?> disabled>
                    </td>
                    <td>
                        <!-- Acciones (Solo Admin) -->
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <a href="index.php?action=edit&id=<?php echo $proyecto['id']; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"> Editar</i>
                            </a>
                            <a href="index.php?action=delete&id=<?php echo $proyecto['id']; ?>" class="btn btn-danger">
                                <i class="bi bi-trash"> Eliminar</i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- ============================================
         SCRIPTS JAVASCRIPT
         ============================================ -->
    <script src="./Views/js/temaOscuro.js"></script>
</body>

</html>
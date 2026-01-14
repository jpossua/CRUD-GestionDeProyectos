<?php

/**
 * ============================================
 * VISTA: DASHBOARD EDITAR (Formulario Edición)
 * ============================================
 * 
 * Muestra el formulario para editar un proyecto existente.
 * Solo accesible para usuarios con rol 'admin'.
 * 
 * Recibe el objeto $proyecto_data del controlador.
 */
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto</title>

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
    <link rel="stylesheet" href="./Views/css/dashboard-form.css">
</head>

<body>
    <!-- ============================================
         HEADER: Dark Mode + Usuario + Logout
         ============================================ -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button id="btnOscuro" type="button" class="btn btn-primary rounded-circle m-3 p-2">🌙</button>
        <div>
            <span class="me-3 fw-bold">Usuario: <?php echo htmlspecialchars($_SESSION['idUser'] ?? 'Invitado'); ?></span>
            <a href="index.php?action=logout" class="btn btn-danger me-3">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- ============================================
         FORMULARIO DE EDICIÓN
         ============================================ -->
    <h2>Editar Proyecto</h2>

    <div class="container">
        <!-- Formulario apunta al controlador GestionController -> edit() pasando el ID -->
        <form action="index.php?action=edit&id=<?php echo $proyecto_data->id; ?>" method="post">

            <label class="form-label"><b>Nombre:</b> <input type="text" name="nombre" id="nombre" value="<?php echo $proyecto_data->nombre; ?>"></label><br>
            <label class="form-label"><b>Descripción:</b> <textarea name="descripcion" id="descripcion"><?php echo $proyecto_data->descripcion; ?></textarea></label><br>
            <label class="form-label"><b>Lider:</b> <input type="text" name="lider" id="lider" value="<?php echo $proyecto_data->lider; ?>"></label><br>
            <label class="form-label"><b>Presupuesto:</b> <input type="number" name="presupuesto" id="presupuesto" value="<?php echo $proyecto_data->presupuesto; ?>"></label><br>

            <label class="form-label"><b>Fecha de inicio:</b> <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $proyecto_data->fecha_inicio; ?>"></label><br>
            <label class="form-label"><b>Fecha de finalización:</b> <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo $proyecto_data->fecha_fin; ?>"></label><br>

            <label class="form-label"><b>Finalizado:</b> <input type="checkbox" name="finalizado" id="finalizado" <?php echo $proyecto_data->finalizado ? 'checked' : ''; ?>></label><br>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-person-fill-add"></i> Editar Proyecto
            </button>
        </form>

        <p>
            <a href="index.php?action=index" class="btn btn-primary">
                <i class="bi bi-arrow-90deg-left"></i> Volver al listado
            </a>
        </p>
    </div>

    <!-- ============================================
         SCRIPTS JAVASCRIPT
         ============================================ -->
    <!-- Script de validación -->
    <script src="./Views/js/validarProyectos.js"></script>
    <!-- Script de tema oscuro/claro -->
    <script src="./Views/js/temaOscuro.js"></script>
</body>

</html>
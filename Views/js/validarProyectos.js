document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        let valid = true;
        let messages = [];

        // Obtener valores
        const nombre = document.getElementById("nombre").value.trim();
        const descripcion = document.getElementById("descripcion").value.trim();
        const lider = document.getElementById("lider").value.trim();
        const presupuesto = document.getElementById("presupuesto").value;
        const fechaInicio = document.getElementById("fecha_inicio").value;
        const fechaFin = document.getElementById("fecha_fin").value;

        // 1. Validar campos vacíos
        if (!nombre) {
            messages.push("El campo 'Nombre' es obligatorio.");
            valid = false;
        }
        if (!descripcion) {
            messages.push("El campo 'Descripción' es obligatorio.");
            valid = false;
        }
        if (!lider) {
            messages.push("El campo 'Lider' es obligatorio.");
            valid = false;
        }

        // 2. Validar Presupuesto
        if (presupuesto === "" || parseFloat(presupuesto) < 0) {
            messages.push("El presupuesto debe ser un número positivo.");
            valid = false;
        }

        // 3. Validar Fechas
        if (!fechaInicio) {
            messages.push("La fecha de inicio es obligatoria.");
            valid = false;
        }
        if (fechaFin) {
            // Si hay fecha fin, debe ser posterior a inicio
            if (new Date(fechaFin) < new Date(fechaInicio)) {
                messages.push("La fecha de finalización no puede ser anterior a la de inicio.");
                valid = false;
            }
        }

        if (!valid) {
            e.preventDefault(); // Detener envío
            alert("Errores detectados:\n\n" + messages.join("\n"));
        }
    });
});

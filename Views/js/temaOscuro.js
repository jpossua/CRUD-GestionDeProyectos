// Lógica para alternar el color de fondo
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("btnOscuro");
    if (btn) {
        // Establecer por defecto a oscuro
        document.body.style.backgroundColor = "#000";
        document.body.style.color = "#fff";
        btn.textContent = "☀";
        let esOscuro = true;

        btn.addEventListener("click", function () {
            if (!esOscuro) {
                document.body.style.backgroundColor = "#000";
                document.body.style.color = "#fff";
                btn.textContent = "☀";
                esOscuro = true;
            } else {
                document.body.style.backgroundColor = "#fff";
                document.body.style.color = "#000";
                btn.textContent = "🌙";
                esOscuro = false;
            }
        });
    }
});

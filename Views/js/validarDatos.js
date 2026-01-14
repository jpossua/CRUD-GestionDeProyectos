/**
 * Validar datos del formulario
 *  El ID del usuario: entre 8 y 15 caracteres
 * La contraseña del usuario: entre 8 y 15 caracteres. Haz que la contraseña tenga, obligatoriamente letras mayúsculas, minúsculas, 
 * caracteres especiales (menos pero no ' " \ / < > = ( ) u otros caracteres que puedan ser parte de un script malicioso)
 * True si los datos son validos, false en caso contrario
 */

// ============================================
// VALIDACIÓN DEL FORMULARIO DE LOGIN (form1)
// ============================================
// Añadimos un 'listener' al evento 'submit' del formulario.
// Esto nos permite ejecutar código antes de que el formulario se envíe.
const formLogin = document.getElementById('form1');
if (formLogin) {
    formLogin.addEventListener("submit", function (event) {
        // Obtenemos los valores del formulario
        const idUser = document.getElementById('idUser').value;
        const password = document.getElementById('password').value;
        // Si la función validarDatos() devuelve false, prevenimos el envío.
        if (!validarDatos(idUser, password)) {
            event.preventDefault(); // Parar el submit por defecto
        }
        // Si la función validarDatos() devuelve true, ocultamos los errores
        else {
            ocultarError('idUserHelp');
            ocultarError('passwordHelp');
        }
    });
}



// Función que valida los datos del formulario
function validarDatos(idUser, password) {
    let valido = true;

    // Longitud entre 8 y 15 caracteres para el idUser
    if (idUser.length < 8 || idUser.length > 15) {
        valido = false;
        mostrarError('idUserHelp', 'El idUser debe tener entre 8 y 15 caracteres');
    }

    /** 
        * Longitud entre 8 y 15 caracteres para la contraseña y debe contener mayusculas, minusculas, números y caracteres especiales (menos pero no ' " \ / < > = ( )
        * u otros caracteres que puedan ser parte de un script malicioso)
        */
    if (password.length < 8 || password.length > 15 || /['"'"\\/\<>=()]/.test(password)) {
        valido = false;
        mostrarError('passwordHelp', `La contraseña debe tener entre 8 y 15 caracteres, sin: ' " ' " \\ / < > = ( )`);
    }
    // Debe contener al menos una mayuscula
    else if (!/[A-Z]/.test(password)) {
        valido = false;
        mostrarError('passwordHelp', 'La contraseña debe contener al menos una mayúscula');
    }
    // Debe contener al menos una minuscula
    else if (!/[a-z]/.test(password)) {
        valido = false;
        mostrarError('passwordHelp', 'La contraseña debe contener al menos una minúscula');
    }
    // Debe contener al menos un numero
    else if (!/[0-9]/.test(password)) {
        valido = false;
        mostrarError('passwordHelp', 'La contraseña debe contener al menos un número');
    }
    // Debe contener al menos un caracter especial
    else if (!/[!@#$%^&*_+=\-\[\]{};:,.?]/.test(password)) {
        valido = false;
        mostrarError('passwordHelp', 'La contraseña debe contener al menos un caracter especial: !@#$%^&*_+-[]{}:,.?');
    }

    return valido;
}
function mostrarError(id, error) {
    document.getElementById(id).textContent = error;
    document.getElementById(id).style.visibility = "visible";
}

function ocultarError(id) {
    document.getElementById(id).style.visibility = "hidden";
}



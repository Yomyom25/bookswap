document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("registroForm");
    const matricula = document.getElementById("matricula");
    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");

    formulario.addEventListener("submit", function (event) {
        let errores = [];

        // Validar matrícula (alfanumérica: ejemplo E21080747 o AB2089000)
        if (!/^[A-Z]{1,2}\d{7,8}$/.test(matricula.value)) {
            errores.push("La matrícula debe comenzar con 1 o 2 letras mayúsculas seguidas de 7 u 8 dígitos. Ejemplo: E21080747 o AB2089000.");
        }

        // Validar nombre
        if (nombre.value.trim() === "") {
            errores.push("El nombre es obligatorio.");
        }

        // Validar apellidos
        if (apellido.value.trim() === "") {
            errores.push("Los apellidos son obligatorios.");
        }

        // Validar correo electrónico
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            errores.push("El correo electrónico no es válido.");
        }

        // Validar contraseña (mínimo 8 caracteres)
        if (password.value.length < 8) {
            errores.push("La contraseña debe tener al menos 8 caracteres.");
        }

        // Validar coincidencia de contraseñas
        if (password.value !== confirmPassword.value) {
            errores.push("Las contraseñas no coinciden.");
        }

        // Mostrar errores si existen
        if (errores.length > 0) {
            event.preventDefault(); // Evitar el envío del formulario
            mostrarErrores(errores);
        }
    });

    function mostrarErrores(errores) {
        const errorContainer = document.querySelector(".error-container");
        if (errorContainer) {
            errorContainer.innerHTML = ""; // Limpiar errores previos
        } else {
            const nuevoDiv = document.createElement("div");
            nuevoDiv.classList.add("error-container");
            formulario.insertBefore(nuevoDiv, formulario.firstChild);
        }

        errores.forEach(error => {
            const p = document.createElement("p");
            p.classList.add("error");
            p.textContent = error;
            document.querySelector(".error-container").appendChild(p);
        });
    }
});

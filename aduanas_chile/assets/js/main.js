// js/main.js

document.addEventListener("DOMContentLoaded", function() {
    
    // Buscar el formulario y el div para mostrar mensajes en la pantalla
    const formEntrada = document.getElementById("form-entrada");
    const divMensaje = document.getElementById("mensaje-respuesta");

    // Verificar si estamos en la página que tiene el formulario
    if (formEntrada) {
        formEntrada.addEventListener("submit", function(e) {
            e.preventDefault(); // ¡Súper importante! Evita que la página se recargue entera

            // Mostrar un mensaje de "Cargando..." mientras se conecta a la base de datos
            divMensaje.innerHTML = `<div class="alert alert-info">Procesando registro...</div>`;

            // Recolectar todos los datos que el usuario escribió en el formulario
            const formData = new FormData(formEntrada);

            // Enviar los datos por AJAX hacia nuestro archivo PHP
            fetch("ajax/registrar_entrada.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json()) // Leer la respuesta de PHP en formato JSON
            .then(data => {
                // PHP nos respondió. Veamos si fue exitoso o hubo un error
                if (data.status === "success") {
                    // Mostrar alerta verde (Bootstrap)
                    divMensaje.innerHTML = `<div class="alert alert-success shadow-sm fw-bold">✔️ ${data.message}</div>`;
                    formEntrada.reset(); // Limpiar las cajas de texto para un nuevo ingreso
                } else {
                    // Mostrar alerta roja (Bootstrap)
                    divMensaje.innerHTML = `<div class="alert alert-danger shadow-sm fw-bold">❌ ${data.message}</div>`;
                }
                
                // Ocultar el mensaje automáticamente después de 5 segundos para mantener limpio
                setTimeout(() => {
                    divMensaje.innerHTML = '';
                }, 5000);
            })
            .catch(error => {
                // Por si el servidor se cae o hay un error de conexión
                console.error("Error en la petición AJAX:", error);
                divMensaje.innerHTML = `<div class="alert alert-danger shadow-sm fw-bold">❌ Error crítico de conexión con el servidor.</div>`;
            });
        });
    }
});
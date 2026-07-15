// assets/js/main.js

// 1. Función JS para evaluar la edad del pasajero automáticamente
function verificarEdad() {
    var fechaNac = document.getElementById("fecha_nac").value;
    if (!fechaNac) return;

    var hoy = new Date();
    var cumpleanos = new Date(fechaNac);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }

    if (edad < 18) {
        document.getElementById("seccion_menor").classList.remove("d-none");
        document.getElementById("menor_edad").value = "1";
        document.getElementById("documento_pdf").required = true; // Hace obligatorio el PDF si es menor
    } else {
        document.getElementById("seccion_menor").classList.add("d-none");
        document.getElementById("menor_edad").value = "0";
        document.getElementById("documento_pdf").required = false;
    }
}

// 2. Ejecutar cuando el documento esté completamente cargado
$(document).ready(function() {

    // Envío del formulario mediante AJAX para procesar datos y archivos simultáneamente
    $("#guardar_persona").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this); // Necesario para enviar archivos PDF por AJAX

        $.ajax({
            url: "ajax/nueva_persona.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $("#guardar_datos").attr("disabled", true).html("Guardando...");
            },
            success: function(response) {
                $("#resultados_ajax").html(response);
                $("#guardar_datos").attr("disabled", false).html("Guardar Pasajero");
                $("#modalRegistroPersona").modal('hide');
                $("#guardar_persona")[0].reset();
                document.getElementById("seccion_menor").classList.add("d-none");
                load(1); // Recarga la lista para ver el nuevo pasajero
            }
        });
    });

});
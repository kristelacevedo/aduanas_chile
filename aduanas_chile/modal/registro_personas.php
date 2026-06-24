<div class="modal fade" id="modalRegistroPersona" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-aduana text-white">
        <h5 class="modal-title fw-bold" id="modalLabel"><i class="fa-solid fa-user-pen me-2"></i>Formulario de Registro de Pasajeros</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-remove="modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <form id="guardar_persona" name="guardar_persona" method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            
            <div class="col-md-6">
              <label class="form-label fw-bold">RUT o Pasaporte <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="rut" required placeholder="Ej: 12345678-9 o Pasaporte">
            </div>
            
            <div class="col-md-6">
              <label class="form-label fw-bold">Nacionalidad <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nacionalidad" required placeholder="Ej: Chilena, Argentina">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Nombres <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nombres" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Apellidos <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="apellidos" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="fecha_nac" name="fecha_nacimiento" required onchange="verificarEdad();">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold">Teléfono de Contacto</label>
              <input type="text" class="form-control" name="telefono" placeholder="+569...">
            </div>

            <div class="col-md-12">
              <label class="form-label fw-bold">Correo Electrónico</label>
              <input type="email" class="form-control" name="email" placeholder="correo@ejemplo.com">
            </div>

            <div class="col-md-12 d-none" id="seccion_menor">
                <div class="alert alert-warning border-warning d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-triangle-exclamation fa-2x me-3 text-warning"></i>
                    <div>
                        <strong>Control de Menor de Edad detectado:</strong> El pasajero es menor de 18 años. De acuerdo a la Regla de Negocio <strong>RN-01</strong>, requiere adjuntar una autorización legal/notarial para cruzar la frontera.
                    </div>
                </div>
                
                <input type="hidden" name="menor_edad" id="menor_edad" value="0">

                <div class="card bg-light border p-3">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-pdf text-danger me-2"></i>Documentación Obligatoria</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Autorización</label>
                            <select class="form-select" name="tipo_autorizacion" id="tipo_autorizacion">
                                <option value="notarial_ambos">Notarial (Viaja con ambos padres)</option>
                                <option value="notarial_un_padre">Notarial (Autorización padre ausente)</option>
                                <option value="judicial">Judicial (Tribunal de Familia Chile)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tribunal / Juzgado Emisor (Si aplica)</label>
                            <input type="text" class="form-control" name="juzgado_emisor" placeholder="Ej: 1er Juzgado de Familia de Santiago">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-danger">Subir Documento Adjunto (Formato PDF) <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="documento_pdf" id="documento_pdf" accept="application/pdf">
                        </div>
                    </div>
                </div>
            </div>

          </div>
          <div class="modal-footer mt-4 px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-aduana" id="guardar_datos">Guardar Pasajero</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

<script>
// Función JS para evaluar la edad del pasajero automáticamente
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
</script>
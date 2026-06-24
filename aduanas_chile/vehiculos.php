<?php
// vehiculos.php
session_start();
// Si no hay sesión iniciada, de vuelta al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once("config/conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Vehículos - Aduanas Chile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .bg-aduana { background-color: #0033a0 !important; }
        .text-aduana { color: #0033a0; }
        .btn-aduana { background-color: #d52b1e; color: white; }
        .btn-aduana:hover { background-color: #b02318; color: white; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

    </div>
</nav>

<div class="container-fluid px-5">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary"><i class="fa-solid fa-car-side text-aduana me-2"></i>Control de Vehículos <small class="fs-6 text-muted">(Acuerdo Chileno-Argentino)</small></h2>
            <button class="btn btn-aduana shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalVehiculo">
                <i class="fa-solid fa-plus me-2"></i>Registrar Ingreso Vehículo
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="q" class="form-control border-start-0" placeholder="Buscar por patente o RUT del propietario..." onkeyup="load(1);">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loader" class="text-center my-3" style="display:none;">
        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
    </div>
    <div id="resultados"></div>
</div>

<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-aduana text-white">
        <h5 class="modal-title fw-bold" id="modalVehiculoLabel"><i class="fa-solid fa-file-signature me-2"></i>Formulario Único de Admisión Temporal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-toggle="modal" data-bs-target="#modalVehiculo" aria-label="Close"></button>
      </div>
      <form id="guardar_vehiculo" method="POST">
          <div class="modal-body p-4">
              <div id="msj_ajax"></div> <div class="row g-3">
                  <div class="col-md-6">
                      <label class="form-label fw-bold text-muted">Patente / Dominio</label>
                      <input type="text" name="patente" class="form-control form-control-lg text-uppercase" placeholder="Ej: AB123CD o AA111AA" required>
                  </div>
                  <div class="col-md-6">
                      <label class="form-label fw-bold text-muted">RUT/Pasaporte Propietario</label>
                      <input type="text" name="rut_propietario" class="form-control form-control-lg" placeholder="Ej: 11222333-K" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label fw-bold text-muted">Marca</label>
                      <input type="text" name="marca" class="form-control" placeholder="Ej: Toyota" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label fw-bold text-muted">Modelo</label>
                      <input type="text" name="modelo" class="form-control" placeholder="Ej: Hilux" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label fw-bold text-muted">Año</label>
                      <input type="number" name="anio" class="form-control" min="1900" max="2027" value="2024" required>
                  </div>
                  <div class="col-md-12">
                      <label class="form-label fw-bold text-muted">Tipo de Vehículo / Franquicia otorgada</label>
                      <select name="tipo_vehiculo" class="form-select form-select-lg" required>
                          <option value="particular">Vehículo Particular (Plazo máximo: 180 días)</option>
                          <option value="diplomatico">Vehículo Diplomático / Misión Oficial (Plazo máximo: 90 días)</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-aduana fw-bold">Autorizar e Imprimir Pases</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Cargar la tabla al abrir la página
$(document).ready(function(){
    load(1);
});

// Función AJAX para buscar vehículos
function load(page){
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: 'ajax/buscar_vehiculos.php?action=ajax&q='+q,
        beforeSend: function(objeto){
            $('#loader').html('<div class="spinner-border text-primary" role="status"></div>');
        },
        success:function(data){
            $("#resultados").html(data);
            $("#loader").html("");
        }
    })
}

// Procesar el formulario de registro por AJAX
$("#guardar_vehiculo").submit(function( event ) {
    event.preventDefault();
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "ajax/nuevo_vehiculo.php",
        data: parametros,
        beforeSend: function(objeto){
            $("#msj_ajax").html('<div class="alert alert-info">Procesando autorización...</div>');
        },
        success: function(datos){
            $("#msj_ajax").html(datos);
            load(1); // Recargar la tabla
        }
    });
});
</script>

</body>
</html>
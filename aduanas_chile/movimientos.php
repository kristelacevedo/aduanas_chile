<?php
// movimientos.php
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
    <title>Control de Movimientos - Aduanas Chile</title>
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

<div class="container-fluid px-5">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary"><i class="fa-solid fa-arrow-right-arrow-left text-aduana me-2"></i>Historial de Movimientos Fronterizos</h2>
            <button type="button" class="btn btn-aduana shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalMovimiento">
                <i class="fa-solid fa-plus me-2"></i>Registrar Entrada / Salida
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="q" class="form-control border-start-0" placeholder="Buscar por RUT de pasajero o Patente..." onkeyup="load(1);">
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

<div class="modal fade" id="modalMovimiento" tabindex="-1" aria-labelledby="modalMovimientoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-aduana text-white">
        <h5 class="modal-title fw-bold" id="modalMovimientoLabel"><i class="fa-solid fa-file-invoice me-2"></i>Nuevo Registro de Cruce</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="guardar_movimiento" method="POST">
          <div class="modal-body p-4">
              <div id="msj_ajax"></div> 
              <div class="row g-3">
                  <div class="col-md-12">
                      <label class="form-label fw-bold text-muted">Tipo de Movimiento</label>
                      <select name="tipo_movimiento" class="form-select form-select-lg" required>
                          <option value="INGRESO">INGRESO (Entrada a Chile)</option>
                          <option value="EGRESO">EGRESO (Salida de Chile)</option>
                      </select>
                  </div>
                  <div class="col-md-12">
                      <label class="form-label fw-bold text-muted">RUT / Pasaporte Pasajero</label>
                      <input type="text" name="rut_persona" class="form-control" placeholder="Ej: 11222333-K" required>
                  </div>
                  <div class="col-md-12">
                      <label class="form-label fw-bold text-muted">Patente del Vehículo <small class="text-muted">(Opcional)</small></label>
                      <input type="text" name="patente_vehiculo" class="form-control text-uppercase" placeholder="Ej: AB123CD">
                  </div>
                  <div class="col-md-12">
                      <label class="form-label fw-bold text-muted">Observaciones</label>
                      <textarea name="observaciones" class="form-control" rows="3" placeholder="Detalles del equipaje, motivos, etc."></textarea>
                  </div>
              </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-aduana fw-bold">Guardar Movimiento</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){
    load(1);
});

function load(page){
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url: 'ajax/buscar_movimientos.php?action=ajax&q='+q,
        beforeSend: function(objeto){
            $('#loader').html('<div class="spinner-border text-primary" role="status"></div>');
        },
        success:function(data){
            $("#resultados").html(data);
            $("#loader").html("");
        }
    })
}

$("#guardar_movimiento").submit(function( event ) {
    event.preventDefault();
    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "ajax/nuevo_movimiento.php",
        data: parametros,
        beforeSend: function(objeto){
            $("#msj_ajax").html('<div class="alert alert-info">Registrando en el sistema...</div>');
        },
        success: function(datos){
            $("#msj_ajax").html(datos);
            load(1);
        }
    });
});
</script>

</body>
</html>
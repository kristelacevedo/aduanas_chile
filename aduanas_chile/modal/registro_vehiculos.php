<!-- modal/registro_vehiculos.php -->
<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="modalVehiculoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-aduana text-white">
        <h5 class="modal-title fw-bold" id="modalVehiculoLabel"><i class="fa-solid fa-file-signature me-2"></i>Formulario Único de Admisión Temporal</h5>
        <!-- Corregido: data-bs-dismiss="modal" para que la X cierre la ventana correctamente -->
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="guardar_vehiculo" method="POST">
          <div class="modal-body p-4">
              <!-- Aquí aparecerá el mensaje de éxito o error devuelto por AJAX -->
              <div id="msj_ajax"></div> 
              
              <div class="row g-3">
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
<?php
// ajax/buscar_movimientos.php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acceso denegado");
}
require_once("../config/conexion.php");

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax') ? $_REQUEST['action'] : '';
if ($action == 'ajax') {
    // Escapar cadena de búsqueda
    $q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
    
    $sTable = "movimientos";
    $sWhere = "";
    
    if ($_REQUEST['q'] != "") {
        $sWhere = "WHERE (rut_persona LIKE '%$q%' OR patente_vehiculo LIKE '%$q%' OR tipo_movimiento LIKE '%$q%')";
    }
    
    // Ordenar por los más recientes
    $sWhere .= " ORDER BY fecha_hora DESC";
    
    $sql = "SELECT * FROM $sTable $sWhere";
    $query = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($query) > 0) {
        ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle bg-white mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Tipo</th>
                        <th>RUT/Pasaporte</th>
                        <th>Vehículo / Patente</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_query_rows_as_array($query) ? mysqli_fetch_array($query) : mysqli_fetch_assoc($query)) {
                        $fecha = date("d/m/Y H:i", strtotime($row['fecha_hora']));
                        $tipo = $row['tipo_movimiento'];
                        $rut = $row['rut_persona'];
                        $patente = !empty($row['patente_vehiculo']) ? strtoupper($row['patente_vehiculo']) : '<span class="text-muted">Ninguno (Peatón)</span>';
                        $obs = !empty($row['observaciones']) ? $row['observaciones'] : '<span class="text-muted">Sin observaciones</span>';
                        
                        // Color según sea ingreso o egreso
                        $badge_color = ($tipo == 'INGRESO') ? 'bg-success' : 'bg-danger';
                        ?>
                        <tr>
                            <td><strong><?php echo $fecha; ?></strong></td>
                            <td><span class="badge <?php echo $badge_color; ?> px-3 py-2 fs-6"><?php echo $tipo; ?></span></td>
                            <td><?php echo $rut; ?></td>
                            <td><?php echo $patente; ?></td>
                            <td><?php echo $obs; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-warning text-center fw-bold" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>No se encontraron movimientos registrados.
        </div>
        <?php
    }
}
?>
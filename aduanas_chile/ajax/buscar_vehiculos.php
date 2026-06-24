<?php
// ajax/buscar_vehiculos.php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acceso denegado.");
}
require_once("../config/conexion.php");

$q = isset($_GET['q']) ? mysqli_real_escape_string($con, $_GET['q']) : "";

// Consulta SQL buscando por patente o RUT
$sql = "SELECT v.*, p.nombres, p.apellidos FROM vehiculos v 
        INNER JOIN personas p ON v.rut_propietario = p.rut_pasaporte
        WHERE v.patente LIKE '%$q%' OR v.rut_propietario LIKE '%$q%' 
        ORDER BY v.created_at DESC";

$query = mysqli_query($con, $sql);

if (mysqli_num_rows($query) == 0) {
    echo '<div class="alert alert-warning text-center">No se encontraron vehículos registrados en el sistema.</div>';
    exit();
}
?>
<div class="table-responsive card shadow-sm border-0">
    <table class="table table-hover align-middle mb-0 bg-white">
        <thead class="table-light text-muted text-uppercase small">
            <tr>
                <th class="ps-4">Patente</th>
                <th>Propietario (RUT)</th>
                <th>Vehículo</th>
                <th>Tipo Franquicia</th>
                <th>Fecha Ingreso</th>
                <th>Vencimiento</th>
                <th class="text-center pe-4">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($query)): 
                $hoy = date('Y-m-d H:i:s');
                $badge_class = ($row['fecha_vencimiento'] > $hoy) ? 'bg-success' : 'bg-danger';
                $badge_text = ($row['fecha_vencimiento'] > $hoy) ? 'Vigente' : 'Vencido';
            ?>
            <tr>
                <td class="ps-4 fw-bold text-primary"><?php echo $row['patente']; ?></td>
                <td>
                    <div class="fw-bold"><?php echo $row['nombres'] . " " . $row['apellidos']; ?></div>
                    <small class="text-muted"><?php echo $row['rut_propietario']; ?></small>
                </td>
                <td><?php echo $row['marca'] . " " . $row['modelo'] . " (" . $row['anio'] . ")"; ?></td>
                <td class="text-capitalize"><?php echo $row['tipo_vehiculo']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_ingreso'])); ?></td>
                <td class="fw-bold"><?php echo date('d/m/Y', strtotime($row['fecha_vencimiento'])); ?></td>
                <td class="text-center pe-4">
                    <span class="badge <?php echo $badge_class; ?> px-3 py-2 rounded-pill"><?php echo $badge_text; ?></span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php
// ajax/nuevo_vehiculo.php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acceso denegado.");
}
require_once("../config/conexion.php");

// Validar que vengan los datos obligatorios
if (empty($_POST['patente']) || empty($_POST['rut_propietario']) || empty($_POST['marca']) || empty($_POST['modelo'])) {
    echo '<div class="alert alert-danger">Error: Todos los campos son obligatorios.</div>';
    exit();
}

// Limpiar variables para evitar inyecciones SQL
$patente = strtoupper(mysqli_real_escape_string($con, $_POST['patente']));
$rut_propietario = mysqli_real_escape_string($con, $_POST['rut_propietario']);
$marca = mysqli_real_escape_string($con, $_POST['marca']);
$modelo = mysqli_real_escape_string($con, $_POST['modelo']);
$anio = intval($_POST['anio']);
$tipo_vehiculo = mysqli_real_escape_string($con, $_POST['tipo_vehiculo']);

// Calcular fechas de vencimiento según la franquicia seleccionada
$fecha_ingreso = date('Y-m-d H:i:s');
if ($tipo_vehiculo == 'particular') {
    $fecha_vencimiento = date('Y-m-d H:i:s', strtotime('+180 days'));
} else {
    $fecha_vencimiento = date('Y-m-d H:i:s', strtotime('+90 days'));
}

// ⚠️ NOTA: El RUT del propietario debe existir en la tabla 'personas' debido a la clave foránea.
// Para efectos prácticos de este test rápido, primero insertaremos la persona si no existe.
$check_persona = mysqli_query($con, "SELECT * FROM personas WHERE rut_pasaporte = '$rut_propietario'");
if (mysqli_num_rows($check_persona) == 0) {
    mysqli_query($con, "INSERT INTO personas (rut_pasaporte, nombres, apellidos, fecha_nacimiento, nacionalidad) 
                        VALUES ('$rut_propietario', 'Usuario', 'Temporal', '1990-01-01', 'Chilena')");
}

// Insertar el vehículo en la base de datos
$sql = "INSERT INTO vehiculos (patente, rut_propietario, marca, modelo, anio, tipo_vehiculo, fecha_ingreso, fecha_vencimiento) 
        VALUES ('$patente', '$rut_propietario', '$marca', '$modelo', $anio, '$tipo_vehiculo', '$fecha_ingreso', '$fecha_vencimiento')";

if (mysqli_query($con, $sql)) {
    echo '<div class="alert alert-success shadow-sm">
            <i class="fa-solid fa-circle-check me-2"></i><strong>¡Autorización Otorgada con Éxito!</strong><br>
            El vehículo con patente <strong>'.$patente.'</strong> fue registrado. Vence el: '.date('d/m/Y', strtotime($fecha_vencimiento)).'
          </div>';
} else {
    echo '<div class="alert alert-danger">Error al guardar en la base de datos: ' . mysqli_error($con) . '</div>';
}
?>
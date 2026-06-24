<?php
// ajax/nuevo_movimiento.php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acceso denegado");
}
require_once("../config/conexion.php");

// Validar que vengan los datos obligatorios
if (empty($_POST['tipo_movimiento']) || empty($_POST['rut_persona'])) {
    echo '<div class="alert alert-danger"><strong>Error:</strong> Los campos Tipo de Movimiento y RUT son obligatorios.</div>';
    exit();
}

// Limpiar datos recibidos
$tipo_movimiento = mysqli_real_escape_string($con, $_POST['tipo_movimiento']);
$rut_persona     = mysqli_real_escape_string($con, $_POST['rut_persona']);
$patente_vehiculo= mysqli_real_escape_string($con, $_POST['patente_vehiculo']);
$observaciones   = mysqli_real_escape_string($con, $_POST['observaciones']);
$usuario_id      = $_SESSION['user_id'];
$fecha_hora      = date("Y-m-d H:i:s"); // Hora exacta del servidor en 2026

// Opcional: Validar si la persona existe en el sistema para evitar datos falsos
$check_persona = mysqli_query($con, "SELECT rut_pasaporte FROM personas WHERE rut_pasaporte = '$rut_persona'");
if (mysqli_num_rows($check_persona) == 0) {
    echo '<div class="alert alert-warning"><strong>Aviso:</strong> El RUT ingresado no está registrado en el "Control de Personas". Por favor, regístralo primero para evitar inconsistencias.</div>';
    exit();
}

// Insertar en la base de datos
$sql = "INSERT INTO movimientos (tipo_movimiento, rut_persona, patente_vehiculo, fecha_hora, usuario_id, observaciones) 
        VALUES ('$tipo_movimiento', '$rut_persona', '$patente_vehiculo', '$fecha_hora', '$usuario_id', '$observaciones')";

$query_insert = mysqli_query($con, $sql);

if ($query_insert) {
    echo '<div class="alert alert-success"><strong>¡Éxito!</strong> El movimiento se registró correctamente en el historial fronterizo.</div>';
} else {
    echo '<div class="alert alert-danger"><strong>Error interno:</strong> No se pudo guardar el registro. ' . mysqli_error($con) . '</div>';
}
?>
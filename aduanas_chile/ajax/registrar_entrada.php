<?php
// ajax/registrar_entrada.php
session_start();

// 1. Seguridad: Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Si no hay sesión, devolvemos un error en formato JSON
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado. La sesión ha expirado.']);
    exit();
}

// 2. Conectar a la base de datos (se usa ../ porque estamos dentro de la carpeta ajax)
require_once("../config/conexion.php");

// 3. Verificar que los datos vengan por el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 4. Recibir y limpiar los datos (mysqli_real_escape_string evita inyecciones SQL)
    // Asumo los campos típicos de una aduana, puedes ajustarlos si en tu formulario se llaman distinto
    $patente       = mysqli_real_escape_string($con, $_POST['patente']);
    $conductor     = mysqli_real_escape_string($con, $_POST['conductor']);
    $tipo_vehiculo = mysqli_real_escape_string($con, $_POST['tipo_vehiculo']);
    $origen        = mysqli_real_escape_string($con, $_POST['origen']);
    $carga         = mysqli_real_escape_string($con, $_POST['carga']);
    
    // Datos automáticos
    $usuario_id    = $_SESSION['user_id']; // El ID del funcionario que ingresó por el login
    $fecha_ingreso = date("Y-m-d H:i:s"); // Fecha y hora actual del servidor
    $estado        = "Ingresado"; // Estado inicial del vehículo en aduana

    // 5. Validación básica: que la patente y el conductor no vengan vacíos
    if (empty($patente) || empty($conductor)) {
        echo json_encode(['status' => 'error', 'message' => 'La patente y el conductor son obligatorios.']);
        exit();
    }

    // 6. Armar la consulta SQL para insertar en la tabla (asumimos que la tabla se llama 'vehiculos')
    $sql = "INSERT INTO vehiculos (patente, conductor, tipo_vehiculo, origen, carga, fecha_ingreso, estado, usuario_id) 
            VALUES ('$patente', '$conductor', '$tipo_vehiculo', '$origen', '$carga', '$fecha_ingreso', '$estado', '$usuario_id')";

    // 7. Ejecutar la consulta y devolver la respuesta al frontend
    if (mysqli_query($con, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Entrada registrada correctamente en el sistema.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar en la base de datos: ' . mysqli_error($con)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de envío no permitido.']);
}
?>
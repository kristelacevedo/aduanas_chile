<?php
// config/conexion.php
$host = "localhost";
$user = "root";       
$password = "";       
$dbname = "aduanas_chile";

// Crear la conexión (ESTA ES LA VARIABLE $con QUE FALTA)
$con = mysqli_connect($host, $user, $password, $dbname);

// Comprobar la conexión
if (mysqli_connect_errno()) {
    echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
    exit();
}
?>
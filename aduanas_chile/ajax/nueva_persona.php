<?php
// ajax/nueva_persona.php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acceso denegado");
}

require_once("../config/conexion.php");

// Validar que los campos obligatorios no estén vacíos
if (empty($_POST['rut']) || empty($_POST['nombres']) || empty($_POST['apellidos']) || empty($_POST['fecha_nacimiento']) || empty($_POST['nacionalidad'])) {
    echo '<div class="alert alert-danger" role="alert"><strong>Error:</strong> Por favor, completa todos los campos obligatorios.</div>';
    exit();
}

// Limpiar datos para evitar inyecciones SQL básicos
$rut = mysqli_real_escape_string($con, $_POST['rut']);
$nacionalidad = mysqli_real_escape_string($con, $_POST['nacionalidad']);
$nombres = mysqli_real_escape_string($con, $_POST['nombres']);
$apellidos = mysqli_real_escape_string($con, $_POST['apellidos']);
$fecha_nacimiento = mysqli_real_escape_string($con, $_POST['fecha_nacimiento']);
$telefono = mysqli_real_escape_string($con, $_POST['telefono']);
$email = mysqli_real_escape_string($con, $_POST['email']);
$menor_edad = intval($_POST['menor_edad']);

// Verificar si la persona ya está registrada para evitar duplicados
$check_query = mysqli_query($con, "SELECT * FROM personas WHERE rut_pasaporte = '$rut'");
if (mysqli_num_rows($check_query) > 0) {
    echo '<div class="alert alert-danger" role="alert"><strong>Error:</strong> El RUT o Pasaporte ya se encuentra registrado en el sistema.</div>';
    exit();
}

// 1. Insertar en la tabla personas
$sql_persona = "INSERT INTO personas (rut_pasaporte, nombres, apellidos, fecha_nacimiento, nacionalidad, telefono, email, menor_edad) 
                VALUES ('$rut', '$nombres', '$apellidos', '$fecha_nacimiento', '$nacionalidad', '$telefono', '$email', '$menor_edad')";
$query_persona = mysqli_query($con, $sql_persona);

if ($query_persona) {
    
    // 2. Si es menor de edad, procesar la autorización y el archivo PDF (RF-09, RF-10, RF-11)
    if ($menor_edad == 1) {
        $tipo_autorizacion = mysqli_real_escape_string($con, $_POST['tipo_autorizacion']);
        $juzgado_emisor = (!empty($_POST['juzgado_emisor'])) ? "'".mysqli_real_escape_string($con, $_POST['juzgado_emisor'])."'" : "NULL";
        
        $sql_autorizacion = "INSERT INTO autorizaciones (rut_menor, tipo_autorizacion, juzgado_emisor, fecha_emision) 
                             VALUES ('$rut', '$tipo_autorizacion', $juzgado_emisor, NOW())";
        mysqli_query($con, $sql_autorizacion);
        $autorizacion_id = mysqli_insert_id($con); // Obtener el ID generado

        // Procesar la subida del archivo PDF
        if (isset($_FILES['documento_pdf']) && $_FILES['documento_pdf']['error'] == 0) {
            
            // Definir y asegurar que exista la carpeta de destino
            $directorio_destino = "../uploads/autorizaciones/";
            if (!file_exists($directorio_destino)) {
                mkdir($directorio_destino, 0777, true);
            }

            $nombre_original = $_FILES['documento_pdf']['name'];
            $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
            
            // Renombrar el archivo de forma única para evitar sobreescrituras y hackeos
            $nuevo_nombre_archivo = "AUT_" . $rut . "_" . time() . "." . $extension;
            $ruta_completa = $directorio_destino . $nuevo_nombre_archivo;

            // Validar que realmente sea un PDF
            if (strtolower($extension) == "pdf") {
                if (move_uploaded_file($_FILES['documento_pdf']['tmp_name'], $ruta_completa)) {
                    
                    // Guardar el registro en la tabla de documentos adjuntos
                    $ruta_db = "uploads/autorizaciones/" . $nuevo_nombre_archivo;
                    $sql_documento = "INSERT INTO documentos_adjuntos (tabla_origen, registro_id, nombre_archivo, ruta_archivo) 
                                      VALUES ('autorizaciones', '$autorizacion_id', '$nuevo_nombre_archivo', '$ruta_db')";
                    mysqli_query($con, $sql_documento);
                    
                } else {
                    echo '<div class="alert alert-warning" role="alert">Persona registrada, pero hubo un error al mover el archivo PDF al servidor.</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">El archivo subido no es un formato PDF válido.</div>';
            }
        }
    }

    echo '<div class="alert alert-success" role="alert"><i class="fa-solid fa-circle-check me-2"></i><strong>¡Éxito!</strong> Pasajero registrado correctamente en el sistema de control.</div>';

} else {
    echo '<div class="alert alert-danger" role="alert"><strong>Error:</strong> No se pudo guardar la información en la base de datos: ' . mysqli_error($con) . '</div>';
}
?>
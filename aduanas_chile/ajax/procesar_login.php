<?php
// ajax/procesar_login.php

// 1. Iniciar la sesión de PHP
session_start();

// 2. Incluir la conexión a la base de datos
require_once("../config/conexion.php");

// 3. Verificar que los datos vengan por el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Limpiar los datos recibidos para evitar inyecciones básicas
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password']; // La contraseña en texto plano que escribió el usuario

    // 4. Consultar si existe el usuario con ese email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $query = mysqli_query($con, $sql);

    if ($query && mysqli_num_rows($query) == 1) {
        $usuario = mysqli_fetch_assoc($query);

        // 5. REQUISITO RN-05 y RF-02: Verificar si el usuario está habilitado
        if ($usuario['habilitado'] == 0) {
            header("Location: ../login.php?error=Usuario deshabilitado. Contacte al administrador.");
            exit();
        }

        // 6. REQUISITO RF-03: Verificar la contraseña usando hashes seguros de PHP
        if (password_verify($password, $usuario['password_hash'])) {
            
            // Si es correcto, guardamos los datos del usuario en la sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_email'] = $usuario['email'];
            $_SESSION['user_rol'] = $usuario['rol'];
            $_SESSION['last_activity'] = time(); // Para controlar la inactividad de 30 min (RF-04)

            // Redireccionar al Dashboard principal del sistema
            header("Location: ../index.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../login.php?error=Contraseña incorrecta.");
            exit();
        }
    } else {
        // El correo no está registrado
        header("Location: ../login.php?error=El correo electrónico no existe.");
        exit();
    }
} else {
    // Si intentan entrar al archivo directamente sin el formulario, los mandamos al login
    header("Location: ../login.php");
    exit();
}
?>
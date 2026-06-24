<?php
// login.php
session_start();
require_once("config/conexion.php");

// Si ya hay sesión iniciada, redirigir al index (que crearemos después)
if (isset($_SESSION['user_id'])) {
    header("Location: vehiculos.php");
    exit();
}

$error = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Buscar al usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
    $query = mysqli_query($con, $sql);

    if (mysqli_num_rows($query) == 1) {
        $row = mysqli_fetch_array($query);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['nombre'];
        $_SESSION['user_rol'] = $row['rol'];
        $_SESSION['user_email'] = $row['email'];
        
        // Redirigir al módulo de vehículos
        header("Location: vehiculos.php");
        exit();
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aduanas Chile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .bg-aduana { background-color: #0033a0; } /* Azul institucional */
        .btn-aduana { background-color: #d52b1e; color: white; } /* Rojo institucional */
        .btn-aduana:hover { background-color: #b02318; color: white; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header bg-aduana text-white text-center py-4">
                    <h3 class="fw-bold mb-0">Aduanas Chile</h3>
                    <p class="mb-0 small">Sistema de Control Fronterizo</p>
                </div>
                <div class="card-body p-4">
                    
                    <?php if ($error != ''): ?>
                        <div class="alert alert-danger shadow-sm" role="alert">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control form-control-lg" required placeholder="admin@aduana.cl" autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg" required placeholder="******">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-aduana btn-lg fw-bold">Ingresar al Sistema</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
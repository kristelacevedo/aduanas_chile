<?php
// index.php
session_start();

// 1. Validar si el usuario ha iniciado sesión. Si no, mandarlo al login.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. REQUISITO RF-04: Control de inactividad de 30 minutos (1800 segundos)
$inactividad = 1800; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactividad)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=Sesión cerrada por inactividad (30 minutos).");
    exit();
}
$_SESSION['last_activity'] = time(); // Actualizar el tiempo de última actividad
?>

<!DOCTYPE html>
<html lang="es" class="h-100">
<?php include("head.php"); ?>
<body class="d-flex flex-column h-100">

<?php include("navbar.php"); ?>

<div class="container my-5">
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm border">
        <div class="container-fluid py-2">
            <h1 class="display-5 fw-bold text-aduana">Bienvenido al Sistema Integrado de Aduanas</h1>
            <p class="col-md-8 fs-4 text-muted">Paso Fronterizo Terrestre - Control de Personas, Vehículos y Declaraciones SAG.</p>
            <hr class="my-4">
            <p>Utilice el menú superior para registrar ingresos, egresos, revisar autorizaciones de menores de edad o tramitar admisiones temporales de vehículos.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fa-solid fa-passport fa-3x text-aduana mb-3"></i>
                    <h5 class="card-title fw-bold">Control de Personas</h5>
                    <p class="card-text text-muted">Registrar pasajeros, verificar alertas de menores de edad y subir autorizaciones notariales.</p>
                    <a href="personas.php" class="btn btn-aduana w-100">Acceder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fa-solid fa-truck-ramp-box fa-3x text-aduana mb-3"></i>
                    <h5 class="card-title fw-bold">Admisión y Salida Temporal</h5>
                    <p class="card-text text-muted">Gestión de vehículos chilenos y extranjeros (Plazo 180 días Acuerdo Chileno-Argentino).</p>
                    <a href="vehiculos.php" class="btn btn-aduana w-100">Acceder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="fa-solid fa-route fa-3x text-aduana mb-3"></i>
                    <h5 class="card-title fw-bold">Registrar Movimiento</h5>
                    <p class="card-text text-muted">Registrar de forma ágil la entrada o salida física de la frontera calculando tiempos de espera.</p>
                    <a href="movimientos.php" class="btn btn-aduana w-100">Acceder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
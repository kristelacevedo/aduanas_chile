<?php
// personas.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Control de inactividad (30 min)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset(); session_destroy();
    header("Location: login.php?error=Sesión expirada."); exit();
}
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="es" class="h-100">
<?php include("head.php"); ?>
<body class="d-flex flex-column h-100">
<?php include("navbar.php"); ?>

<div class="container my-4">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h2 class="text-aduana fw-bold"><i class="fa-solid fa-users me-2"></i>Control de Personas / Pasajeros</h2>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-aduana shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRegistroPersona">
                <i class="fa-solid fa-user-plus me-1"></i> Registrar Persona
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="q" placeholder="Buscar por RUT, Pasaporte o Apellidos..." onkeyup="load(1);">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-light border w-100" onclick="load(1);"><i class="fa-solid fa-rotate"></i> Actualizar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="resultados_ajax"></div><div class="outer_div"></div></div>

<?php 
include("modal/registro_personas.php"); // Incluimos el formulario modal
include("footer.php"); 
?>

<script>
$(document).ready(function(){
    load(1); // Carga la tabla apenas abre la página
});

function load(page){
    var q = $("#q").val();
    $("#loader").fadeIn('slow');
    $.ajax({
        url:'ajax/buscar_personas.php?action=ajax&page='+page+'&q='+q,
        beforeSend: function(objeto){
            $('.outer_div').html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"></div></div>');
        },
        success:function(data){
            $(".outer_div").html(data);
        }
    })
}
</script>
</body>
</html>
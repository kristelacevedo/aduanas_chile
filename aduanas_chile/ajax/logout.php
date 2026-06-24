<?php
// ajax/logout.php
session_start();
session_unset();
session_destroy();
header("Location: ../login.php?mensaje=Sesión cerrada correctamente.");
exit();
?>
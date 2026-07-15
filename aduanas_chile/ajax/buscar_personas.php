<?php
// ajax/buscar_personas.php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit("Acceso denegado");
}

require_once("../config/conexion.php");

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax') ? $_REQUEST['action'] : '';

if ($action == 'ajax') {
    // Escapar la cadena de búsqueda ingresada por el usuario
    $q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
    
    // Base de la consulta SQL
    $tables = "personas p LEFT JOIN autorizaciones a ON p.rut_pasaporte = a.rut_menor 
               LEFT JOIN documentos_adjuntos d ON d.registro_id = a.id AND d.tabla_origen = 'autorizaciones'";
    
    $campos = "p.*, a.tipo_autorizacion, d.ruta_archivo";
    
    // Filtro de búsqueda por RUT, Nombres o Apellidos (RF-06)
    $where = " WHERE (p.rut_pasaporte LIKE '%".$q."%' OR p.nombres LIKE '%".$q."%' OR p.apellidos LIKE '%".$q."%')";
    
    $sql = "SELECT $campos FROM $tables $where ORDER BY p.created_at DESC";
    $query = mysqli_query($con, $sql);

    // Contar filas devueltas
    $numrows = mysqli_num_rows($query);

    if ($numrows > 0) {
        ?>
        <div class="table-responsive shadow-sm rounded border bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-aduana text-white">
                    <tr>
                        <th>RUT / Pasaporte</th>
                        <th>Nombre Completo</th>
                        <th>Nacionalidad</th>
                        <th>Fecha Nac.</th>
                        <th>Condición / Tipo</th>
                        <th>Contacto</th>
                        <th class="text-center">Documento Adjunto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($query)) {
                        $rut = $row['rut_pasaporte'];
                        $nombre_completo = $row['nombres'] . " " . $row['apellidos'];
                        $nacionalidad = $row['nacionalidad'];
                        $fecha_nac = date('d/m/Y', strtotime($row['fecha_nacimiento']));
                        
                        // CORRECCIÓN A PRUEBA DE FALLOS:
                        $menor_edad = isset($row['menor_edad']) ? $row['menor_edad'] : 0;
                        
                        $telefono = (!empty($row['telefono'])) ? $row['telefono'] : 'N/A';
                        $email = (!empty($row['email'])) ? $row['email'] : 'N/A';
                        
                        // Protegemos también estas variables
                        $ruta_pdf = isset($row['ruta_archivo']) ? $row['ruta_archivo'] : '';
                        $tipo_aut = isset($row['tipo_autorizacion']) ? $row['tipo_autorizacion'] : '';
                        ?>
                        <tr>
                            <td class="fw-bold text-secondary"><?php echo $rut; ?></td>
                            <td><?php echo $nombre_completo; ?></td>
                            <td><?php echo $nacionalidad; ?></td>
                            <td><?php echo $fecha_nac; ?></td>
                            <td>
                                <?php if ($menor_edad == 1) { ?>
                                    <span class="badge bg-danger"><i class="fa-solid fa-child me-1"></i> Menor de Edad</span>
                                    <br>
                                    <small class="text-muted text-capitalize" style="font-size: 11px;">
                                        Aut: <?php echo str_replace('_', ' ', $tipo_aut); ?>
                                    </small>
                                <?php } else { ?>
                                    <span class="badge bg-success"><i class="fa-solid fa-user me-1"></i> Adulto</span>
                                <?php } ?>
                            </td>
                            <td>
                                <small><strong>Tlf:</strong> <?php echo $telefono; ?></small><br>
                                <small><strong>Mail:</strong> <?php echo $email; ?></small>
                            </td>
                            <td class="text-center">
                                <?php if ($menor_edad == 1 && !empty($ruta_pdf)) { ?>
                                    <a href="<?php echo $ruta_pdf; ?>" target="_blank" class="btn btn-outline-danger btn-sm shadow-sm" title="Ver Autorización PDF">
                                        <i class="fa-solid fa-file-pdf fa-lg"></i> Ver PDF
                                    </a>
                                <?php } else if ($menor_edad == 1 && empty($ruta_pdf)) { ?>
                                    <span class="text-danger fw-bold text-uppercase small"><i class="fa-solid fa-circle-xmark"></i> Falta PDF</span>
                                <?php } else { ?>
                                    <span class="text-muted small">No requiere</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-info border-info text-center my-4" role="alert">
            <i class="fa-solid fa-circle-info fa-2x mb-2 text-info"></i>
            <div>No se encontraron pasajeros registrados con ese criterio de búsqueda.</div>
        </div>
        <?php
    }
}
?>
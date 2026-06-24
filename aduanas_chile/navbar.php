<nav class="navbar navbar-expand-lg navbar-dark bg-aduana shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-shield-halved me-2"></i>SNA ADUANAS CHILE
        </a>
        <button class="navbar-expand navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fa-solid fa-chart-pie me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="personas.php"><i class="fa-solid fa-users me-1"></i> Control Personas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vehiculos.php"><i class="fa-solid fa-car me-1"></i> Vehículos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="movimientos.php"><i class="fa-solid fa-arrow-right-arrow-left me-1"></i> Movimientos</a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white me-3">
                <i class="fa-solid fa-user-gear me-2"></i>
                <span><?php echo $_SESSION['user_email']; ?></span>
            </div>
            <a href="ajax/logout.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-from-bracket"></i> Salir</a>
        </div>
    </div>
</nav>
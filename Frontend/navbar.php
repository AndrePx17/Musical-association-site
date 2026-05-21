<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/index.php"><span class="text-accent">AM</span> Pedroso</a>
        <?php if(isset($_SESSION["id_user"])) { ?>
            <a class="navbar-brand d-flex align-items-center" href="/index.php">
                <span class="badge bg-danger ms-2" style="font-size: 0.6rem; vertical-align: middle;">ADMIN</span>
            </a>
        <?php } ?>        
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link px-3" href="/index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="/Backend/historia.php">Quem Somos</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="/Backend/noticias.php">Notícias</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="/Backend/horarios.php">Horários</a></li>
                
                <?php if(isset($_SESSION["id_user"])) { ?>
                    <li class="nav-item"><a class="nav-link px-3" href="/Backend/login.php">Criar novo user</a></li>
                    <li class="nav-item ms-lg-3"><a class="nav-link px-3 fw-bold text-white" href="/Backend/admin.php">Painel</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-outline-danger ms-lg-2" href="/Backend/logout.php">Sair</a></li>
                <?php } else { ?>
                <li class="nav-item ms-lg-3"><a class="btn btn-sm btn-accent" href="/Backend/login.php">Área Reservada</a></li>
                <?php }?>
            </ul>
        </div>
    </div>
</nav>

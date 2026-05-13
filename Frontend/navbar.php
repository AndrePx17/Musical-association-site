<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <?php if(isset($_SESSION["id_user"])) { ?>
            <a class="navbar-brand" href="#">AM Pedroso - MODO ADMIN</a>
        <?php } else { ?>
            <a class="navbar-brand" href="/index.php">AM Pedroso</a>
        <?php } ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="/Backend/noticias.php">Notícias</a></li>
                <li class="nav-item"><a class="nav-link" href="/Backend/horarios.php">Horários</a></li>
                <li class="nav-item"><a class="nav-link" href="/Backend/login.php">Login Admin</a></li>
                <?php if(isset($_SESSION["id_user"])) { ?>
                    <li class="nav-item"><a class="nav-link text-danger" href="../Backend/logout.php">Sair</a></li>
                <?php }?>
            </ul>
        </div>
    </div>
</nav>
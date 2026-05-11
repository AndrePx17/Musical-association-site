<?php
session_start();
include "config.php";
$erro="";
$erroReg="";
$sucessoReg="";

include "funcoes.php";
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="../Frontend/style.css" rel="stylesheet">
    <style>
        .card {
            transform: none !important;
            transition: none !important;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
        }
        .card:hover {
            transform: none !important;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <?php include "../Frontend/navbar.php"; ?>
        </div>
    </nav>

    <section class="hero-section login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-4 text-center">Login Admin</h1>
                            <?php if ($erro!=""){?>
                                <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                            <?php } ?>

                            <?php if (isset($_SESSION["id_user"])){?>
                            <hr class="my-3">
                            <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#modalRegisto">
                                + Criar novo utilizador
                            </button>
                            <?php } else{ ?>
                            <form method="post">
                                <label class="form-label">Utilizador:</label>
                                <input type="text" name="username" class="form-control mb-3" required>
                                <label class="form-label">Palavra-passe:</label>
                                <input type="password" name="password" class="form-control mb-4" required>
                                <input type="submit" name="entrar" value="Entrar" class="btn btn-dark w-100">
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION["id_user"])){ ?>
    <div class="modal fade" id="modalRegisto" tabindex="-1" aria-labelledby="modalRegistoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalRegistoLabel">Criar novo utilizador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php if ($erroReg!=""){ ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erroReg) ?></div>
                    <?php } ?>
                    <?php if ($sucessoReg!=""){ ?>
                        <div class="alert alert-success"><?= $sucessoReg ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Nome de utilizador</label>
                            <input type="text" name="reg_username" class="form-control"
                                   value="<?= isset($_POST['reg_username']) ? htmlspecialchars($_POST['reg_username']) : '' ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de utilizador</label>
                            <select name="reg_tipo" class="form-select" required>
                                <option value="1">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Palavra-passe <small class="text-muted">(mín. 8 caracteres)</small></label>
                            <input type="password" name="reg_password" class="form-control" required minlength="8">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirmar palavra-passe</label>
                            <input type="password" name="reg_confirm" class="form-control" required minlength="8">
                        </div>
                        <button type="submit" name="registar" class="btn btn-dark w-100">Criar utilizador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php
        include "../Frontend/footer.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($_SESSION["id_user"]) && ($erroReg!="" || $sucessoReg!="")){ ?>
    <script>
        var modal = new bootstrap.Modal(document.getElementById('modalRegisto'));
        modal.show();
    </script>
    <?php } ?>
</body>
</html>

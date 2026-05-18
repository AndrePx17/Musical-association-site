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
</head>
<body>
    <div id="flex-wrapper">
    <?php include "../Frontend/navbar.php"; ?>

    <section class="section-padding flex-grow-1 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <span class="badge bg-light text-primary px-3 py-2 mb-3">Acesso Restrito</span>
                                <h1 class="fw-bold">Login Admin</h1>
                            </div>
                            
                            <?php if ($erro!=""){?>
                                <div class="alert alert-danger small"><?= htmlspecialchars($erro) ?></div>
                            <?php } ?>

                            <?php if (isset($_SESSION["id_user"])){?>
                                <div class="text-center">
                                    <button class="btn btn-accent w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalRegisto">
                                        + Criar novo utilizador
                                    </button>
                                </div>
                            <?php } else{ ?>
                                <form method="post">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Utilizador</label>
                                        <input type="text" name="username" class="form-control" placeholder="O seu username" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Palavra-passe</label>
                                        <input type="password" name="password" class="form-control" placeholder="********" required>
                                    </div>
                                    <button type="submit" name="entrar" class="btn btn-primary w-100 py-2">Entrar no Sistema</button>
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
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalRegistoLabel">Criar novo utilizador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-4">
                    <?php if ($erroReg!=""){ ?>
                        <div class="alert alert-danger small"><?= htmlspecialchars($erroReg) ?></div>
                    <?php } ?>
                    <?php if ($sucessoReg!=""){ ?>
                        <div class="alert alert-success small"><?= $sucessoReg ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nome de utilizador</label>
                            <input type="text" name="reg_username" class="form-control"
                                   value="<?= isset($_POST['reg_username']) ? htmlspecialchars($_POST['reg_username']) : '' ?>"
                                   required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tipo de utilizador</label>
                            <select name="reg_tipo" class="form-select" required>
                                <option value="1">Administrador</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Palavra-passe <small class="muted fw-normal">(mín. 8 caracteres)</small></label>
                            <input type="password" name="reg_password" class="form-control" required minlength="8">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Confirmar palavra-passe</label>
                            <input type="password" name="reg_confirm" class="form-control" required minlength="8">
                        </div>
                        <button type="submit" name="registar" class="btn btn-primary w-100">Criar utilizador</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    </div>

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

<?php
session_start();
include "config.php";

$erroNoticia="";
$sucessoNoticia="";

if(isset($_GET["sucesso"]) && $_GET["sucesso"]=="1"){
    $sucessoNoticia="Noticia criada com sucesso.";
}

if(isset($_POST["criar_noticia"]) && isset($_SESSION["id_user"])){
    $titulo=trim($_POST["titulo"] ?? "");
    $resumo=trim($_POST["resumo"] ?? "");
    $corpo=trim($_POST["corpo"] ?? "");
    $imagem=trim($_POST["imagem"] ?? "");
    $user_id=(int) $_SESSION["id_user"];

    if($titulo=="" || $resumo=="" || $corpo==""){
        $erroNoticia="Preencha o titulo, o resumo e a mensagem da noticia.";
    }
    else{
        $stmt=$conn->prepare("INSERT INTO TB_noticias (titulo, resumo, corpo, imagem, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $titulo, $resumo, $corpo, $imagem, $user_id);

        try{
            $criada=$stmt->execute();
        }
        catch(mysqli_sql_exception $e){
            $criada=false;
        }

        if($criada){
            header("Location: noticias.php?sucesso=1");
            exit();
        }
        else{
            $erroNoticia="Erro ao criar a noticia. Tente novamente.";
        }
    }
}

$noticias=$conn->query("SELECT id_noticia, titulo, corpo, imagem, data_criacao FROM TB_noticias ORDER BY data_criacao DESC, id_noticia DESC");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Associação Musical de Pedroso</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../Frontend/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
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

        <?php if(isset($_SESSION["id_user"])){ ?>
            <section class="hero-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body p-4">
                                    <h1 class="h3 mb-4 text-center">Criar notícia</h1>

                                    <?php if($erroNoticia!=""){ ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($erroNoticia) ?></div>
                                    <?php } ?>

                                    <?php if($sucessoNoticia!=""){ ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($sucessoNoticia) ?></div>
                                    <?php } ?>

                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">Título</label>
                                            <input type="text" name="titulo" class="form-control"
                                                   value="<?= isset($_POST["titulo"]) ? htmlspecialchars($_POST["titulo"]) : "" ?>"
                                                   required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Resumo</label>
                                            <input type="text" name="resumo" class="form-control"
                                                   value="<?= isset($_POST["resumo"]) ? htmlspecialchars($_POST["resumo"]) : "" ?>"
                                                   required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mensagem</label>
                                            <textarea name="corpo" class="form-control" rows="6" required><?= isset($_POST["corpo"]) ? htmlspecialchars($_POST["corpo"]) : "" ?></textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Imagem</label>
                                            <input type="text" name="imagem" class="form-control"
                                                   placeholder="Ex: Frontend/imagens/noticia.jpg"
                                                   value="<?= isset($_POST["imagem"]) ? htmlspecialchars($_POST["imagem"]) : "" ?>">
                                        </div>
                                        <button type="submit" name="criar_noticia" class="btn btn-dark w-100">Publicar notícia</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>

        <section id="noticias" class="py-5">
            <div class="container">
                <h3 class="mb-4">Todas as Notícias</h3>
                <div class="row g-4">
                    <?php if($noticias && $noticias->num_rows > 0){ ?>
                        <?php while($noticia=$noticias->fetch_assoc()){ ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm">
                                    <?php if($noticia["imagem"] != ""){ ?>
                                        <img src="../<?= htmlspecialchars($noticia["imagem"]) ?>" class="card-img-top" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                                    <?php } ?>
                                    <div class="card-body">
                                        <p class="text-muted small mb-2">
                                            <?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>
                                        </p>
                                        <h5 class="card-title"><?= htmlspecialchars($noticia["titulo"]) ?></h5>
                                        <p class="card-text text-muted"><?= nl2br(htmlspecialchars($noticia["corpo"])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else{ ?>
                        <div class="col-12">
                            <div class="alert alert-info mb-0">Ainda não existem notícias.</div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <?php include "../Frontend/footer.php";?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>

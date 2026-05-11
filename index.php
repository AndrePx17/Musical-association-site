<?php
session_start();
include "Backend/config.php";

$ultimasNoticias=$conn->query("SELECT id_noticia, titulo, resumo, corpo, imagem, data_criacao FROM TB_noticias ORDER BY data_criacao DESC, id_noticia DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Associação Musical de Pedroso</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="Frontend/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <?php include "Frontend/navbar.php"; ?>
        </div>
    </nav>

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 400px;">
                    <h1>Bem-vindo à Associação Musical</h1>
                </div>
            </div>
            </div>
    </div>

    <section id="noticias" class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0">Últimas Notícias</h3>
                <a href="Backend/noticias.php" class="btn btn-outline-primary btn-sm">Ver todas</a>
            </div>
            <div class="row g-4">
                <?php if($ultimasNoticias && $ultimasNoticias->num_rows > 0){ ?>
                    <?php while($noticia=$ultimasNoticias->fetch_assoc()){ ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <?php if($noticia["imagem"] != ""){ ?>
                                    <img src="<?= htmlspecialchars($noticia["imagem"]) ?>" class="card-img-top" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                                <?php } ?>
                                <div class="card-body">
                                    <p class="text-muted small mb-2">
                                        <?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>
                                    </p>
                                    <h5 class="card-title"><?= htmlspecialchars($noticia["titulo"]) ?></h5>
                                    <p class="card-text text-muted"><?= htmlspecialchars($noticia["resumo"] ?? "") ?></p>
                                    <a href="Backend/noticias.php" class="btn btn-outline-primary btn-sm">Ler mais</a>
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
    <?php
        include "Frontend/footer.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

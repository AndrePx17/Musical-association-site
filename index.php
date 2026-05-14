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
                    <h1>Bem-vindo ao site da Associação Musical de Pedroso</h1>
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
                        <?php $imagemModal=$noticia["imagem"] ?? ""; ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <?php if($noticia["imagem"] != ""){ ?>
                                    <img src="<?= htmlspecialchars($noticia["imagem"]) ?>" class="card-img-top noticia-card-img" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                                <?php } ?>
                                <div class="card-body d-flex flex-column">
                                    <p class="text-muted small mb-2">
                                        <?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>
                                    </p>
                                    <h5 class="card-title"><?= htmlspecialchars($noticia["titulo"]) ?></h5>
                                    <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($noticia["resumo"] ?? "") ?></p>
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm align-self-start"
                                            data-bs-toggle="modal"
                                            data-bs-target="#noticiaModal"
                                            data-noticia-titulo="<?= htmlspecialchars($noticia["titulo"], ENT_QUOTES, "UTF-8") ?>"
                                            data-noticia-data="<?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>"
                                            data-noticia-imagem="<?= htmlspecialchars($imagemModal, ENT_QUOTES, "UTF-8") ?>"
                                            data-noticia-corpo="<?= htmlspecialchars($noticia["corpo"], ENT_QUOTES, "UTF-8") ?>">
                                        Ler mais
                                    </button>
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

    <div class="modal fade" id="noticiaModal" tabindex="-1" aria-labelledby="noticiaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl noticia-modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content noticia-modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="noticiaModalLabel"></h5>
                        <p class="text-muted small mb-0" id="noticiaModalData"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body noticia-modal-body">
                    <div class="noticia-modal-media" id="noticiaModalMedia">
                        <img src="" alt="" class="noticia-modal-img d-none" id="noticiaModalImagem">
                    </div>
                    <div class="noticia-modal-copy">
                        <p class="noticia-modal-text mb-0" id="noticiaModalCorpo"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        include "Frontend/footer.php";
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var noticiaModal=document.getElementById("noticiaModal");

        if(noticiaModal){
            noticiaModal.addEventListener("show.bs.modal", function(event){
                var botao=event.relatedTarget;

                if(!botao){
                    return;
                }

                var titulo=botao.getAttribute("data-noticia-titulo") || "";
                var data=botao.getAttribute("data-noticia-data") || "";
                var imagem=botao.getAttribute("data-noticia-imagem") || "";
                var corpo=botao.getAttribute("data-noticia-corpo") || "";
                var imagemModal=document.getElementById("noticiaModalImagem");
                var mediaModal=document.getElementById("noticiaModalMedia");

                document.getElementById("noticiaModalLabel").textContent=titulo;
                document.getElementById("noticiaModalData").textContent=data;
                document.getElementById("noticiaModalCorpo").textContent=corpo;

                if(imagem!=""){
                    imagemModal.src=imagem;
                    imagemModal.alt=titulo;
                    imagemModal.classList.remove("d-none");
                    mediaModal.classList.remove("d-none");
                }
                else{
                    imagemModal.removeAttribute("src");
                    imagemModal.alt="";
                    imagemModal.classList.add("d-none");
                    mediaModal.classList.add("d-none");
                }
            });
        }
    </script>
</body>
</html>

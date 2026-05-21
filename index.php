<?php
session_start();
include "Backend/config.php";

$ultimasNoticias=$conn->query("SELECT n.id_noticia, n.titulo, n.resumo, n.corpo, n.imagem, n.data_criacao, u.username AS autor FROM TB_noticias n JOIN TB_users u ON n.user_id = u.id_user ORDER BY n.data_criacao DESC, n.id_noticia DESC LIMIT 3");
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
    <div id="flex-wrapper">
    <?php include "Frontend/navbar.php"; ?>

    <div id="heroCaurossel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCaurossel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCaurossel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCaurossel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/carrossel-1.png" class="d-block w-100">
                <div class="carousel-caption">
                    <h2>Bem-vindos à Associação Musical de Pedroso</h2>
                    <p class="opacity-75">Há mais de um século a promover a cultura e a arte musical na nossa freguesia.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="Backend/noticias.php" class="btn btn-accent btn-lg">Explorar Notícias</a>
                        <a href="Backend/horarios.php" class="btn btn-outline-light btn-lg">Ver Horários</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/carrossel-2.png" class="d-block w-100">
                <div class="carousel-caption">
                    <h2>A Nossa História</h2>
                    <p class="opacity-75">Descubra as origens e a tradição que nos define ao longo das décadas.</p>
                    <a href="Backend/historia.php" class="btn btn-outline-light btn-lg">Saber Mais</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="images/carrossel-3.png" class="d-block w-100">
                <div class="carousel-caption">
                    <h2>As Nossas Atividades</h2>
                    <p class="opacity-75">Concertos, ensaios e eventos para toda a comunidade.</p>
                    <a href="Backend/horarios.php" class="btn btn-outline-light btn-lg">Ver Horários</a>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCaurossel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCaurossel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Seguinte</span>
        </button>
    </div>
    <section id="Quem_somos_res" class="section-padding">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h2 class="mb-2">Quem Somos?</h2>
                    <p class="mb-0 muted">A Associação Musical de Pedroso (AMP) é uma instituição de utilidade pública fundada em 1980, nascida do entusiasmo e do amor à música que, desde 1975, animava já o Coro Polifónico de Pedroso. Com mais de quatro décadas de história ao serviço da comunidade, somos um polo de dinamização cultural que une a Escola de Música, a Escola de Dança <i>Let's Dance</i>, o Coro Polifónico de Pedroso e a Orquestra de Câmara de Pedroso. Acreditamos que a música e a dança transformam pessoas e comunidades — por isso oferecemos um ensino de qualidade, rigoroso e humano, aberto a todas as idades, com o orgulho de fazer de Pedroso um lugar onde a cultura floresce.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="noticias" class="section-padding">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h2 class="mb-2">Últimas Notícias</h2>
                    <p class="muted mb-0">Fique a par das novidades da nossa associação.</p>
                </div>
                <a href="Backend/noticias.php" class="btn btn-link text-decoration-none fw-bold p-0">Ver todas as notícias &rarr;</a>
            </div>
            <div class="row g-4">
                <?php if($ultimasNoticias && $ultimasNoticias->num_rows > 0){ ?>
                    <?php while($noticia=$ultimasNoticias->fetch_assoc()){ ?>
                        <?php $imagemModal=$noticia["imagem"] ?? ""; ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100">
                                <?php if($noticia["imagem"] != ""){ ?>
                                    <div class="overflow-hidden">
                                        <img src="<?= htmlspecialchars($noticia["imagem"]) ?>" class="card-img-top" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                                    </div>
                                <?php } ?>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-light text-primary align-self-start px-3 py-2">
                                            <?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>
                                        </span>
                                        <span class="small muted">
                                            Por: <?= htmlspecialchars($noticia["autor"]) ?>
                                        </span>
                                    </div>
                                    <h4 class="card-title h5 mb-3"><?= htmlspecialchars($noticia["titulo"]) ?></h4>
                                    <p class="card-text muted flex-grow-1 small"><?= htmlspecialchars($noticia["resumo"] ?? "") ?></p>
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm mt-3 align-self-start"
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
                        <div class="alert border shadow-sm p-4 text-center mb-0">
                            <p class="mb-0 muted">Ainda não existem notícias publicadas.</p>
                        </div>
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
                        <p class="muted small mb-0" id="noticiaModalData"></p>
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
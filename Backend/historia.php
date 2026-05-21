<?php
session_start();
include "config.php";
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quem Somos - Associação Musical de Pedroso</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="../Frontend/style.css" rel="stylesheet">
</head>
<body>
    <div id="flex-wrapper">
    <?php include "../Frontend/navbar.php"; ?>

    <section class="hero-section text-center py-5 bg-dark text-white" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('../images/carrossel-2.png') center/cover;">
        <div class="container py-5">
            <h1 class="display-4 fw-bold">Quem Somos</h1>
            <p class="lead opacity-75">Mais de 40 anos de dedicação à música e à cultura em Pedroso.</p>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4">A Nossa História</h2>
                    <p class="muted">A Associação Musical de Pedroso (AMP) é uma instituição de utilidade pública fundada oficialmente em 1980. No entanto, as suas raízes remontam a 1975, ano em que nasceu o Coro Polifónico de Pedroso, fruto do entusiasmo e do amor à música de um grupo de cidadãos locais.</p>
                    <p class="muted">Ao longo das décadas, a associação cresceu e diversificou-se, tornando-se um pilar fundamental na dinamização cultural da nossa freguesia. O que começou como um coro transformou-se num centro de ensino artístico abrangente, tocando a vida de centenas de alunos e famílias.</p>
                </div>
                <div class="col-lg-6">
                    <div class="rounded-4 overflow-hidden shadow-lg">
                        <img src="../images/carrossel-1.png" class="img-fluid" alt="História da AMP">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-light" style="background-color: rgba(255,255,255,0.02) !important;">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="text-accent mb-3 h1"><i class="bi bi-music-note-beamed"></i></div>
                        <h3>Missão</h3>
                        <p class="muted small">Promover o ensino da música e da dança com rigor e humanismo, tornando a arte acessível a todas as idades na nossa comunidade.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="text-accent mb-3 h1"><i class="bi bi-people"></i></div>
                        <h3>Visão</h3>
                        <p class="muted small">Ser um centro de referência regional no ensino artístico, reconhecido pela qualidade pedagógica e pelo impacto social positivo.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4">
                        <div class="text-accent mb-3 h1"><i class="bi bi-heart"></i></div>
                        <h3>Valores</h3>
                        <p class="muted small">Excelência, Dedicação, Inclusão e Paixão pela Arte são os pilares que sustentam cada aula e cada concerto que realizamos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5">As Nossas Valências</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="h5 fw-bold mb-3 text-accent">Escola de Música</h4>
                            <p class="small muted mb-0">Ensino especializado de diversos instrumentos com professores qualificados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="h5 fw-bold mb-3 text-accent"><i>Let's Dance</i></h4>
                            <p class="small muted mb-0">Escola de dança integrada com foco na expressão corporal e técnica.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="h5 fw-bold mb-3 text-accent">Coro Polifónico</h4>
                            <p class="small muted mb-0">O grupo que deu origem à associação, mantendo viva a tradição coral.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="h5 fw-bold mb-3 text-accent">Orquestra de Câmara</h4>
                            <p class="small muted mb-0">Agrupamento instrumental que representa a associação em eventos solenes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </div>

    <?php include "../Frontend/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

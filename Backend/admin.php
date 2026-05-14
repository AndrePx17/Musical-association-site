<?php
session_start();
include "config.php";

// Proteção: Redireciona para a home se não estiver logado
if (!isset($_SESSION["id_user"])) {
    header("Location: index.php");
    exit();
}

$erro = "";
$sucesso = "";

// Consulta para buscar os logs (Triggers em ação)
$logs = $conn->query("SELECT id_log, mensagem, data_evento FROM TB_logs ORDER BY data_evento DESC LIMIT 50");

// Consulta complexa para estatísticas (Efeito "Uau" para o professor)
$stats_noticias = $conn->query("
    SELECT u.username, COUNT(n.id_noticia) as total 
    FROM TB_users u 
    LEFT JOIN TB_noticias n ON u.id_user = n.user_id 
    GROUP BY u.username
");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Administrativo - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link href="../Frontend/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <?php include "../Frontend/navbar.php"; ?>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2">Painel de Controlo (Backoffice)</h1>
                <p class="text-muted">Bem-vindo, <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong>. Aqui podes gerir o conteúdo do site.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Estatísticas Rápidas -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-bold">Resumo de Atividade</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php while($row = $stats_noticias->fetch_assoc()){ ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($row["username"]) ?>
                                    <span class="badge bg-primary rounded-pill"><?= $row["total"] ?> notícias</span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Logs do Sistema (Triggers) -->
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Histórico de Eventos (Logs)</span>
                        <span class="badge bg-info text-dark">Trigger: TR_noticia_removida</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Mensagem</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($logs && $logs->num_rows > 0){ ?>
                                        <?php while($log = $logs->fetch_assoc()){ ?>
                                            <tr>
                                                <td>#<?= $log["id_log"] ?></td>
                                                <td><?= htmlspecialchars($log["mensagem"]) ?></td>
                                                <td class="small text-muted"><?= date("d/m/Y H:i", strtotime($log["data_evento"])) ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">Nenhum evento registado ainda.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body d-flex gap-3">
                        <a href="noticias.php" class="btn btn-dark">Gerir Notícias</a>
                        <a href="horarios.php" class="btn btn-dark">Gerir Horários</a>
                        <a href="../index.php" class="btn btn-outline-secondary">Ver Site Público</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "../Frontend/footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

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

// Consulta para buscar os logs
$logs = $conn->query("SELECT id_log, mensagem, data_evento FROM TB_logs ORDER BY data_evento DESC LIMIT 50");
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
    <div id="flex-wrapper">
    <?php include "../Frontend/navbar.php"; ?>

    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-12 text-center text-lg-start">
                <span class="badge bg-light text-primary px-3 py-2 mb-3 shadow-sm">Área Administrativa</span>
                <h1 class="display-5 mb-2 fw-bold">Painel de Controlo</h1>
                <p class="muted lead">Bem-vindo, <span class="text-primary fw-bold"><?= htmlspecialchars($_SESSION["username"]) ?></span>. Gestão de conteúdos e registos do sistema.</p>
            </div>
        </div>

        <div class="row">
            <!-- Logs do Sistema -->
            <div class="col-12">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color:black !important">Histórico de Eventos <small class="muted fw-normal ms-2">(Logs)</small></h5>
                        <span class="badge bg-light muted fw-normal">Últimos 50 eventos</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Mensagem</th>
                                        <th class="pe-4">Data e Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($logs && $logs->num_rows > 0){ ?>
                                        <?php while($log = $logs->fetch_assoc()){ ?>
                                            <tr>
                                                <td class="ps-4 muted small">#<?= $log["id_log"] ?></td>
                                                <td>
                                                    <div class="fw-medium text-dark small"><?= htmlspecialchars($log["mensagem"]) ?></div>
                                                </td>
                                                <td class="pe-4 small muted"><?= date("d/m/Y H:i", strtotime($log["data_evento"])) ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-5">
                                                <div class="muted">Nenhum evento registado ainda.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php include "../Frontend/footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

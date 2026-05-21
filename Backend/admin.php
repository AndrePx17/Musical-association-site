<?php
session_start();
include "config.php";

if (!isset($_SESSION["id_user"])){
    header("Location: index.php");
    exit();
}

$erro="";
$sucesso="";

// Consulta para buscar os logs
$logs=$conn->query("SELECT id_log, mensagem, data_evento FROM TB_logs ORDER BY data_evento DESC LIMIT 50");

// Consulta para buscar o estado dos horários através da view
$estadoHorarios=$conn->query("SELECT * FROM VW_gestao_horarios ORDER BY instrumento ASC");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Administrativo</title>
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

        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm h-100 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold" style="color:black !important">Estado dos Horários</h5>
                    </div>
                    <div class="card-body p-0 bg-white">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase" style="font-size: 0.75rem;">
                                    <tr>
                                        <th class="ps-4">Instrumento</th>
                                        <th>Estado</th>
                                        <th class="pe-4">Atualização</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($estadoHorarios && $estadoHorarios->num_rows > 0){ ?>
                                        <?php while($item=$estadoHorarios->fetch_assoc()){ 
                                            $badgeClass=($item["estado"]=="Atualizado") ? "bg-success-subtle text-success" : "bg-warning-subtle text-warning";
                                        ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold small"><?= htmlspecialchars($item["instrumento"]) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $badgeClass ?> px-2 py-1" style="font-size: 0.65rem;">
                                                        <?= htmlspecialchars($item["estado"]) ?>
                                                    </span>
                                                </td>
                                                <td class="pe-4 small muted" style="font-size: 0.75rem;">
                                                    <?= $item["data_atualizacao"] ? date("d/m", strtotime($item["data_atualizacao"])) : "---" ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <a href="horarios.php" class="btn btn-outline-primary btn-sm w-100">Gerir Horários</a>
                    </div>
                </div>
            </div>

            <!-- Logs do Sistema -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color:black !important">Histórico de Eventos <small class="muted fw-normal ms-2">(Logs)</small></h5>
                        <span class="badge bg-light muted fw-normal">Últimos 50</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-uppercase" style="font-size: 0.75rem;">
                                    <tr>
                                        <th class="ps-4">ID</th>
                                        <th>Mensagem</th>
                                        <th class="pe-4">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($logs && $logs->num_rows>0){ ?>
                                        <?php while($log = $logs->fetch_assoc()){ ?>
                                            <tr>
                                                <td class="ps-4 muted small">#<?= $log["id_log"] ?></td>
                                                <td>
                                                    <div class="fw-medium text-dark small"><?= htmlspecialchars($log["mensagem"]) ?></div>
                                                </td>
                                                <td class="pe-4 small muted text-nowrap"><?= date("d/m H:i", strtotime($log["data_evento"])) ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else{ ?>
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

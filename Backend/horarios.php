<?php
session_start();
include "config.php";
include "funcoes.php";

$erroHorario="";
$sucessoHorario="";

if(isset($_GET["sucesso"]) && $_GET["sucesso"]=="1"){
    $sucessoHorario="Horário atualizado com sucesso.";
}

if(isset($_GET["removido"]) && $_GET["removido"]=="1"){
    $sucessoHorario="Horário removido com sucesso.";
}

if(isset($_GET["instrumento"]) && $_GET["instrumento"]=="1"){
    $sucessoHorario="Instrumento adicionado com sucesso.";
}

if(isset($_POST["adicionar_instrumento"]) && isset($_SESSION["id_user"])){
    $novoInstrumento=trim(strtolower($_POST["novo_instrumento"] ?? ""));

    if($novoInstrumento==""){
        $erroHorario="Escreva o nome do instrumento.";
    }
    else{
        $stmt=$conn->prepare("SELECT id_instrumento FROM TB_instrumentos WHERE nome=?");
        $stmt->bind_param("s", $novoInstrumento);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows>0){
            $erroHorario="Esse instrumento já existe.";
        }
        else{
            $stmt->close();
            $stmt=$conn->prepare("INSERT INTO TB_instrumentos (nome) VALUES (?)");
            $stmt->bind_param("s", $novoInstrumento);

            if($stmt->execute()){
                header("Location: horarios.php?instrumento=1");
                exit();
            }
            else{
                $erroHorario="Erro ao adicionar o instrumento.";
            }
        }
        $stmt->close();
    }
}

if(isset($_POST["publicar_horario"]) && isset($_SESSION["id_user"])){
    $instrumentoId=(int) ($_POST["instrumento_id"] ?? 0);
    $instrumento="";

    if($instrumentoId<=0 || !isset($_FILES["imagem"]) || $_FILES["imagem"]["error"]!=UPLOAD_ERR_OK){
        $erroHorario="Preencha todos os campos corretamente.";
    }
    else{
        $stmt=$conn->prepare("SELECT nome FROM TB_instrumentos WHERE id_instrumento=?");
        $stmt->bind_param("i", $instrumentoId);
        $stmt->execute();
        $stmt->bind_result($instrumento);

        if(!$stmt->fetch()){
            $erroHorario="Escolha um instrumento válido.";
            $stmt->close();
        }
        else{
            $stmt->close();
            $imagem = uploadImagem($_FILES["imagem"], "horarios", $erroHorario);

            if($imagem !== false){
                $stmt=$conn->prepare("INSERT INTO TB_horarios (instrumento, imagem) VALUES (?, ?) ON DUPLICATE KEY UPDATE imagem = VALUES(imagem)");
                $stmt->bind_param("ss", $instrumento, $imagem);

                if($stmt->execute()){
                    header("Location: horarios.php?sucesso=1");
                    exit();
                }
                else{
                    $erroHorario="Erro ao guardar na base de dados.";
                }
                $stmt->close();
            }
        }
    }
}

if(isset($_POST["remover_horario"]) && isset($_SESSION["id_user"])){
    $instrumentoRemover=trim($_POST["instrumento_remover"] ?? "");

    if($instrumentoRemover==""){
        $erroHorario="Escolha um horário para remover.";
    }
    else{
        $stmt=$conn->prepare("DELETE FROM TB_horarios WHERE instrumento=?");
        $stmt->bind_param("s", $instrumentoRemover);

        if($stmt->execute()){
            header("Location: horarios.php?removido=1");
            exit();
        }
        else{
            $erroHorario="Erro ao remover o horário.";
        }
        $stmt->close();
    }
}

$instrumentos=[];
$resultadoInstrumentos=$conn->query("SELECT id_instrumento, nome FROM TB_instrumentos ORDER BY nome ASC");
if($resultadoInstrumentos){
    while($instrumento=$resultadoInstrumentos->fetch_assoc()){
        $instrumentos[]=$instrumento;
    }
}

$horarios=[];
$resultado=$conn->query("SELECT instrumento, imagem FROM TB_horarios ORDER BY instrumento ASC");
if($resultado){
    while($horario=$resultado->fetch_assoc()){
        $horarios[]=$horario;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Horários – Associação Musical de Pedroso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body>
    <div id="flex-wrapper">
    <?php include "../Frontend/navbar.php"; ?>

    <?php if(isset($_SESSION["id_user"])){ ?>
        <section class="section-padding border-bottom">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4 p-md-5">
                                <div class="text-center mb-4">
                                    <span class="badge bg-light px-3 py-2 mb-2">Administração</span>
                                    <h1 class="fw-bold">Gestão de Horários</h1>
                                </div>

                                <?php if($erroHorario!=""){ ?>
                                    <div class="alert alert-danger small"><?= htmlspecialchars($erroHorario) ?></div>
                                <?php } ?>

                                <?php if($sucessoHorario!=""){ ?>
                                    <div class="alert alert-success small"><?= htmlspecialchars($sucessoHorario) ?></div>
                                <?php } ?>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="mb-0 fw-bold small">Instrumentos Registados</h6>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#adicionarInstrumento">
                                        + Adicionar Instrumento
                                    </button>
                                </div>

                                <div class="collapse mb-4" id="adicionarInstrumento">
                                    <div class="p-3 rounded-3" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                                        <form method="post">
                                            <div class="input-group">
                                                <input type="text" name="novo_instrumento" class="form-control" placeholder="Ex: Viola d'Arco" style="border-right: none;">
                                                <button type="submit" name="adicionar_instrumento" class="btn btn-primary btn-sm px-4">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Instrumento</label>
                                        <select name="instrumento_id" class="form-select" required>
                                            <option value="">-- Selecionar --</option>
                                            <?php foreach($instrumentos as $instrumento){ ?>
                                                <option value="<?= (int) $instrumento["id_instrumento"] ?>">
                                                    <?= htmlspecialchars($instrumento["nome"]) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Imagem do Horário</label>
                                        <input type="file" name="imagem" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                        <div class="form-text small">Formatos suportados: JPG, PNG, JPEG ou GIF.</div>
                                    </div>
                                    <button type="submit" name="publicar_horario" class="btn btn-primary w-100 py-2">Publicar / Atualizar Horário</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <section id="exibir-horarios" class="section-padding">
        <div class="container">
            <div class="mb-5">
                <h2 class="h1 fw-bold">Consulta de Horários</h2>
                <p class="muted">Selecione o instrumento para visualizar o respetivo horário das aulas.</p>
            </div>

            <?php if(empty($horarios)){ ?>
                <div class="alert border shadow-sm p-5 text-center mb-0">
                    <p class="mb-0 muted">Ainda não existem horários publicados.</p>
                </div>
            <?php } else{ ?>
                <div class="row mb-5">
                    <div class="col-md-5 col-lg-4">
                        <div class="card border-0 shadow-sm p-4">
                            <label for="selecionarInstrumento" class="form-label fw-bold small mb-3">Escolha o Instrumento:</label>
                            <select id="selecionarInstrumento" class="form-select form-select-lg">
                                <option value="">-- Selecionar --</option>
                                <?php foreach($horarios as $horario){
                                    $idLimpo=preg_replace('/[^a-z0-9]/', '-', strtolower($horario["instrumento"]));
                                ?>
                                    <option value="horario-<?= $idLimpo ?>">
                                        <?= htmlspecialchars($horario["instrumento"]) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="areaConteudoHorario" class="row justify-content-center">
                    <div class="col-lg-10">
                        <?php foreach($horarios as $horario){
                            $idLimpo=preg_replace('/[^a-z0-9]/', '-', strtolower($horario["instrumento"]));
                        ?>
                            <div id="horario-<?= $idLimpo ?>" class="horario-item d-none text-center">
                                <div class="card border-0 shadow-lg p-4 p-md-5">
                                    <h4 class="mb-4 text-primary fw-bold"><?= htmlspecialchars($horario["instrumento"]) ?></h4>
                                    <div class="overflow-hidden rounded-3 shadow-sm border mb-4">
                                        <img src="../<?= htmlspecialchars($horario["imagem"]) ?>" alt="Horário" class="img-fluid">
                                    </div>

                                    <?php if(isset($_SESSION["id_user"])){ ?>
                                        <form method="post" class="mt-4 pt-4 border-top" onsubmit="return confirm('Tem a certeza que quer remover este horário?')">
                                            <input type="hidden" name="instrumento_remover" value="<?= htmlspecialchars($horario["instrumento"]) ?>">
                                            <button type="submit" name="remover_horario" class="btn btn-outline-danger btn-sm">Remover Horário</button>
                                        </form>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
    </div>

    <?php include "../Frontend/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var selecionarInstrumento = document.getElementById("selecionarInstrumento");

        if(selecionarInstrumento){
            selecionarInstrumento.addEventListener("change", function(){
                var horarios=document.querySelectorAll(".horario-item");

                horarios.forEach(function(horario){
                    horario.classList.add("d-none");
                });

                var escolhido=this.value;

                if(escolhido!=""){
                    var horarioEscolhido=document.getElementById(escolhido);

                    if(horarioEscolhido){
                        horarioEscolhido.classList.remove("d-none");
                    }
                }
            });
        }
    </script>
</body>
</html>

<?php
session_start();
include "config.php";

$erroHorario="";
$sucessoHorario="";

function limparNomeInstrumento($nome)
{
    return trim(preg_replace('/\s+/', ' ', $nome));
}

function criarCodigoInstrumento($nome)
{
    $codigo=iconv("UTF-8", "ASCII//TRANSLIT", $nome);

    if($codigo===false){
        $codigo=$nome;
    }

    $codigo=strtolower($codigo);
    $codigo=preg_replace('/[^a-z0-9]/', '', $codigo);

    return $codigo;
}

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
    $novoInstrumento=limparNomeInstrumento($_POST["novo_instrumento"] ?? "");
    $codigoInstrumento=criarCodigoInstrumento($novoInstrumento);

    if($novoInstrumento=="" || $codigoInstrumento==""){
        $erroHorario="Escreva o nome do instrumento.";
    }
    else{
        $stmt=$conn->prepare("SELECT id_instrumento FROM TB_instrumentos WHERE codigo=?");
        $stmt->bind_param("s", $codigoInstrumento);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows>0){
            $erroHorario="Esse instrumento já existe.";
        }
        else{
            $stmt->close();
            $stmt=$conn->prepare("INSERT INTO TB_instrumentos (nome, codigo) VALUES (?, ?)");
            $stmt->bind_param("ss", $novoInstrumento, $codigoInstrumento);

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
            $target_path="../images/horarios/";
            $nomeImagem=basename($_FILES["imagem"]["name"]);
            $target_file=$target_path.$nomeImagem;
            $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $imagem="images/horarios/".$nomeImagem;

            if($imageFileType!="jpg" && $imageFileType!="png" && $imageFileType!="jpeg" && $imageFileType!="gif"){
                $erroHorario="Formato inválido. Use JPG, PNG, JPEG ou GIF.";
            }
            else{
                if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)){
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
                else{
                    $erroHorario="Erro ao guardar o ficheiro no servidor.";
                }
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
        .horario-img {
            max-width: 900px;
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
                                <h1 class="h3 mb-4 text-center">Gestão de Horários</h1>

                                <?php if($erroHorario!=""){ ?>
                                    <div class="alert alert-danger"><?= htmlspecialchars($erroHorario) ?></div>
                                <?php } ?>

                                <?php if($sucessoHorario!=""){ ?>
                                    <div class="alert alert-success"><?= htmlspecialchars($sucessoHorario) ?></div>
                                <?php } ?>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold">Instrumentos</span>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#adicionarInstrumento">
                                        + Adicionar
                                    </button>
                                </div>

                                <div class="collapse mb-4" id="adicionarInstrumento">
                                    <form method="post">
                                        <div class="input-group">
                                            <input type="text" name="novo_instrumento" class="form-control" placeholder="Ex: Viola d'Arco">
                                            <button type="submit" name="adicionar_instrumento" class="btn btn-outline-dark">Guardar</button>
                                        </div>
                                    </form>
                                </div>

                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Instrumento</label>
                                        <select name="instrumento_id" class="form-select" required>
                                            <option value="">-- Selecionar --</option>
                                            <?php foreach($instrumentos as $instrumento){ ?>
                                                <option value="<?= (int) $instrumento["id_instrumento"] ?>">
                                                    <?= htmlspecialchars($instrumento["nome"]) ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Imagem do Horário</label>
                                        <input type="file" name="imagem" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                        <div class="form-text">Formatos: JPG, PNG, JPEG ou GIF.</div>
                                    </div>
                                    <button type="submit" name="publicar_horario" class="btn btn-dark w-100">Publicar / Atualizar Horário</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <section id="exibir-horarios" class="py-5">
        <div class="container">
            <h3 class="mb-4">Consulta de Horários</h3>

            <?php if(empty($horarios)){ ?>
                <div class="alert alert-info">Ainda não existem horários publicados.</div>
            <?php } else{ ?>
                <div class="row mb-5">
                    <div class="col-md-4">
                        <label for="selecionarInstrumento" class="form-label fw-bold">Escolha o Instrumento:</label>
                        <select id="selecionarInstrumento" class="form-select">
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

                <div id="areaConteudoHorario">
                    <?php foreach($horarios as $horario){
                        $idLimpo=preg_replace('/[^a-z0-9]/', '-', strtolower($horario["instrumento"]));
                    ?>
                        <div id="horario-<?= $idLimpo ?>" class="horario-item d-none text-center">
                            <h4 class="mb-4 text-primary"><?= htmlspecialchars($horario["instrumento"]) ?></h4>
                            <img src="../<?= htmlspecialchars($horario["imagem"]) ?>" alt="Horário" class="horario-img">

                            <?php if(isset($_SESSION["id_user"])){ ?>
                                <form method="post" class="mt-4" onsubmit="return confirm('Tem a certeza que quer remover este horário?')">
                                    <input type="hidden" name="instrumento_remover" value="<?= htmlspecialchars($horario["instrumento"]) ?>">
                                    <button type="submit" name="remover_horario" class="btn btn-outline-danger">Remover Horário</button>
                                </form>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>

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

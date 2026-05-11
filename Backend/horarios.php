<?php
session_start();
include "config.php";

$erroHorario="";
$sucessoHorario="";

if(isset($_GET["sucesso"]) && $_GET["sucesso"]=="1"){
    $sucessoHorario="Horário atualizado com sucesso!";
}

if(isset($_POST["publicar_horario"]) && isset($_SESSION["id_user"])){
    $instrumento=trim($_POST["instrumento"] ?? "");
    $imagem=$_FILES["imagem"] ?? null;

    if($instrumento=="" || !$imagem || $imagem["error"]!=UPLOAD_ERR_OK){
        $erroHorario="Preencha todos os campos corretamente.";
    }
    else{
        $extensoesPermitidas=["jpg","jpeg","png","webp"];
        $ext=strtolower(pathinfo($imagem["name"],PATHINFO_EXTENSION));

        if(!in_array($ext,$extensoesPermitidas)){
            $erroHorario="Formato inválido. Use JPG, PNG ou WEBP.";
        }
        else{
            $pasta="/images/horarios/";
            $nomeImagem="horario_".preg_replace('/[^a-z0-9]/','_',strtolower($instrumento)).".".$ext;
            $destino=$pasta.$nomeImagem;

            if(move_uploaded_file($imagem["tmp_name"],$destino)){
                $caminhoRelativo="images/horarios/".$nomeImagem;

                $stmt=$conn->prepare("
                    INSERT INTO TB_horarios (instrumento, imagem)
                    VALUES (?, ?)
                    ON DUPLICATE KEY UPDATE imagem = VALUES(imagem)
                ");
                $stmt->bind_param("ss",$instrumento,$caminhoRelativo);
                
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

$horarios=[];
$resultado=$conn->query("SELECT instrumento, imagem FROM TB_horarios ORDER BY instrumento ASC");
while($row=$resultado->fetch_assoc()){
    $horarios[]=$row;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Horários – Associação Musical de Pedroso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../Frontend/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .card{transform:none !important;transition:none !important;box-shadow:0 .125rem .25rem rgba(0,0,0,.075) !important;}
        .horario-img{max-width:100%;height:auto;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <?php include "../Frontend/navbar.php";?>
        </div>
    </nav>

    <?php if(isset($_SESSION["id_user"])){?>
        <section class="hero-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-body p-4">
                                <h1 class="h3 mb-4 text-center">Gestão de Horários</h1>

                                <?php if($erroHorario!=""){?>
                                    <div class="alert alert-danger"><?=htmlspecialchars($erroHorario)?></div>
                                <?php }?>

                                <?php if($sucessoHorario!=""){?>
                                    <div class="alert alert-success"><?=htmlspecialchars($sucessoHorario)?></div>
                                <?php }?>

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Instrumento</label>
                                        <input type="text" name="instrumento" class="form-control" placeholder="Ex: Piano, Violino..." required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Imagem do Horário</label>
                                        <input type="file" name="imagem" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                                        <div class="form-text">Formatos: JPG, PNG ou WEBP.</div>
                                    </div>
                                    <button type="submit" name="publicar_horario" class="btn btn-dark w-100">Publicar / Atualizar Horário</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php }?>

    <section id="exibir-horarios" class="py-5">
        <div class="container">
            <h3 class="mb-4">Consulta de Horários</h3>

            <?php if(empty($horarios)){?>
                <div class="alert alert-info">Ainda não existem horários publicados.</div>
            <?php }else{?>
                
                <div class="row mb-5">
                    <div class="col-md-4">
                        <label for="selecionarInstrumento" class="form-label fw-bold">Escolha o Instrumento:</label>
                        <select id="selecionarInstrumento" class="form-select">
                            <option value="">-- Selecionar --</option>
                            <?php foreach($horarios as $h){
                                $idLimpo=preg_replace('/[^a-z0-9]/','-',strtolower($h["instrumento"]));
                            ?>
                                <option value="horario-<?=$idLimpo?>">
                                    <?=htmlspecialchars($h["instrumento"])?>
                                </option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div id="areaConteudoHorario">
                    <?php foreach($horarios as $h){
                        $idLimpo=preg_replace('/[^a-z0-9]/','-',strtolower($h["instrumento"]));
                    ?>
                        <div id="horario-<?=$idLimpo?>" class="horario-item d-none text-center">
                            <h4 class="mb-4 text-primary"><?=htmlspecialchars($h["instrumento"])?></h4>
                            <img src="../<?=htmlspecialchars($h["imagem"])?>" alt="Horário" class="horario-img" style="max-width:900px;">
                        </div>
                    <?php }?>
                </div>
            <?php }?>
        </div>
    </section>

    <?php include "../Frontend/footer.php";?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("selecionarInstrumento")?.addEventListener("change",function(){
            document.querySelectorAll(".horario-item").forEach(el=>el.classList.add("d-none"));
            const escolhido=this.value;
            if(escolhido){
                const alvo=document.getElementById(escolhido);
                if(alvo)alvo.classList.remove("d-none");
            }
        });
    </script>
</body>
</html>
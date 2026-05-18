<?php
session_start();
include "config.php";

$erroNoticia="";
$sucessoNoticia="";

if(isset($_GET["sucesso"]) && $_GET["sucesso"]=="1"){
    $sucessoNoticia="Noticia criada com sucesso.";
}

if(isset($_GET["removida"]) && $_GET["removida"]=="1"){
    $sucessoNoticia="Noticia removida com sucesso.";
}

if(isset($_GET["editada"]) && $_GET["editada"]=="1"){
    $sucessoNoticia="Noticia editada com sucesso.";
}

if(isset($_POST["remover_noticia"]) && isset($_SESSION["id_user"])){
    $noticiaId=(int) ($_POST["id_noticia"] ?? 0);

    if($noticiaId<=0){
        $erroNoticia="Escolha uma noticia válida para remover.";
    }
    else{
        $stmt=$conn->prepare("DELETE FROM TB_noticias WHERE id_noticia=?");
        $stmt->bind_param("i", $noticiaId);

        if($stmt->execute()){
            header("Location: noticias.php?removida=1");
            exit();
        }
        else{
            $erroNoticia="Erro ao remover a noticia.";
        }
        $stmt->close();
    }
}

if(isset($_POST["editar_noticia"]) && isset($_SESSION["id_user"])){
    $noticiaId=(int) ($_POST["id_noticia"] ?? 0);
    $titulo=trim($_POST["titulo"] ?? "");
    $resumo=trim($_POST["resumo"] ?? "");
    $corpo=trim($_POST["corpo"] ?? "");
    $novaImagem="";
    $erroUpload=$_FILES["imagem"]["error"] ?? UPLOAD_ERR_NO_FILE;

    if($noticiaId<=0 || $titulo=="" || $resumo=="" || $corpo==""){
        $erroNoticia="Preencha todos os campos da noticia.";
    }
    elseif($erroUpload!=UPLOAD_ERR_OK && $erroUpload!=UPLOAD_ERR_NO_FILE){
        $erroNoticia="Erro ao carregar a imagem.";
    }
    else{
        if($erroUpload==UPLOAD_ERR_OK){
            $target_path="../images/noticias/";
            $nomeImagem=basename($_FILES["imagem"]["name"]);
            $target_file=$target_path.$nomeImagem;
            $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $novaImagem="images/noticias/".$nomeImagem;

            if($imageFileType!="jpg" && $imageFileType!="png" && $imageFileType!="jpeg" && $imageFileType!="gif"){
                $erroNoticia="Formato inválido. Use JPG, PNG, JPEG ou GIF.";
            }
            elseif(!move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)){
                $erroNoticia="Erro ao guardar o ficheiro no servidor.";
            }
        }

        if($erroNoticia==""){
            if($novaImagem!=""){
                $stmt=$conn->prepare("UPDATE TB_noticias SET titulo=?, resumo=?, corpo=?, imagem=? WHERE id_noticia=?");
                $stmt->bind_param("ssssi", $titulo, $resumo, $corpo, $novaImagem, $noticiaId);
            }
            else{
                $stmt=$conn->prepare("UPDATE TB_noticias SET titulo=?, resumo=?, corpo=? WHERE id_noticia=?");
                $stmt->bind_param("sssi", $titulo, $resumo, $corpo, $noticiaId);
            }

            if($stmt->execute()){
                header("Location: noticias.php?editada=1");
                exit();
            }
            else{
                $erroNoticia="Erro ao editar a noticia.";
            }
            $stmt->close();
        }
    }
}

if(isset($_POST["criar_noticia"]) && isset($_SESSION["id_user"])){
    $titulo=trim($_POST["titulo"] ?? "");
    $resumo=trim($_POST["resumo"] ?? "");
    $corpo=trim($_POST["corpo"] ?? "");
    $user_id=(int) $_SESSION["id_user"];

    if($titulo=="" || $resumo=="" || $corpo==""){
        $erroNoticia="Preencha o titulo, o resumo e a mensagem da noticia.";
    }
    elseif(!isset($_FILES["imagem"]) || $_FILES["imagem"]["error"]!=UPLOAD_ERR_OK){
        $erroNoticia="Escolha uma imagem para a noticia.";
    }
    else{
        $target_path="../images/noticias/";
        $nomeImagem=basename($_FILES["imagem"]["name"]);
        $target_file=$target_path.$nomeImagem;
        $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $imagem="images/noticias/".$nomeImagem;

        if($imageFileType!="jpg" && $imageFileType!="png" && $imageFileType!="jpeg" && $imageFileType!="gif"){
            $erroNoticia="Formato inválido. Use JPG, PNG, JPEG ou GIF.";
        }
        else{
            if(move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)){
                $stmt=$conn->prepare("INSERT INTO TB_noticias (titulo, resumo, corpo, imagem, user_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $titulo, $resumo, $corpo, $imagem, $user_id);

                try{
                    $criada=$stmt->execute();
                }
                catch(mysqli_sql_exception $e){
                    $criada=false;
                }

                if($criada){
                    header("Location: noticias.php?sucesso=1");
                    exit();
                }
                else{
                    $erroNoticia="Erro ao criar a noticia. Tente novamente.";
                }
                $stmt->close();
            }
            else{
                $erroNoticia="Erro ao guardar o ficheiro no servidor.";
            }
        }
    }
}

$noticias=$conn->query("SELECT id_noticia, titulo, resumo, corpo, imagem, data_criacao FROM TB_noticias ORDER BY data_criacao DESC, id_noticia DESC");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Associação Musical de Pedroso</title>
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
                                    <span class="badge bg-light text-primary px-3 py-2 mb-2">Redação</span>
                                    <h1 class="fw-bold">Criar Nova Notícia</h1>
                                </div>

                                <?php if($erroNoticia!=""){ ?>
                                    <div class="alert alert-danger small"><?= htmlspecialchars($erroNoticia) ?></div>
                                <?php } ?>

                                <?php if($sucessoNoticia!=""){ ?>
                                    <div class="alert alert-success small"><?= htmlspecialchars($sucessoNoticia) ?></div>
                                <?php } ?>

                                <form method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Título da Notícia</label>
                                        <input type="text" name="titulo" class="form-control"
                                               placeholder="Título chamativo..."
                                               value="<?= isset($_POST["titulo"]) ? htmlspecialchars($_POST["titulo"]) : "" ?>"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Resumo <small class="muted fw-normal">(breve descrição para a listagem)</small></label>
                                        <input type="text" name="resumo" class="form-control"
                                               placeholder="Breve introdução..."
                                               value="<?= isset($_POST["resumo"]) ? htmlspecialchars($_POST["resumo"]) : "" ?>"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Corpo da Notícia</label>
                                        <textarea name="corpo" class="form-control" rows="6" placeholder="Escreva aqui o conteúdo completo..." required><?= isset($_POST["corpo"]) ? htmlspecialchars($_POST["corpo"]) : "" ?></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Imagem de Destaque</label>
                                        <input type="file" name="imagem" class="form-control" accept="image/jpeg,image/png,image/gif" required>
                                        <div class="form-text small">Formatos suportados: JPG, PNG, JPEG ou GIF.</div>
                                    </div>
                                    <button type="submit" name="criar_noticia" class="btn btn-primary w-100 py-2">Publicar Notícia</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <section id="noticias" class="section-padding">
        <div class="container">
            <div class="mb-5">
                <h2 class="h1 fw-bold">Arquivo de Notícias</h2>
                <p class="muted">Explore todas as novidades e eventos da Associação Musical de Pedroso.</p>
            </div>
            
            <div class="row g-4">
                <?php if($noticias && $noticias->num_rows > 0){ ?>
                    <?php while($noticia=$noticias->fetch_assoc()){ ?>
                        <?php $imagemModal=$noticia["imagem"] != "" ? "../".$noticia["imagem"] : ""; ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100">
                                <?php if($noticia["imagem"] != ""){ ?>
                                    <div class="overflow-hidden">
                                        <img src="../<?= htmlspecialchars($noticia["imagem"]) ?>" class="card-img-top" alt="<?= htmlspecialchars($noticia["titulo"]) ?>">
                                    </div>
                                <?php } ?>
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-light text-primary px-3 py-2">
                                            <?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>
                                        </span>
                                    </div>
                                    <h4 class="card-title h5 mb-3 fw-bold"><?= htmlspecialchars($noticia["titulo"]) ?></h4>
                                    <p class="card-text muted flex-grow-1 small"><?= htmlspecialchars($noticia["resumo"] ?? "") ?></p>
                                    
                                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                                        <button type="button"
                                                class="btn btn-outline-primary btn-sm flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#noticiaModal"
                                                data-noticia-titulo="<?= htmlspecialchars($noticia["titulo"], ENT_QUOTES, "UTF-8") ?>"
                                                data-noticia-data="<?= date("d/m/Y", strtotime($noticia["data_criacao"])) ?>"
                                                data-noticia-imagem="<?= htmlspecialchars($imagemModal, ENT_QUOTES, "UTF-8") ?>"
                                                data-noticia-corpo="<?= htmlspecialchars($noticia["corpo"], ENT_QUOTES, "UTF-8") ?>">
                                            Ler mais
                                        </button>

                                        <?php if(isset($_SESSION["id_user"])){ ?>
                                            <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editarNoticiaModal"
                                                    data-noticia-id="<?= (int) $noticia["id_noticia"] ?>"
                                                    data-noticia-titulo="<?= htmlspecialchars($noticia["titulo"], ENT_QUOTES, "UTF-8") ?>"
                                                    data-noticia-resumo="<?= htmlspecialchars($noticia["resumo"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                                                    data-noticia-corpo="<?= htmlspecialchars($noticia["corpo"], ENT_QUOTES, "UTF-8") ?>"
                                                    data-noticia-imagem="<?= htmlspecialchars($imagemModal, ENT_QUOTES, "UTF-8") ?>">
                                                Editar
                                            </button>

                                            <form method="post" class="m-0" onsubmit="return confirm('Tem a certeza que quer remover esta notícia?')">
                                                <input type="hidden" name="id_noticia" value="<?= (int) $noticia["id_noticia"] ?>">
                                                <button type="submit" name="remover_noticia" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else{ ?>
                    <div class="col-12">
                        <div class="alert border shadow-sm p-5 text-center mb-0">
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

        <?php if(isset($_SESSION["id_user"])){ ?>
            <div class="modal fade" id="editarNoticiaModal" tabindex="-1" aria-labelledby="editarNoticiaModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="post" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarNoticiaModalLabel">Editar notícia</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_noticia" id="editarNoticiaId">

                                <div class="mb-3">
                                    <label class="form-label">Título</label>
                                    <input type="text" name="titulo" id="editarNoticiaTitulo" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Resumo</label>
                                    <input type="text" name="resumo" id="editarNoticiaResumo" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mensagem</label>
                                    <textarea name="corpo" id="editarNoticiaCorpo" class="form-control" rows="6" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nova imagem</label>
                                    <input type="file" name="imagem" id="editarNoticiaImagem" class="form-control" accept="image/jpeg,image/png,image/gif">
                                    <div class="form-text" id="editarNoticiaImagemAtual">Deixe em branco para manter a imagem atual.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="editar_noticia" class="btn btn-dark">Guardar alterações</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>

        </div>

        <?php include "../Frontend/footer.php";?>
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

            var editarNoticiaModal=document.getElementById("editarNoticiaModal");

            if(editarNoticiaModal){
                editarNoticiaModal.addEventListener("show.bs.modal", function(event){
                    var botao=event.relatedTarget;

                    if(!botao){
                        return;
                    }

                    var imagem=botao.getAttribute("data-noticia-imagem") || "";

                    document.getElementById("editarNoticiaId").value=botao.getAttribute("data-noticia-id") || "";
                    document.getElementById("editarNoticiaTitulo").value=botao.getAttribute("data-noticia-titulo") || "";
                    document.getElementById("editarNoticiaResumo").value=botao.getAttribute("data-noticia-resumo") || "";
                    document.getElementById("editarNoticiaCorpo").value=botao.getAttribute("data-noticia-corpo") || "";
                    document.getElementById("editarNoticiaImagem").value="";
                    document.getElementById("editarNoticiaImagemAtual").textContent=imagem!="" ? "Deixe em branco para manter a imagem atual." : "Esta notícia não tem imagem atual.";
                });
            }
        </script>
    </body>
</html>

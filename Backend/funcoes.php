<?php
if(isset($_POST["entrar"])){
    $username=$_POST["username"];
    $password=$_POST["password"];
    $sql="SELECT * FROM TB_users WHERE username=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultado=$stmt->get_result();
    $user=$resultado->fetch_assoc();
    if($user && password_verify($password, $user["pass_user"])){
        $_SESSION["id_user"]=$user["id_user"];
        $_SESSION["username"]=$user["username"];
        $_SESSION["tipo_id"]=$user["tipo_id"];
        header("Location: ../index.php");
        exit();
    } 
    else{
        $erro="Dados incorretos.";
    }
}

if (isset($_POST["registar"])){
    $newUser=trim($_POST["reg_username"]);
    $newPass=$_POST["reg_password"];
    $confPass=$_POST["reg_confirm"];
    $tipo=(int) $_POST["reg_tipo"];

    if (empty($newUser) || empty($newPass) || empty($confPass)){
        $erroReg="Preencha todos os campos.";
    } 
    elseif ($newPass!=$confPass){
        $erroReg="As palavras-passe não coincidem.";
    } 
    elseif (strlen($newPass)<8){
        $erroReg="A palavra-passe deve ter pelo menos 8 caracteres.";
    } 
    else{
        $check=$conn->prepare("SELECT id_user FROM TB_users WHERE username=?");
        $check->bind_param("s", $newUser);
        $check->execute();
        $check->store_result();
        if ($check->num_rows>0){
            $erroReg="Esse nome de utilizador já existe.";
        } 
        else{
            $hash=password_hash($newPass, PASSWORD_DEFAULT);
            $ins=$conn->prepare("INSERT INTO TB_users (username, pass_user, tipo_id) VALUES (?, ?, ?)");
            $ins->bind_param("ssi", $newUser, $hash, $tipo);
            if ($ins->execute()){
                $sucessoReg="Utilizador <strong>".htmlspecialchars($newUser)."</strong> criado com sucesso.";
            } 
            else{
                $erroReg="Erro ao criar o utilizador. Tente novamente.";
            }
        }
    }
}
?>

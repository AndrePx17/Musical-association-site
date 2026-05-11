<?php
$is_docker = file_exists('/.dockerenv');

if($is_docker)
{
    $bd_host="db";
    $bd_port=3306;
    $bd_user="root";
    $bd_pass="root";
    $bd_name="BD_associacao";
}
else
{
    if ($_SERVER['SERVER_NAME']=="localhost") {
        $bd_host="localhost";
        $bd_user="root";
        $bd_pass="";
        $bd_name="BD_associacao";
        $bd_port=3306;
    }
    else {
        $bd_host="localhost";
        $bd_user="usr21";
        $bd_pass="dacic2020";
        $bd_name="usr21";
        $bd_port=3306;
    }
}

$conn=new mysqli($bd_host, $bd_user, $bd_pass, $bd_name, $bd_port);

$conn->set_charset("utf8mb4");

if($conn->connect_error)
{
    die("Erro de ligação:". $conn->connect_error);
}
?>

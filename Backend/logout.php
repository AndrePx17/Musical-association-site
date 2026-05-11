<?php
session_start();
session_destroy();
header("Location: ../www/index.php");
exit();
?>
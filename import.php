<?php
if (isset($_POST["import"])) {
    var_dump($_POST);
}
die();
//Retourner à la page index.php
header('Location: index.php');
exit;
?>
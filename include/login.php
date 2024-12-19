<?php
require "../algoritimos/atalho.php";

$id_user = intval($_SESSION['id_user']); 
$IP = mysqli_real_escape_string(conn(), $_SERVER["REMOTE_ADDR"]); 

$sql = mysqli_query(
    conn(),
    "UPDATE $bdnome2.login 
    SET data=NOW() 
    WHERE id_user=$id_user AND IP='$IP'"
);

if (!$sql) {
    die("Erro ao atualizar o login: " . mysqli_error(conn()));
}
?>

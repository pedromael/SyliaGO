<?php
require "../algoritimos/atalho.php";
$id_user = $_SESSION['id_user'];
$IP = $_SERVER["REMOTE_ADDR"];
$sql = mysqli_query(conn(), "SELECT * FROM $bdnome2.login WHERE id_user=$id_user AND IP='$IP'");
$sql = mysqli_fetch_assoc($sql);
$id_login = $sql['id_login'];
if ($sql["logado"] == 1) {
    $sql = mysqli_query(conn() ,"UPDATE $bdnome2.login SET data=NOW() WHERE id_login=$id_login");
}else {
    $sql = mysqli_query(conn() ,"UPDATE $bdnome2.login SET data=NOW() WHERE id_login=$id_login");
}
?>
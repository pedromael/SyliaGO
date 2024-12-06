<?php
require "../algoritimos/atalho.php";
$c = new process;

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$tipo = $data['tipo'];
$para = $data['para'];

$c->reagir($id,$tipo, $para);

$reacao = $c->pdo->prepare("SELECT * FROM $bdnome2.reacao WHERE id=:id AND para = :p");
$reacao->bindValue(":id", $id);
$reacao->bindValue(":p", $para);
$reacao->execute();

$reacao_numero = $reacao->rowCount();
if ($reacao->rowCount() <= 0) {
    $reacao_numero = "";
}
echo $reacao_numero;
?>
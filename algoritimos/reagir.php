<?php
require "../algoritimos/atalho.php";
$c = new process;

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$tipo = $data['tipo'];
$para = $data['para'];

$c->reagir($id,$tipo, $para);

?>

<img src="/bibliotecas/bootstrap/icones/<?= $c->qtd_reacao($id, 'poste', $_SESSION['id_user']) > 0 ? 'heart-fill.svg' : 'heart.svg' ?>" alt="Gosto">
<?= $c->qtd_reacao($id, 'poste') ?>

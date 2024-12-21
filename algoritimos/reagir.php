<?php
require "../algoritimos/atalho.php";
$c = new process;

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$tipo = $data['tipo'];
$para = $data['para'];

$c->reagir($id,$tipo, $para);
?>

<i class="bi <?= $c->qtd_reacao($id, 'poste', $_SESSION['id_user']) > 0 ? 'bi-heart-fill' : 'bi-heart' ?>" style="color: red;"></i>
<?= $c->qtd_reacao($id, 'poste') ?>
<?php
include "../algoritimos/atalho.php";
include "../algoritimos/seguranca.php";
$data = json_decode(file_get_contents('php://input'), true);

$id = intval(filtro(descriptografar($data['id'])));
$tipo = filtro($data['tipo']);
$texto= filtro($data['texto']);

$process = new comentarios;
$dados = $process->comentar($id,$texto,$tipo);
$process->mostrar($dados);
?>
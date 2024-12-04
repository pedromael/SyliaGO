<?php
function filtro($text)
{
    return $text;
}

function criptografar($text)
{
    filtro($text);
    //$text = decbin($text);
    $text = base64_encode($text);
    return $text;
}
function descriptografar($text)
{
    filtro($text);
    $text = base64_decode($text);
    //$text = bindec($text);
    return filtro($text);
}
function verificar_requisito_de_seguranca_de_senha($senha) {
    return true;
}
?>

<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

$r = new repositorio();

foreach ($r->repositorio() as $value) {
    echo "". $value["nome"] ."\n";
}
?>


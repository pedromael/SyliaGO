<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

$r = new repositorio();

foreach ($r->repositorio() as $value) {
    ?>
    <a href="/coder/repositorio.php?id_repositorio=<?=criptografar($value['id_repositorio'])?>">
        <div class="container m-2">
            <div class="row">
                <div class="col"><?=$value['nome']?></div>
                <div class="col"><span class="titulo"></span></div>
            </div>
            <div class="row"><?=$value['descricao']?></div>
        </div>
    </a>
    <?php
}
?>
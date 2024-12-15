<?php
require "../../algoritimos/atalho.php";
require "../../algoritimos/seguranca.php";
$cmdd = new comunidade();
?>
<div class="container d-flex justify-content-center w-100">
    <button class="btn btn-link text-decoration-none">
        Comunidades sugeridas
    </button>
</div>
<div class="çontainer">
    <?php
    $cmdd->comunidades_sugerida();
    ?>
</div>
<?php
require "algoritimos/atalho.php";
require "algoritimos/seguranca.php";

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}else {
    ?>
        <script>
            document.location.href = "./"
        </script>
    <?php
}
if (isset($_GET['valor'])) {
    $valor = filtro($_GET['valor']);
}else {
    $valor = NULL;
}
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link href="/bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <title><?=$valor?></title>
</head>
<body>
    <script src="/bibliotecas/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/js/script.js"></script>
    <script>var indereco = "./"</script>
    <nav id="metade_da_nav">
        <a href="./">
            <img src="/bibliotecas/bootstrap/icones/house.svg">
        </a>
    </nav>
    
    <nav class="">
      <div class="">
        <form action="" method="get" id="pesquisar">
            <input type="search" name="valor" placeholder="<?=$valor?>" required>
            <button>pesquisar</button>
        </form>
      </div>
    </nav>
    <div class="corpos overflow-y-auto">
        <div id="corpo">
            <div class="filtro"></div>
            <div class="pesquisas">
                <?php
                $s = new search($valor,"tudo");
                $s->procurar();
                ?>
            </div>
        </div>
        <div class="corpo2"></div>
    </div>

    <?php $abrir_nav = "primeiro"; require "include/footer.php"; ?>
    <script src="/src/js/fim_script.js"></script>
</body>
</html>
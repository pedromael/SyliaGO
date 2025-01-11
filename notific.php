<?php
require "algoritimos/atalho.php";
require "algoritimos/seguranca.php";
$c = new process;
$n=0;

if (!isset($_SESSION['id_user'])) {
    ?>
    <script type="text/javascript">
        window.location.href="login/";
    </script>
    <?php    
}
$id_user = $_SESSION['id_user'];
$imagen = pegar_foto_perfil("perfil",$_SESSION['id_user']);
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <title>Notificacoes</title>
</head>
<body>
    <script>var indereco = "./";</script>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/js/script.js"></script>
    <?php
    require "include/nav.php";
    ?>
    <div class="corpos">
      <div id="corpo" class="overflow-y-auto overflow-y-auto">
          <div class="container d-flex justify-content-center w-100">
              <button class="btn btn-link text-decoration-none">
                  Marcar todas como lidas
              </button>
          </div>
          <?php
          $n = new notificacoes();
          $n->procurar();
          ?>
      </div>
      <div class="corpo2 overflow-y-auto"></div>
    </div>
    <div id="mais_pbl"><a href="./">Ver Mais</a></div>      
    <?php require "include/footer.php"; ?>
    <?php require "sent.php";?>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/js/fim_script.js"></script>
</body>
</html>
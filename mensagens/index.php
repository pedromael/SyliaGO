<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";
 $c = new process;

 if (!isset($_SESSION["id_user"])) {
    #header("location: ../login/");
    ?>
        <script>
            document.location.href = "../login/"
        </script>
    <?php
 }
 if (isset($_GET['user'])) {
    $id_dest = descriptografar($_GET['user']);
    $imagen = pegar_foto_perfil("perfil",$id_dest);
 } else {
    $id_dest = NULL;
 }
 $id_user = $_SESSION['id_user'];
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
    <link rel="stylesheet" href="/src/css/coder.css">
    <title><?=$user['nome']?></title>
</head>
<body>
    <script>var indereco = "../";</script>
    <script src="/bibliotecas/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/js/script.js"></script>
    <?php
    require "../include/nav.php";
    ?>
<div class="corpos">
    <div class="corpo3 crp"></div>
    <div id="corpo" class="crp">
      <div class="corpo_diminuido overflow-y-auto">
        
      <div class="msg">
        <?php
        if($id_dest != NULL){
          $msg = new mensagens;
          $msg->receptor = $id_dest;
          $msg->selecionar();
        }else {
          ?>
          <div class="container d-flex justify-content-center align-items-center h-100">
            <h4>selecine uma mensagen</h>
          </div>
          <?php
        }
        ?>
      </div>
  </div> 
  <footer class="footer_chat">
        <div class="formulario_normal_de_envio">
            <textarea name="texto_cmt" id="" placeholder="a tua opiniao e importante"></textarea>
            <div class="carregar"  style="background-image: url(../bibliotecas/bootstrap/icones/file-earmark-image.svg);"></div>
            <button name="btn_cmt" style="background-image: url(../bibliotecas/bootstrap/icones/send.svg);" class="form-control" onclick="enviar_mensagem('<?=criptografar($id_dest)?>')"></button>
        </div>
      <?php
      require "../sent.php";
      ?>
  </footer>
  </div> 
  <div class="corpo2 crp"></div>
</div>

<?php require "../include/footer.php"; ?>
<script>
  const corpo_chat = document.querySelector(".corpo_diminuido");
  corpo_chat.scrollTop = corpo_chat.scrollHeight
</script>
<script src="/src/js/fim_script.js"></script>
</body>
</html>
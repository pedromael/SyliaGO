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
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">    
    <link rel="stylesheet" href="/src/css/stilo.css">
    <link rel="stylesheet" href="/src/css/coder.css">
    <title><?=$user['nome']?></title>
</head>
<body>
    <script>var indereco = "../";</script>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/js/script.js"></script>
    <?php
    require "../include/nav.php";
    ?>
<div class="corpos">
    <div class="corpo3 crp"></div>
    <div id="corpo" class="crp">
      <div class="corpo_diminuido overflow-y-auto"> 
        <div class="container p-2 m-1">
          <?php
          if($id_dest != null){
            $info = $c->usuario($id_dest);
            $info_login = (new verificar_logados())->dados_de_login($id_dest);
            ?>
            <style>
              .data_logado{
                font-size: 11pt;
                color: #d3d3ddff;
                text-shadow: 1px 1px 2px #ddd;
                <?php if($info_login["ativo"]):?>
                  border-bottom: 2px solid green;
                <?php else:?>
                  border-bottom: 2px solid #d3d3ddff;
                <?php endif;?>
              }
            </style>
            <div class="card mb-2 p-1">
              <div class="row g-0 align-items-center">
                <div class="col-auto">
                  <div class="rounded-circle" 
                      style="width: 60px; height: 60px; background-image: url('<?=pegar_foto_perfil("perfil", $id_dest)?>'); background-size: cover; background-position: center;">
                  </div>
                </div>
                <div class="col">
                  <div class="container">
                    <div class="row p-1"><?=$info['nome']?></div>
                    <div class="row text-end p-1 data_logado">
                      <div class="container">
                        <?php
                          if($info_login['ativo']){
                            echo "ativo";
                          }else{
                            echo "ativo ". resumir_data($info_login["data"]);
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
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
      <?php
      if($id_dest != NULL){
        ?>
          <footer class="formulario_mensagem d-flex align-items-center justify-content-center bg-transparent w-100 position-absolute bottom-0 mb-2">
            <div class="d-flex align-items-center position-relative w-100 border rounded-pill shadow" style="height: 50px;">
                <button class="btn d-flex align-items-center justify-content-center rounded-circle p-0 position-absolute start-0" style="width: 40px; height: 40px;">
                    <i class="bi bi-file-earmark-image"></i>
                </button>
                <textarea class="form-control border-0 shadow-none mx-5 px-0 bg-transparent text-secondary" 
                            placeholder="A tua opinião é importante" 
                            style="resize: none; height: 100%;"></textarea>
                <button name="btn_cmt" 
                        class="btn d-flex align-items-center justify-content-center rounded-circle p-0 position-absolute end-0" 
                        style="width: 40px; height: 40px;" 
                        onclick="enviar_mensagem('<?=criptografar($id_dest)?>')">
                    <i class="bi bi-send"></i>
                </button>
            </div>
          </footer>
        <?php
        require "../sent.php";
      }
      ?>
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
<script>
    var largura = window.innerWidth;
    var sms = document.querySelector(".corpo_diminuido")

    var xhr = new XMLHttpRequest()
    xhr.open('POST', 'include/sms.php', true)
    xhr.setRequestHeader('Content-Type', 'application/json')

    const urlParams = new URLSearchParams(window.location.search)
    const user = urlParams.get('user')
    if (largura < 750 && user == null) {
        xhr.onload = function() {
            if (xhr.status === 200) {
                sms.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>
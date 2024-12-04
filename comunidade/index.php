<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

$c = new process;

$_SESSION['visualizado'] = array();
if (!isset($_SESSION["id_user"])) {
  ?>
      <script>
          document.location.href = "../login/"
      </script>
  <?php
}

$imagen = pegar_foto_perfil("perfil",$_SESSION["id_user"]);

if (isset($_GET['cmndd'])) {
  $id_comunidade = descriptografar($_GET['cmndd']);
  $cmdd = new comunidade($id_comunidade);
} else {
  ?>
      <script>
          document.location.href = "lista.php"
      </script>
  <?php
}

$sql = $cmdd->comunidade();
$lider_da_comunidade = $sql['id_user'];
$privado = mysqli_query(conn(), "SELECT count(*) AS valor FROM $bdnome2.privado 
WHERE id=$id_comunidade AND tipo='comunidade'");
$privado = mysqli_fetch_assoc($privado);

$privado =  ($privado['valor'] >= 1) ? true : false;

$menbro = $cmdd->pertence_a_comunidade();

if (isset($_FILES['img']) && $_SESSION['id_user'] == $sql['id_user']) {
  if (carregar_img($_FILES['img'],"cmdd",$id_comunidade)) {
    ?>
        <script>
            document.location.href = "./?cmndd=<?=criptografar($id_comunidade)?>"
        </script>
    <?php
  }
}
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <link rel="stylesheet" href="/src/css/comunidade.css">
    <link rel="stylesheet" href="/src/css/coder.css">
    <script src="/src/js/script.js"></script>
    <title><?=$sql['nome']?></title>
</head>
<body>
  <script>var indereco = "../"</script>
    <?php
      require "../include/nav.php";
    ?>
    <?php
      if (true) {
        if ($_SESSION['id_user'] == $sql['id_user']) {
            if (true) {
                ?>
                <div id="alerta" class="remover">
                    <div class="modal modal-sheet d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSheet">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                          <div class="modal-header border-bottom-0">
                            <h1 class="modal-title fs-5">carregar foto de perfil</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="aba_carregar_foto()"></button>
                          </div>
                          <form action="" method="post" enctype="multipart/form-data">
                            <div class="modal-body py-0">
                                <p><input type="file" name="img" class="form-control"></p>
                            </div>
                            <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                                <input name="btn_img" type="submit" class="btn bg-sec" value="carregar"> 
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
    <div class="corpos">
      <div class="corpo3 crp">
          <div id="container" class="overflow-y-auto">
              <div> 
                  <div class="texto_interativo">
                      aque apareceram <a href="" class="destaque"><span>codigos</span></a>,
                      sugeridos para vc
                  </div>
                  <div class="texto_interativo">
                      adiciona <a href="../contactos/" class="destaque"><span>contactos</span></a>, para podermos sugerir
                      codigos relacionados a ele. <a href="" class="destaque"><span>saber mais</span></a>
                  </div>
                  <div class="texto_interativo">
                      entra em <a href="../comunidade/" class="destaque"><span>comunidades</span></a>, e expande tua rede
                      de interacoes...
                  </div>
              </div>
          </div>
      </div>
      <div id="corpo" class="crp">
        <?php
        $imagen = pegar_foto_perfil("cmdd",$id_comunidade);
        ?>
          <style>
              #img_comunidade{
                      background-image: url(<?=$imagen?>);
              }
          </style>
          <header class="cabecalho">
            <div id="cabe">
                <div id="img_comunidade" class="perfil_img">
                  <?php
                  if ($_SESSION['id_user'] == $sql['id_user']) {
                  ?>
                    <div class="carregar_img"  onclick="aba_carregar_foto()"></div>
                  <?php
                  }
                  ?>
                </div>
            </div>
            <div class="info">
              <?php
              $criador = $c->usuario($sql['id_user']);
              if ($_SESSION['id_user'] == $lider_da_comunidade) {
                ?>
                  <div class="definicoes direita"><a href="definicoes.php?cmndd=<?=criptografar($id_comunidade)?>"><img class="icone_medio" src="/bibliotecas/bootstrap/icones/gear.svg" alt=""></a></div>
                  <div class="direita">
                    <span class="titulo">membros:</span><span class="descricao"><?=pegar_qtd_de_membros_de_grupo($id_comunidade)?></span>
                  </div>
                <?php
              }else {
                ?>
                <div class="definicoes direita">
                  <span class="titulo">criador:</span> <span class="descricao"><?=$criador['nome']?></span><br>
                  <span class="titulo">membros:</span> <span class="descricao"><?=pegar_qtd_de_membros_de_grupo($id_comunidade)?></span>
                </div>
                <?php
              }
              ?>
              <div class="descricao_cmd"><?=$sql['descricao']?></div>
            </div>
            <div id="nome_comunidade"><h3><?=$sql['nome']?></h3></div>
          </header>
          <br>
          <div class="ops_perfil">
              <a href=""><div>mais</div></a>
              <a href=""><div>eventos</div></a>
              <a href=""><div>arquivos</div></a>
              <a href=""><div class="">membros</div></a>
          </div>
          <style>
                .fazer_poste{
                    padding: 5px 10px 2px 10px !important;
                    margin: 15px auto;
                    margin-bottom: 6px;
                    width: 85%;
                }
            </style>
            <div class="container bg-white p-4 rounded shadow-sm fazer_poste">
              <form action="" method="post" enctype="multipart/form-data">
                  <div class="mb-3 d-flex align-items-start">
                      <img src="<?=$imagen?>" alt="Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                      
                      <textarea name="texto" 
                              class="form-control border-0 shadow-none" 
                              rows="3" 
                              placeholder="No que você está pensando?"
                              style="resize: none;"></textarea>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                      <label for="input_file_pbl" class="btn btn-outline-secondary d-flex align-items-center">
                          <img src="/bibliotecas/bootstrap/icones/file-earmark-image.svg" alt="Upload" class="me-2" style="width: 20px;"> 
                          Adicionar Fotos
                      </label>
                      <input type="file" id="input_file_pbl" name="doc[]" accept="image/*" multiple hidden>
                      <button name="btn_pbl_comunidade" class="btn btn-primary d-flex align-items-center">
                          <img src="/bibliotecas/bootstrap/icones/send.svg" alt="Enviar" class="me-2" style="width: 20px;">
                          Publicar
                      </button>
                  </div>
              </form>
          </div>
          <?php require "../sent.php"; ?>
          <?php
          if (isset($_GET['pbl'])) {
            if ($_GET['pbl'] = "true") {
                ?>
                    <div class="info_corrente">publicacao carregada com sucesso</div>
                <?php
            }else {
                ?>
                    <div class="info_corrente">erro ao carregar imagen</div>
                <?php
            }
          }
          $_SESSION['visualizado'] = array();
          $s = new selecionar_feed();
          $s->id = $id_comunidade;
          $s->selecionar_poste("comunidade");
          ?>
      </div>
      <div class="corpo2 crp"></div>
    </div>
    <?php include "../include/footer.php"; ?>
    <script src="/src/js/fim_script.js"></script>
</body>
</html>
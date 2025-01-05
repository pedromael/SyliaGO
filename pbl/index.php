<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";

 $c = new process;

 if (!isset($_SESSION["id_user"])) {
    header("location: ../login/");
 }
 $id_user = $_SESSION['id_user'];
 if (!isset($_GET['pbl'])) {
    #header("location: ../");
    ?>
        <script>
            document.location.href = "../"
        </script>
    <?php
 }else {
    $id_pbl = descriptografar($_GET['pbl']);
 }
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <title>Poste</title>
</head>
<body>
    <script>var indereco="../";</script>
    <script src="/bibliotecas/jquery.js"></script>
    <script src="/src/js/script.js"></script>
    <?php
        require "../include/nav.php";
    ?>
    <div class="corpos">
        <div class="corpo3 crp"></div>
        <div id="corpo" class="crp">
            <div class="corpo_diminuido overflow-y-auto">
                <?php
                $poste = new postes;
                $post = $poste->poste($id_pbl);
                $poste->mostrar($post,true);
                if ($post['id_user'] == $_SESSION['id_user']) {
                    ?>
                    <div class="ops_perfil">
                        <a href="editar.php?id_pbl=<?=criptografar($id_pbl)?>"><div>editar</div></a>
                        <div>---</div>
                        <a href="editar.php?id_pbl=<?=criptografar($id_pbl)?>&eliminar"><div>eliminar</div></a>
                        <a href=""><div>---</div></a>
                    </div>
                    <?php
                }
                ?>
                <div class="comentarios">
                    <?php
                    $_SESSION['cmt_visualizado'] = array();
                    $comentarios =  new comentarios; 
                    $comentarios->id = $id_pbl;
                    $comentarios->pegar("poste", 8);
                    ?>
                </div>
            </div>    
            <footer class="formulario-comentario d-flex align-items-center justify-content-center bg-transparent w-100 position-absolute bottom-0 mb-2">
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
                            onclick="comentar('<?=criptografar($id_pbl)?>','poste')">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </footer>
        </div>
        <div class="corpo2 crp"></div>
    </div> 
    <?php require "../include/footer.php"; ?>
    <script src="/src/js/fim_script.js"></script>
    <script src="/src/js/coder.js"></script>
    <?php
     if (isset($_GET['cmt'])) {
        if (!empty($_GET['cmt'])) {
            ?><script>rolagem_automatica("#cmt")</script><?php
        }
     }
    ?>
</body>
</html>
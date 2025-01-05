<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";
 $c = new process;
 $comentarios = new comentarios;
 if (!isset($_SESSION["id_user"])) {
    header("location: ../login/");
 }
 $id_user = $_SESSION['id_user'];
 if (!isset($_GET['cmt'])) {
    #header("location: ../");
    ?>
        <script>
            document.location.href = "../"
        </script>
    <?php
 }else {
    $id_cmt = descriptografar($_GET['cmt']);
 }
 $sql = $comentarios->comentario($id_cmt);
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
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <title>Responder</title>
</head>
<body>
    <script src="/src/js/script.js"></script>
    <script>var indereco = "../";</script>
    <?php
        require "../include/nav.php";
    ?>
    <div class="corpos">
        <div class="corpo3 crp"></div>
        <div id="corpo" class="crp">
            <div class="corpo_diminuido">
                <div class="ver_info">
                    <a href="./?pbl=<?=criptografar($sql['id'])?>">ver publicacao</a>
                </div>
                <div id="pbl" class="rounded">
                    <?php
                    $comentarios->mostrar($sql);
                    ?>
                </div>
                <div class="comentarios resposta">
                    <?php
                    $_SESSION['cmt_visualizado'] = array();
                    $comentarios->id = $sql['id'];
                    $comentarios->id_cmt = $sql['id_cmt'];
                    $comentarios->pegar("comentario", 8);
                    ?>
                </div>
            </div>
            <footer class="footer_chat">
                <div class="formulario_normal_de_envio">
                    <textarea name="texto_cmt" id="" placeholder="a tua opiniao e importante"></textarea>
                    <div class="carregar"  style="background-image: url(/bibliotecas/bootstrap/icones/file-earmark-image.svg);"></div>
                    <button name="btn_cmt" style="background-image: url(/bibliotecas/bootstrap/icones/send.svg);" class="form-control" onclick="comentar('<?=criptografar($sql['id_cmt'])?>','comentario')"></button>
                </div>
            </footer>
        </div>
        <div class="corpo2 crp"></div>
    </div> 
    <?php require "../include/footer.php"; ?>
    <script src="/src/js/fim_script.js"></script>
</html>
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
 $link = conn();
 if (isset($_GET['image'])) {
    $imagen = descriptografar($_GET['image']);
    $sql = mysqli_query(conn(), "SELECT * FROM doc WHERE indereco='$imagen'");
    $sql = mysqli_fetch_assoc($sql);
    if ($sql['id_user'] > 0) {
        $id_dest = $sql['id_user']; 
    }
    $id = $sql['id_doc'];
 }
 if (isset($_GET['id_img']) && isset($_GET['action'])) {
    $id = descriptografar($_GET['id_img']);
    $sql = mysqli_query(conn(), "SELECT * FROM doc WHERE id_doc=$id");
    $sql = mysqli_fetch_assoc($sql);
    $imagen = $sql['indereco'];
    if ($sql['id_user'] > 0) {
        $id_dest = $sql['id_user']; 
    }
    if ($_GET['action'] == 1) {
        $sql = mysqli_query(conn(), "SELECT * FROM doc WHERE id_doc < $id AND id_user=$id_dest ORDER BY id_doc DESC");
        $sql = mysqli_fetch_assoc($sql);
        $imagen = $sql['indereco']; 
        $id = $sql['id_doc'];
    }else {
        $sql = mysqli_query(conn(), "SELECT * FROM doc WHERE id_doc > $id AND id_user=$id_dest");
        $sql = mysqli_fetch_assoc($sql);
        $imagen = $sql['indereco']; 
        $id = $sql['id_doc'];
    }
 }
 if (!isset($_GET['id_img']) && !isset($_GET['image'])) {
    #header("location: ..?");
    ?>
        <script>
            document.location.href = "..?"
        </script>
    <?php
 }
 
 $info = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user =$id_dest");
 $info = mysqli_fetch_assoc($info);
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/stilo.css">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="stylesheet" href="../css/coder.css">
    <script src="../js/script.js"></script>
    <title><?=$info['nome']?></title>
</head>
<body>
    <nav>
        <div>
            <ul>
                <a href="../"><li>inicial</li></a>
                <a href="../comunidade/"><li>comunidade</li></a>
                <a href="../contactos/"><li>contactos</li></a>
                <a href=""><li>noticias</li></a>
            </ul>
        </div>
    </nav>
    <div id="corpo">
        <?php
        $imagen_recent = pegar_foto_perfil("perfil",$id_dest);
        ?>
        <table>
        <tr>
            <td id="img_cmt"><img src="../media/img/<?=$imagen_recent?>" alt=""></td>
            <td><a href="./?user=<?=criptografar($id_dest)?>"><?=$info['nome']?></a></td>
        </tr>
        </table>
        <div id="v_imagen">
            <img src="./../media/img/<?=$imagen?>" alt="">
        </div>
        <div class="conteiner">
            <?php
            $sql = mysqli_query($link, "SELECT count(*) AS valor FROM doc WHERE id_doc > $id AND id_user=$id_dest");
            $sql = mysqli_fetch_assoc($sql);
            if ($sql['valor'] > 0) {
                ?>
                    <div class="esq"><a href="visualizar.php?id_img=<?=criptografar($id)?>&action=<?=0?>">anterior</a></div>
                <?php
            }
            $sql = mysqli_query($link, "SELECT count(*) AS valor FROM doc WHERE id_doc < $id AND id_user=$id_dest");
            $sql = mysqli_fetch_assoc($sql);
            if ($sql['valor'] > 0) {
                ?>
                    <div class="dir"><a href="visualizar.php?id_img=<?=criptografar($id)?>&action=<?=1?>">proximo</a></div>
                <?php
            }
            ?>          
        </div>
        <?php
        ?>
    </div>
    <footer class="bg-sec footer">
        <div>
            <a href="../login/">sair da sessao</a>
        </div>
    </footer>
</body>
</html>
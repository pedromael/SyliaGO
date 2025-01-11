<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";
 $c = new process;
 $poste = new postes;
 $comentarios = new comentarios;

 if (!isset($_SESSION["id_user"])) {
    #header("location: ../login/");
    ?>
        <script>
            document.location.href = "../login/"
        </script>
    <?php
 }
 $id_user = $_SESSION['id_user'];
 $link = conn();
if (isset($_GET['id_pbl']) || isset($_GET['id_cmt'])) {
    if (isset($_GET['id_pbl'])) {
        $id_pbl = descriptografar($_GET['id_pbl']);
        $pbl = $poste->poste($id_pbl);
        $texto = $pbl['texto'];
    }else {
        $id_cmt = descriptografar($_GET['id_cmt']);
        $cmt = $comentarios->comentario($id_cmt);
        $texto = $cmt['texto'];
    }
}else {
    #header("location: ../?erro=1");
    ?>
        <script>
            document.location.href = "../?erro=1"
        </script>
    <?php
}
if (isset($_POST['btn_eliminar']) || isset($_GET['eliminar'])) {
$c = new process;
    if ($c->eliminar_pbl($id_pbl)) {
        ?>
            <script>
                document.location.href = "../?pbl_eliminada"
            </script>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="stylesheet" href="../css/temas/<?=pegar_tema()?>.css">
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/stilo.css">
    <title></title>
</head>
<body>
    <nav id="nav_simples"><a href="./?pbl=<?=criptografar($id_pbl)?>">cancelar</a></nav>
    <div class="corpos">
        <div id="corpo" class="overflow-y-auto">
        <div id="corpo_pbl">
            <h3 class="text-center">pagina de edicao</h3>
            <div id="pbl_insert" class="conteiner_pbl">
                <form action="" method="post" enctype="multipart/form-data">
                    <div id="pbl1">
                        <textarea placeholder="faca a edicao da sua publicacao" name="texto"><?=$texto?></textarea>
                    </div>
                    <div id="pbl1">
                        <div><input class="file" type="submit" name="eliminar" value="eliminar"></div>
                        <div><button name="btn_editar">publicar</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    
    <?php $abrir_nav = "segundo"; require "../include/footer.php"; ?>
</body>
</html>
<?php
if (isset($_POST['btn_editar'])) {
    $texto = filtro($_POST['texto']);
    if (!empty($texto)) {
        if (isset($id_cmt)) {
            $upd = mysqli_query($link, "UPDATE cmt SET texto='$texto' WHERE id_cmt=$id_cmt");
            if ($upd) {
                #header("location: ./?ed=pbl&pbl=".criptografar($id_pbl));
                ?>
                    <script>
                        document.location.href = "./?ed=pbl&pbl=<?=criptografar($id_pbl)?>"
                    </script>
                <?php
            }
        }else {
            $upd = mysqli_query($link, "UPDATE pbl SET texto='$texto' WHERE id_pbl=$id_pbl");
            if ($upd) {
                #header("location: ./?ed=cmt&pbl=".criptografar($id_pbl));
                ?>
                    <script>
                        document.location.href = "./?ed=cmt&pbl=<?=criptografar($id_pbl)?>"
                    </script>
                <?php
            }
        }
    }
}

?>
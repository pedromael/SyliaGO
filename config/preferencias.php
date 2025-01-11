<?php
require "../conect.php";
require "../classes/process.php";
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";
$c = new process;
$n=0;
session_start();

if (!isset($_SESSION['id_user'])) {
    ?>
    <script type="text/javascript">
        window.location.href="login/";
    </script>
    <?php    
}
$id_user = $_SESSION['id_user'];
if (isset($_GET['id'])) {
    $id_tema = descriptografar($_GET['id']);
    $sql = mysqli_query(conn(), "SELECT * FROM $bdnome2.temas WHERE id_tema = $id_tema");
    $sql = mysqli_fetch_assoc($sql);
    if (isset($sql['id_tema'])) {
        $sql = mysqli_query(conn(), "SELECT * FROM $bdnome2.preferencias_usuarios WHERE id_user = $id_user");
        $sql = mysqli_fetch_assoc($sql);
        if (isset($sql['id_preferencia'])) {
            $id_preferencia = $sql['id_preferencia'];
            $sql = "UPDATE $bdnome2.preferencias_usuarios SET id_tema=$id_tema WHERE id_preferencia = $id_preferencia";
            if (mysqli_query(conn(),$sql)) {
                $tema_aplicado = true;
            }
        }else{
            $sql = "INSERT INTO $bdnome2.preferencias_usuarios(id_user,id_tema) VALUES($id_user,$id_tema)";
            if (mysqli_query(conn(),$sql)) {
                $tema_aplicado = true;
            }
        }
    }
}
$sql = mysqli_query(conn(), "SELECT nome FROM $bdnome2.temas WHERE id_user = $id_user");
$sql = mysqli_fetch_assoc($sql);
if (isset($sql['nome'])) {
    $nome_tema = $sql['nome'];
    if ($nome_tema == NULL) {
        $nome_tema = "padrao";
    }
}

?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <title>preferencias</title>
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/temas/<?=$nome_tema?>.css">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="../css/stilo.css">
</head>
<body>
    <nav id="metade_da_nav">
        <a href="../"><img src="../bibliotecas/bootstrap/icones/house.svg"></a>
    </nav>
    <nav>
        <h2>preferencias de Tema</h2>
        <select name="tema" id="">
            <option value="padrao">azul</option>
            <option value="branco">Branco</option>
            <option value="preto">Preto</option>
        </select>
    </nav>
    <div id="corpo">
        
    </div>
</body>
</html>
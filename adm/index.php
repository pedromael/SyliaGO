<?php
 $local_adm=true;
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";
 $c = new process;
 ?>
 <script>
     document.location.href = "../";
 </script>
<?php
 if (!isset($_SESSION["id_user"])) {
    #header("location: ../login/");
    ?>
        <script>
            document.location.href = "../login/";
        </script>
    <?php
 }
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link rel="stylesheet" href="../css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="../css/stilo.css">
    <link rel="stylesheet" href="../css/adm.css">
    <script src="../js/script.js"></script>
    <title>Gestor Do Sistema</title>
</head>
<body>
    <script>indereco = "../"</script>
    <div class="nav_adm">
        <div class="container">
            <div class="row">
                <div class="col"><a href="./">pagina inicial</a></div>
                <div class="col"><a href="./?usuarios">usuarios</a></div>
                <div class="col"><a href="./?mensagens">mensagens</a></div>
                <div class="col"><a href="./?estatisticas">estatisticas</a></div>
            </div>
        </div>
    </div>
    <div class="adm_corpos">
        <?php
        if (isset($_GET['usuarios'])) {
            include "includes/usuarios.php";
        }elseif(isset($_GET['estatisticas'])){
            include "includes/estatisticas.php";
        }elseif(isset($_GET['mensagens'])){
            include "includes/mensagens.php";
        }else{

        }
        ?>
    </div>
</body>
</html>
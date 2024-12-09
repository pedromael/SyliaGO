<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";
$c = new process;
if (!isset($_SESSION['id_user'])) {
   ?>
   <script>
    document.location.href = "../";
   </script>
   <?php 
}else {
    $id_user = $_SESSION['id_user'];
}

$user = mysqli_query(conn(),"SELECT * FROM usuarios WHERE id_user = $id_user");
$user = mysqli_fetch_assoc($user);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../src/img/glou_icon.png" type="image/x-icon">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="/src/css/temas/branco.css">
    <link rel="stylesheet" href="/src/css/welcome.css">
    <script src="../src/js/welcome.js"></script>
    <title>Bem vindo</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <h1 class="navbar-brand" href="#">SyliaGO</h1>
            <div class="collapse navbar-collapse justify-content-center">
                <div class="linha esq"></div>
                <div><h1 class="mx-3">
                    <span class="escrever_automatico" onclick="escrever()"><?=$user['nome']?></span> 
                    SEJA BEM-VINDO A SyliaGO
                </h1></div>
                <div class="linha dir"></div>
            </div>
        </div>
    </nav>

    <div class="container text-center mt-5">
        <div class="row align-items-center">
            <div class="col-md-6 mx-auto">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h2 class="card-title">Bem-vindo, <?=$user['nome']?>!</h2>
                        <p class="card-text text-muted">Estamos felizes em ter você aqui. Explore, aprenda e se divirta!</p>
                        <a href="coder.php" class="btn btn-primary btn-lg">Ir para Coder <span class="ms-2">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center text-light py-3 mt-5">
        <div class="d-flex justify-content-between align-items-center container">
            <a href="#" class="btn btn-outline-light px-3">
                <span class="anterior">&lt;</span> Voltar
            </a>
            <a href="coder.php" class="btn btn-outline-light px-3">
                Próximo <span class="proximo">&gt;</span>
            </a>
        </div>
    </footer>
</body>
</html>

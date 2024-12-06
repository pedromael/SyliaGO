<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

if (isset($_GET["id_repositorio"])) {
    $r = new repositorio(descriptografar($_GET['id_repositorio']));
}else{
    ?>
        <script>
            document.location.href = "../"
        </script>
    <?php
}

if (!isset($_SESSION['dir'])) {
    $_SESSION['dir'] = $r->diretotio;
}

$_SESSION['visualizado'] = array();

if (!isset($_SESSION["id_user"])) {
?>
    <script>
        document.location.href = "../login/"
    </script>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="../css/repositorio.css">
    <script src="../js/script.js"></script>
    <title>Repositorio</title>
</head>
<body class="d-flex flex-column">
    <!-- Barra de Navegação -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CodeRepo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Explorar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Projetos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Issues</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pull Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sobre</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Área de conteúdo principal -->
    <div class="container-fluid flex-grow-1 d-flex">
        <!-- Coluna de 10% -->
        <div class="menu-lateral col-1 d-flex">
            <h2>Menu Lateral</h2>
            <div class="container">
                <ul>
                    <?php
                    foreach (scandir($r->diretotio) as $dados) {
                        if ($dados != '.' && $dados != '..') {
                            ?>
                            <li><?=$dados?></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Coluna de 90% -->
        <div class="conteudo-principal col-11 d-flex">
            Conteúdo Principal
        </div>
    </div>

    <!-- Link do JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
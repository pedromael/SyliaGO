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
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
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
        <!-- Coluna de 20% -->
        <div class="menu-lateral col-2 d-flex">
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

        <!-- Coluna de 80% -->
        <div class="conteudo-principal col-10">
            <div class="container mt-4">
                <!-- Nome e ações do repositório -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><?= htmlspecialchars($r->repositorio()['nome']) ?></h2>
                    <div>
                        <a href="<?=$r->diretotio?>" target="_blank" class="btn btn-outline-primary me-2">
                            <i class="bi bi-github"></i> Repositório Remoto
                        </a>
                        <button class="btn btn-outline-success">Fork</button>
                    </div>
                </div>

                <!-- Informações gerais -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <p class="text-muted"><?= htmlspecialchars($r->repositorio()['descricao']) ?></p>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="badge bg-primary">HTML</span>
                        <span class="badge bg-secondary">CSS</span>
                        <span class="badge bg-success">JavaScript</span>
                    </div>
                </div>

                <!-- Commits recentes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Commits Recentes</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($r->commits_recentes as $commit): ?>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong><?= htmlspecialchars($commit['autor']) ?></strong> - 
                                            <span><?= htmlspecialchars($commit['mensagem']) ?></span>
                                        </div>
                                        <small class="text-muted"><?= htmlspecialchars($commit['data']) ?></small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Colaboradores -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Colaboradores</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <?php foreach ($r->colaboradores as $colaborador): ?>
                                <div class="me-3 mb-3 text-center">
                                    <img src="<?= htmlspecialchars($colaborador['avatar']) ?>" alt="Avatar" class="rounded-circle" width="50" height="50">
                                    <p class="mt-2 mb-0"><?= htmlspecialchars($colaborador['nome']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Issues e Pull Requests -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Issues</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <?php foreach ($r->issues as $issue): ?>
                                        <li class="list-group-item">
                                            <a href="#" class="text-decoration-none"><?= htmlspecialchars($issue['titulo']) ?></a>
                                            <span class="badge bg-warning"><?= htmlspecialchars($issue['status']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Pull Requests</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <?php foreach ($r->pull_requests as $pr): ?>
                                        <li class="list-group-item">
                                            <a href="#" class="text-decoration-none"><?= htmlspecialchars($pr['titulo']) ?></a>
                                            <span class="badge bg-info"><?= htmlspecialchars($pr['status']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link do JS do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
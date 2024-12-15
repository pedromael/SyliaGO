<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

$c = new process;
$cmdd = new comunidade;

if (!isset($_SESSION["id_user"])) {
    ?>
    <script>
        document.location.href = "../login/"
    </script>
    <?php
}

$imagen = pegar_foto_perfil("perfil",$_SESSION['id_user']);

if (isset($_GET['abrir'])) {
    if ($_GET['abrir'] == "pdd") {
        $novos = "pdd";
    }else if ($_GET['abrir'] == "nova") {
        $novos = "nova";
    }else if ($_GET['abrir'] == "") {
        $novos = false;
    }
}else{
    $novos = false;
}
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link href="/bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <link rel="stylesheet" href="/src/css/comunidade.css">
    <title>Comunidades</title>
</head>
<body>
    <script src="/src/js/script.js"></script>
    <script>var indereco = "../"</script>
    <?php
    require "../include/nav.php";
    ?>
    <div class="corpos">
        <div class="d-flex justify-content-center items-align-center w-100">
            <div class="container text-center">
                <div class="row">
                    <div class="btn bg-white m-1 col">minhas</div>
                    <div class="btn bg-white m-1 col-3">criar nova</div>
                    <div class="btn bg-white m-1 col">sugeridas</div>
                </div>
            </div>
        </div>
        <div class="corpo_metade1 rolagem_vertical">
            <div class="container d-flex justify-content-center w-100">
                <button class="btn btn-link text-decoration-none">
                    Minhas Comunidades
                </button>
            </div>

            <?php
            if ($novos == "nova") {?>
                <div class="card p-4 shadow-lg">
                    <h4 class="mb-4">Criar Nova Comunidade</h4>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Comunidade</label>
                            <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o nome da comunidade" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea name="descricao" id="descricao" class="form-control" rows="4" placeholder="Escreva uma breve descrição sobre sua comunidade"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Prosseguir</button>
                    </form>
                </div>
                <?php
                if (isset($_POST['nome'])) {
                    $nome = filtro($_POST['nome']);
                    $descricao = filtro($_POST['descricao']);
                    if (!empty($nome) && !empty($descricao)) {
                        if ($id = $cmdd->criar_comunidade($nome, $descricao)) {
                            ?>
                            <script>document.location.href = "./?cmndd=<?= criptografar($id) ?>";</script>
                            <?php
                        } else {
                            echo "<div class='alert alert-danger mt-3'>Ocorreu um erro na criação da comunidade.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning mt-3'>Por favor, preencha todos os campos.</div>";
                    }
                }
            }else{
                ?>
                <div class="container">
                    <?php
                    $num = $cmdd->minhas_comunidade();

                    if ($num < 5) {
                        ?>
                        <div class="alert1">
                            <div class="texto_interativo">Navega em <a href="./lista.php?abrir=pdd" class="destaque"><span>procurar comunidades</span></a> para aumentar sua experiencia na <span>Glou Game</span></div>
                        </div>
                        <?php
                    }else {
                        if ($num < 8) {
                            ?>
                                <div id="mais_pbl"><a href="./">Ver Mais</a></div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?> 
        </div>
        <div class="corpo_metade2 rolagem_vertical">
            <div class="container d-flex justify-content-center w-100">
                <button class="btn btn-link text-decoration-none">
                    Comunidades sugeridas
                </button>
            </div>
        <?php
        if($novos == "pdd" || true){
                ?>
                <div>
                <?php
                /*encontrar novas comunidades*/
                    if (isset($_GET['cmndd'])) {
                        $id_comunidade = filtro(descriptografar($_GET['cmndd']));

                        $sql = mysqli_query(conn(), "SELECT * FROM comunidade WHERE id_comunidade=$id_comunidade");
                        $sql = mysqli_fetch_assoc($sql);
                        $nome = $sql['nome'];
                        if ($caso = $cmdd->entrar_na_comunidade($id_comunidade)) {
                            if ($caso == 1) {
                                ?>
                                    <div class="info_corrente">agora es um membro da(o) <a href="./?cmndd=<?=criptografar($id_comunidade)?>"><span><?=$nome?></span></a>, acessa para saber mais</div>
                                <?php
                            }else {
                                ?>
                                    <div class="info_corrente">pedido de participacaoo enviado para <span><?=descriptografar($nome)?></span> com sucesso</div>
                                <?php
                            }                  
                        }else {
                            ?>
                                <div class="info_corrente">ocorreu algum erro no pedido de participacao para <span><?=descriptografar($nome)?></span></div>
                            <?php
                        }
                    }
                    $cmdd->comunidades_sugerida();
                    ?>
                </div>
                <?php
            }?>
        </div>
    </div>
    <?php
    include "../include/footer.php";
    ?>
</body>
</html>
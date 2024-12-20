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
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
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
        <style>
            @media (min-width: 620px) {
                .apagar{
                    display: none;
                }
            }
        </style>
        <div class="d-flex justify-content-center items-align-center w-100">
            <div class="container text-center m-1">
                <div class="row">
                    <div class="btn apagar bg-white m-1 p-1 col <?php if ($novos == false) echo 'active'; ?>" onclick="document.location.href='lista.php?abrir='">minhas</div>
                    <div class="btn bg-white m-1 p-1 col-3      <?php if ($novos == "nova") echo 'active'; ?>" onclick="document.location.href='lista.php?abrir=nova'">criar nova</div>
                    <div class="btn apagar bg-white m-1 p-1 col <?php if ($novos == "pdd") echo 'active'; ?>" onclick="document.location.href='lista.php?abrir=pdd'">sugeridas</div>
                </div>
            </div>
        </div>
        <div class="container">
            <?php
            if (isset($_GET["cmndd"])) {
                $id_comunidade = filtro(descriptografar($_GET['cmndd']));

                $sql = mysqli_query(conn(), "SELECT * FROM comunidade WHERE id_comunidade=$id_comunidade");
                $sql = mysqli_fetch_assoc($sql);
                $nome = $sql['nome'];
                if ($caso = $cmdd->entrar_na_comunidade($id_comunidade)) {
                    if ($caso == 1) {
                        ?>
                            <div class="info_corrente">agora es um membro da(o) <a href="./?cmndd=<?=criptografar($id_comunidade)?>"><span><?=descriptografar($nome)?></span></a>, acessa para saber mais</div>
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
            ?>
        </div>
        <div class="corpo_metade1 rolagem_vertical">
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
                }else if($novos != "pdd"){
                    ?>
                    <div class="container d-flex justify-content-center w-100">
                        <button class="btn btn-link text-decoration-none">
                            Minhas Comunidades
                        </button>
                    </div>
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
        <div class="corpo_metade2 rolagem_vertical"></div>
    </div>
    <?php
    include "../include/footer.php";
    ?>
</body>
</html>
<script>
    var elemento = document.querySelector('.info_corrente')

    var largura = window.innerWidth;
    var corpo1 = document.querySelector(".corpo_metade1")
    var corpo2 = document.querySelector(".corpo_metade2")

    if (elemento !== null) {
        corpo1.style.height = "calc(100% - 40px - 16px - 53px)";
        corpo2.style.height = "calc(100% - 40px - 16px - 53px)";
    }

    var xhr = new XMLHttpRequest()
    xhr.open('POST', 'include/comunidades_sugeridas.php', true)
    xhr.setRequestHeader('Content-Type', 'application/json')

    if (largura > 620) {
        xhr.onload = function() {
            if (xhr.status === 200) {
                corpo2.innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }else{
        const urlParams = new URLSearchParams(window.location.search)
        const nome = urlParams.get('abrir')

        if (nome == "pdd") {
            xhr.onload = function() {
                if (xhr.status === 200) {
                    corpo1.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    }
</script>
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
 }else {
    $id_user = $_SESSION["id_user"];
    $user = mysqli_query(conn(), "SELECT * FROM usuarios WHERE id_user=$id_user");
    $user = mysqli_fetch_assoc($user);
    $imagen = pegar_foto_perfil("perfil",$id_user);
   }
 $id_user = $_SESSION['id_user'];

 if (isset($_GET['abrir'])) {
    if ($_GET['abrir'] == "pdd") {
        $novos = "pdd";
    }
    if ($_GET['abrir'] == "nova") {
        $novos = "nova";
    }
    if ($_GET['abrir'] == "") {
        $novos = false;
    }
 }
 if (!isset($novos)) {
    $novos = false;
 }
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <div id="aps" class="">
            <div class="container p-5">
                <a href="lista.php?abrir=nova" class="text-center btn_simples2">criar comunidade</a>
            </div>
            <div class="block">
                <div class="inline">
                <a href="./lista.php" class="btn_simples 
                <?php
                    if (!isset($_GET['abrir'])) {
                        echo "selecionado";
                    }
                ?>
                ">minhas comunidades</a>
                </div>
                <div class="inline">
                    <a href="./lista.php?abrir=pdd" class="btn_simples 
                    <?php
                        if (isset($_GET['abrir'])) {
                            if ($_GET['abrir'] == 'pdd') {
                                echo "selecionado";
                            } 
                        }
                    ?>
                    ">procurar comunidades</a>
                </div>
            </div>
            
        </div>
        <div class="container  d-flex justify-content-center align-items-center">
            <a href="lista.php?abrir=nova">
                <div class="ops_nova_comunidade btn bg-sec">Criar nova comunidade</div>
            </a>
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
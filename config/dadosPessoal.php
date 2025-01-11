<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";

$p = new process;
if (!isset($_SESSION['id_user'])) {
    ?>
    <script>
        document.location.href = '../login/';
    </script>
    <?php
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <link rel="stylesheet" href="/src/css/login.css">
</head>
<body>
    <nav id="nav_simples">
        <a href="../" class="link">pagina inicial</a>
        <a href="./" class="link">voltar</a>
    </nav>
    <div id="corpo">
        <?php
            if (empty($_GET['abrir'])) {
                # code...
            }elseif($_GET['abrir'] == 'mudarnome'){
                if (isset($_POST['nome'])) {
                    $nome = $_POST['nome'];
                    function verificar_nome($nome) {
                        return true;
                    }
                    if (verificar_nome($nome)) {
                        if ($nome = $p->mudar_nome($nome)) {
                            ?>
                            <div id="alerta" class="">
                                <div class="modal modal-sheet d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSheet">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content rounded-4 shadow">
                                    <div class="modal-header border-bottom-0">
                                        <h1 class="modal-title fs-5">nome actualizado com sucesso</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="aba_carregar_foto()"></button>
                                    </div>
                                        <div class="modal-body py-0">
                                            <p>
                                                <a href="../perfil/"><?=$nome?></a>, continua fazendo proveito da sua zona de conforto, a PRO-Start...
                                            </p>
                                        </div>
                                        <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                                            <input name="btn_img" type="submit" class="btn bg-sec" onclick="document.location.href = '../'" value="Voltar a pagina inicial"> 
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <?php
                        }else {
                            echo "problema ao processar evento tente mais tarde";
                        }
                    }else{
                        echo "nome nao aceite, expirimente outro";
                    }
                }else{
                    ?>
                    <div class="size80 center login login_conteiner">
                        <form action="dadosPessoal.php?abrir=mudarnome" method="post">
                            <fieldset>
                                <div class="bg">
                                    <div>
                                    <p class="center">digite aqui o novo nome</p>
                                    <input class="form-control" type="text" name="nome">
                                    </div>
                                    <p></p>
                                    <div class="size80">
                                        <button class="form-control button">mudar nome</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <?php
                }
            }elseif($_GET['abrir'] == 'mudarsenha'){

            }elseif($_GET['abrir'] == 'pbl'){
                ?>
                <div class="conteiner">
                    <form action="" method="post">
                        <div class="contein">
                            <p>quem pode ver minhas publicacoes</p>
                            <select name="" id="">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="contein">
                            <p>quem pode ver minhas publicacoes</p>
                            <select name="" id="">
                                <option value=""></option>
                            </select>
                        </div>
                    </form>
                </div>
                <?php
            }
        ?>
    </div>
</body>
</html>
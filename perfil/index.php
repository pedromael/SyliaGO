<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";

 $c = new process;

 if (!isset($_SESSION["id_user"])) {
    #header("location: ../login/");
    ?>
        <script>
            document.location.href = "../login/"
        </script>
    <?php
 }
 if (isset($_GET['user'])) {
    $id_user = descriptografar($_GET['user']);
 } else {
    $id_user = $_SESSION['id_user'];
 }
 ?>
 <script>
 </script>
 <?php
 $link = conn();
 $sql = mysqli_query(conn(), "SELECT * FROM usuarios WHERE id_user=$id_user");
 $sql = mysqli_fetch_assoc($sql);

 $imagen_perfil = pegar_foto_perfil("perfil",$id_user);

 if (isset($_FILES['img'])) {
    
    if (carregar_img($_FILES['img'],"perfil",0)) {
        ?>
        <script>
            document.location.href = "./"
        </script>
        <?php
    }
}

?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link href="/bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">  
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <link rel="stylesheet" href="/src/css/perfil.css">
    <link rel="stylesheet" href="/src/css/coder.css">
    <script src="/src/js/script.js"></script>
    <title><?=$sql['nome']?></title>
</head>
<body>
    <script>var indereco="../";</script>
    <?php
    require "../include/nav.php";
    if ($id_user == $_SESSION['id_user']) {
        ?>
        <div id="alerta" class="remover">
            <div class="modal modal-sheet d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSheet">
                <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5">carregar foto de perfil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="aba_carregar_foto()"></button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body py-0">
                        <p><input type="file" name="img" class="form-control"></p>
                    </div>
                    <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                        <input name="btn_img" type="submit" class="btn bg-sec" value="carregar"> 
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="corpos">
        <div class="corpo3 crp"></div>
        <div id="corpo" class="crp">
            <?php
            if ($_SESSION['id_user'] == $sql['id_user']) {
                ?>
                    <a href="../mdd.php">
                        <p class="ver_info" >de mais detalhes sobre a sua carreira</p>
                    </a>
                <?php
            }
            ?>
            <div id="cabe">
                <div id="dir">
                    <div id="dados">
                        <style>
                            .perfil_img{
                                background-image: url(<?=$imagen_perfil?>);
                            }
                        </style>
                        <?php
                        if ($id_user == $_SESSION['id_user']) {
                            $parametro = 1;
                        }else {
                            $parametro = 2;
                        }
                        ?>
                        <div class="perfil_img" onmouseover="perfil_img(<?=$parametro?>,1)" onmouseout="perfil_img(<?=$parametro?>,11)">
                        <?php
                        if ($id_user == $_SESSION['id_user']) {
                            if ($imagen_perfil != "sem_img_no_perfil.jpeg") {
                            ?>
                            <a href="visualizar.php?image=<?=criptografar($imagen)?>"><div class="div1_img perfil_img_1 remover" onmouseover="personalizar(2)"></div></a>
                            <?php
                            }
                            ?>
                            <div class="div2_img perfil_img_1 remover" onmouseover="personalizar(2)" onclick="aba_carregar_foto()"></div>
                        <?php
                        }else{
                            if ($imagen_perfil != "sem_img_no_perfil.jpeg") {
                        ?>
                            <a href="visualizar.php?image=<?=criptografar($imagen)?>"><div class="div1_img perfil_img_1 remover" onmouseover="personalizar(2)"></div></a>
                        <?php
                            }
                        }
                        ?>
                        </div>
                        <p><?=$sql['nome']?></p>
                        <?php
                        $a_seguir = $seguidores = 0;
                        $sql = mysqli_query($link, "SELECT * FROM contacto WHERE id_user= $id_user OR id_user_dest=$id_user");
                        while ($row = mysqli_fetch_assoc($sql)) {
                            $id_contacto = $row['id_contacto'];
                            if ($id_user == $row['id_user']) {
                                $aceite = mysqli_query($link, "SELECT count(*) AS valor FROM $bdnome2.contacto_aceite WHERE id_contacto =$id_contacto");
                                $aceite = mysqli_fetch_assoc($aceite);
                                if ($aceite['valor'] > 0) {
                                    $a_seguir++;
                                    $seguidores++;
                                } else {
                                    $a_seguir++;
                                }
                            }else {
                                $aceite = mysqli_query($link, "SELECT count(*) AS valor FROM $bdnome2.contacto_aceite WHERE id_contacto =$id_contacto");
                                $aceite = mysqli_fetch_assoc($aceite);
                                if ($aceite['valor'] > 0) {
                                    $a_seguir++;
                                    $seguidores++;
                                } else {
                                    $seguidores++;
                                }
                            }
                        }
                        ?>
                        <div id="a_seguir">
                            <p>seguidores <?=$seguidores?> / a seguir <?=$a_seguir?></p>
                        </div>
                    </div>
                </div>
                <div id="esq">
                    <table class="cima">
                            <tr>
                                <td class="bloco">
                                    <div class="estatistica"><?=media_de_interacao($id_user)?></div>
                                    <div class="referencia"><p>media de interacao</p></div>
                                </td>
                                <td class="bloco">
                                    <div class="estatistica"><?=qtd_pbl_user($id_user)?></div>
                                    <div class="referencia"><p>Postes</p></div>
                                </td>
                                <td class="bloco">
                                    <div class="estatistica"></div>
                                    <div class="referencia"><p>projectos realizados</p></div>
                                </td>
                            </tr>
                        
                    </table>
                    <div class="baixo">
                        <?php
                        if ($id_user == $_SESSION['id_user']) {
                            ?>
                            <div class="esq">
                                <a href=""><button class="form-control msg">mais detalhes</button></a>
                            </div>
                            <?php
                        }else {
                            ?>
                            <div class="esq">
                                <?php
                                    if (verificar_contacto($id_user,$_SESSION['id_user'])) {
                                        ?>
                                            <button class="form-control msg">opcoes</button>
                                        <?php
                                    }else {
                                        ?>
                                            <button class="form-control msg">adicionar</button>
                                        <?php
                                    }
                                ?>
                                <a href="/mensagens/?user=<?=criptografar($id_user)?>"><button class="form-control msg">mensagem</button></a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if ($id_user == $_SESSION['id_user']) {
                ?>
                <div class="ops_perfil">
                    <a href=""><div>files</div></a>
                    <div onclick="mostrar_lista_amigos('<?=criptografar($id_user)?>')">amigos</div>
                    <a href=""><div>CODES</div></a>
                    <a href=""><div>mais</div></a>
                </div>
                <?php
            }else{
                ?>
                <div class="ops_perfil">
                    <a href=""><div>fotos</div></a>
                    <div onclick="mostrar_lista_amigos('<?=criptografar($id_user)?>')">amigos</div>
                    <a href=""><div>CODES</div></a>
                    <a href=""><div>mais</div></a>
                </div>
                <?php
            }
            ?>
            <div class="container_amigos"></div>
            <div>
                <?php
                if (!isset($_GET['user'])) {
                    $id_user = $_SESSION['id_user'];
                }
                $_SESSION['visualizado'] = array();
                $s = new selecionar_feed();
                $s->id = $id_user;
                $s->selecionar_poste("perfil");
                ?>
                <p></p>
            </div>
        </div>
        <div class="corpo2 crp"></div>
    </div>
    <?php
        include "../include/footer.php";
    ?>
    <script src="/src/js/fim_script.js"></script>
    <script src="/src/js/coder.js"></script>
</body>
</html>
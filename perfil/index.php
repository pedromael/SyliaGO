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
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">  
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="/src/css/stilo.css">
    <!-- <link rel="stylesheet" href="/src/css/perfil.css"> -->
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
            <div class="container-fluid py-4">
                <div class="row">
                    <!-- Coluna esquerda: Perfil e Dados do Usuário -->
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div class="position-relative">
                            <div class="perfil_img" style="background-image: url(<?=$imagen_perfil?>); width: 100px; height: 100px; border-radius: 50%; background-size: cover; background-position: center;" onmouseover="perfil_img(<?=$parametro?>,1)" onmouseout="perfil_img(<?=$parametro?>,11)">
                            <?php if ($id_user == $_SESSION['id_user']): ?>
                                <?php if ($imagen_perfil != "sem_img_no_perfil.jpeg"): ?>
                                <a href="visualizar.php?image=<?=criptografar($imagen)?>">
                                    <div class="position-absolute top-0 end-0 m-2">
                                    <!-- <div class="btn btn-outline-light btn-sm">Remover</div> -->
                                    </div>
                                </a>
                                <?php endif; ?>
                                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2">
                                <button class="btn btn-outline-light btn-sm" onclick="aba_carregar_foto()">Alterar foto</button>
                                </div>
                            <?php elseif ($imagen_perfil != "sem_img_no_perfil.jpeg"): ?>
                                <a href="visualizar.php?image=<?=criptografar($imagen)?>">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <!-- <div class="btn btn-outline-light btn-sm">Visualizar</div> -->
                                </div>
                                </a>
                            <?php endif; ?>
                            </div>
                        </div>
                        <p class="mt-2  fs-5 fw-bold"><?=$sql['nome']?></p>
                        <?php
                            $a_seguir = $seguidores = 0;
                            $sql = mysqli_query($link, "SELECT * FROM contacto WHERE id_user= $id_user OR id_user_dest=$id_user");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                $id_contacto = $row['id_contacto'];
                                $aceite = mysqli_query($link, "SELECT count(*) AS valor FROM $bdnome2.contacto_aceite WHERE id_contacto =$id_contacto");
                                $aceite = mysqli_fetch_assoc($aceite);
                                if ($id_user == $row['id_user']) {
                                    if ($aceite['valor'] > 0) {
                                        $a_seguir++;
                                        $seguidores++;
                                    } else {
                                        $a_seguir++;
                                    }
                                } else {
                                    if ($aceite['valor'] > 0) {
                                        $a_seguir++;
                                        $seguidores++;
                                    } else {
                                        $seguidores++;
                                    }
                                }
                            }
                        ?>
                        <div class="mt-2 text-muted text-center fs-6">
                            <span>Seguidores: <?=$seguidores?> </span>/<span> A seguir: <?=$a_seguir?></span>
                        </div>
                    </div>

                    <!-- Coluna direita: Estatísticas -->
                    <div class="col-md-8">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">Média de Interação</th>
                            <th scope="col" class="text-center">Postagens</th>
                            <th scope="col" class="text-center">Repositorios</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center"><?=media_de_interacao($id_user)?></td>
                            <td class="text-center"><?=qtd_pbl_user($id_user)?></td>
                            <td class="text-center">-</td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="mt-4 mb-2 p-2 border rounded">
                        <!-- <h5>Área</h5> -->
                        <p>Desenvolvedor Web</p>
                    </div>
                    <div class="d-flex justify-content-start">
                        <?php if ($id_user == $_SESSION['id_user']): ?>
                        <a href="" class="btn btn-primary btn-sm">Mais detalhes</a>
                        <?php else: ?>
                        <a href="/mensagens/?user=<?=criptografar($id_user)?>" class="btn btn-outline-info btn-sm ms-2">Mensagem</a>
                        <?php endif; ?>
                        <?php if (true): // varificar amizade?>
                            <button class="btn btn-outline-primary btn-sm">Opções</button>
                        <?php else: ?>
                            <button class="btn btn-outline-success btn-sm">Adicionar</button>
                        <?php endif; ?>
                    </div>
                    </div>
                </div>
            </div>
<?php
if ($id_user == $_SESSION['id_user']) {
    ?>
    <div class="container p-3">
        <div class="row">
            <a href="#" class="btn bg-white col m-1">Files</a>
            <div class="btn bg-white col m-1" onclick="mostrar_lista_amigos('<?=criptografar($id_user)?>')">Amigos</div>
            <a href="#" class="btn bg-white col m-1">Codes</a>
            <a href="#" class="btn bg-white col m-1">Mais</a>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="container p-3">
        <div class="row">
            <a href="#" class="btn bg-white col m-1">Files</a>
            <div class="btn bg-white col m-1" onclick="mostrar_lista_amigos('<?=criptografar($id_user)?>')">Amigos</div>
            <a href="#" class="btn bg-white col m-1">Codes</a>
            <a href="#" class="btn bg-white col m-1">Mais</a>
        </div>
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
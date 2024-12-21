<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";
 $c = new process;
 
 if (!isset($_SESSION["id_user"])) {
    #header("location: ../login/");
    ?>
        <script>
            windows.location.href = "../login/"
        </script>
    <?php
 }

 $id_user = $_SESSION['id_user'];

 if (isset($_GET['abrir'])) {
    $novos = "pdd";
 }else {
    $novos = false;
 }
 $link = conn();
 $user = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user=$id_user");
 $user = mysqli_fetch_assoc($user);
 $imagen = pegar_foto_perfil('perfil',$id_user);
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="/src/img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="/src/css/temas/<?=pegar_tema()?>.css">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">    
    <link rel="stylesheet" href="/src/css/stilo.css">
    <title>Contactos</title>
</head>
<body>
    <script>var indereco = "../"</script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/src/js/script.js"></script>
    <?php
    require "../include/nav.php";
    ?>
    <div class="corpos">
        <div class="container d-flex justify-content-center p-2">
            <div class="row align-items-center">
                <!-- Aba Centralizada -->
                <div class="col">
                    <a href="./index.php" 
                    class="btn btn-outline-primary fw-bold 
                    <?php if (!isset($_GET['abrir'])) echo 'active'; ?>">
                        Novos
                    </a>
                </div>

                <!-- Aba à Direita -->
                <div class="col">
                    <?php if ($c->verificar_qtd("pdd", $id_user) > 0) { ?>
                        <a href="index.php?abrir=pdd" 
                        class="btn btn-outline-primary fw-bold 
                        <?php if (isset($_GET['abrir']) && $_GET['abrir'] === 'pdd') echo 'active'; ?>">
                            Pedidos(<span><?=$c->verificar_qtd("pdd", $id_user)?></span>)
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>  
        <div id="corpo_contactos">
            <?php
            if($novos == "pdd"){
                ?>
                <div class="pdd">
                    <?php 
                    if (isset($_GET['id']) && isset($_GET['case'])) {
                        if ($_GET['case'] == "aceitar") {
                            $id = descriptografar($_GET['id']);
                            $nome = descriptografar($_GET['nome']);
                            if ($resultado = $c->aceitar_contacto($id)) {
                                ?>
                                    <div class="info_corrente">pedido de contacto de <span><?=$nome?></span> aceite com sucesso</div>
                                <?php
                            }
                        }
                    }
                    if (isset($_GET['id']) && isset($_GET['case'])) {
                        if ($_GET['case'] == "apagar") {
                            $id = descriptografar($_GET['id']);
                            $nome = descriptografar($_GET['nome']);
                            if ($resultado = $c->eliminar_pedido_contacto($id)) {
                                ?>
                                    <div class="info_corrente">pedido de contacto de <span><?=$nome?></span> aliminado com sucesso</div>
                                <?php
                            }
                        }
                    }
                        

                    $sql = mysqli_query($link, "SELECT * FROM contacto WHERE id_user_dest=$id_user");
                    while ($row = mysqli_fetch_assoc($sql)) {
                        $id_contacto = $row['id_contacto'];
                        $sqll = mysqli_query($link, "SELECT count(*) as valor FROM $bdnome2.contacto_aceite WHERE id_contacto = $id_contacto");
                        $sqll = mysqli_fetch_assoc($sqll);
                        if ($sqll['valor'] <= 0) {
                            $id_dest = $row['id_user'];

                            $user = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user=$id_dest");
                            $user = mysqli_fetch_assoc($user);

                            $imagen = pegar_foto_perfil("perfil",$id_dest);
                            ?>
                            <div class="d-flex align-items-center p-3 border-bottom shadow-sm">
                                <!-- Imagem do usuário -->
                                <div class="flex-shrink-0">
                                    <div class="rounded-circle" 
                                        style="background-image: url('<?=$imagen?>'); 
                                                width: 50px; 
                                                height: 50px; 
                                                background-size: cover; 
                                                background-position: center;">
                                    </div>
                                </div>

                                <!-- Informações do usuário -->
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="/perfil/?user=<?=criptografar($id_dest)?>" class="text-decoration-none text-dark">
                                            <?=$user['nome']?>
                                        </a>
                                    </h6>
                                </div>

                                <!-- Botões de ação -->
                                <div class="d-flex">
                                    <!-- Botão aceitar -->
                                    <a href="/contactos/?abrir=pdd&id=<?=criptografar($row['id_contacto'])?>&nome=<?=criptografar($user['nome'])?>&case=aceitar" 
                                    class="btn btn-primary btn-sm me-2">
                                        Aceitar
                                    </a>
                                    <!-- Botão eliminar -->
                                    <a href="./index.php?abrir=pdd&id=<?=criptografar($row['id_contacto'])?>&nome=<?=criptografar($user['nome'])?>&case=apagar" 
                                    class="btn btn-danger btn-sm">
                                        Eliminar
                                    </a>
                                </div>
                            </div>

                            <?php
                        }
                    }
                ?>
                </div>
                <?php
            } 
            if($novos == false) {
                ?>
                <div>
                <?php
                if (isset($_GET['user'])) {
                    $id = descriptografar($_GET['user']);
                    $nome = descriptografar($_GET['nome']);
                    if ($resultado = $c->solicitar_contacto($id)) {
                        if ($resultado == 1) {
                        ?>
                            <div class="info_corrente">selicitacao de amizade enviada para <span><?=$nome?></span> com sucesso.</div>
                        <?php
                        } elseif($resultado == 2) {
                        ?>
                            <div><span><?=descriptografar($nome)?></span> agora faz parte dos seus contactos</div>
                        <?php
                        } elseif($resultado == 3) {
                            ?>
                                <div>ja tem um pedido feito para esse usuario <span><?=descriptografar($nome)?></span></div>
                            <?php
                        }
                    }
                }
                ?>
                <?php
                $id_user = $_SESSION['id_user'];
                $sql = mysqli_query($link, "SELECT * FROM usuarios WHERE id_user!=$id_user AND id_user!=4");              
                $pessoas_sugeridas = array();

                function comparar_peso($a,$b){
                    return $b['ligacao'] - $a['ligacao'];//ordem descrescente pelo caso do b estar primeiro
                }
                while ($row = mysqli_fetch_assoc($sql)) {
                    $id = $row['id_user'];
                    $sqll = mysqli_query($link, "SELECT count(*) AS total FROM contacto WHERE (id_user = $id
                    AND id_user_dest = $id_user) OR (id_user = $id_user AND id_user_dest = $id)");
                    $sqll = mysqli_fetch_assoc($sqll);
                    if ($sqll['total'] <= 0) {
                        $row["ligacao"] = (new informacoes_usuario())->ligacao_entre_usuario($id);
                        array_push($pessoas_sugeridas, $row);
                        usort($pessoas_sugeridas,'comparar_peso');
                    }
                }
                foreach ($pessoas_sugeridas as $pessoas_sugerida) {

                    $id = $pessoas_sugerida['id_user'];
                    $imagen = pegar_foto_perfil("perfil",$id);
                    $nome = $pessoas_sugerida['nome'];
                    ?>
                        <div class="d-flex align-items-center p-3 border-bottom shadow-sm">
                            <!-- Imagem do usuário -->
                            <div class="flex-shrink-0">
                                <div class="rounded-circle" 
                                    style="background-image: url('<?=$imagen?>'); 
                                            width: 50px; 
                                            height: 50px; 
                                            background-size: cover; 
                                            background-position: center;">
                                </div>
                            </div>

                            <!-- Informações do usuário -->
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="/perfil/?user=<?=criptografar($id)?>" class="text-decoration-none text-dark">
                                        <?=$pessoas_sugerida['nome']?>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <?php
                                    $AC = verificar_contactos_em_comum($_SESSION['id_user'], $id);
                                    if ($AC <= 0) {
                                        // No mutual friends
                                    } elseif ($AC == 1) {
                                        echo $AC, " amigo em comum";
                                    } elseif ($AC > 1 && $AC <= 15) {
                                        echo $AC, " amigos em comum";
                                    } else {
                                        echo "15+ amigos em comum";
                                    }
                                    ?>
                                </small>
                            </div>

                            <!-- Botão de ação -->
                            <div>
                                <a href="/contactos/index.php?user=<?=criptografar($id)?>&nome=<?=criptografar($nome)?>" 
                                class="btn btn-primary btn-sm">
                                    Fazer Pedido
                                </a>
                            </div>
                        </div>

                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
    include "../include/footer.php";
    ?>
    <script src="/src/js/fim_script.js"></script>
</body>
</html>
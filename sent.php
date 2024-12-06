<?php
if (isset($_POST['btn_pbl'])) {
    $texto = filtro($_POST['texto']);
    if (!isset($id_comunidade)) {
        $id_comunidade = 0;
    }
    $doc = false;
    $imagens = array();

    // Verificando se o formulário tem arquivos enviados
    if (isset($_FILES['doc']) && $_FILES['doc']['name'][0] != NULL) {
        $nome = $_FILES['doc']['name'];
        $tmp = $_FILES['doc']['tmp_name'];
        $type = $_FILES['doc']['type'];
        $size = $_FILES['doc']['size'];
        $a = 0;

        // Loop para processar cada imagem enviada
        while ($a < count($nome)) {
            $ext = strtolower(substr($nome[$a], -4));
            if ($ext[0] != ".") {
                $ext = "." . $ext;
            }
            $nome_img = "IMG-" . $a . "-" . $_SESSION['id_user'] . "-pbl-" . date("Y.m.d-H.i.s") . $ext;

            array_push($imagens, array(
                "indereco" => $nome_img,
                "name" => $nome[$a],
                "tmp_name" => $tmp[$a],
                "type" => $type[$a],
                "size" => $size[$a]
            ));

            $a++;
        }

        $dir = 'src/userFile/'.$user['code_nome'].'/img/';
        $doc = true;
    } else {
        $nome_img = NULL;
    }

    if (!empty($texto) || $doc) {
        if (true) {  // Este "if (true)" é redundante, pode ser removido
            // Processamento do upload de imagens
            if (isset($_FILES['doc']) && !empty($imagens)) {
                foreach ($imagens as $imagen) {
                    // Corrigido: Não precisa redefinir $_FILES['doc'], use diretamente $imagen
                    if (!move_uploaded_file($imagen['tmp_name'], $dir . $imagen['indereco'])) {
                        ?>
                        <script>
                            window.location.href="index.php?pbl=erro_img";
                        </script>
                        <?php
                    }
                }
            }

            // Publicando o texto e os arquivos
            if ($id_pbl = $c->publicar($texto, $id_comunidade, $nome_img)) {
                if (isset($id_comunidade)) {
                    if ($nome_img != NULL) {
                        $tipo = "poste";
                        // Carregar documentos após a publicação
                        foreach ($imagens as $imagen) {
                            if ($c->carregar_documento($id_pbl, $tipo, $imagen['indereco'])) {
                                // Documento carregado com sucesso
                            } else {
                                echo "Falha ao carregar documento.";
                            }
                        }
                        unset($_FILES['doc']);
                        unset($imagens);
                    }

                    ?>
                    <script>
                        window.location.href="index.php?cmndd=<?=$id_comunidade?>&pbl=<?=criptografar($id_pbl)?>";
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        window.location.href="index.php?pbl=<?=criptografar($id_pbl)?>";
                    </script>
                    <?php
                }
            } else {
                echo "Ocorreu algum erro ao realizar publicação.";
            }
        }
    }
}

if (isset($_POST['btn_pbl_comunidade'])) {
    $texto = filtro($_POST['texto']);
    if (!isset($id_comunidade)) {
        $id_comunidade = 0;
    }
    if (isset($_FILES['doc']) && $_FILES['doc']['name'][0] != NULL) {
        $nome = $_FILES['doc']['name'];
        $tmp = $_FILES['doc']['tmp_name'];
        $type = $_FILES['doc']['type'];
        $size = $_FILES['doc']['size'];
        $a =0;
        $imagens = array();

        while ($a < count($nome)) {
            $ext = strtolower(substr($nome[$a], -4));
            if ($ext[0] != ".") {
                $ext = "." . $ext;
            }
            $nome_img = "IMG-".$a ."-". $_SESSION['id_user'] . "-pbl-" . date("Y.m.d-H.i.s") . $ext;
            array_push($imagens,array("indereco"=>$nome_img,"name"=>$nome[$a],"tmp_name"=>$tmp[$a],"type"=>$type[$a],"size"=>$size[$a]));
            $a++;
        }
        $dir = '../src/userFile/'.$user['code_nome'].'/img/';
        $doc = true;
    }else {
        $nome_img = NULL;
    }
    if (!empty($texto) || $doc) {
        if(true){
            if (isset($_FILES['doc']) && $_FILES['doc']['name'][0] != NULL) {
                foreach ($imagens as $imagen) {
                    $_FILES['doc'] = $imagen;
                    if (!move_uploaded_file($_FILES['doc']['tmp_name'], $dir . $_FILES['doc']['indereco'])) {
                        ?>
                        <script>
                            window.location.href="index.php?pbl=erro_img";
                        </script>
                        <?php
                    }
                }
                
            }
            if  ($id_pbl = $c->publicar($texto,$id_comunidade,$nome_img)) {
                if (isset($id_comunidade)) {
                    if ($nome_img != NULL) {
                        $tipo = "poste";
                        if ($nome_img != NULL) {
                            $tipo = "pbl";
                            foreach ($imagens as $imagen) {
                                if ($c->carregar_documento($id_pbl,$tipo,$imagen['indereco'])) {
    
                                }else {
                                    echo "falha a carregar documento";
                                }
                            }
                            unset($_FILES['doc']);
                            unset($imagens);
                        }
                    }
                    ?>
                    <script>
                        window.location.href="index.php?cmndd=<?=criptografar($id_comunidade)?>&pbl=true";
                    </script>
                    <?php
                }else {
                    ?>
                    <script>
                        window.location.href="index.php?pbl=true";
                    </script>
                    <?php
                }
            } else {
                echo "ocorreu algum erro ao realizar publicacao";
            }
        }    
    }
}
if (isset($_POST['texto_chat'])) {
    $texto = filtro($_POST['texto_chat']);
    $id_doc = 0;
    if (!empty($texto)) {
        if ($c->enviar_mensagem($texto,$id_dest,$id_doc)) {
            #header("location: chat.php?user=".criptografar($id_dest));
            ?>
                <script>
                    document.location.href = "chat.php?user=<?=criptografar($id_dest)?>"
                </script>
            <?php
        } else {
            ?>
            <script>
                alert("algo deu errado")
            </script>
            <?php
        }    
    }
}
if (isset($_GET['eliminar_pbl'])) {
    require "algoritimos/atalho.php";
    require "algoritimos/seguranca.php";
    $c = new process;

    $id_pbl = descriptografar($_GET['eliminar_pbl']);

    if ($c->eliminar_pbl($id_pbl)) {
        ?>
            <script>
            document.location.href = "./?pbl_eliminada=true";
            </script>
        <?php
    }
}
if (isset($_POST['btn_repositorio'])) {
    if (!empty($_POST['desc']) && !empty($_POST['nome']) && !empty($_POST['privacidade'])) {
        $dados = [
            'desc' => $_POST['desc'],
            'nome' => $_POST['nome'],
            'privacidade' => $_POST['privacidade']
        ];
        if($id = $r->criar($dados)){
            ?>
            <script type="text/javascript">
                window.location.href= "repositorio.php?rep="+<?=criptografar($id)?>;
            </script>
            <?php    
        }
    }
}
?>
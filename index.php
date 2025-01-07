<?php
require "algoritimos/atalho.php";
require "algoritimos/seguranca.php";

$c = new process;
$n=0;
 
if (!isset($_SESSION['id_user'])) {
    ?>
    <script type="text/javascript">
        window.location.href="login/";
    </script>
    <?php    
}

$id_user = $_SESSION['id_user'];
$imagen = pegar_foto_perfil("perfil",$_SESSION['id_user']);
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="max-age=3600">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="src/img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="src/css/temas/<?=pegar_tema()?>.css">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
    
    <link rel="stylesheet" href="src/css/stilo.css">
    <link rel="stylesheet" href="src/css/coder.css">
    <title>SyliaGO</title>
</head>
<body> 
    <script>var indereco="./";</script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/js/script.js"></script>
    <script src="src/js/coder.js"></script>
    <?php
    require "include/nav.php";
    ?>
    <div class="visualizar_storie remover">
        <div onclick="abrir_storie('remover')" class="removedor">X</div>
        <div class="container overflow-y-auto"></div>
    </div>
    <div class="corpos">
        <div class="corpo3 crp">
            <div id="container" class="overflow-y-auto">
                <div> 
                    <div class="texto_interativo">
                        aque apareceram <a href="" class="destaque"><span>codigos</span></a>,
                        sugeridos para vc
                    </div>
                    <div class="texto_interativo">
                        adiciona <a href="../contactos/" class="destaque"><span>contactos</span></a>, para podermos sugerir
                        codigos relacionados a ele. <a href="" class="destaque"><span>saber mais</span></a>
                    </div>
                    <div class="texto_interativo">
                        entra em <a href="../comunidade/" class="destaque"><span>comunidades</span></a>, e expande tua rede
                        de interacoes...
                    </div>
                </div>
            </div>
        </div>
        <div id="corpo" class="crp">
            <div id="alerta" class="novo_storie remover">
                <div class="modal modal-sheet d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSheet">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header border-bottom-0">
                                <h1 class="modal-title fs-5">Novo storie</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="aba_alert('.novo_storie')"></button>
                            </div>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="modal-body py-0"> 
                                    <div>
                                        <input type="file" name="img_storie[]" alt="" class="form-control" accept="image/*" multiple>
                                    </div>
                                </div>
                                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                                    <input name="btn_storie" type="submit" class="btn bg-sec" value="postar storie"> 
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_POST['btn_storie'])) {
                if (isset($_FILES['img_storie'])) {
                    $nome = $_FILES['img_storie']['name'];
                    $tmp = $_FILES['img_storie']['tmp_name'];
                    $type = $_FILES['img_storie']['type'];
                    $size = $_FILES['img_storie']['size'];
                    $a = 0;
                    $imagens = array();
                    while ($a < count($nome)) {
                        array_push($imagens,array("name"=>$nome[$a],"tmp_name"=>$tmp[$a],"type"=>$type[$a],"size"=>$size[$a]));
                        $a++;
                    }
                    carregar_img_storie($imagens);
                }
            }
            ?>
            <div class="container p-0">
                <div id="prev" class="p-2"onload="alert('carregou')">
                    <div class="trans trans_dir"></div>
                    <div class="trans trans_esq"></div>
                    <div class="scroll  rolagem_x">
                        <div class="scroll_content">
                        <div class="item carregar_storie d-flex align-items-center justify-content-center bg-light text-secondary rounded-circle" style="background: rgba(255,255,255,0.5) url(<?=pegar_foto_perfil('perfil',$id_user)?>) center center/cover;" onclick="aba_alert('.novo_storie')">
                            <p class="fs-1">+</p>
                        </div>
                        <?php
                            $storie = new stories;
                            foreach ($storie->stories as $row) {
                                $dados = $storie->storie_info($row['id_user']);
                                $capa_storie = '/src/userFile/'.(new informacoes_usuario())->usuario($row['id_user'])['code_nome'].'/img/'.$dados['bg_storie'];
                                ?> 
                                <div class="item" onclick="abrir_storie(<?=$row['id_user']?>)">
                                    <div class="item_fundo" style="background-image : url(<?=$capa_storie?>);"></div>
                                    <div class="item_storie" style="background-image : url(<?=$capa_storie?>);"></div>
                                    <div class="usuario">
                                        <div class="d-flex align-items-center">
                                            <div class="borda_1 m-1">
                                                <div id="img_user_storie" style="background-image: url(<?=pegar_foto_perfil("perfil", $row['id_user'])?>);"></div>
                                            </div>
                                            <div class="nome">
                                                <?=$dados['nome']?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="numero_de_storie">
                                        <div class="content">
                                        <?php
                                        foreach ($dados['contagem'] as $i) {
                                            if ($i) {$i = "visto";}else {$i = "n_visto";}
                                            ?>
                                            <div class="<?=$i?>"></div>
                                            <?php
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .fazer_poste{
                    padding: 5px 10px 2px 10px !important;
                    margin: 15px auto;
                    margin-bottom: 6px;
                    width: 85%;
                }
            </style>
            <?php 
            require "include/formulario_de_poste.php";
            require "sent.php";

            if (isset($_GET['pbl'])) {
                if ($id_pbl = descriptografar($_GET['pbl'])) {
                    ?>
                        <div class="info_corrente">publicacao carregada com sucesso</div>
                    <?php
                }else {
                    ?>
                        <div class="info_corrente">erro ao carregar poste</div>
                    <?php
                }
            }
            if (isset($_GET['pbl_eliminada'])) {
                if ($_GET['pbl_eliminada'] = "true") {
                    ?>
                        <div class="info_corrente_red">publicacao eliminada com sucesso</div>
                    <?php
                }
            }
            ?>
            <div class="outros_pbl">
                <div id="alerta" class="pbl_denuncia remover">
                    <div class="modal modal-sheet d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSheet">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h1 class="modal-title fs-5">algum motivo especifico</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="aba_carregar_foto()"></button>
                                </div>
                                    <div class="modal-body py-0"> 
                                        <?php
                                        /*$motivos = mysqli_query($this->link, "SELECT * FROM $this->bdnome2.razoes_para_denuncias");
                                        
                                        while ($motivo = mysqli_fetch_assoc($motivos)) {
                                            ?>
                                                <p><label for="m<?=$motivo['razao']?>"><?=$motivo['razao']?></label><input type="radio" name="razao" id="m<?=$motivo['razao']?>"  class="razao" value="<?=$motivo['id_razao']?>"></p>
                                            <?php
                                        }*/
                                        ?>
                                        <input type="text" name="id_pbl" id="id_pbl_da_denuncia" class="remover">
                                    </div>
                                    <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                                        <input name="btn_img"  onclick="denunciar('pbl')" type="submit" class="btn bg-sec" value="denunciar"> 
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="alerta" class="pbl_partilhar remover">
                    <div class="modal modal-sheet d-block p-4 py-md-5" tabindex="-1" role="dialog" id="modalSheet">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content rounded-4 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h1 class="modal-title fs-5">opcoes de partilha</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="aba_alert('.pbl_partilhar')"></button>
                                </div>
                                <div class="modal-body py-0"> 
                                    <div>
                                        <textarea name="descricao" id="" class="form-control descricao_partilha" placeholder="de uma descricao a sua partilha"></textarea>
                                    </div>
                                    <input type="text" name="id_pbl" id="id_pbl_da_partilha" class="remover">
                                    <input type="text" name="tipo" id="tipo_de_conteudo_partilha" class="remover">
                                    <input type="text" name="como" id="partilhado_em" class="remover">
                                </div>
                                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                                    <input name="btn_img"  onclick="partilhar()" type="submit" class="btn bg-sec" value="partilhar"> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pbls">
                <?php
                $_SESSION['visualizado'] = array();
                $_SESSION['code_visualizado'] = array();
                if (isset($id_pbl)) {
                    if ($id_pbl > 0) {
                        $pbl = new postes();
                        $pbl->para = "pagina_inicial"; 
                        $pbl->oque = "poste";
                        $pbl->mostrar($pbl->poste($id_pbl)); 
                    } 
                }

                $s = new selecionar_feed();
                $s->selecionar_poste();
                ?>
            </div>
            <?php
            if ($s->postes_encontrados < $s->quantidade_de_postes) {
                ?>
                    <div class="texto_interativo">
                        entre em <a href="comunidade/?abrir=pdd" class="destaque"><span>comunidades</span></a> com interesses de sua escolha,
                        adiciona mais <a href="contactos/?abrir=pdd" class="destaque"><span>contactos</span></a> a sua lista de contactos,
                        para recheares a sua <a href="" class="destaque"><span>pagina inicial</span></a>
                    </div>
                <?php
            }else {
                ?>
                    <div class="mais_pbl_process"></div>
                <?php
            }
            ?>
        </div>    
        <div class="corpo2 crp"></div> 
    </div>   
    <?php 
        require "include/footer.php";
        require "include/search.php";
    ?>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="src/js/fim_script.js"></script>
    <script src="src/js/coder.js"></script>
</body>
</html>
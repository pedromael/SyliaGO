<?php
    require "../../algoritimos/atalho.php";
    require "../../algoritimos/seguranca.php";

    $data = json_decode(file_get_contents('php://input'), true);
    $id_dest = $data['id_user'];
?>
<style>
    .send_end_sms{
        position: relative;
        width: 100%;
        height: 100%;
    }

    .sms_screen{
        width: 100%;
        height: calc(100% - 65px);
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
    }
    .send_sms{
        width: 100%;
        height: 65px;
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 100;
    }
</style>
<div class="send_end_sms">
    <div class="sms_screen overflow-y-auto">
        <div class="container p-1">
            <?php
            if($id_dest != null){
            $info = (new process)->usuario($id_dest);
            $info_login = (new verificar_logados())->dados_de_login($id_dest);
            ?>
            <style>
                .data_logado{
                font-size: 11pt;
                color: #d3d3ddff;
                text-shadow: 1px 1px 2px #ddd;
                <?php if($info_login["ativo"]){
                    echo "border-bottom: 2px solid green;";
                }else{
                    echo "border-bottom: 2px solid #d3d3ddff;";
                }?>
                }
            </style>
            <div class="card mb-2 p-1">
                <div class="row g-0 align-items-center">
                    <div class="col-auto">
                        <div class="rounded-circle" 
                            style="width: 60px; height: 60px; background-image: url('<?=pegar_foto_perfil("perfil", $id_dest)?>'); background-size: cover; background-position: center;">
                        </div>
                    </div>
                    <div class="col">
                        <div class="container">
                            <div class="row p-1"><?=$info['nome']?></div>
                            <div class="row text-end p-1 data_logado">
                                <div class="container">
                                <?php
                                    if($info_login['ativo']){
                                    echo "ativo";
                                    }else{
                                    echo "ativo ". resumir_data($info_login["data"]);
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto" onclick="fechar_mensagen()">
                        <i class="fa fa-times p-2" style="color: var(--cor_sec); font-size: 36pt;"></i>
                    </div>
                </div>
            </div>
            <div class="msg container">
            <?php
            if($id_dest != NULL){
                $msg = new mensagens;
                $msg->receptor = $id_dest;
                $msg->selecionar();
            }else {
                ?>
                <div class="container d-flex justify-content-center align-items-center h-100">
                <h4>selecine uma mensagen</h>
                </div>
                <?php
            }
            ?>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="send_sms">
        <div class="formulario_mensagem d-flex align-items-center justify-content-center bg-transparent w-100 position-absolute bottom-0 mb-2">
            <div class="d-flex align-items-center position-relative w-100 border rounded-pill shadow" style="height: 50px;">
                <button class="btn d-flex align-items-center justify-content-center rounded-circle p-0 position-absolute start-0" style="width: 40px; height: 40px;">
                    <i class="bi bi-file-earmark-image"></i>
                </button>
                <textarea class="form-control border-0 shadow-none mx-5 px-0 bg-transparent text-secondary" 
                            placeholder="Diz ola!" 
                            style="resize: none; height: 100%;"></textarea>
                <button name="btn_cmt" 
                        class="btn d-flex align-items-center justify-content-center rounded-circle p-0 position-absolute end-0" 
                        style="width: 40px; height: 40px;" 
                        onclick="enviar_mensagem('<?=criptografar($id_dest)?>')">
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </div>
    </div>
</div>

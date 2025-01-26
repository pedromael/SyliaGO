<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";
?>
<style>
    .prev_logados_e_sms{
        position: relative;
        width: 100%;
        height: 100%;
    }
    #corpo_lista_sms{
        position: absolute;
        top: 0;
        width: 100%;
        height: calc(100% - 70px);
    }
    .prev_logados_chat{
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 69px;
        margin: auto;
        border-top: 4px solid var(--cor_terc);
    }
    .prev_logados_chat .user{
        width: 60px;
        height: 60px;
        margin: 3px;
        border: 1px solid var(--cor_sec);
        border-radius: 50%;
        background-size: cover;
        background-position: center center;
        display: inline-block;
        position: relative;
    }
    .prev_logados_chat .user div{
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: absolute;
        bottom: 4px;
        right: 4px;
    }
    .prev_logados_chat .user .logado{
        background-color: green;
    }
    .prev_logados_chat .user .nao_logado{
        background-color: rgb(139, 141, 139);
    }

</style>
<div class="prev_logados_e_sms">
    <div class="prev_logados_chat overflow-x-auto">
        <?php
        $listagem = new verificar_logados();
        $listagem->logados();
        ?>
    </div>
    <div id="corpo_lista_sms" class="overflow-y-auto">
        <?php
        $listagem = new lista_mensagens(); 
        $retorno = $listagem->getListaAmigos();
        $numero_de_sms_encontradas = $retorno['tamanho'];
        $hash = $retorno['hash'];

        if ($numero_de_sms_encontradas < 1) 
        {
            ?>
                <div class="texto_interativo">
                    aque apareceram suas <a href="" class="destaque"><span>mensagens</span></a>,
                    e seus <a href="" class="destaque"><span>contactos</span></a>
                </div>
                <div class="texto_interativo">
                    adiciona <a href="../contactos/" class="destaque"><span>contactos</span></a> com quem tenhas interesse,
                    para estares podendo trocar ideias... <a href="" class="destaque"><span>saber mais</span></a>
                </div>
                <div class="texto_interativo">
                    encontra usuarios com mesmos ideias que os seus nas 
                    <a href="../comunidade/" class="destaque"><span>comunidades</span></a>, e mantem contacto
                    os adicionando a sua lista de <a href="../contactos/" class="destaque"><span>contacto</span></a>
                </div>
            <?php
        }elseif($numero_de_sms_encontradas < 6)
        {
        ?>
            <div class="texto_interativo">
                adiciona <a href="../contactos/" class="destaque"><span>contactos</span></a> com quem tenhas interesse,
                para estares podendo trocar ideias... <a href="" class="destaque"><span>saber mais</span></a>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
    $resposta = array(
        'status' => "sucess",
        'hash' => $hash
    );

    echo json_encode($resposta);
?>
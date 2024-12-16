<?php
require "../../algoritimos/atalho.php";
require "../../algoritimos/seguranca.php";
$listagem = new lista_mensagens();
$numero_de_sms_encontradas = $listagem->getListaAmigos();
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
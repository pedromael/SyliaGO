<?php
function verificar_texto_poste($len,$doc,$partilha) {
    if ($doc || $partilha) {
        return 'texto-pequeno';
    }
    if ($len < 100) {
        return 'texto-grande';
    }
    if ($len > 100 && $len < 300) {
        return 'texto-medio';
    }
    if ($len > 300) {
        return 'texto-pequeno';
    }
}
class postes extends process
{ 
    private $link;
    public $oque;
    public $para;
    private $comunidade;
    function __construct()
    {
        parent::__construct();
        $this->link = conn();
        $this->comunidade = new comunidade;
    }
    public function poste($id){
        $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
        LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
        WHERE id_pbl = :id");   
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetch();
    }
    public function mostrar($row,$visualizacao_unica=false,$reac=true,$partilha=false)
    { 
        if (empty($row['id_user'])) {
            return false;
        }
        $sqll = $this->usuario($row['id_user']);
        $id_pbl = $row['id_pbl'];
        $imagen = pegar_foto_perfil("perfil",$row['id_user']);
        $id = $sqll['id_user'];
        $do = mysqli_query($this->link, "SELECT * FROM doc WHERE id=$id_pbl AND (tipo='poste' OR tipo='video')");
        $inderecos = array();
        while ($doc = mysqli_fetch_assoc($do)) {
            array_push($inderecos,"/src/userFile/".$this->usuario($row['id_user'])['code_nome']."/img/".$doc['indereco']);
        }  

        $comunidade=[];
        if ($row['id_comunidade']) {
            $comunidade = $this->comunidade->comunidade($row['id_comunidade']);
        }
        $qtd_indereco = count($inderecos);

        if (!$partilha) {?><div id="pbl" class=""><?php }else{?><div id="" class="pbl_partilhadaa"><?php }
        ?>
            <table>
                <tr>
                    <?php
                    if ($row['id_comunidade'] > 0 && $this->oque == "pbl") {
                        $imagen = pegar_foto_perfil("cmdd",$row['id_comunidade']);
                    }
                    ?>  
                    <td id="img">
                        <a href="/perfil/?user=<?=criptografar($id)?>">
                            <div id="img" class="loader" style="background-image: url(<?=$imagen?>);"></div>
                        </a>
                    </td>
                    <td id="nome" colspan="2">
                        <?php
                        if ($row['id_comunidade'] > 0 && $this->oque == "pbl") {
                            ?>
                            <span>
                                <a class="nome_comunidade" href="/comunidade/?cmndd=<?=criptografar($row['id_comunidade'])?>"><?=$comunidade['nome']?></a><br>
                            </span>
                            <?php
                        }
                        if ($this->oque == "pbl") {
                            if ($row['id_comunidade']) {
                                ?>
                                <span class="da_comunidade">
                                    <a href="/perfil/?user=<?=criptografar($id)?>"><?=$sqll['nome']?></a>
                                </span>
                                <?php
                            }else {
                                ?>
                                <span>
                                    <a href="/perfil/?user=<?=criptografar($id)?>"><?=$sqll['nome']?></a>
                                </span>
                                <?php
                                $id = $sqll['id_user'];
                                $v_a = $this->pdo->prepare("SELECT count(*) AS total FROM contacto WHERE (id_user = $id
                                AND id_user_dest = :id) OR (id_user = :id AND id_user_dest = $id)");
                                $v_a->bindValue(":id", $this->user['id_user']);
                                $v_a->execute();
                                $v_a  = $v_a->fetch();
                                if ($v_a['total'] <= 0 && $id != $this->user['id_user']) {
                                    ?>
                                    <div class="img_add_user">
                                        <img src="/bibliotecas/bootstrap/icones/person-fill-add.svg" alt="">
                                    </div>
                                    <?php
                                }
                            }
                            
                        }else {
                            ?>
                                <a href="<?=criptografar($id)?>"><?=$sqll['nome']?></a>
                            <?php        
                        }
                        ?>
                    </td>
                    <?php if (!$partilha) {?>
                        <td id="mais_pbl">
                            <span align="right" class="mais <?="pbl",$id_pbl?>" onclick="abrir_info_pbl(<?=$id_pbl?>)">...</span>
                            <br>
                            <span class="data">
                                <?=resumir_data($row['data'])?>
                            </span>
                        </td>
                        <?php
                            $diretorio = "include/mais_pbl.php";
                            if (file_exists($diretorio)) {
                                require $diretorio;
                            }else{ require "../".$diretorio;}
                        }
                    ?>
                </tr>
                <tr id="txt">
                    <td colspan="3"><p class="text <?=verificar_texto_poste(strlen($row['texto']),$qtd_indereco,$row['id_partilha'])?> pbl_text<?=$id_pbl?>"><?=htmlspecialchars_decode($row['texto'])?></p></td>
                </tr>
                <?php
                if (count($inderecos) > 0 && !$visualizacao_unica) {
                ?>
                <tr id="img_pbl">
                    <td colspan="3">
                        <figcaption>
                            <?php
                            $a=0;
                            foreach ($inderecos as $indereco) {
                                if ($a == 0) {$class = "primeiro";}else {$class = "";}
                                if ($a == 1 && $qtd_indereco > 5) {$class = "primeiro";}
                                if ($a < 3 && count($inderecos) > 4) {$class = "primeiro";}
                                if ($qtd_indereco <= 5) {
                                    ?>
                                        <a href="/pbl/?pbl=<?=criptografar($id_pbl)?>"><img src="<?=$indereco?>" alt="" class="qtd<?=$qtd_indereco?> <?=$class?>"></a>
                                    <?php    
                                }else {
                                    ?>
                                        <a href="/pbl/?pbl=<?=criptografar($id_pbl)?>"><img src="<?=$indereco?>" alt="" class="qtd_mais <?=$class?>"></a>
                                    <?php
                                }
                                if ($a == 4) {break;}
                                $a++;
                            }
                            ?>
                        </figcaption>
                    </td>
                </tr>
                <?php
                }elseif(count($inderecos) > 0 && $visualizacao_unica){
                    $class = "unica";
                    ?>
                    <tr id="img_pbl" class="">
                        <td colspan="3">
                                <?php
                                $a=0;
                                foreach ($inderecos as $indereco) {
                                    ?>
                                    <figcaption class="v_unica">
                                        <a href="/pbl/?pbl=<?=criptografar($id_pbl)?>"><img src="<?=$indereco?>" alt="" class="<?=$class?>"></a>
                                    </figcaption>
                                    <?php    
                                    $a++;
                                }
                                ?>
                        </td>
                    </tr>
                    <?php
                }
                if ($row['id_partilha']>0) {
                    ?>
                    <tr>
                        <td colspan="3">
                            <div class="pbl_partilhada">
                                <?php
                                if ($row['tipo_partilha'] == 'pbl') {
                                    $this->mostrar($this->poste($row['id_partilha']),false,false,true);
                                }elseif($row['tipo_partilha'] == 'code') {
                                    //$this->codigo->mostrar($this->codigo->codigo($row['id_partilha']),4,'feed',NULL,1);
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                if ($reac) {
                ?>
                <tr id="reac">
                    <td colspan="3">
                        <div class="container">
                            <div class="row">
                                <div class="reac_pbl col">
                                    <div class="centralizador reac<?='pbl'.$id_pbl?>" onclick="reagir(<?=$id_pbl?>,'gosto','poste')">
                                        <?php    
                                        if ($this->qtd_reacao($id_pbl,'poste',$_SESSION['id_user']) > 0) {
                                            ?>
                                            <img class="teste add" src="/bibliotecas/bootstrap/icones/heart-fill.svg" alt=""><span><?=$this->qtd_reacao($id_pbl,'poste')?></span>
                                            <?php
                                        }else {
                                            ?>
                                            <img class="" src="/bibliotecas/bootstrap/icones/heart.svg" alt=""> <span><?=$this->qtd_reacao($id_pbl,'poste')?></span>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="reac_pbl col">
                                    <a href="/pbl/?pbl=<?=criptografar($id_pbl)?>&cmt=true">
                                        <div class="centralizador">
                                            <img src="/bibliotecas/bootstrap/icones/chat-dots.svg" alt=""> <span><?=qtd_de_cmt($id_pbl)?></span>
                                        </div> 
                                    </a>   
                                </div>
                                <?php
                                if ($row['id_partilha'] <= 0) {
                                    ?>
                                    <div class="reac_pbl col" id="">
                                        <div class="centralizador" onclick="abrir_partilhar('pbl',<?=$id_pbl?>)" >
                                            <img src="/bibliotecas/bootstrap/icones/reply.svg" alt="">
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>    
                        </div> 
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <?php 
        $this->marcar_visto($id_pbl,"pbl");
        if ($visualizacao_unica) {
            $this->marcar_lido($id_pbl,"pbl");
        }
        array_push($_SESSION['visualizado'],$id_pbl);
    }
    private function procura_aleatoria()
    {
        if($this->oque == "pbl") {
            $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
            LEFT JOIN contacto AS a ON ((a.id_user = pbl.id_user AND a.id_user_dest = :id) 
            OR (a.id_user_dest = pbl.id_user AND a.id_user = :id))
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
            LEFT JOIN $this->bdnome2.contacto_aceite AS aa ON (aa.id_contacto = a.id_contacto)
            LEFT JOIN $this->bdnome2.comunidade_integrante AS ci ON (ci.id_comunidade = pbl.id_comunidade AND ci.id_user = :id)
            LEFT JOIN comunidade as c ON (c.id_comunidade = pbl.id_comunidade)
            WHERE ((a.id_contacto = aa.id_contacto OR a.id_user_dest = pbl.id_user  AND a.id_user = :id) AND pbl.id_comunidade <= 0)
            OR (ci.id_integrante > 0 OR c.id_user = :id)
            ORDER BY RAND() DESC");  
            $sql->bindValue(":id", $_SESSION['id_user']);
        }elseif($this->oque > 0 && $this->para == "comunidade"){
            $sql = $this->pdo->prepare("SELECT pbl.*,p.id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
            WHERE id_comunidade = :id ORDER BY RAND() DESC");   
            $sql->bindValue(":id", $this->oque);
        }   
        $sql->execute();
        foreach ($sql->fetchAll() AS $row) {
            if (!in_array($row['id_pbl'], $_SESSION['visualizado'])) {
                return  $this->mostrar($row);
            }
        }
        return 404;
    }
    public function procurar($tipo = "global")
    {
        if ($this->oque == "pbl") {
            if ($tipo != "global") {
                $sql = $this->pdo->prepare("SELECT pbl.*,p.id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
                LEFT JOIN contacto AS a ON ((a.id_user = pbl.id_user AND a.id_user_dest = :user) 
                OR (a.id_user_dest = pbl.id_user AND a.id_user = :user))
                LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
                LEFT JOIN $this->bdnome2.contacto_aceite AS aa ON (aa.id_contacto = a.id_contacto)
                LEFT JOIN $this->bdnome2.comunidade_integrante AS ci ON (ci.id_comunidade = pbl.id_comunidade AND ci.id_user = :user)
                LEFT JOIN comunidade as c ON (c.id_comunidade = pbl.id_comunidade)
                WHERE ((a.id_contacto = aa.id_contacto OR a.id_user_dest = pbl.id_user  AND a.id_user = :user) AND pbl.id_comunidade <= 0)
                OR (ci.id_integrante > 0 OR c.id_user = :user)
                ORDER BY id_pbl DESC");  
            }else {
                $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
                LEFT JOIN $this->bdnome2.visto as v ON (v.id = pbl.id_pbl AND v.tipo = 'pbl' AND v.id_user = :user)
                LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
                WHERE id_visto IS NULL AND pbl.id_user != :user
                ORDER BY id_pbl DESC");  
            }
            $sql->bindValue(":user", $this->user['id_user']);
        } elseif ($this->oque > 0 && $this->para == "comunidade") {
            $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
            WHERE id_comunidade = :id
            ORDER BY id_pbl DESC");
            $sql->bindValue(":id", $this->oque);  
        } elseif ($this->oque > 0 && $this->para == "perfil"){
            $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
            WHERE id_user = :id AND id_comunidade = 0
            ORDER BY id_pbl DESC");  
            $sql->bindValue(":id", $this->oque);
        } 
        $sql->execute();
        if ($tipo != "global") 
        {
            $postes = $sql->fetchAll();
        }else {
            if ($this->para != "perfil") // na seccao comunidade e index, se faz um filtro para ordenar os postes por interesse
            {
                $publicacoes_verificadas =  array();

                foreach ($sql->fetchAll() AS $row) {
                    if (Verificar_pontuacao($row,$this->user['id_user']) >= 0) {
                        $row['pontuacao'] = Verificar_pontuacao($row,$this->user['id_user']);
                        array_push($publicacoes_verificadas,$row);
                    }
                }

                usort($publicacoes_verificadas,'verificar_peso');
                $postes = $publicacoes_verificadas;
            }else {
                $postes = $sql->fetchAll();
            }
        }

        foreach ($postes AS $row) {
            if (!in_array($row['id_pbl'], $_SESSION['visualizado'])) {
                return  $this->mostrar($row);
            }
        }

        if ($this->para != "perfil") {
            return $this->procura_aleatoria();
        }
    }
}
?>
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

function verificar_peso($a,$b){
    return $b['pontuacao'] - $a['pontuacao'];
}

class postes extends process
{ 
    public $oque;
    public $para;
    private $comunidade;
    private $proveniente; //controlo
    function __construct()
    {
        parent::__construct();
        $this->comunidade = new comunidade;
    }
    public function poste($id){
        $sql = $this->pdo->prepare("SELECT pbl.*, id_partilhado,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
        LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
        WHERE id_pbl = :id");   
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetch();
    }
    public function relacao_poste_usuario($poste,$id_user): int 
    {
        $id_pbl = $poste['id_pbl'];
        $pontuacao = 0;
    
        $pontuacao = $this->ligacao_entre_usuario($poste['id_user'], $id_user);
    
        $pontuacao = $pontuacao + 2 * verificar_contactos_em_comum($id_user,$poste['id_user']);
    
        $vistos = mysqli_query(conn(), "SELECT COUNT(*) AS qtd FROM $this->bdnome2.visto WHERE id = $id_pbl AND tipo='poste'");
        $qtd_vistos = mysqli_fetch_assoc($vistos);
        $reacao = mysqli_query(conn(), "SELECT COUNT(*) AS qtd FROM $this->bdnome2.reacao WHERE id = $id_pbl AND tipo='poste'");
        $qtd_reacao = mysqli_fetch_assoc($reacao);
    
        if ($qtd_reacao['qtd'] <= 0) {
            $qtd_reacao['qtd'] = 1;
        }
        if ($qtd_vistos['qtd'] <= 25) {
            $qtd_vistos['qtd'] = 25;
        }
    
        $media_de_aceitacao = ($qtd_reacao['qtd'] / $qtd_vistos['qtd']) * (100);
        $pontuacao = $pontuacao + $media_de_aceitacao;
        
        return $pontuacao;
    }

    public function pegarMidiaCarroceu($inderecos, $id_pbl): void
    {
        ?>
            <style>
                .container-img-carrocel{
                    width: 100%;
                    height: 600px;
                    background-color: var(--cor_sec);
                }
            </style>
            <div id="carouselMidia<?=$id_pbl?>" class="carousel slide pb-1" data-bs-ride="false">
                <div class="carousel-inner">
                    <?php foreach ($inderecos as $index => $indereco): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="container-img-carrocel container p-0 m-0 d-flex justify-content-center align-items-center">  
                            <?php if ($indereco['tipo'] == 'imagen'): ?>
                                    <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>">
                                        <img src="<?= $indereco['indereco'] ?>" class="d-block w-100 img-fluid" style="max-height: 600px;" alt="Imagen do Poste">
                                    </a>
                                <?php elseif ($indereco['tipo'] == 'video'): ?>
                                    <video class="d-block w-100 img-fluid embed-responsive" style="max-height: 700px;" controls>
                                        <source src="<?= $indereco['indereco'] ?>" type="video/mp4">
                                        Seu navegador não suporta o elemento de vídeo.
                                    </video>
                                <?php endif; ?>
                            </div>
                        </div>  
                    <?php endforeach; ?>
                </div>
                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselMidia<?=$id_pbl?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselMidia<?=$id_pbl?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <?php foreach ($inderecos as $index => $indereco): ?>
                        <button type="button" data-bs-target="#carouselMidia<?=$id_pbl?>" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
    }
    
    public function mostrar($row, $visualizacao_unica = false, $reac = true, $partilha = false)
    {
        if (empty($row['id_user']) || $row['id_user'] <= 0) return false;

        $usuario = $this->usuario($row['id_user']);
        $id_pbl = $row['id_pbl'];
        $id_user = $row['id_user'];
        $nome_usuario = $usuario['nome'];
        if ($row['id_comunidade'] > 0) {
            $imagem_perfil = pegar_foto_perfil("comunidade", $row['id_comunidade']);
        } else {
            $imagem_perfil = pegar_foto_perfil("perfil", $row['id_user']);
        }
            
        $stmt = $this->pdo->prepare("SELECT * FROM doc WHERE id = :id_pbl AND para = 'poste' AND (tipo = 'imagen' OR tipo = 'video')");
        $stmt->execute(['id_pbl' => $id_pbl]);

        $inderecos = array_map(function ($doc) use ($usuario) {
            $arquivo = ($doc['tipo'] === 'imagen') ? 'img' : $doc['tipo'];
            return [
                "indereco" => "/src/userFile/{$usuario['code_nome']}/{$arquivo}/{$doc['indereco']}",
                "tipo" => $doc['tipo']
            ];
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));


        $qtd_indereco = count($inderecos);
        $comunidade = $row['id_comunidade'] ? $this->comunidade->comunidade($row['id_comunidade']) : null;

        $classPartilha = $partilha ? "pbl_partilhada" : "";
        ?>
        <div class="<?= $classPartilha ?> card mb-3 poste_<?=$id_pbl?>">
            <div class="card-body p-0 m-0">
                <div class="container mb-2">
                    <div class="row p-1">
                        <a href="/perfil/?user=<?= criptografar($id_user) ?>" class="col-auto p-0">
                            <img src="<?= $imagem_perfil ?>" class="rounded-circle m-0" alt="Perfil" style="width: 40px; height: 40px;">
                        </a>
                        <div class="col px-1">
                            <style>
                                .text_desf{
                                    font-size: 15px;
                                }
                                .data{
                                    font-size: 14px;
                                }
                                .texto{
                                    text-indent: 10px;
                                }
                            </style>
                            <?php if ($comunidade && $this->oque == "poste"): ?>
                                    <span><a class="text-decoration-none fw-bold fs-6" href="/comunidade/?cmndd=<?= criptografar($row['id_comunidade']) ?>"><?= $comunidade['nome'] ?></a></span><br>
                                    <span><a href="/perfil/?user=<?= criptografar($id_user) ?>" class="text-decoration-none text_desf"><?= $nome_usuario ?></a></span>
                                <?php else: ?>
                                    <span><a href="/perfil/?user=<?= criptografar($id_user) ?>" class="text-decoration-none fw-bold fs-6"><?= $nome_usuario ?></a></span>
                                <?php endif; ?>
                            <?php if(!$partilha): ?>
                                <div class="text-muted data"><?= resumir_data($row['data']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto p-0">
                            <?php if(!$partilha): ?>
                                <button class="btn btn-light" onclick="abrir_info_pbl(<?= $id_pbl ?>)">...</button>
                            <?php else: ?>
                                <div class="text-muted"><?= resumir_data($row['data']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php 
                $texto = htmlspecialchars_decode($row['texto']);
                if (strlen($texto) > 500): 
                    $texto_resumido = substr($texto, 0, 500) . '...';
                ?>
                    <p class="m-2 text-muted mb-1 texto" id="texto_<?= $id_pbl ?>">
                        <?= $texto_resumido ?>
                        <a href="javascript:void(0)" onclick="mostrarTextoCompleto(<?= $id_pbl ?>)">ver mais</a>
                    </p>
                    <p id="texto_completo_<?= $id_pbl ?>" class="m-2 text-muted mb-1 texto" style="display:none;">
                        <?= $texto ?>
                    </p>
                <?php else: ?>
                    <p class="m-2 text-muted mb-1 texto"><?= $texto ?></p>
                <?php endif; ?>

                <?php if ($row['id_partilhado'] > 0): ?>
                    <div class="container m-0 p-1">
                        <?php $this->mostrar($this->poste($row['id_partilhado']),false,false,true);?>
                    </div>
                <?php endif; ?>

                <style>
                    .container-img{
                        background-color: var(--cor_sec);
                        backdrop-filter: blur(5px);
                    }
                </style>
                <?php if ($qtd_indereco == 1): ?>
                    <div class="mb-3">
                        <div class="container-img d-flex flex-wrap gap-2 justify-content-center align-items-center">
                            <?php if ($inderecos[0]['tipo'] == "imagen"): ?>
                                <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>">
                                    <img src="<?= $inderecos[0]['indereco'] ?>" class="img-fluid" style="max-width: 100%; max-height: 600px;" alt="Imagen do Poste">
                                </a>
                            <?php elseif ($inderecos[0]['tipo'] == "video"): ?>
                                <video class="" style="max-width: 100%; max-height: 650px;" controls>
                                    <source src="<?= $inderecos[0]['indereco'] ?>" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif ($qtd_indereco > 1): 
                    $this->pegarMidiaCarroceu($inderecos, $id_pbl);
                endif; ?>

                <?php $comentarios = qtd_de_cmt($id_pbl)?>
                
                <?php if ($reac): ?>
                    <div class="d-flex justify-content-between p-2 pt-0">
                        <button id="reac_poste<?=$id_pbl?>" class="btn btn-light" onclick="reagir(<?= $id_pbl ?>, 'gosto', 'poste')">
                            <i class="bi <?= $this->qtd_reacao($id_pbl, 'poste', $_SESSION['id_user']) > 0 ? 'bi-heart-fill' : 'bi-heart' ?>" style="color: red;"></i>
                            <?php $reacoes = $this->qtd_reacao($id_pbl, 'poste'); ?>
                            <?= $reacoes > 0 ? $reacoes : "" ?>
                        </button>
                        <div class="btn btn-light" onclick="abrir_poste('<?=$id_pbl?>',<?= $comentarios <= 1 ? 1 : 0?>)">
                            <i class="fa fa-comment" style="color: var(--cor_sec);"></i> 
                            <?= $comentarios > 0 ? $comentarios : "" ?>
                        </div>
                        <?php if ($row['id_partilhado'] <= 0): ?>
                            <button class="btn btn-light" onclick="abrir_partilhar('poste', <?= $id_pbl ?>,'poste')">
                                <i class="fa fa-reply" style="color: var(--cor_sec);"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            if (!isset($comentarios)) {
                $comentarios = 0;
            }
    
            // verificar se existe apenas numero X de comentarios para mostrar logo abaixo do post
            if($comentarios == 1 && !$visualizacao_unica && !$partilha) {
                $cmt = new comentarios();
                $cmt->id = $id_pbl;
                $cmt->pegar("poste", $comentarios);
            }

            // se poste tem menos de 2 comentarios, cria um formulario de comentario logo abaixo
            if ($comentarios <= 1) {
                ?>
                <div class="formulario_comentario d-flex p-1 remover border-top">
                    <input type="hidden" name="id_pbl" value="<?= $id_pbl ?>">
                    <input type="hidden" name="tipo" value="poste">
                    <input 
                        type="text" 
                        name="comentario" 
                        class="form-control me-2" 
                        placeholder="Escreva um comentário..." 
                        required 
                    >
                    <button type="submit" class="btn btn-primary" onclick="comentar(<?= criptografar($id_pbl) ?>, 'poste')">Comentar</button>
            </div>
                <?php
            }

            ?>
        </div>
        <?php

        $this->marcar_visto($id_pbl, "poste");
        if ($visualizacao_unica) {
            $this->marcar_lido($id_pbl, "poste");
        }
        $_SESSION['visualizado'][$id_pbl] = true;

        return true;
    }
    
    private function procura_aleatoria()
    {
        if($this->oque == "poste") {
            $sql = $this->pdo->prepare("SELECT pbl.*, id_partilhado, COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
            LEFT JOIN contacto AS a ON ((a.id_user = pbl.id_user AND a.id_user_dest = :id) 
            OR (a.id_user_dest = pbl.id_user AND a.id_user = :id))
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
            LEFT JOIN $this->bdnome2.contacto_aceite AS aa ON (aa.id_contacto = a.id_contacto)
            LEFT JOIN $this->bdnome2.comunidade_integrante AS ci ON (ci.id_comunidade = pbl.id_comunidade AND ci.id_user = :id)
            LEFT JOIN comunidade as c ON (c.id_comunidade = pbl.id_comunidade)
            WHERE ((a.id_contacto = aa.id_contacto OR a.id_user_dest = pbl.id_user  AND a.id_user = :id) AND pbl.id_comunidade <= 0)
            OR (ci.id_integrante > 0 OR c.id_user = :id)
            ORDER BY RAND() DESC");  
            $sql->bindValue(":id", $_SESSION['id_user']);

            $this->proveniente = "pbl_aleatorio";

        }elseif($this->oque > 0 && $this->para == "comunidade"){
            $sql = $this->pdo->prepare("SELECT pbl.*,p.id_partilhado,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
            WHERE id_comunidade = :id ORDER BY RAND() DESC");   
            $sql->bindValue(":id", $this->oque);

            $this->proveniente = "pbl_comunidade_aleatorio";
        }   
        $sql->execute();
        foreach ($sql->fetchAll() AS $row) {
            if (!isset($_SESSION['visualizado'][$row['id_pbl']])) {
                return  $this->mostrar($row);
            }
        }
        return false;
    }
    public function procurar($tipo = "global")
    {
        if ($this->oque == "poste") {
            if ($tipo != "global") {
                $sql = $this->pdo->prepare("SELECT pbl.*, id_partilhado, COALESCE(p.tipo, 'null') AS tipo_partilha 
                FROM pbl 
                LEFT JOIN contacto AS a ON ((a.id_user = pbl.id_user AND a.id_user_dest = :user) 
                    OR (a.id_user_dest = pbl.id_user AND a.id_user = :user))
                LEFT JOIN $this->bdnome2.visto AS v ON (v.id = pbl.id_pbl AND v.tipo = 'poste' AND v.id_user = :user)
                LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
                LEFT JOIN $this->bdnome2.contacto_aceite AS aa ON (aa.id_contacto = a.id_contacto)
                LEFT JOIN $this->bdnome2.comunidade_integrante AS ci ON (ci.id_comunidade = pbl.id_comunidade AND ci.id_user = :user)
                LEFT JOIN comunidade AS c ON (c.id_comunidade = pbl.id_comunidade)
                WHERE 
                    (
                        ((a.id_contacto = aa.id_contacto OR a.id_user_dest = pbl.id_user AND a.id_user = :user) 
                        AND pbl.id_comunidade <= 0) 
                        OR (ci.id_integrante > 0 OR c.id_user = :user)
                    )
                    AND v.id_visto IS NULL
                ORDER BY pbl.id_pbl DESC");

                $this->proveniente = "pbl_normal";
            }else {
                $sql = $this->pdo->prepare("SELECT pbl.*,id_partilhado,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
                LEFT JOIN $this->bdnome2.visto as v ON (v.id = pbl.id_pbl AND v.tipo = 'poste' AND v.id_user = :user)
                LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
                WHERE id_visto IS NULL AND pbl.id_user != :user
                ORDER BY id_pbl DESC");  

                $this->proveniente = "pbl_global";
            }
            $sql->bindValue(":user", $_SESSION['id_user']);
        } elseif ($this->oque > 0 && $this->para == "comunidade") {
            $sql = $this->pdo->prepare("SELECT pbl.*,id_partilhado,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
            WHERE id_comunidade = :id
            ORDER BY id_pbl DESC");
            
            $sql->bindValue(":id", $this->oque);  
            
            $this->proveniente = "comunidade";
        } elseif ($this->oque > 0 && $this->para == "perfil"){
            $sql = $this->pdo->prepare("SELECT pbl.*,id_partilhado,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id_partilha = pbl.id_pbl AND p.como = 'poste')
            WHERE id_user = :id AND id_comunidade = 0
            ORDER BY id_pbl DESC");  
            $sql->bindValue(":id", $this->oque);

            $this->proveniente = "perfil";
        }else{
            return false;
        }
        $sql->execute();
        if ($tipo == "poste") 
        {
            $postes = $sql->fetchAll();
        }else {
            if ($this->para != "perfil") // na seccao comunidade e index, se faz um filtro para ordenar os postes por interesse
            {
                $publicacoes_verificadas =  array();

                foreach ($sql->fetchAll() AS $row) {
                    if ($this->relacao_poste_usuario($row,$_SESSION['id_user']) >= 0) {
                        $row['pontuacao'] = $this->relacao_poste_usuario($row,$_SESSION['id_user']);
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
            if (!isset($_SESSION['visualizado'][$row['id_pbl']])) {
                return  $this->mostrar($row);
            }
        }

        if ($this->para != "perfil") {
            return $this->procura_aleatoria();
        }
    }
}
?>
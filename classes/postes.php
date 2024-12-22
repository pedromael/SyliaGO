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
    private $link;
    public $oque;
    public $para;
    private $comunidade;
    private $proveniente; //controlo
    function __construct()
    {
        parent::__construct();
        $this->link = conn();
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
    public function relacao_poste_usuario($poste,$id_user): int {
        global $bdnome2;
        $id_pbl = $poste['id_pbl'];
        $pontuacao = 0;
    
        $pontuacao = $this->ligacao_entre_usuario($poste['id_user'], $id_user);
    
        $pontuacao = $pontuacao + 2 * verificar_contactos_em_comum($id_user,$poste['id_user']);
    
        $vistos = mysqli_query(conn(), "SELECT COUNT(*) AS qtd FROM $bdnome2.visto WHERE id = $id_pbl AND tipo='poste'");
        $qtd_vistos = mysqli_fetch_assoc($vistos);
        $reacao = mysqli_query(conn(), "SELECT COUNT(*) AS qtd FROM $bdnome2.reacao WHERE id = $id_pbl AND tipo='poste'");
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
    public function mostrar($row, $visualizacao_unica = false, $reac = true, $partilha = false)
    {
        if (empty($row['id_user']) || $row['id_user'] <= 0) return false;

        $usuario = $this->usuario($row['id_user']);
        $id_pbl = $row['id_pbl'];
        $id_user = $row['id_user'];
        $nome_usuario = $usuario['nome'];
        if ($row['id_comunidade'] > 0) {
            $imagem_perfil = pegar_foto_perfil("comunidade", $row['id_comunidade']);
        }else{
            $imagem_perfil = pegar_foto_perfil("perfil", $row['id_user']);
        }
            
        $inderecos = array_map(function ($doc) use ($usuario) {
            if ($doc['tipo'] == 'imagen') {
                $arquivo = "img";
            }else{
                $arquivo = $doc['tipo'];
            }
            return [
                "indereco" => "/src/userFile/{$usuario['code_nome']}/".$arquivo."/{$doc['indereco']}",
                "tipo" => $doc['tipo']
            ];
        }, mysqli_fetch_all(mysqli_query($this->link, "SELECT * FROM doc WHERE id=$id_pbl AND para = 'poste' AND (tipo='imagen' OR tipo='video')"), MYSQLI_ASSOC));
    
        $qtd_indereco = count($inderecos);
        $comunidade = $row['id_comunidade'] ? $this->comunidade->comunidade($row['id_comunidade']) : null;
    
        $classPartilha = $partilha ? "pbl_partilhada" : "";
        ?>
        <div class="<?= $classPartilha ?> card mb-3">
            <div class="card-body">
                <div class="conatiner mb-3">
                    <div class="row">
                        <a href="/perfil/?user=<?= criptografar($id_user) ?>" class="col-auto pr-2 pl-3">
                            <img src="<?= $imagem_perfil ?>" class="rounded-circle me-2" alt="Perfil" style="width: 50px; height: 50px;">
                        </a>
                        <div class="col p-0">
                            <?php if ($comunidade && $this->oque == "poste"): ?>
                                <span><a class="text-decoration-none bold" href="/comunidade/?cmndd=<?= criptografar($row['id_comunidade']) ?>"><?= $comunidade['nome'] ?></a></span><br>
                            <?php endif; ?>
                            <span><a href="/perfil/?user=<?= criptografar($id_user) ?>" class="text-decoration-none"><?= $nome_usuario ?></a></span>
                            <?php if(!$partilha): ?>
                                <div class="text-muted"><?= resumir_data($row['data']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-auto">
                            <?php if(!$partilha): ?>
                                <button class="btn btn-light" onclick="abrir_info_pbl(<?= $id_pbl ?>)">...</button>
                            <?php else: ?>
                                <div class="text-muted"><?= resumir_data($row['data']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($row['id_partilhado'] <= 0): ?>
                    <p class="text-muted mb-3"><?=htmlspecialchars_decode($row['texto']) ?></p>
                <?php else: ?>
                    <div class="container m-0 p-0">
                        <p class="text-muted mb-3"><?=htmlspecialchars_decode($row['texto']) ?></p>
                        <?php $this->mostrar($this->poste($row['id_partilhado']),false,false,true);?>
                    </div>
                <?php endif; ?>

                <?php if ($qtd_indereco > 0): ?>
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($inderecos as $indereco): ?>
                                <?php if ($indereco['tipo'] == "imagen"): ?>
                                    <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>">
                                        <img src="<?= $indereco['indereco'] ?>" class="img-fluid" style="max-width: 100%; max-height: 400px; border-radius: 8px;" alt="Imagen do Poste">
                                    </a>
                                <?php elseif ($indereco['tipo'] == "video"): ?>
                                    <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>">
                                        <video class="img-fluid" style="max-width: 100%; max-height: 400px; border-radius: 8px;" controls>
                                            <source src="<?= $indereco['indereco'] ?>" type="video/mp4">
                                            Seu navegador não suporta o elemento de vídeo.
                                        </video>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
    
                <?php if ($reac): ?>
                    <div class="d-flex justify-content-between">
                        <button id="reac_poste<?=$id_pbl?>" class="btn btn-light" onclick="reagir(<?= $id_pbl ?>, 'gosto', 'poste')">
                            <i class="bi <?= $this->qtd_reacao($id_pbl, 'poste', $_SESSION['id_user']) > 0 ? 'bi-heart-fill' : 'bi-heart' ?>" style="color: red;"></i>
                            <?= $this->qtd_reacao($id_pbl, 'poste') ?>
                        </button>
                        <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>&cmt=true" class="btn btn-light">
                            <i class="bi bi-chat-dots"></i> <?= qtd_de_cmt($id_pbl) ?>
                        </a>
                        <?php if ($row['id_partilhado'] <= 0): ?>
                            <button class="btn btn-light" onclick="abrir_partilhar('poste', <?= $id_pbl ?>,'poste')">
                                <i class="bi bi-reply"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
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
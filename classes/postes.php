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
    private $proveniente; //controlo
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
    public function mostrar($row, $visualizacao_unica = false, $reac = true, $partilha = false)
    {
        if (empty($row['id_user'])) return false;
    
        $usuario = $this->usuario($row['id_user']);
        $id_pbl = $row['id_pbl'];
        $id_user = $usuario['id_user'];
        $nome_usuario = $usuario['nome'];
        $imagem_perfil = pegar_foto_perfil("perfil", $row['id_user']);
        $inderecos = array_map(function ($doc) use ($usuario) {
            return "/src/userFile/{$usuario['code_nome']}/img/{$doc['indereco']}";
        }, mysqli_fetch_all(mysqli_query($this->link, "SELECT * FROM doc WHERE id=$id_pbl AND (tipo='poste' OR tipo='video')"), MYSQLI_ASSOC));
    
        $qtd_indereco = count($inderecos);
        $comunidade = $row['id_comunidade'] ? $this->comunidade->comunidade($row['id_comunidade']) : null;
    
        $classPartilha = $partilha ? "pbl_partilhada" : "";
        ?>
        <div id="pbl" class="<?= $classPartilha ?> card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <a href="/perfil/?user=<?= criptografar($id_user) ?>">
                        <img src="<?= $imagem_perfil ?>" class="rounded-circle me-2" alt="Perfil" style="width: 50px; height: 50px;">
                    </a>
                    <div>
                        <?php if ($comunidade && $this->oque == "pbl"): ?>
                            <span><a class="fw-bold" href="/comunidade/?cmndd=<?= criptografar($row['id_comunidade']) ?>"><?= $comunidade['nome'] ?></a></span><br>
                        <?php endif; ?>
                        <span><a href="/perfil/?user=<?= criptografar($id_user) ?>" class="text-decoration-none"><?= $nome_usuario ?></a></span>
                        <div class="text-muted"><?= resumir_data($row['data']) ?></div>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-light" onclick="abrir_info_pbl(<?= $id_pbl ?>)">...</button>
                    </div>
                </div>
                
                <p class="text-muted mb-3"><?= $this->proveniente."---".htmlspecialchars_decode($row['texto']) ?></p>
    
                <?php if ($qtd_indereco > 0): ?>
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach (array_slice($inderecos, 0, 5) as $indereco): ?>
                                <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>">
                                    <img src="<?= $indereco ?>" class="img-fluid" style="max-width: 150px; border-radius: 8px;" alt="Poste">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
    
                <?php if ($reac): ?>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-light" onclick="reagir(<?= $id_pbl ?>, 'gosto', 'poste')">
                            <img src="/bibliotecas/bootstrap/icones/<?= $this->qtd_reacao($id_pbl, 'poste', $_SESSION['id_user']) > 0 ? 'heart-fill.svg' : 'heart.svg' ?>" alt="Gosto">
                            <?= $this->qtd_reacao($id_pbl, 'poste') ?>
                        </button>
                        <a href="/pbl/?pbl=<?= criptografar($id_pbl) ?>&cmt=true" class="btn btn-light">
                            <img src="/bibliotecas/bootstrap/icones/chat-dots.svg" alt="Comentários"> <?= qtd_de_cmt($id_pbl) ?>
                        </a>
                        <?php if ($row['id_partilha'] <= 0): ?>
                            <button class="btn btn-light" onclick="abrir_partilhar('pbl', <?= $id_pbl ?>)">
                                <img src="/bibliotecas/bootstrap/icones/reply.svg" alt="Partilhar">
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
        $_SESSION['visualizado'][] = $id_pbl;

        return true;
    }
    
    private function procura_aleatoria()
    {
        if($this->oque == "poste") {
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

            $this->proveniente = "pbl_aleatorio";

        }elseif($this->oque > 0 && $this->para == "comunidade"){
            $sql = $this->pdo->prepare("SELECT pbl.*,p.id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
            WHERE id_comunidade = :id ORDER BY RAND() DESC");   
            $sql->bindValue(":id", $this->oque);

            $this->proveniente = "pbl_comunidade_aleatorio";
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
        if ($this->oque == "poste") {
            if ($tipo != "global") {
                $sql = $this->pdo->prepare("SELECT pbl.*, p.id_partilha, COALESCE(p.tipo, 'null') AS tipo_partilha 
                FROM pbl 
                LEFT JOIN contacto AS a ON ((a.id_user = pbl.id_user AND a.id_user_dest = :user) 
                    OR (a.id_user_dest = pbl.id_user AND a.id_user = :user))
                LEFT JOIN $this->bdnome2.visto AS v ON (v.id = pbl.id_pbl AND v.tipo = 'poste' AND v.id_user = :user)
                LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
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
                $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl 
                LEFT JOIN $this->bdnome2.visto as v ON (v.id = pbl.id_pbl AND v.tipo = 'poste' AND v.id_user = :user)
                LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
                WHERE id_visto IS NULL AND pbl.id_user != :user
                ORDER BY id_pbl DESC");  

                $this->proveniente = "pbl_global";
            }
            $sql->bindValue(":user", $this->user['id_user']);
        } elseif ($this->oque > 0 && $this->para == "comunidade") {
            $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
            WHERE id_comunidade = :id
            ORDER BY id_pbl DESC");
            
            $sql->bindValue(":id", $this->oque);  
            
            $this->proveniente = "comunidade";
        } elseif ($this->oque > 0 && $this->para == "perfil"){
            $sql = $this->pdo->prepare("SELECT pbl.*,id2 AS id_partilha,COALESCE(p.tipo,'null') AS tipo_partilha FROM pbl
            LEFT JOIN $this->bdnome2.partilha AS p ON (p.id1 = pbl.id_pbl)
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
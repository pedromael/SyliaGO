<?php
class mensagens extends process
{
    public $receptor;
    public function __construct(){
        parent::__construct();
    }    
    public function enviar($texto,$id_doc){
        $sql = $this->pdo->prepare("INSERT INTO chat(id_user,id_user_dest,id_doc,texto,data)
            VALUES (:1,:2,:3,:4,now())");
        $sql->bindValue(":1", $_SESSION['id_user']);
        $sql->bindValue(":2", $this->receptor);
        $sql->bindValue(":3", $id_doc);
        $sql->bindValue(":4", nl2br($texto));
        if ($sql->execute()) {
            $sql = $this->pdo->prepare("SELECT * FROM chat WHERE id_chat=:id");
            $sql->bindValue(":id", $this->pdo->lastInsertId());
            $sql->execute();
            return $sql->fetch();
        }else {
            return false;
        }
    }
    public function mostrar($dados, $anterior = false)
    {
        // Determina se é uma mensagem do receptor ou do emissor
        $isReceptor = ($this->receptor == $dados['id_user']);
        $classe_flex = $isReceptor ? "justify-content-start" : "justify-content-end";
        $posicao_img = $isReceptor ? "me-2" : "ms-2";
        $bg_cor = $isReceptor ? "bg-light" : "bg-primary";
        $texto_cor = $isReceptor ? "text-dark" : "text-white";

        ?>
        <div class="d-flex <?=$classe_flex?> mb-2">
            <?php if ($isReceptor && $anterior): ?>
                <div class="<?=$posicao_img?>">
                    <div class="rounded-circle" 
                        style="width: 45px; height: 45px; 
                        background-image: url('<?=pegar_foto_perfil('perfil', $dados['id_user'])?>'); 
                        background-size: cover; 
                        background-position: center 10%;">
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mensagem -->
            <div class="d-flex flex-column">
                <div class="p-2 <?=$bg_cor?> border rounded <?=$texto_cor?>">
                    <p class="mb-0"><?=$dados['texto']?></p>
                </div>
                <small class="text-muted mt-1"><?=resumir_data($dados['data'])?></small>
            </div>
        </div>
        <?php 
        $this->marcar_visto($dados['id_chat'], "chat");
    }

    public function selecionar(){
        $sql = $this->pdo->prepare("SELECT * FROM (SELECT * FROM chat WHERE (id_user = :r
        AND id_user_dest = :t) OR (id_user = :t AND id_user_dest = :r) ORDER BY id_chat DESC LIMIT 10)
        AS subquery ORDER BY id_chat ASC");
        $sql->bindValue(":t", $_SESSION['id_user']);
        $sql->bindValue(":r", $this->receptor);
        $sql->execute();
        $dados = $sql->fetchALL();
        $a = 1;
        foreach ($dados as $key) {
            $this->mostrar($key,$a);
            if ($this->receptor == $key['id_user']) {
                $a = false;
            }else {$a = true;}
        }
    }
}
?>
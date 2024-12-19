<?php
class comentarios extends process
{
    public $id;
    public $id_cmt = false;
    public $indereco;

    public function mostrar($dados) {
        $user = $this->usuario($dados['id_user']);
        ?>
        <div class="d-flex border p-3 rounded mb-3">
            <div class="me-3">
                <a href="">
                    <img src="<?= pegar_foto_perfil("perfil", $dados['id_user']) ?>" alt="Foto de perfil" class="rounded-circle" style="width: 50px; height: 50px;">
                </a>
            </div>
            <div class="flex-grow-1">
                <div>
                    <a href="/perfil/?user=<?= criptografar($dados['id_user']) ?>" class="fw-bold text-dark text-decoration-none">
                        <?= $user['nome'] ?>
                    </a>
                </div>
                <div class="mt-2">
                    <p class="text-break mb-1"><?= $dados['texto'] ?></p>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        <a href="cmt.php?cmt=<?= criptografar($dados['id_cmt']) ?>" class="text-muted me-3">
                            Responder <span class="badge bg-primary"><?= qtd_cmt_respostas($dados['id_cmt']) ?></span>
                        </a>
                        <?php if ($dados['id_user'] == $_SESSION['id_user']) { ?>
                            <a href="editar.php?id_cmt=<?= criptografar($dados['id_cmt']) ?>" class="text-muted me-3">Editar</a>
                            <a href="eliminar" class="text-danger me-3">Eliminar</a>
                        <?php } else { ?>
                            <a href="#" class="text-muted me-3">Guardar</a>
                        <?php } ?>
                    </div>
                    <div class="text-muted small"><?= resumir_data($dados['data']) ?></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function comentar($id,$texto,$tipo){
        $tipos_disponiveis = ['comentario', 'poste', 'repositorio'];
        if (!in_array($tipo, $tipos_disponiveis)) {
            return false;
        }
        
        $sql = $this->pdo->prepare("INSERT INTO cmt(id_user,id,tipo,texto,data)
        VALUES(:user,:id,:tipo,:t,NOW())");
        $sql->bindValue(":user", $_SESSION['id_user']);
        $sql->bindValue(":id", $id);
        $sql->bindValue(":tipo", $tipo);
        $sql->bindValue(":t", nl2br($texto));
        if ($sql->execute()) {
            $id_cmt = $this->pdo->lastInsertId();
            
            switch ($tipo) {
                case 'comentario':
                    $id_user = $this->comentario($id)['id_user'];
                    break;

                case 'repositorio':
                    # code...
                    break;

                case 'poste':
                    $id_user = (new postes())->poste($id)['id_user'];
                    break;

                default:
                    return false;
            }

            if ($this->inserir_historico("comentario", $id_cmt, $tipo,$id_user)) { 
                return $this->comentario($id_cmt);
            }else {
               return  false;
            }
        } else {
            return false;
        }
    }
    public function pegar($tipo = "poste",$numero_max = 1) 
    {
        $sql = $this->pdo->prepare("SELECT * FROM cmt WHERE tipo = :t AND id = :id  ORDER BY id_cmt DESC");
        $sql->bindValue(":t", $tipo);
        if ($this->id_cmt) {
            $sql->bindValue(":id", $this->id_cmt);
        }else {
            $sql->bindValue(":id", $this->id);
        }
        $sql->execute();
        $dados = $sql->fetchALL();
        $a = 0;
        foreach ($dados as $dado) {
            if (!in_array($dado['id_cmt'],$_SESSION['cmt_visualizado'])) {
                if ($a == $numero_max) {return true;}
                array_push($_SESSION['cmt_visualizado'],$dado['id_cmt']);
                $this->mostrar($dado);
                $a++;
            }
        }
        return true;
    }
    public function comentario($id){
        $sql = $this->pdo->prepare("SELECT * FROM cmt WHERE id_cmt=:id");
        $sql->bindValue(":id",$id);
        $sql->execute();
        if ($sql->rowCount() <= 0) {
            return false;
        }
        return $sql->fetch();
    }
}

?>
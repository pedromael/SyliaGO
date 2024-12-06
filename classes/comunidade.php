<?php
class comunidade extends conexao
{
    private $process;
    private $id_comunidade;
    public $indereco;
    public function __construct($id_comunidade = null){
        parent::__construct();
        $this->id_comunidade = $id_comunidade;
        $this->process = new process;
    }
    public function comunidade($id = NULL)
    {
        $id = ($id==NULL) ? $this->id_comunidade : $id;

        $sql = $this->pdo->prepare("SELECT * FROM comunidade
        WHERE id_comunidade = :id");   
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetch();
    }
    public function mostrar_comunidades($row,$caso){
        $id_comunidade = $row['id_comunidade'];
        $imagen = pegar_foto_perfil("cmdd",$id_comunidade);
        if ($caso == "minhas") {
            ?>
        <div class="card mb-3">
            <div class="row g-0 align-items-center">
                <!-- Imagem da Comunidade -->
                <div class="col-auto">
                    <div class="rounded-circle" 
                        style="width: 60px; height: 60px; background-image: url('<?=$imagen?>'); background-size: cover; background-position: center;">
                    </div>
                </div>

                <!-- Informações da Comunidade e Botão -->
                <div class="col">
                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                        <!-- Informações -->
                        <div>
                            <h5 class="card-title mb-1">
                                <a href="/comunidade/?cmndd=<?=criptografar($id_comunidade)?>" class="text-decoration-none text-dark">
                                    <?=$row['nome']?>
                                </a>
                            </h5>
                            <div class="d-flex gap-3">
                                <span class="text-muted">seguindo: <strong>1</strong></span>
                                <span class="text-muted">Gostos: <strong>1</strong></span>
                            </div>
                        </div>
                        <!-- Botão -->
                        <div>
                            <a href="/comunidade/?cmndd=<?=criptografar($id_comunidade)?>" class="btn btn-primary">
                                Visualizar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }else {
            ?>
            <div class="card mb-3">
                <div class="row g-0 align-items-center">
                    <!-- Imagem da Comunidade -->
                    <div class="col-auto">
                        <div class="rounded-circle" 
                            style="width: 60px; height: 60px; background-image: url('<?=$imagen?>'); background-size: cover; background-position: center;">
                        </div>
                    </div>

                    <!-- Informações da Comunidade e Botão -->
                    <div class="col">
                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                            <!-- Informações -->
                            <div>
                                <h5 class="card-title mb-1">
                                    <a href="/comunidade/?cmndd=<?=criptografar($id_comunidade)?>" class="text-decoration-none text-dark">
                                        <?=$row['nome']?>
                                    </a>
                                </h5>
                                <div class="d-flex gap-3">
                                    <span class="text-muted">seguindo: <strong>1</strong></span>
                                    <span class="text-muted">Gostos: <strong>1</strong></span>
                                </div>
                            </div>
                            <!-- Botão -->
                            <div>
                                <a class="btn btn-primary" href="/comunidade/lista.php?abrir=pdd&cmndd=<?=criptografar($id_comunidade)?>">
                                    entrar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        if ($this->indereco == "../") {
            $this->indereco = NULL;
        }
    }
    public function minhas_comunidade($id_user = NULL){
        if (empty($id_user)) {
            $id_user = $_SESSION['id_user'];
        }
        $num = 0;
        $sql = mysqli_query(conn(), "SELECT DISTINCT c.* FROM comunidade AS c
        LEFT JOIN $this->bdnome2.comunidade_integrante AS i ON 
        ((i.id_comunidade = c.id_comunidade AND i.id_user = $id_user) OR (c.id_user = $id_user))
        WHERE (i.id_comunidade = c.id_comunidade AND i.id_user = $id_user) OR (c.id_user = $id_user)
        ORDER BY c.id_comunidade DESC");
        while ($row = mysqli_fetch_assoc($sql)) {
            $num++;
            $this->mostrar_comunidades($row,'minhas');
        }
        return $num;
    }
    public function comunidades_sugerida($id_user = NULL){
        if (empty($id_user)) {
            $id_user = $_SESSION['id_user'];
        }
        $num = 0;
        $sql = mysqli_query(conn(), "SELECT * FROM comunidade");
        while ($row = mysqli_fetch_assoc($sql)) {
            $id_comunidade = $row['id_comunidade'];
            $sqll = mysqli_query(conn(), "SELECT count(*) as valor FROM $this->bdnome2.comunidade_integrante WHERE id_comunidade = $id_comunidade AND id_user=$id_user");
            $sqll = mysqli_fetch_assoc($sqll);
            if ($sqll['valor'] <= 0 && $id_user != $row['id_user']) {
                $num++;
                $this->mostrar_comunidades($row,'sugeridas');
            }
        }
    }
    public function entrar_na_comunidade($id_comunidade){
        $sql = $this->pdo->prepare("SELECT * FROM comunidade WHERE id_comunidade=:c");
        $sql->bindValue(":c", $id_comunidade);
        if ($sql->execute()) {
           $dados = $sql->fetch();
           $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.privado WHERE id=:c AND tipo='comunidade'");
           $sql->bindValue(":c", $id_comunidade);
           $sql->execute();
           if ($sql->rowCount() > 0) {
            # code...
           } else {
                $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.comunidade_integrante WHERE id_user=:user AND id_comunidade=:c");
                $sql->bindValue(":c", $id_comunidade);
                $sql->bindValue(":user", $_SESSION['id_user']);
                $sql->execute();
                if ($sql->rowCount() <= 0) {
                    $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.comunidade_integrante(id_user,id_comunidade,data)
                    VALUES(:user,:c,now())");
                    $sql->bindValue(":c", $id_comunidade);
                    $sql->bindValue(":user", $_SESSION['id_user']);
                    if ($sql->execute()) {
                        if ($this->process->inserir_historico("comfirmado", $id_comunidade, "comunidade")) {
                            return true;
                        }else {
                            return  false;
                        }
                    }else {
                        return false;
                    }
                } else{
                    return 1;
                }
           }
            
        }else {
            return false;
        }
    }
    public function criar_comunidade($nome,$descricao){
        $sql = $this->pdo->prepare("INSERT INTO comunidade(id_user,nome,descricao,data)
        VALUeS(:user,:n,:d,now())");
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":d", $descricao);
        $sql->bindValue(":user", $_SESSION['id_user']);
        if ($sql->execute()) {
            $sql = $this->pdo->prepare("SELECT id_comunidade FROM comunidade
            WHERE nome =:n AND descricao=:d AND id_user=:user");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":d", $descricao);
            $sql->bindValue(":user", $_SESSION['id_user']);
            if ($sql->execute()) {
                $dados = $sql->fetch();
                return $dados['id_comunidade'];
            }
        }else {
            return false;
        }
    }
    public function pertence_a_comunidade($id_user = NULL, $id_comunidade = null): bool|int
    {
        $id_comunidade = $id_comunidade == NULL ? $this->id_comunidade : $id_comunidade;
        $id_user = ($id_user == NULL) ? $_SESSION['id_user'] : $id_user;

        $comunidade = $this->pdo->prepare("SELECT * FROM $this->bdnome2.comunidade_integrante 
        WHERE id_user=$id_user AND id_comunidade= :id");
        $comunidade->bindParam(":id", $id_comunidade);
        $comunidade->execute();
        if ($comunidade->rowCount() > 0) {
            return true;
        }else 
        {
            $comunidade = $this->pdo->prepare("SELECT * FROM comunidade 
            WHERE id_user=$id_user AND id_comunidade = :id");
            $comunidade->bindParam(":id", $id_comunidade);
            $comunidade->execute();
            if ($comunidade->rowCount() > 0) {
                return 1;
            } else {
                return false;
            }
        }
    }
}

?>
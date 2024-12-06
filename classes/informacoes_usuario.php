<?php
class informacoes_usuario extends conexao
{
    public $user;
    //public $linguas_falada;
    public $gostos;
    public $indereco; 

    public function __construct(){ 
        parent::__construct();
        $this->user = $this->usuario($_SESSION['id_user']);
        //$this->linguas_falada = $this->linguas_falada($_SESSION['id_user']);
        // $this->gostos = $this->gostos_do_usuario($_SESSION['id_user']);
    }
    public function usuario($id_user = false,$todo=false) {
        if (!$id_user) {
            $id_user = $_SESSION['id_user'];
        }
        if ($todo) {
            $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_user!=:user");
            $sql->bindValue(":user", $id_user);
            $sql->execute();
            return $sql->fetchAll();
        }else {
            $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_user=:user");   
        }
        $sql->bindValue(":user", $id_user);
        if ($sql->execute()) {
            return $sql->fetch();
        }   
        return false;
    }
    public function linguas_falada($id_user) {
        $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.linguas_falada WHERE id_user=:user");
        $sql->bindValue(":user", $id_user);
        if ($sql->execute()) {
            $dados = $sql->fetch();
            return $dados;
        }else{return false;}
    }
    public function gostos_do_usuario($id_user) {
        // $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.gostos_do_usuario WHERE id_user=:user");
        // $sql->bindValue(":user", $id_user);
        // if ($sql->execute()) {
        //     $dados = $sql->fetch();
        //     return $dados;
        // }else{return false;}
    }
    public function mostrar_amigos($dados){
        if (!isset($this->indereco)) {
            $this->indereco = "./";
        }
        ?>
        <div class="usuario container">
            <div class="img d-inline-block" style="background-image :url(<?=$this->indereco."media/img/".pegar_foto_perfil("perfil",$dados['id_user'])?>);"></div>
            <div class="nome d-inline-block">
                <div class="container">
                    <div class="d-block"><?=$dados['nome']?></div>
                    <div class="d-block">info</div>
                </div>
            </div>
        </div>
        <?php
    }
    public function lista_amigos($id_user,$so_id = false){
        $lista = [];
        $sql = $this->pdo->prepare("SELECT u.* FROM usuarios AS u
        JOIN contacto AS c ON ((c.id_user = :id AND c.id_user_dest = u.id_user) OR (c.id_user_dest = $id_user AND c.id_user = u.id_user))
        JOIN $this->bdnome2.contacto_aceite AS ca ON (ca.id_contacto = c.id_contacto)
        WHERE ca.id_contacto IS NOT NULL");
        $sql->bindValue(":id", $id_user);
        $sql->execute();
        if (!$so_id) {
            return $sql->fetchALL(); 
        }
        foreach ($sql->fetchALL() as $query) {
            array_push($lista,$query['id_user']);
        }
        return $lista;
    }
}
?>
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
    public function usuario($id_user = false, $todo = false) {
        try {
            if (!$id_user) {
                $id_user = $_SESSION['id_user'];
            }
    
            if ($todo) {
                $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_user != :user");
            } else {
                $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_user = :user");
            }
    
            $sql->bindValue(":user", $id_user);
    
            if ($sql->execute()) {
                return $todo ? $sql->fetchAll() : $sql->fetch();
            }  
        } catch (\Throwable $th) {
            return $this->usuario($id_user, $todo);   
        }
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
        ?>
        <div class="usuario container">
            <div class="img d-inline-block" style="background-image :url(<?=pegar_foto_perfil("perfil",$dados['id_user'])?>);"></div>
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
        JOIN contacto AS c ON ((c.id_user = :id AND c.id_user_dest = u.id_user) OR (c.id_user_dest = :id AND c.id_user = u.id_user))
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

    //funcao responsavel por analizar em pontos o nivel de ligacao de dois usuario
    public function ligacao_entre_usuario($id_user, $id_user2 = NULL): int 
    {
        $user = new informacoes_usuario;
        if(is_null($id_user2)) {
            $user1 = $user->user;
        }else{
            $user1 = $user->usuario($id_user2);
            if($user1 == false){
                return false;
            }
        }
        $user2 = $user->usuario($id_user);
        if($user2 == false){
            return false;
        }

        $pontuacao = 0;
        $pontuacao = $pontuacao + 5 * verificar_contactos_em_comum($user1['id_user'],$user2['id_user']);
        $pontuacao = $pontuacao + 10 * verificar_grupos_em_comum($user1['id_user'],$user2['id_user']);
        if ($user1['id_pais'] == $user2['id_pais']) {
            $pontuacao = $pontuacao + 15;
        }
    
        return $pontuacao;
    
    }
}
?>
<?php
class process extends informacoes_usuario{
    function __construct() {
        parent::__construct(); 
    }
    public function inserir_historico($tipo, $id, $de, $id_receptor, $inverso = false) {
        if ($inverso) {
            // Deletar o histórico com os valores passados
            $sql = $this->pdo->prepare("DELETE FROM $this->bdnome2.historico WHERE id_emissor = :user AND id_receptor = :receptor AND id = :id AND tipo = :t AND de = :d");
            $sql->bindValue(":user", $_SESSION['id_user']);
            $sql->bindValue(":receptor", $id_receptor);
            $sql->bindValue(":t", $tipo);
            $sql->bindValue(":d", $de);
            $sql->bindValue(":id", $id);
            
            if (!$sql->execute()) {
                return false;
            }
        } else {
            // Inserir o histórico normalmente
            $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.historico(id_emissor, id_receptor, id, tipo, de, data) VALUES(:user, :receptor, :id, :t, :d, NOW())");
            $sql->bindValue(":user", $_SESSION['id_user']);
            $sql->bindValue(":receptor", $id_receptor);
            $sql->bindValue(":t", $tipo);
            $sql->bindValue(":d", $de);
            $sql->bindValue(":id", $id);
            
            if (!$sql->execute()) {
                return false;  // Retorna falso se a inserção falhar
            }
            return true;  // Retorna verdadeiro se a inserção for bem-sucedida
        }
    }
    
    public function marcar_visto($id,$tipo){
        $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.visto WHERE id_user=:user AND id=$id AND tipo='$tipo'");
        $sql->bindValue(":user", $_SESSION['id_user']);
        $sql->execute();
        if ($sql->rowCount() <= 0) {
            $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.visto (id_user,id,tipo,data) VALUES (:user,$id,'$tipo',now())");
            $sql->bindValue(":user", $_SESSION['id_user']);
            if ($sql->execute()) {
                return $this->pdo->LastInsertId();
            }else {
                return false;
            }
        }else {
            $dados = $sql->fetch();
            return $dados['id_visto'];
        }
    }
    public function qtd_vistos($id,$tipo,$id_user = NULL,$retornar_dados=false){
        if ($id_user) {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.visto WHERE id=:id AND tipo='$tipo' AND id_user=:user");
            $sql->bindValue(":id", $id);
            $sql->bindValue(":user", $id_user);
            $sql->execute();
        }else {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.visto WHERE id=:id AND tipo='$tipo'");
            $sql->bindValue(":id", $id);
            $sql->execute();
        }
        
        if ($retornar_dados) {
            return $sql->fetchALL();
        }
        return $sql->rowCount();
    }
    public function marcar_lido($id,$tipo){
        $id_visto = $this->marcar_visto($id,$tipo);
        if (!$id_visto) {return false;}
        $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.lido WHERE id_visto=:id");
        $sql->bindValue(":id", $id_visto);
        $sql->execute();
        if ($sql->rowCount() <= 0) {
            $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.lido (id_visto,data) VALUES (:id,now())");
            $sql->bindValue(":id", $id_visto);
            if ($sql->execute()) {
                return $this->pdo->LastInsertId();
            }else {
                return false;
            }
        }
        $dados = $sql->fetch();
        return $dados['id_visto'];
        
    }
    public function qtd_leitores($id,$tipo,$retornar_dados=false){
        $i=0;
        foreach ($this->qtd_vistos($id,$tipo,false,true) as $value) {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.lido WHERE id_visto=:id");
            $sql->bindValue(":id", $value['id_visto']);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                $i++;
            }
        }
        return $i;
    }
    public function verificar_qtd($de,$id_dest)
    {
        global $bdnome2;
        $n = 0;
        $id_user = $_SESSION['id_user'];

        if ($de == "chat") {
            $sql = mysqli_query(conn(), "SELECT * FROM chat WHERE id_user_dest=$id_user");
            while ($row = mysqli_fetch_assoc($sql)) {
                $id = $row['id_chat'];
                $visto = mysqli_query(conn(), "SELECT count(*) AS valor FROM $bdnome2.visto WHERE tipo='chat' AND id=$id");
                $visto = mysqli_fetch_assoc($visto);
                if ($visto['valor'] <= 0) {
                    $n++;
                }
            }
            if ($n == 0) {
                $n = "";
            }
            return $n;
        }
        if ($de == "notificacao") {
            $notificacoes = new notificacoes;
            $i=0;
            foreach ($notificacoes->procurar("Conta") as $value) {
                if ($this->qtd_vistos($value['id_historico'],'notific',$_SESSION['id_user']) < 1) {
                    $i++;
                }
            }
            return $i;
        }
        if ($de == "user_chat") {
            $sql = mysqli_query(conn(), "SELECT * FROM chat WHERE id_user_dest=$id_user AND id_user=$id_dest");
            while ($row = mysqli_fetch_assoc($sql)) {
                $id = $row['id_chat'];
                $visto = mysqli_query(conn(), "SELECT count(*) AS valor FROM $bdnome2.visto WHERE tipo='chat' AND id=$id AND id_user= $id_user");
                $visto = mysqli_fetch_assoc($visto);
                if ($visto['valor'] <= 0) {
                    $n++;
                }
            }
            if ($n == 0) {
                $n = null;
            }
            return $n;
        }
        if ($de == "pdd") {
            $sql = mysqli_query(conn(), "SELECT * FROM contacto WHERE id_user_dest=$id_user");
            while ($row = mysqli_fetch_assoc($sql)) {
                $id = $row['id_contacto'];
                $visto = mysqli_query(conn(), "SELECT count(*) AS valor FROM $bdnome2.contacto_aceite WHERE id_contacto=$id");
                $visto = mysqli_fetch_assoc($visto);
                if ($visto['valor'] <= 0) {$n++;}
            }
            if ($n == 0) {$n = null;}
            return $n;
        }
        if ($de == "cmt") {
            $sql = mysqli_query(conn(), "SELECT count(*) AS valor FROM cmt WHERE id_pbl=$id_dest");
            $row = mysqli_fetch_assoc($sql);
            $n = $row['valor'];
            if ($n == 0) {$n = null;}
            return $n;
        }
    }
    public function carregar_documento($id,$tipo,$indereco){
        $sql = $this->pdo->prepare("INSERT INTO doc(id,id_user,tipo,indereco)
        VALUES (:id,:user,:t,:i)");
        $sql->bindValue(":id", $id);
        $sql->bindValue(":user", $_SESSION['id_user']);
        $sql->bindValue(":t", $tipo);
        $sql->bindValue(":i", $indereco);
        if ($sql->execute()) {
            return true;
        }else {
            return false;
        }
    }
    public function publicar($texto,$id_comunidade,$null){
        $sql = $this->pdo->prepare("INSERT INTO pbl(texto,id_user,id_comunidade,data)
        VAlUES(:t,:user,:c,NOW())");
        $sql->bindValue(":t", nl2br($texto));
        $sql->bindValue(":c", $id_comunidade);
        $sql->bindValue(":user", $_SESSION['id_user']);
        if ($sql->execute()) {
            return $this->pdo->LastInsertId();
        } else {
            return false;
        } 
    }
    public function reagir($id,$tipo,$para) {
        switch ($para) {
            case 'comentario':
                $id_user = (new comentarios())->comentario($id)['id_user'];
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

        $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.reacao WHERE id_user=:user AND id=:id AND para = :p");
        $sql->bindValue(":user", $_SESSION['id_user']);
        $sql->bindValue(":id", $id); 
        $sql->bindValue(":p", $para);
        $sql->execute();
        if ($sql->rowCount() > 0) 
        {
            $id_reacao = $sql->fetch()['id_reacao'];
            $sql = $this->pdo->prepare("DELETE FROM $this->bdnome2.reacao WHERE id_reacao=:i");
            $sql->bindValue(":i", $id_reacao);
            $sql->execute();

            if (!$this->inserir_historico("reacao", $id, $para, $id_user, true)) {
                return false;
            }
        }else{
            $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.reacao(id_user, id, tipo, para, data)
            VALUES(:user, :id, :t, :p, NOW()) ");
            $sql->bindValue(":user", $_SESSION['id_user']);
            $sql->bindValue(':id', $id);
            $sql->bindValue(':p', $para);
            $sql->bindValue(':t', $tipo);
            $sql->execute();

            if (!$this->inserir_historico("reacao", $id, $para, $id_user)) {
                return false;
            }
        }
        return true;
    }
    public function qtd_reacao($id,$para,$id_user = NULL): int
    {
        if ($id_user != NULL) {
            $reac = $this->pdo->prepare("SELECT * FROM $this->bdnome2.reacao WHERE id_user = :id AND id=$id AND para = '$para'");
            $reac->bindValue(":id", $id_user);
        }else {
            $reac = $this->pdo->prepare("SELECT * FROM $this->bdnome2.reacao WHERE id=$id AND para = '$para'");
        }
        $reac->execute();
        return $reac->rowCount();
    }
    public function postar_stories($imgs) {
        if (!is_array($imgs)) {
            $img = $imgs;
            $imgs = array();
            array_push($imgs,$img);
        }
        $id_user = $_SESSION['id_user'];
        foreach ($imgs as $img) {
            $sql = $this->pdo->prepare("INSERT INTO stories(id_user,indereco_img,data)
            VALUES(:user,:img,NOW())");
            $sql->bindValue(":user", $id_user);
            $sql->bindValue(":img", $img);
            if (!$sql->execute()) {
                return false;
            }
        }
        return true;
    }
    public function eliminar_pbl($ids_pbl){
        if (!is_array($ids_pbl)) {
            $id = $ids_pbl;
            $ids_pbl = array();
            array_push($ids_pbl,$id); 
        }
        foreach ($ids_pbl as $id_pbl) {
            $sql = $this->pdo->prepare("DELETE FROM $this->bdnome2.gosto WHERE id = :pbl AND tipo = 'pbl'"); 
            $sql->bindValue(":pbl", $id_pbl);
            if($sql->execute()){
                $sql = $this->pdo->prepare("DELETE FROM cmt WHERE id = :pbl AND tipo=:tipo");
                $sql->bindValue(":pbl", $id_pbl);
                $sql->bindValue(":tipo", 'pbl');
                if ($sql->execute()) {
                    $sql = $this->pdo->prepare("DELETE FROM pbl WHERE id_pbl = :pbl"); 
                    $sql->bindValue(":pbl", $id_pbl);
                    if($sql->execute()){
                        $sql = $this->pdo->prepare("SELECT id_doc,indereco FROM doc WHERE id = :pbl AND tipo = 'pbl'");
                        $sql->bindValue(":pbl", $id_pbl);
                        if ($sql->execute()) {
                            if ($sql->rowCount() > 0) {
                                $dados = $sql->fetchALL();
                                foreach ($dados as $dado) {   
                                    if (unlink("../media/img/".$dado['indereco'])) 
                                    {    
                                        $eliminar_doc = $this->pdo->prepare("DELETE FROM doc WHERE id_doc = :id AND tipo = 'pbl'"); 
                                        $eliminar_doc->bindValue(":id", $dado['id_doc']);
                                        $eliminar_doc->execute(); 
                                    }
                                }        
                            }
                        }else {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
    public function actualizar(){
        $id_user = $_SESSION['id_user'];

        $sql = $this->pdo->prepare("SELECT count(c.id_chat) AS valor FROM chat AS c
        INNER JOIN $this->bdnome2.visto AS v ON (v.id = c.id_chat AND v.tipo = 'chat' AND v.id_user=$id_user)
        WHERE c.id_user_dest = $id_user AND v.id != c.id_chat");
        $sql->execute();
        $dados = $sql->fetch();
        $chat = $dados['valor'];

        $sql = $this->pdo->prepare("SELECT count(*) AS valor FROM contacto AS c
        INNER JOIN $this->bdnome2.contacto_aceite AS ca ON (ca.id_contacto = c.id_contacto)
        WHERE c.id_user_dest = $id_user AND ca.id_contacto = c.id_contacto");
        $sql->execute();
        $dados = $sql->fetch();
        $contacto = $dados['valor'];

        return [
            'chat' => $chat,
            'contacto' => $contacto
        ];
    }
    public function mudar_nome($nome) {
        $id_user = $_SESSION['id_user'];

        $sql = $this->pdo->prepare("UPDATE usuarios SET nome = :n WHERE id_user = :u");
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":u", $id_user);
        if ($sql->execute()) {
            return $nome;
        }else {
            return false;
        }
    }
    public function deletar_conta($id_user){
        $id_user = $_SESSION['id_user'];

        $sql = $this->pdo->prepare("SELECT  FROM id_pbl WHERE id_user = :user");
        $sql->bindValue(":user", $id_user);
        $sql->execute();
        $dados = $sql->fetchAll();

        if ($this->eliminar_pbl($dados['id_pbl'])) {
            $sql = $this->pdo->prepare("DELETE FROM usuarios WHERE id_user = :user");
            $sql->bindValue(":user", $id_user);
            if ($sql->execute()) {
                return true;
            }
        }
    }
    public function solicitar_contacto($id){
        $sql = $this->pdo->prepare("SELECT * FROM contacto WHERE (id_user = :id
            AND id_user_dest = :id_user) OR (id_user = :id_user AND id_user_dest = :id)");
        $sql->bindValue(":id_user", $_SESSION['id_user']);
        $sql->bindValue(":id", $id);
        $sql->execute();
        $dados = $sql->fetch();
        
        if (isset($dados['id_contacto'])) {
            $id_contacto =  $dados['id_contacto'];
            if ($dados['id_user'] == $_SESSION['id_user']) {
                return 3;
            }
        }
        if ($sql->rowCount() <= 0) {
            $sql = $this->pdo->prepare("INSERT INTO contacto(id_user,id_user_dest,data)
            VALUES (:user,:dest,now())");
            $sql->bindValue(":user", $_SESSION['id_user']);
            $sql->bindValue(":dest", $id);
            $sql->execute();
            return 1;
        } else {
            $sql = $this->pdo->prepare("INSERT INTO contacto_aceite(id_contacto,data) VALUES(:id,now())");
            $sql->bindValue(":id", $id_contacto);
            $sql->execute();
            return 2;
        }
    }
    public function eliminar_pedido_contacto($id_contacto){
        $sql = $this->pdo->prepare("SELECT * FROM contacto WHERE id_contacto = :id AND id_user_dest = :id_user");
        $sql->bindValue(":id_user", $_SESSION['id_user']);
        $sql->bindValue(":id", $id_contacto);
        $sql->execute();
        
        if ($sql->rowCount() > 0) {
            $sql = $this->pdo->prepare("DELETE FROM contacto WHERE id_contacto = :id");
            $sql->bindValue(":id", $id_contacto);
            if ($sql->execute()) {
                return true;
            }
        }else {
            return false;
        }
    }
    public function aceitar_contacto($id_contacto){
        $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.contacto_aceite WHERE id_contacto = $id_contacto");
        $sql->execute();
        if ($sql->rowCount() <= 0) {
            $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.contacto_aceite(id_contacto,data) VALUES (:id,now())");
            $sql->bindValue(":id", $id_contacto);
            if ($sql->execute()) {
                $sql = $this->pdo->prepare("SELECT * FROM contacto WHERE id_contacto = $id_contacto");
                $sql->execute();
                $dados = $sql->fetch();
                $this->inserir_historico("comfirmado", $id_contacto, "perfil", $dados['id_user']);
            } else {
                return false;
            }
        }else {
            return false; 
        }
        
    }
    public function enviar_mensagem($texto,$id_dest,$id_doc)
    {
        $sql = $this->pdo->prepare("INSERT INTO chat(id_user,id_user_dest,id_doc,texto,data)
            VALUES (:1,:2,:3,:4,now())");
        $sql->bindValue(":1", $_SESSION['id_user']);
        $sql->bindValue(":2", $id_dest);
        $sql->bindValue(":3", $id_doc);
        $sql->bindValue(":4", nl2br($texto));
        if ($sql->execute()) {
            return true;
        }else {
            return false;
        }
    }
    public function alterar_privacidade($id, $tipo)
    {
        $sql = $this->pdo->prepare("SELECT id_privado FROM privado WHERE id = :id AND tipo = :t");
        $sql->bindValue(":id", $id);
        $sql->bindValue(":t", $tipo);
        $sql->execute();

        if($sql->rowCount() > 0)
        {
            $sql = $this->pdo->prepare("DELETE FROM privado WHERE id = :id AND tipo = :t");
            $sql->bindValue(":id", $id);
            $sql->bindValue(":t", $tipo);
            return $sql->execute() ? true: false; 
        }

        $sql = $this->pdo->prepare("INSERT INTO privado(id,tipo)
        VALUES(:id, :t)");
        $sql->bindValue(":id", $id);
        $sql->bindValue(":t", $tipo);
        return $sql->execute() ? true: false;
    }
}
?>
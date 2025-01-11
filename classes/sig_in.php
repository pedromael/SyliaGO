<?php
class sig_in extends conexao{
    public function __construct(){
        parent::__construct();
    }
    public function registrar_login() {
        $id_user = $_SESSION['id_user'];
        $IP = $_SERVER["REMOTE_ADDR"];
        $sql = $this->pdo->prepare("SELECT * FROM $this->bdnome2.login WHERE id_user = $id_user AND IP = '$IP'");
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $sql = $this->pdo->prepare("UPDATE $this->bdnome2.login SET logado=1,data=NOW() WHERE id_user = $id_user AND IP = '$IP'");
            if ($sql->execute()) {
                return true;
            }
        }else {
            $sql = $this->pdo->prepare("INSERT INTO $this->bdnome2.login(id_user,logado,IP,data) VALUES($id_user,1,'$IP',NOW())");
            if ($sql->execute()) {
                return true;
            }
        }
        return false;
    }

    private function diretorios_pessoal($code_nome): bool
    {
        $base_path = "../src/userFile/" . $code_nome . "/";
        $subdirs = ["img", "video", "repositorio", "thumbnail"];

        foreach ($subdirs as $subdir) {
            $path = $base_path . $subdir . "/";
            if (!is_dir($path) && !mkdir($path, 0755, true)) {
                return false;
            }
        }

        return true;
    }

    public function logar($email,$senha)
    {
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE email =:e AND senha =:s");
        $sql->bindValue(":s", md5($senha));
        $sql->bindValue(":e", $email);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();

            if(!$this->diretorios_pessoal($dados['code_nome'])){
                return false;
            }

            $_SESSION['id_user'] = $dados['id_user'];
            if ($this->registrar_login()) {
                return true;
            }else {
                ?><script>alert("erro ao iniciar login")</script><?php
            }
        } else {
            return false;
        }
    }
    public function cadastrar($nome,$email,$pais,$senha)
    {
        if (!$code_nome = gerar_code_nome($nome)) {
            return false;
        }

        $sqll = $this->pdo->prepare("SELECT * FROM usuarios WHERE email =:e");
        $sqll->bindValue(":e", $email);
        $sqll->execute();
        if ($sqll->rowCount() <= 0) {
            $sql = $this->pdo->prepare("INSERT INTO usuarios(nome,email,id_pais,senha,code_nome,data) 
            VALUES (:n,:e,:p,:s,:c,now())");
            $sql->bindValue(":n", $nome);
            $sql->bindValue(":s", md5($senha));
            $sql->bindValue(":e", $email);
            $sql->bindValue(":p", $pais);
            $sql->bindValue(":c", $code_nome);
            if ($sql->execute()) {
                $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE email =:e AND senha=:s");
                $sql->bindValue(":s", md5($senha));
                $sql->bindValue(":e", $email);
                if ($sql->execute()) {

                    if(!$this->diretorios_pessoal($code_nome)){
                        return false;
                    }

                    $dados = $sql->fetch();

                    $_SESSION['id_user'] = $dados['id_user'];
                    if ($this->registrar_login()) {
                        return true;
                    }else {
                        ?><script>alert("conta resgistrada com sucesso")</script><?php
                    }

                    if ($sql->execute()) {
                        return true;
                    }
                }else {
                    return false;
                }              
            } else {
                return false;
            }
        }
        return false;
    }
}
?>
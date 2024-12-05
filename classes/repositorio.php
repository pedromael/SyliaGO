<?php

class repositorio extends informacoes_usuario
{
    public $diretotio;
    private $id_repositorio;
    public function __construct($id_repositorio = null) {
        parent::__construct();
        
        if ($id_repositorio != null) {
            $this->id_repositorio = $id_repositorio;
            
            $this->diretotio = "../src/userFile/".$this->user['code_nome']."/repositorio/".$this->repositorio($id_repositorio)."/";
        }
    }

    public function repositorio($id = false) {
        if ($id != false) {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdrepositorio.repositorio WHERE id_repositorio = :i");
            $sql->bindValue(":i", $id);
        } else {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdrepositorio.repositorio ORDER BY id_repositorio DESC");
        }
        $sql->execute();
         
        return $id ? $sql->fetch() : $sql->fetchAll();
    }

    public function alterar_privacidade_de_repositorio($id): void{
        (new process)->alterar_privacidade($id, "repositorio");
    }

    private function git_init($dir) {
        
        if (is_dir($dir)) 
        {
            $comando = "cd $dir && sudo git init";
            shell_exec($comando);
        } else {
            return false;
        }
        return true;
    }

    public function criar($dados) {
        // Antes de criar, verifica se o nome já está em uso
        if (!$this->verificarNomeDisponivel($dados['nome'], $_SESSION['id_user'])) {
            return false; // Nome indisponível
        }

        $dir = "../src/userFile/".$this->user['code_nome']."/repositorio/".$dados['nome'];
        if(mkdir($dir,0755, true)){
            $this->git_init($dir);
        }else{
            return false;
        }

        $sql = $this->pdo->prepare("INSERT INTO $this->bdrepositorio.repositorio(id_user, nome, descricao, data)
                                    VALUES(:i, :n, :d, NOW())");
        $sql->bindValue(":i", $_SESSION['id_user']);
        $sql->bindValue(":n", $dados['nome']);
        $sql->bindValue(":d", $dados['desc']);
        $sql->execute();

        $id = $this->pdo->lastInsertId();

        if ($dados['privacidade'] == 'privado') {
            $this->alterar_privacidade_de_repositorio($id);
        }

        return $id;
    }

    public function verificarNomeDisponivel($nome, $id_user) {
        $sql = $this->pdo->prepare("SELECT COUNT(*) as total FROM $this->bdrepositorio.repositorio 
                                    WHERE nome = :n AND id_user = :u");
        $sql->bindValue(":n", $nome);
        $sql->bindValue(":u", $id_user);
        $sql->execute();
        $result = $sql->fetch();
        return $result['total'] == 0; // Retorna true se não há repositórios com o mesmo nome
    }

    public function pegar_repositorio_info($id, $tipo) {
        $username = $this->usuario($this->repositorio($id)['id_user'])['code_nome'];
        function pegar($diretorio, $ficheiros, $dados) {
            foreach ($ficheiros as $ficheiro) {
                if ($ficheiro !== "." && $ficheiro !== "..") {
                    if (is_dir($diretorio."/".$ficheiro)) {
                        $diretorio = $diretorio."/".$ficheiro;
                        $ficheiros_1 = scandir($diretorio);
                        $dados = pegar($diretorio, $ficheiros_1, $dados);
                    } else {
                        if (!in_array(pathinfo($ficheiro, PATHINFO_EXTENSION), $dados[1])) {
                            array_push($dados[1], pathinfo($ficheiro, PATHINFO_EXTENSION));
                        }
                        $dados[0]++;
                    }
                }
            }
            return $dados;
        }

        if ($tipo == "f") { // ficheiros
            $diretorio = "../perfil/".$username."/repositorio/".$this->repositorio($id)['nome']."/";
            $ficheiros = scandir($diretorio);
            $dados = [0, array()];
            $dados = pegar($diretorio, $ficheiros, $dados);
            return $dados;
        } else if ($tipo == "r") { // repositorio
            // Implementar lógica para tipo "r", se necessário
        }
        return true;
    }

    public function apagar() {
        return true;
    }
}

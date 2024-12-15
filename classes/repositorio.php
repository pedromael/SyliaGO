<?php

class repositorio extends informacoes_usuario
{
    public $diretotio;
    private $id_repositorio;
    public $commits_recentes = [];
    public $colaboradores = [];
    public $issues = [];
    public $pull_requests = [];

    public function __construct($id_repositorio = null) {
        parent::__construct();
        
        if ($id_repositorio != null) {
            $this->id_repositorio = $id_repositorio;
            $this->diretotio = "../src/userFile/".$this->usuario()['code_nome']."/repositorio/".$this->repositorio()['nome']."/";
        }
    }

    public function repositorio($id = false,$tudo = false) {
        if ($tudo == false) {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdrepositorio.repositorio WHERE id_repositorio = :i");
            $id ? $sql->bindValue(":i", $id) : $sql->bindValue(":i", $this->id_repositorio);
        } else {
            $sql = $this->pdo->prepare("SELECT * FROM $this->bdrepositorio.repositorio ORDER BY id_repositorio DESC");
        }
        $sql->execute();
         
        return !$tudo ? $sql->fetch() : $sql->fetchAll();
    }

    public function alterar_privacidade_de_repositorio($id): void{
        (new process)->alterar_privacidade($id, "repositorio");
    }

    private function git_init($dir, $nome) {
        if (is_dir($dir)) {
            // Inicializa o repositório Git no diretório
            $comando = "cd $dir && git init --bare $nome.git 
            && git config --global --add safe.directory $nome.git";
            
            shell_exec($comando);

            return true;
        } else {
            throw new Exception("Diretório inválido: $dir");
        }
    }
    
    public function criar($dados) 
    {
        if (!$this->verificarNomeDisponivel($dados['nome'], $_SESSION['id_user'])) {
            return false; // Nome já em uso
        }
    
        $dir = __DIR__."/../src/userFile/" . $this->usuario()['code_nome'] . "/repositorio/";
    
        // if (!mkdir($dir, 0755, true)) {
        //     throw new Exception("Erro ao criar o diretório: $dir");
        // }
    
        $this->git_init($dir, $dados['nome']);
    
        try {
            $this->pdo->beginTransaction();
    
            $sql = $this->pdo->prepare("INSERT INTO {$this->bdrepositorio}.repositorio (id_user, nome, descricao, data) 
                                        VALUES (:i, :n, :d, NOW())");
            $sql->bindValue(":i", $_SESSION['id_user'], PDO::PARAM_INT);
            $sql->bindValue(":n", $dados['nome'], PDO::PARAM_STR);
            $sql->bindValue(":d", $dados['desc'], PDO::PARAM_STR);
            $sql->execute();
    
            $id = $this->pdo->lastInsertId();
    
            if ($dados['privacidade'] == 'privado') {
                $this->alterar_privacidade_de_repositorio($id);
            }
    
            $this->pdo->commit();
    
            return $id;
        } catch (Exception $e) {
            // Reverte a transação em caso de erro
            $this->pdo->rollBack();
            throw new Exception("Erro ao criar repositório: " . $e->getMessage());
        }
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

    public function pegarCommits($limite = 10) 
    {
        if (!is_dir($this->diretotio) || !is_dir($this->diretotio . '/.git')) {
            return ["Erro: Diretório não é um repositório Git válido"];
        }

        $comando = "git --git-dir={$this->diretotio}/.git --work-tree={$this->diretotio} log -n {$limite} --pretty=format:'%h|%an|%s|%ci'";
        
        exec($comando, $output, $status);
        if ($status !== 0) {
            return ["Erro ao executar comando Git"];
        }
    
        $commits = [];
        foreach ($output as $linha) {
            list($hash, $autor, $mensagem, $data) = explode('|', $linha);
            $commits[] = [
                'hash' => $hash,
                'autor' => $autor,
                'mensagem' => $mensagem,
                'data' => $data
            ];
        }
    
        return $commits;
    }

    public function pegarProblemas($limite = 10, $id = NULL)
    {
        $sql = $this->pdo->prepare("SELECT * FROM $this->bdrepositorio.problemas WHERE id_repositorio = :id ORDER BY id_problema DESC LIMIT :l ");
        $sql->bindValue(":id", $this->id_repositorio);
        $sql->bindValue(":l", $limite);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarProblema($titulo, $descricao, $tags = NULL){
        $sql = $this->pdo->prepare("INSERT INTO $this->bdrepositorio.problemas(id_repositorio,titulo,descricao,data)
        VALUES(:r, :t, :d, NOW())");
        $sql->bindValue(":r", $this->id_repositorio);
        $sql->bindValue(":t", $titulo);
        $sql->bindValue(":d", $descricao);
        $sql->execute();

        if ($tags == NULL) {
            # code...
        }
        
        return true;
    }
}

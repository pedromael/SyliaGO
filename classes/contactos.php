<?php
class contactos extends informacoes_usuario
{
    public function pegar_contacto($id_user){
        
    }

    public function pegar_amigos($id_user,$so_id = false): array{
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

    public function pegar_sugestao($id_user): array
    {
        function comparar_peso($a,$b){
            return $b['ligacao'] - $a['ligacao'];//ordem descrescente pelo caso do b estar primeiro
        }

        $id_user = $_SESSION['id_user'];
        $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_user!=$id_user AND id_user!=4");  
        $sql->execute();

        $pessoas_sugeridas = array();

        foreach ($sql->fetch() as $row) {
            $id = $row['id_user'];
            $sqll = $this->pdo->prepare("SELECT count(*) AS total FROM contacto WHERE (id_user = $id
            AND id_user_dest = $id_user) OR (id_user = $id_user AND id_user_dest = $id)");
            $sqll->execute();
            $dados = $sqll->fetch(PDO::FETCH_ASSOC);
            if ($dados['total'] <= 0) {
                $row["ligacao"] = (new informacoes_usuario())->ligacao_entre_usuario($id);
                array_push($pessoas_sugeridas, $row);
                usort($pessoas_sugeridas,'comparar_peso');
            }
        }
        return $pessoas_sugeridas;
    }

    public function pegar_pedidos($id_usuario)
    {
        
    }

    public function HTML($id_user,$id_contacto,$nome,$imagen,$caso)                                                                                  : string
    {
        $id_contacto_criptografado = criptografar($id_contacto);
        $nome_criptografado = criptografar($nome);
        return <<<HTML
        <div class="d-flex align-items-center p-3 border-bottom shadow-sm">
            <!-- Imagem do usuário -->
            <div class="flex-shrink-0">
                <div class="rounded-circle" 
                    style="background-image: url('$imagen');
                            width: 50px; 
                            height: 50px; 
                            background-size: cover; 
                            background-position: center;">
                </div>
            </div>

            <!-- Informações do usuário -->
            <div class="ms-3 flex-grow-1">
                <h6 class="mb-1">
                    <a href="/perfil/?user=<?=criptografar($id_user)?>" class="text-decoration-none text-dark">
                        $nome
                    </a>
                </h6>
            </div>

            <!-- Botões de ação -->
            <div class="d-flex">
                <!-- Botão aceitar -->
                <a href="/contactos/?abrir=pdd&id=<?=$id_contacto_criptografado&nome=$nome_criptografado&case=aceitar" 
                class="btn btn-primary btn-sm me-2">
                    Aceitar
                </a>
                <!-- Botão eliminar -->
                <a href="./index.php?abrir=pdd&id=$id_contacto_criptografado&nome=$nome_criptografado&case=apagar" 
                class="btn btn-danger btn-sm">
                    Eliminar
                </a>
            </div>
        </div>
        HTML;
    }
}
?>
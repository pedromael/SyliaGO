<?php
class search extends informacoes_usuario
{
    private $filtro;
    private $valor;
    private $link;
    function __construct($valor,$filtro){
        parent::__construct();
        $this->filtro = $filtro;
        $this->valor = $valor;
        $this->link = conn();
    }
    private function mostrar($row) {
        if ($this->filtro == "tudo") {
            if (isset($row['tipo'])) {
                if ($row['tipo'] == "usuario") {
                    (new contactos())->pegar_contacto($row['id']);
                } elseif($row['tipo'] == "comunidade"){
                    $cmd = new comunidade;
                    $cmd->indereco = "./";
                    $id_user = $_SESSION['id_user'];
                    $id_comunidade = $row['id'];
                    $row = $cmd->comunidade($id_comunidade);
                    $sqll = mysqli_query(conn(), "SELECT count(*) as valor FROM $this->bdnome2.comunidade_integrante WHERE id_comunidade = $id_comunidade AND id_user=$id_user");
                    $sqll = mysqli_fetch_assoc($sqll);
                    if ($sqll['valor'] <= 0 && $id_user != $row['id_user']) {
                        $cmd->mostrar_comunidades($row,'sugeridas');
                    }else{
                        $cmd->mostrar_comunidades($row,'minhas');
                    }
                    ?>

                    <?php
                } elseif($row["tipo"] == "poste"){
                    $m = new postes();
                    $m->mostrar($m->poste($row['id']));
                }
            }
        }
    }
    public function procurar() {
        $sql = $this->pdo->prepare("
            SELECT u.nome AS texto, u.id_user AS id, 'usuario' AS tipo 
                FROM usuarios AS u 
                WHERE id_user = :v OR LOWER(nome) LIKE CONCAT('%', :v, '%') 
                AND id_user != :i
            UNION ALL
                SELECT c.nome AS texto, c.id_comunidade AS id, 'comunidade' AS tipo 
                FROM comunidade AS c 
                WHERE LOWER(nome) LIKE CONCAT('%', :v, '%')
            UNION ALL 
                SELECT p.texto, p.id_pbl AS id, 'poste' AS tipo 
                FROM pbl AS p 
                WHERE LOWER(texto) LIKE CONCAT('%', :v, '%')
            LIMIT 12
        ");

        $sql->bindValue(":v", strtolower($this->valor));
        $sql->bindValue(":i", $_SESSION['id_user']);
        $sql->execute();

        $c_e = false;
        foreach ($sql->fetchAll() AS $dado) {
            if ($dado['tipo'] == 'comunidade') {
                if (!$c_e) {
                    $c_e = true;
                    ?>
                    <div class="comunidades">
                        <h2>comunidades</h2>
                    <?php
                }
            }
            if ($c_e && $dado['tipo'] != 'comunidade') {
                ?>
                </div>
                <?php
                $c_e = false;
            }
            $this->mostrar($dado);
        }
        
    }

}
?>
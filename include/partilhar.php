<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

$c = new process;
$data = json_decode(file_get_contents('php://input'), true);

$id = filtro($data['id']);
$como = filtro($data['como']);
$tipo = filtro($data['tipo']);
$texto = filtro($data['descricao']);

$id_user = $_SESSION['id_user'];

try {
    $c->pdo->beginTransaction();

    if (isset($id)) {
        if (!isset($id_comunidade)) {
            $id_comunidade = 0; // Caso o id_comunidade não seja definido, setamos como 0
        }

        $id_pbl = $c->publicar($texto, $id_comunidade, NULL);

        if ($id_pbl) {
            $stmt = $c->pdo->prepare("INSERT INTO $c->bdnome2.partilha (id_partilha, id_partilhado, como, tipo) VALUES (:id_pbl, :id, :como, :tipo)");
            $stmt->bindParam(':id_pbl', $id_pbl, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':como', $como, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);

            if(!$stmt->execute()){
                throw new Exception("Erro ao inserir dados da tabela partilha.");    
            }

            $c->pdo->commit();
            echo "Publicação realizada com sucesso!";
        } else {
            throw new Exception("Erro ao realizar a publicação.");
        }
    } else {
        throw new Exception("ID não fornecido.");
    }

} catch (Exception $e) {
    $c->pdo->rollBack();
    
    echo "Erro ao realizar a publicação: " . $e->getMessage();
}
?>

<?php
class notificacoes extends process
{
    private $id_user; 

    public function __construct(){
        parent::__construct();
        $this->id_user = $_SESSION['id_user'];
    }
    private function mostrar($sql)
    {
        $imagen = pegar_foto_perfil("perfil", $sql['id_emissor']);
        $data = resumir_data($sql['data']);
        $id = $sql['id'];

        if($sql['tipo'] == "reacao" && $sql['de'] == 'poste'){
            $pbl = mysqli_query(conn(), "
                SELECT pbl.texto, pbl.id_pbl 
                FROM pbl 
                WHERE id_pbl = $id
            ");
            $pbl = mysqli_fetch_assoc($pbl);

            $reactionText = resumir_texto($pbl['texto'], 25);
            $postId = criptografar($pbl['id_pbl']);
            echo $this->renderNotification(
                $imagen,
                $sql['nome'],
                " reagiu à tua publicação",
                $reactionText,
                "/pbl/?pbl=$postId",
                "heart-fill",
                $data
            );
        } elseif ($sql['tipo'] == "comfirmado" && $sql['de'] == "perfil") {
            $profileLink = "/perfil/?user=" . criptografar($id);
            echo $this->renderNotification(
                $imagen,
                $sql['nome'],
                " aceitou teu pedido de amizade",
                "",
                $profileLink,
                "people",
                $data
            );
        } elseif ($sql['tipo'] == "comentario" && $sql['de'] == "poste") {
            $profileLink = "/pbl/cmt.php?cmt=" . criptografar($id);
            echo $this->renderNotification(
                $imagen,
                $sql['nome'],
                " comentou no teu poste",
                "",
                $profileLink,
                "chat-left-dots",
                $data
            );
        }
    }

    private function renderNotification($image, $userName, $action, $content, $link, $icon, $date)
    {
    return <<<HTML
    <div class="d-flex align-items-start p-1 mb-1 border rounded shadow-sm">
        <div class="profile-pic rounded-circle img-fluid" style="background-image: url('$image'); width: 50px; height: 50px; background-size: cover; background-position: center;"></div>
        <div class="ms-3 flex-grow-1">
            <a href="$link" class="text-decoration-none text-dark">
                <div>
                    <strong>$userName</strong>$action
                </div>
                <div class="text-muted small">$content</div>
            </a>
        </div>
        <div class="ms-auto">
            <img src="/bibliotecas/bootstrap/icones/$icon.svg" alt="" class="icon-small">
        </div>
        <div class="text-muted small ms-3">$date</div>
    </div>
    HTML;
    }

    public function procurar($tipo = false)
    {
        $query =  $this->pdo->prepare(
"SELECT 
            h.id_historico, 
            h.id, 
            h.id_emissor, 
            u.nome AS nome, 
            h.tipo, 
            h.de, 
            h.data 

        FROM $this->bdnome2.historico AS h
            INNER JOIN usuarios AS u ON (u.id_user = h.id_emissor)
        WHERE   
            h.id_receptor = :user AND h.id_emissor != :user
        ");
        $query->bindValue(":user", $_SESSION['id_user']);
        $query->execute();
        
        if ($tipo) {
            return $query->fetchAll();
        }
        foreach ($query->fetchAll() as $sql) {
            $this->mostrar($sql);
            //$this->marcar_visto($sql['id_historico'],"notific");
        }  
    }
}
?>
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
        $imagen = pegar_foto_perfil("perfil", $sql['id_user']);
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
                $sql['data']
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
                $sql['data']
            );
        } elseif ($sql['tipo'] == "comentario" && $sql['de'] == "poste") {
            $profileLink = "/perfil/?user=" . criptografar($id);
            echo $this->renderNotification(
                $imagen,
                $sql['nome'],
                " comentou no teu poste",
                "",
                $profileLink,
                "chat-left-dots",
                $sql['data']
            );
        }
    }

    private function renderNotification($image, $userName, $action, $content, $link, $icon, $date)
    {
    return <<<HTML
    <div class="d-flex align-items-start p-3 mb-3 border rounded shadow-sm">
        <div class="profile-pic rounded-circle" style="background-image: url('$image'); width: 50px; height: 50px; background-size: cover; background-position: center;"></div>
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
        $query =  $this->pdo->prepare("SELECT h.id_historico, h.id, h.id_user, emissor.nome AS nome, h.tipo, h.de, h.data FROM $this->bdnome2.historico AS h
        LEFT JOIN usuarios AS emissor ON (emissor.id_user = h.id_user)
        LEFT JOIN pbl AS p ON (p.id_pbl = h.id AND p.id_user = :user AND h.de = 'poste')
        LEFT JOIN cmt AS c ON (c.id_cmt = h.id AND c.id_user = :user AND h.de = 'comentario')
        LEFT JOIN usuarios AS u ON (u.id_user = p.id_user OR u.id_user = c.id_user)
        WHERE (p.id_pbl > 0 OR c.id_cmt > 0) AND u.id_user = :user AND emissor.id_user != :user
        ");
        $query->bindValue(":user", $_SESSION['id_user']);
        $query->execute();
        
        if ($tipo) {
            return $query->fetchAll();
        }
        $query = $query->fetchAll();
        foreach ($query as $sql) {
            $this->mostrar($sql);
            //$this->marcar_visto($sql['id_historico'],"notific");
        }  
    }
}
?>
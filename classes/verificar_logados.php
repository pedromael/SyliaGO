<?php
class verificar_logados extends conexao
{
    private $id_user;
    public function __construct(){
        parent::__construct();
        $this->id_user = $_SESSION['id_user'];
    }
    function mostrar($id_dest,$ativo = false) {
        $imagen = pegar_foto_perfil("perfil",$id_dest);
        ?>
        <a href="/mensagens/?user=<?=criptografar($id_dest)?>">
            <div class="user" style="background-image: url(<?=$imagen?>);">
                <?php if ($ativo) {?><div class="logado"></div><?php
                }else {?><div class="nao_logado"></div><?php
                }?>
            </div>
        </a>
        <?php
        return true;
    }
    public function logados()
    {
        $sql = "SELECT DISTINCT u.id_user, u.nome, MAX(id_login) AS max_id_login
        FROM usuarios u
        LEFT JOIN contacto a ON ((a.id_user = u.id_user AND a.id_user_dest = :id) 
            OR (a.id_user_dest = u.id_user AND a.id_user = :id))
        LEFT JOIN $this->bdnome2.contacto_aceite aa ON (aa.id_contacto = a.id_contacto)
        LEFT JOIN chat c ON ((c.id_user = u.id_user AND c.id_user_dest = :id) 
            OR (c.id_user_dest = u.id_user AND c.id_user = :id))
        LEFT JOIN $this->bdnome2.login AS l ON (l.id_user = u.id_user AND ABS(TIMESTAMPDIFF(SECOND, NOW(), l.data)) < 30)
        WHERE u.id_user != :id AND (aa.id_contacto = a.id_contacto OR (u.id_user = c.id_user OR u.id_user = c.id_user_dest))
        AND ((a.id_user = u.id_user AND a.id_user_dest = :id) 
            OR (a.id_user_dest = u.id_user AND a.id_user = :id))
        GROUP BY u.id_user, u.nome
        ORDER BY max_id_login DESC";

        $sql = $this->pdo->prepare($sql);
        $sql->bindValue(":id", $this->id_user);
        $sql->execute();
        $rows = $sql->fetchAll();
        $numero_de_contactos = 0;
        if ($sql->rowCount()) {
            foreach ($rows as $row) {
                $numero_de_contactos++;
                $this->mostrar($row['id_user'],$row['max_id_login']);
            }
        } else {return array();}
        return $numero_de_contactos;
    }
}

?>
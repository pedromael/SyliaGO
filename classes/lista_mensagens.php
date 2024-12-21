<?php

class lista_mensagens extends conexao
{
    private $conn;
    public $process;
    private $id_user;

    public function __construct()
    {   
        parent::__construct();
        global $bdnome2;
        $this->bdnome2 = $bdnome2; 
        $this->process = new process;
        $this->conn = conn();
        $this->id_user = $_SESSION['id_user'];
    }
    private function mostrar($sqll,$id_dest,$sms)
    {
        $imagen = pegar_foto_perfil("perfil",$id_dest);
        
        $id_user = $this->id_user;
        if (isset($sms['texto'])) {
            $n = strlen($sms['texto']);
            $tres_pontos = "";
            if ($n > 17) {
                $n = 17;
                $tres_pontos = "...";
            }
            $texto = "";
            $nn = 0;
            while ($nn != $n) {
                $texto = $texto.$sms['texto'][$nn];
                $nn++;
            }
            $texto = $texto." ".$tres_pontos;
        }
        ?>
        <a href="/mensagens/?user=<?=criptografar($id_dest)?>" class="text-decoration-none text-dark">
            <div class="d-flex align-items-center p-2 border-bottom">
                <!-- Imagem do usuário -->
                <div class="flex-shrink-0 px-1">
                    <img src="<?=$imagen?>" 
                        alt="Foto do usuári endifo" 
                        class="rounded-circle" 
                        style="width: 50px; height: 50px; object-fit: cover;">
                </div>

                <!-- Informações do usuário -->
                <div class="ms-3 flex-grow-1">
                    <!-- Nome do usuário -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fs-1">
                            <?=$sqll['nome']?>
                        </h6>
                        <!-- Quantidade de mensagens não lidas -->
                        <?php if ($this->process->verificar_qtd("user_chat", $id_dest) > 0) { ?>
                            <span class="badge bg-danger">
                                <?=$this->process->verificar_qtd("user_chat", $id_dest)?>
                            </span>
                        <?php } ?>
                    </div>

                    <!-- Última mensagem -->
                    <small class="text-muted">
                        <?php
                            if (isset($sms['texto'])) {
                                if ($sms['id_user'] == $id_user) {
                                    echo "Você: " . htmlspecialchars($texto);
                                } else {
                                    echo htmlspecialchars($texto);
                                }
                            } else {
                                echo "...";
                            }
                        ?>
                    </small>
                </div>
            </div>
        </a>
        <?php
    }
    public function getListaAmigos()
    {
        $id_user = $this->id_user;
        $sql = "SELECT DISTINCT u.id_user, u.nome FROM usuarios u
        LEFT JOIN contacto a ON ((a.id_user = u.id_user AND a.id_user_dest = $id_user) 
            OR (a.id_user_dest = u.id_user AND a.id_user = $id_user))
        LEFT JOIN $this->bdnome2.contacto_aceite aa ON (aa.id_contacto = a.id_contacto)
        LEFT JOIN chat c ON ((c.id_user = u.id_user AND c.id_user_dest = $id_user) 
            OR (c.id_user_dest = u.id_user AND c.id_user = $id_user))
        
        WHERE u.id_user != $id_user AND ((aa.id_contacto = a.id_contacto OR (u.id_user = c.id_user OR u.id_user = c.id_user_dest))
        AND ((a.id_user = u.id_user AND a.id_user_dest = $id_user) OR (a.id_user_dest = u.id_user AND a.id_user = $id_user))) OR (c.id_chat > 0)
        
        GROUP BY u.id_user, u.nome
        ORDER BY MAX(c.id_chat) DESC";

        $sql = mysqli_query($this->conn, $sql);

        $numero_de_contactos = 0;

        if ($sql) {
            $sqll = array();

            while ($row = mysqli_fetch_assoc($sql)) {
                $numero_de_contactos++;
                
                $sqll = array(
                    'id_user' => $row['id_user'],
                    'nome' => $row['nome']
                );

                $id_dest = $row['id_user'];
                $sms = mysqli_query($this->conn, "SELECT texto,id_user FROM chat WHERE 
                (id_user=$id_user AND id_user_dest=$id_dest) OR 
                (id_user=$id_dest AND id_user_dest=$id_user) 
                ORDER BY id_chat DESC LIMIT 1");
                $sms = mysqli_fetch_assoc($sms);

                $this->mostrar($sqll,$row['id_user'],$sms);
            }

            $this->conn->close();

        } else {

            $this->conn->close();
            return array();
        }
        return $numero_de_contactos;
    }
}
?>
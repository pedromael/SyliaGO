<?php
class selecionar_feed 
{
    private $postes;
    public $id;
    public $postes_encotrados;
    public $quantidade_de_postes = 5;

    public function __construct(){
        $this->postes = new postes;
    }
    public function selecionar_poste($tipo_de_feed = null){
        if ($tipo_de_feed == "perfil") {
            $this->postes->para = "perfil";
            $this->postes->oque = $this->id;
            $this->quantidade_de_postes = 10;
            $a = 0;
            while ($a <= $this->quantidade_de_postes) {
                if ($this->postes->procurar() == 404) {
                    return true;
                }
                $a++;
            }
        }elseif($tipo_de_feed == "comunidade"){
            $this->postes->para = "comunidade";
            $this->postes->oque = $this->id;
            $a = 0;
            while ($a <= $this->quantidade_de_postes) {
                if ($this->postes->procurar() == 404) {
                    $this->postes_encotrados = $a;
                    return true;
                }
                //$a++;
            }
        }else {
            $a = 0;
            $this->postes->para = "pagina_inicial";
            $this->postes->oque = "pbl";
            while ($a <= $this->quantidade_de_postes) {
                $this->postes_encotrados = $a;
                if ($this->quantidade_de_postes <= $a) {
                    return 404;
                }
                $this->postes->procurar();
                $a++;
            }
            return true;
        }
    }
}

?>
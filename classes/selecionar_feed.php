<?php
class selecionar_feed 
{
    private $postes;
    public $id;
    public $postes_encotrados;
    public $quantidade_de_postes = 10;
    public $numero_postes_globais = 3;

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
            $this->postes->oque = "poste";
            while ($a < $this->quantidade_de_postes) {
                // Verificar se devemos adicionar um post global entre os posts normais
                if (($this->numero_postes_globais > 0) && (($a + 1) % ceil($this->quantidade_de_postes / $this->numero_postes_globais) == 0)) {
                    $this->postes->procurar("global");
                    $this->numero_postes_globais--;  
                    $a++; 
                    continue;
                }
        
                if ($this->postes->procurar(NULL) == 404) {
                    $a++;
                    break;
                }
        
                $a++;
            }
        
            $this->postes_encotrados = $a;
            return true;
        }        
    }
}

?>
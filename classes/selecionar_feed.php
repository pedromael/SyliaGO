<?php
class selecionar_feed 
{
    private $postes;
    public $id;
    public $postes_encontrados;
    public $quantidade_de_postes = 10;
    public $numero_postes_globais = 3;

    public function __construct(){
        $this->postes = new postes;
    }

    public function selecionar_poste($tipo_de_feed = null){
        if (!in_array($tipo_de_feed, ['perfil', 'comunidade', 'pagina_inicial']) && $tipo_de_feed != NULL) {
            return false;
        }

        if ($tipo_de_feed == "perfil") {
            $this->postes->para = "perfil";
            $this->postes->oque = $this->id;
            $this->quantidade_de_postes = 10;
            $a = 0;
            while ($a < $this->quantidade_de_postes) {
                if ($this->postes->procurar() === false) {
                    return true;
                }
                $a++;
            }
        } elseif ($tipo_de_feed == "comunidade") {
            $this->postes->para = "comunidade";
            $this->postes->oque = $this->id;
            $a = 0;
            while ($a < $this->quantidade_de_postes) {
                if ($this->postes->procurar() === false) {
                    $this->postes_encontrados = $a;
                    return true;
                }
                $a++;
            }
        } else {
            $a = 0;
            $this->postes->para = "pagina_inicial";
            $this->postes->oque = "poste";
            while ($a < $this->quantidade_de_postes) {
                if (($this->numero_postes_globais > 0) && (($a + 1) % ceil($this->quantidade_de_postes / $this->numero_postes_globais) == 0)) {
                    $this->postes->procurar("global");
                    $this->numero_postes_globais--;
                    $a++; 
                    continue;
                }

                if ($this->postes->procurar(null) === false) {
                    break;
                }

                $a++;
            }

            $this->postes_encontrados = $a;
            return true;
        }        
    }
}
?>
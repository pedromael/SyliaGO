<?php
class conexao
{
    public $pdo;
    public $erro;
    public $bdnome = "pro_start";
    public $bdhost = "localhost";
    public $bdpass = "";
    public $bduser = "root";
    public $bdnome2 = "pro_start_outros";
    public $bdrepositorio = "pro_start_repositorios";

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:dbname=".$this->bdnome.";host=".$this->bdhost,$this->bduser,$this->bdpass);
        } catch (PDOexception $e) {
            $this->erro = $e->getMessage();
        }
    }
}
?>
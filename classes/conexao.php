<?php
class conexao
{
    public $pdo;
    public $erro;
    public $bdnome = "sylia";
    public $bdhost = "localhost";
    public $bdpass = "543216+";
    public $bduser = "pedmanue";
    public $bdnome2 = "sylia_outros";
    public $bdrepositorio = "sylia_repositorios";

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
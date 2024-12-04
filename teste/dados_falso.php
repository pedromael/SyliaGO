<?php
require "algoritimos/atalho.php";
require "algoritimos/seguranca.php";
require "bibliotecas/Faker-master/src/autoload.php";
require "algoritimos/code_nome.php";
$c = new sig_in;
$p = new process;
$f = Faker\Factory::create("pt_US");
$sessao = $_SESSION['id_user'];
for ($i=1; $i < 50; $i++) { 
    $nome = $f->name;
    $email = $f->email;
    $usa = 225;
    $angola = $usa;
    session_start();
    $c->cadastrar($nome,$email,$angola,"senha");
    $vezes_de_pbl = rand(1,10);
    for ($ii=0; $ii <= $vezes_de_pbl; $ii++) { 
        $p->publicar($f->text,0,1);
    }
    echo $nome."-".$email."-postes:".$vezes_de_pbl."<br>";
    session_destroy();
}
for ($i=2; $i < 2; $i++) {
    $_SESSION['id_user'] = $i;
    carregar_img($f->image("media/"),"perfil",0);
}
//$_SESSION['id_user'] = $sessao;
session_start();
$_SESSION['id_user'] = $sessao;
?>
<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";
unset($_SESSION['id_user']);
$c = new sig_in;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../bibliotecas/PHPMailer/src/Exception.php';
require '../bibliotecas/PHPMailer/src/PHPMailer.php';
require '../bibliotecas/PHPMailer/src/SMTP.php';
require '../classes/email.php';
$PHPMailer = new PHPMailer(true);
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="../src/img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../src/css/temas/branco.css">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../src/css/stilo.css">
    <title>Login</title>
</head>
<body id="inicio">
    <nav class="nw-100 nav_login">
        <h1><a href="./">login</a></h1>
    </nav>
    <div class="corpos">
        <div class="login_container">
            <div class="size80 center login">
                <p></p>
                <div class="img">
                    <img src="../src/img/glou_icon.png" alt="">
                </div>
                <form name="Game" method="post">
                    <fieldset class="login">
                        <div class="bg">
                            <div>
                                <p class="text-center">E-mail ou nome de usuario</p>
                                <input class="form-control" type="email" name="e" id="" required>
                            </div>
                            <p></p>
                            <div class="size80">
                                <button class="form-control button">prosseguir</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <p id="" class="text-center info_login"><a href="./">fazer login</a></p>
            </div>
        </div>
        <div class="login_conteiner_2"></div>
    </div>
    <footer class="footer">
        <div>
            <a href="#">mais info...</a>
        </div>
    </footer>
</body>
</html>
<?php
    if (isset($_POST['email'])) {
        $email = filtro($_POST['email']);
        $destinatario[0] = $email;
        $destinatario[1] = 'pedro manuel';
        $conteudo[0] = 'teste';
        $conteudo[1] = 'str(rand(1000,9999))';
        $conteudo[2] = 'str(rand(1000,9999))';
        if (!empty($email)) {
            if (enviar_email($conteudo,$destinatario,$PHPMailer)) {}
        }else {
            echo 'preencha todos os dados';
        }
    }
?>
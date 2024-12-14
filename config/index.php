<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

if (!isset($_SESSION['id_user'])) {
    ?>
    <script>
        document.location.href = '../login/';
    </script>
    <?php
}
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="../css/stilo.css">
</head>
<body>
    <nav id="nav_simples">
        <a href="../" class="link">pagina inicial</a>
    </nav>
    <div id="corpo">
        <div class="bloco_de_definic">
            <h2>Dados Pessoais</h2>
            <ul>
                <a href="dadosPessoal.php?abrir=mudarnome"><li>mudar nome</li></a>
                <a href=""><li>rever meus dados</li></a>
                <a href=""><li>mudar palavra passe</li></a>
            </ul>
        </div>
        <div class="bloco_de_definic">
            <h2>Definicoes de privacidade</h2>
            <ul>
                <a href=""><li>minhas publicacoes</li></a>
                <a href=""><li>meu perfil</li></a>
                <a href=""><li>meus codigos</li></a>
            </ul>
        </div>
        <div class="bloco_de_definic">
            <h2>Opcoes de conta</h2>
            <ul>
                <a href=""><li>eliminar ou blockear conta</li></a>
            </ul>
        </div>
    </div>
    <footer>

    </footer>
</body>
</html>
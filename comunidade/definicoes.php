<?php
 require "../algoritimos/atalho.php";
 require "../algoritimos/seguranca.php";

 if (isset($_GET['cmndd'])) {
    $id_comunidade = descriptografar($_GET['cmndd']);
    $id_user = $_SESSION['id_user'];
 } else {
    #header("location: lista.php");
    ?>
        <script>
            document.location.href = "lista.php"
        </script>
    <?php
 }
?>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="icon" href="../img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/temas/<?=pegar_tema()?>.css">
    <link rel="stylesheet" href="../css/stilo.css">
    <link rel="stylesheet" href="../css/comunidade.css">
    <link rel="stylesheet" href="../css/coder.css">
    <script src="../js/script.js"></script>
    <title>definicoes</title>
</head>
<body>
    <nav id="metade_da_nav">
        <a href="./?cmndd=<?=criptografar($id_comunidade)?>">
            <img src="../bibliotecas/bootstrap/icones/border-width.svg">
        </a>
    </nav>
    <nav>
        <h1>definicoes</h1>
    </nav>
    <div class="corpos">
        <div>
            <h2>dados</h2>
            <div class="dados">
                
            </div>
            <h2>definicoes de privacidade</h2>
            <div class="privacidade">
                <div class="container">
                    <div class="d-inline-block">
                        <p>acesso privado a comunidade</p>
                    </div>
                    <div class="d-inline-block">
                        <input type="checkbox" name="acesso" id="acesso" class="remover">
                        <label for="acesso"><div class="on_off"><div class="caso on"></div></div></label>
                    </div>
                </div>
            </div>
            <h2>definicoes de controlo</h2>
            <div class="controlo">
                <div class="container">
                    <div class="d-inline-block">
                        <p>ativar chat da comunidade</p>
                    </div>
                    <div class="d-inline-block">
                        <span><input type="checkbox" name="chat" id=""></span>
                    </div>
                </div>
                <div class="container">
                    <div class="d-inline-block">
                        <p>so eu posso postar na comunidade</p>
                    </div>
                    <div class="d-inline-block">
                    <input type="checkbox" name="acesso_poste" id="acesso" class="remover">
                        <label for="acesso_poste"><div class="on_off off"><div class="caso off"></div></div></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
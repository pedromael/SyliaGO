<?php
require "algoritimos/atalho.php";
require "algoritimos/seguranca.php";

$c = new process;
if (!isset($_SESSION['id_user'])) {
    ?>
    <script>
        document.location.href = "./";
    </script>
    <?php
}
$linguagens_programacao = mysqli_query(conn(), "SELECT * FROM $bdnome2.linguagens_programacao");
$areas_programacao = mysqli_query(conn(), "SELECT * FROM $bdnome2.areas_programacao");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="img/glou_icon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/temas/padrao.css">
    <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/stilo.css">
    <title>Mais - PRO-Start</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .caixa {
            margin-bottom: 10px;
        }
        .caixa input {
            margin-right: 10px;
        }
        .mais_dados {
            margin: 20px;
        }
        .btn-custom {
            background-color: #28a745;
            color: white;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body id="inicio">
    <nav class="nw-100 nav_login d-flex justify-content-between align-items-center">
        <strong><h1>Melhor experiência na PRO-Start</h1></strong>
        <a href="./perfil" class="btn btn-back">Voltar</a>
    </nav>
    
    <div id="corpo_inicio">
        <div class="mais_dados">
            <form action="processar_selecao.php" method="POST">
                <div class="mb-4">
                    <h3>Selecione as linguagens de programação que você usa, ou tem interesse em aprender...</h3>
                    <p>Deixe que a PRO-Start selecione o melhor feed e as melhores comunidades para você.</p>
                    <?php
                    while ($key = mysqli_fetch_assoc($linguagens_programacao)) {
                        ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="linguagem[]" value="<?=$key['id_linguagem']?>" id="<?="lp".$key['id_linguagem']?>">
                            <label class="form-check-label" for="<?="lp".$key['id_linguagem']?>"><?=$key['nome']?></label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <div class="mb-4">
                    <h3>Selecione as áreas como programador que ocupa, ou que tem interesse em exercer algum dia...</h3>
                    <?php
                    while ($key = mysqli_fetch_assoc($areas_programacao)) {
                        ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="area[]" value="<?=$key['id_area']?>" id="<?="ar".$key['id_area']?>">
                            <label class="form-check-label" for="<?="ar".$key['id_area']?>"><?=$key['nome']?></label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-custom">Enviar</button>
                </div>
            </form>
        </div>
    </div>   
    
    <script src="js/login.js"></script>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

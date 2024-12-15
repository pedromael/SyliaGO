<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";
unset($_SESSION['id_user']);
$c = new sig_in;
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="icon" href="../src/img/glou_icon.png" type="image/x-icon">
    <link href="../bibliotecas/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/src/css/temas/branco.css">
    <link rel="stylesheet" href="/src/css/login.css">
    <title>Login</title>
</head>

<script>
    function mudar_formulario() {
        login = window.document.querySelector(".corpo_login");
        cad = window.document.querySelector(".corpo_sig-in");
        changer = window.document.querySelector(".changer");

        if (login.classList.contains('d-none')) {
            cad.classList.add('d-none');
            login.classList.remove('d-none');
            changer.innerText = "criar conta";
        }else{
            login.classList.add('d-none');
            cad.classList.remove('d-none');
            changer.innerText = "fazer login";
        }
    }
</script>

<body>

    <!-- Navbar fixa -->
    <nav class="navbar fixed-top">
        <h1 class="">SyliaGO</h1>
        <div class="changer" onclick="mudar_formulario()">criar conta</div>
    </nav>
    <?php
        require "sender.php"
    ?>
    <!-- Conteúdo principal -->
    <div class="container corpo_login">

        <div class=" form-container mx-2">
            <h2 class="text-center">Login</h2>
            <form name="Game" method="post">
                <div class="text-center p-2 w-100">digite seu email e senha</div>
                <div class="form-group">
                    <input class="form-control" type="email" name="l_e" placeholder="E-mail" value="<?php if(isset($_POST['l_e'])){echo $_POST['l_e'];}?>" required>
                </div>
                <div class="form-group mt-3">
                    <input class="form-control" type="password" name="l_s" placeholder="Senha" value="<?php if(isset($_POST['l_s'])){echo $_POST['l_s'];}?>" required>
                </div>
                <button type="submit">Entrar</button>
            </form>

            <hr class="my-4">

            <!-- Botões para Redes Sociais -->
            <div class="text-center">
                <p>Ou entre com:</p>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="#" class="btn btn-danger d-flex align-items-center m-2">
                        <img src="/bibliotecas/bootstrap/icones/bell.svg" alt="Google" style="width: 20px;">
                    </a>
                    <a href="#" class="btn btn-primary d-flex align-items-center m-2">
                        <img src="/bibliotecas/bootstrap/icones/bell.svg" alt="Facebook" style="width: 20px;">
                    </a>
                    <a href="#" class="btn btn-info d-flex align-items-center m-2 text-white">
                        <img src="/bibliotecas/bootstrap/icones/bell.svg" alt="LinkedIn" style="width: 20px;">
                    </a>
                    <a href="#" class="btn btn-dark d-flex align-items-center m-2">
                        <img src="/bibliotecas/bootstrap/icones/bell.svg" alt="GitHub" style="width: 20px;">
                    </a>
                </div>
            </div>
            <p class="text-center mt-3">
                <a href="recuperar.php" class="text-dark">Esqueci minha conta</a>
            </p>
        </div>
        <div class="img-container mx-2"></div>
    </div>
    <div class="container corpo_sig-in d-none">
        <div class="img-container"></div>

        <div class="form-container">
            <h2 class="text-center">Cadastro</h2>
            <form method="post">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="n" class="form-control" value="<?php if(isset($_POST['n'])){echo $_POST['n'];}?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="e" class="form-control" value="<?php if(isset($_POST['e'])){echo $_POST['e'];}?>" required>
                </div>
                <div class="form-group">
                    <label for="pais">País de Residência</label>
                    <select id="pais" name="p" class="form-control" >
                        <?php
                        $paises = mysqli_query(conn(), "SELECT * FROM $bdnome2.pais ORDER BY nome");
                        while ($pais = mysqli_fetch_assoc($paises)) {
                            if(isset($_POST["p"])){
                                $selected = ($pais['nome'] == $_POST['p']) ? "selected" : "";
                            }else{
                                $selected = ($pais['nome'] == "Angola") ? "selected" : "";
                            }
                            echo "<option value='{$pais['id_pais']}' $selected>{$pais['nome']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="senha_sig">Senha</label>
                    <input type="password" id="senha_sig" name="s" class="form-control" value="<?php if(isset($_POST['s'])){echo $_POST['s'];}?>" required>
                </div>
                <div class="form-group">
                    <label for="senha_conf">Confirmar Senha</label>
                    <input type="password" id="senha_conf" name="cs" class="form-control" value="<?php if(isset($_POST['cs'])){echo $_POST['cs'];}?>" required>
                </div>
                <button type="submit" name="btn_cadastrar">Cadastrar</button>
            </form>
            <p class="text-center mt-3">
                <a href="" class="text-center w-100 m-2" onclick="trocar_formulario()">Já tem uma conta? Entrar</a>
            </p>
        </div>
    </div>

    <footer class="text-white text-center py-2 fixed-bottom">
        <a href="#" class="text-black text-decoration-none">Mais informações...</a>
    </footer>
</body>
</html>
<?php
if (isset($_POST['btn_cadastrar'])) {
    ?><script>mudar_formulario()</script><?php
}
?>
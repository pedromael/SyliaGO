<?php
$c = new sig_in;

if (isset($_POST['l_e'])) {
    $senha = filtro($_POST['l_s']);
    $email = filtro($_POST['l_e']);
    if (!empty($senha) && !empty($email)) {
        if ($c->logar($email,$senha)) {
            ?>
                <script>
                    document.location.href = "../"
                </script>
            <?php
        } else {
            echo "<div class='erro_ao_entrar'>dados de acesso encorreto</div>";
        }
    } else {
        echo "<div class='erro_ao_entrar'>preencha todos os campos</div>";
    }
}

if (isset($_POST['e'])) {
    $senha = filtro($_POST['s']);
    $c_senha = filtro($_POST['cs']);
    $email = filtro($_POST['e']);
    $pais = filtro($_POST['p']);
    $nome = filtro($_POST['n']);
    if (!empty($senha) && !empty($email) && !empty($pais) && !empty($nome) && !empty($c_senha)) {
        if ($senha == $c_senha) {
            if (verificar_requisito_de_seguranca_de_senha($senha)) {
                require "../algoritimos/code_nome.php";
                if ($c->cadastrar($nome, $email, $pais, $senha)) {
                    echo "<script>document.location.href = '../instrucoes/';</script>";
                } else {
                    echo "<div class='erro_ao_entrar'>Já existe um usuário com esse email</div>";
                }
            } else {
                echo "<div class='erro_ao_entrar'>Senha não atende aos requisitos mínimos de segurança</div>";
            }
        } else {
            echo "<div class='erro_ao_entrar'>As senhas não correspondem</div>";
        }
    } else {
        echo "<div class='erro_ao_entrar'>Preencha todos os campos</div>";
    }
}
?>
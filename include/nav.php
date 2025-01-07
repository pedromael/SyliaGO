<link rel="stylesheet" href="/src/css/nav.css">
<nav id="metade_da_nav" onclick="abri_fecha('#segunda_nav')">
    <i class="fa fa-bars" style="font-size: 24px;"></i>
</nav>
<nav class="px-1 py-2">
    <div class="container_nav">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col justify-content-center my-md-0 text-small">
            <li class="nav-item p-2">
                <a href="/./" class="nav-link">
                    <i class="fa fa-home" style="font-size: 28px;"></i>
                </a>
            </li>
            <li class="nav-item p-2">
                <a href="/comunidade/" class="nav-link">
                    <i class="fa fa-users" style="font-size: 28px;"></i>
                </a>
            </li>
            <li class="nav-item p-2">
                <a href="/coder/" class="nav-link p-1">
                    <button id="coder">GO</button>
                </a>
            </li>
            <li class="nav-item p-2">
                <a href="/mensagens/" class="nav-link">
                    <i class="fa fa-comment-dots" style="font-size: 28px;"></i>
                    <?php
                    if($c->verificar_qtd("chat",$_SESSION['id_user']) > 0){
                        ?>
                        <div class="info_qtd_c info_qtd_chat actualizar"><?=$c->verificar_qtd("chat",$_SESSION['id_user'])?></div>
                        <?php
                    }else {
                        ?>
                        <div class="info_qtd_chat actualizar"></div>
                        <?php
                    }
                    ?>          
                </a>
            </li>
            <li class="nav-item p-2">
                <a href="/./notific.php" class="nav-link">
                    <i class="fa fa-bell" style="font-size: 28px;"></i> <!-- Ajustado o tamanho do ícone -->
                    <?php
                    if($c->verificar_qtd("notificacao",$_SESSION['id_user']) > 0){
                        ?>
                        <div class="info_qtd_n info_qtd_notific actualizar"><?=$c->verificar_qtd("notificacao",$_SESSION['id_user'])?></div>
                        <?php
                    }else {
                        ?>
                        <div class="info_qtd_notific actualizar"></div>
                        <?php
                    }
                    ?>   
                </a>
            </li>
            </ul>
            <div class="pesquisar">
                <form action="/./search.php" method="GET">
                    <input type="search" name="valor" placeholder="em que esta pensando">
                    <button name="btn" style="background-image: url(/bibliotecas/bootstrap/icones/search.svg);"></button>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    #segunda_nav {
        z-index: 1050;
        overflow-y: auto;
    }

    ul.list-unstyled {
        padding-left: 0;
    }

    .nav-link {
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        height: 100%;
        padding: 0;
    }

    .nav-item {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Ajustes para os ícones */
    .nav-link i {
        font-size: 26px;
        vertical-align: middle;
    }

    .info_qtd_c, .info_qtd_n {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 12px;
        background-color: red;
        color: white;
        padding: 2px 6px;
        border-radius: 50%;
    }
</style>

<div id="segunda_nav" class="remover bg-light position-fixed shadow-lg" style="width: 280px; z-index: 1050;">
    <div class="container py-0 d-flex flex-column h-100">
        <!-- Perfil -->
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <a href="/perfil/?user=<?=criptografar($user['id_user'])?>">
                    <img src="<?=pegar_foto_perfil("perfil", $_SESSION['id_user'])?>" alt="" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                </a>
            </div>
            <div>
                <a href="/perfil/?user=<?=criptografar($user['id_user'])?>" class="text-decoration-none fw-bold text-dark p-1">
                    <?=$user['code_nome']?>
                </a>
            </div>
        </div>

        <!-- Links principais -->
        <ul class="list-unstyled mb-4">
            <li class="mb-2">
                <a href="/contactos/" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="bi bi-person-lines-fill pr-2"></i>
                    <span class="px-1">Encontrar Amigos</span>
                    <?php if ($c->verificar_qtd("pdd", $user['id_user']) > 0) { ?>
                        <span class="badge bg-primary ms-auto"><?=$c->verificar_qtd("pdd", $user['id_user'])?></span>
                    <?php } ?>
                </a>
            </li> 
            <li>
                <a href="/salvos.php" class="d-flex align-items-center text-decoration-none text-dark">
                    <i class="bi bi-bookmark-fill"></i>
                    <span class="px-1">Salvos</span>
                </a>
            </li>
        </ul>

        <!-- Configurações -->
        <legend class="fs-6 text-muted mb-3">Configurações</legend>
        <ul class="list-unstyled mb-4">
            <li class="mb-2">
                <a href="/config/preferencias.php" class="text-decoration-none text-dark">
                    Preferências
                </a>
            </li>
            <li class="mb-2">
                <a href="/config/" class="text-decoration-none text-dark">
                    Configurações
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="text-decoration-none text-dark">
                    Mudar Idioma
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="text-decoration-none text-dark">
                    Ajuda
                </a>
            </li>
        </ul>

        <!-- Apresentação -->
        <a href="/instrucoes/" class="text-decoration-none">
            <legend class="fs-6 text-muted">Apresentação</legend>
        </a>

        <!-- Terminar Sessão -->
        <div class="mt-auto">
            <a href="/login/" class="btn btn-danger w-100">
                <i class="fa fa-power-off me-2" style="font-size: 20px;"></i> <!-- Ajustado o tamanho do ícone -->
                Terminar Sessão
            </a>
        </div>
    </div>
</div>

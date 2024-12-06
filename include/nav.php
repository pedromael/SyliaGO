<link rel="stylesheet" href="/src/css/nav.css">
<nav id="metade_da_nav" onclick="abri_fecha('#segunda_nav')">
    <img src="/bibliotecas/bootstrap/icones/border-width.svg">
</nav>
<nav class="px-3 py-2">
    <div class="container_nav">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <ul class="nav col-12 justify-content-center my-md-0 text-small">
            <li>
                <a href="/./" class="nav-link text-secondary">
                <img src="/bibliotecas/bootstrap/icones/house.svg">
                </a>
            </li>
            <li>
                <a href="/comunidade/" class="nav-link text-white">
                <img src="/bibliotecas/bootstrap/icones/people.svg">
                </a>
            </li>
            <li>
                <a href="/coder/" class="nav-link text-white">
                    <button id="coder">GO</button>
                </a>
            </li>

            <li>
                <a href="/mensagens/" class="nav-link text-white">
                    <img src="/bibliotecas/bootstrap/icones/chat-left-dots.svg"/> 
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
            <li>
                <a href="/./notific.php" class="nav-link text-white">
                    <img src="/bibliotecas/bootstrap/icones/bell.svg"/>
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
<div id="segunda_nav" class="remover bg-light position-fixed shadow-lg" style="width: 280px; z-index: 1050;">
    <div class="container py-3 d-flex flex-column h-100">
        <!-- Perfil -->
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <a href="/perfil/?user=<?=criptografar($user['id_user'])?>">
                    <img src="<?=$imagen?>" alt="" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                </a>
            </div>
            <div>
                <a href="/perfil/?user=<?=criptografar($user['id_user'])?>" class="text-decoration-none fw-bold text-dark">
                    <?=$user['code_nome']?>
                </a>
            </div>
        </div>

        <!-- Links principais -->
        <ul class="list-unstyled mb-4">
            <li class="mb-2">
                <a href="/contactos/" class="d-flex align-items-center text-decoration-none text-dark">
                    <img src="/bibliotecas/bootstrap/icones/people.svg" alt="" class="me-2" style="width: 24px; height: 24px;">
                    <span>Encontrar Amigos</span>
                    <?php if ($c->verificar_qtd("pdd", $user['id_user']) > 0) { ?>
                        <span class="badge bg-primary ms-auto"><?=$c->verificar_qtd("pdd", $user['id_user'])?></span>
                    <?php } ?>
                </a>
            </li>
            <li>
                <a href="/salvos.php" class="d-flex align-items-center text-decoration-none text-dark">
                    <img src="/bibliotecas/bootstrap/icones/bookmark.svg" alt="" class="me-2" style="width: 24px; height: 24px;">
                    <span>Salvos</span>
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
                <img src="/bibliotecas/bootstrap/icones/power.svg" alt="" class="me-2" style="width: 20px; height: 20px;">
                Terminar Sessão
            </a>
        </div>
    </div>
</div>

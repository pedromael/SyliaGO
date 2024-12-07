<?php
require "../algoritimos/atalho.php";
require "../algoritimos/seguranca.php";

$r = new repositorio();

foreach ($r->repositorio(false, true) as $value) {
    ?>
    <a href="/coder/repositorio.php?id_repositorio=<?= criptografar($value['id_repositorio']) ?>" class="text-decoration-none">
        <div class="card mb-3 shadow-sm m-2 bg-dark" style="color: white;">
            <div class="card-body">
                <!-- Nome do projeto -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0"><?= htmlspecialchars($value['nome']) ?></h5>
                    <div class="d-flex align-items-center">
                        <div class="container">
                            <img src="/bibliotecas/bootstrap/icones/chat-dots.svg" alt="Estrelas no projeto" class="me-1" width="20" height="20">
                            <span>9</span>
                        </div>
                        <div class="container">
                            <img src="/bibliotecas/bootstrap/icones/star-fill.svg" alt="Estrelas no projeto" class="me-1" width="20" height="20">
                            <span>9</span>
                        </div>
                    </div>
                </div>

                <!-- Descrição -->
                <p class="card-text text-muted"><?= htmlspecialchars($value['descricao']) ?></p>

                <!-- Detalhes adicionais -->
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary">HTML</span>
                    <div class="d-flex align-items-center">
                        <img src="<?=pegar_foto_perfil("perfil",$value['id_user'])?>" alt="Criador" class="me-2 rounded-circle" width="30" height="30">
                        <span>19 colaboradores</span>
                    </div>
                </div>
            </div>
        </div>
    </a>
    <?php
}
?>
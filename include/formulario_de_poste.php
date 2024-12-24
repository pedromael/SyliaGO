<div class="container bg-white p-2 rounded shadow-sm my-2">
    <form action="" method="post" enctype="multipart/form-data" class="p-0 m-0">
        <div class="mb-1 d-flex align-items-start">
            <?php if(isset($id_comunidade)): ?>
                <img src="<?=pegar_foto_perfil("comunidade", $id_comunidade)?>" alt="Avatar" class="rounded-circle me-3" style="width: 40px; height: 40px;">
            <?php else:?>
                <img src="<?=pegar_foto_perfil("perfil", $_SESSION['id_user'])?>" alt="Avatar" class="rounded-circle me-3" style="width: 40px; height: 40px;">
            <?php endif;?>
            <textarea name="texto" 
                    class="form-control border-0 shadow-none" 
                    rows="3" 
                    placeholder="No que está pensando?"
                    style="resize: none;"></textarea>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <label for="input_file_pbl" class="btn btn-outline-secondary d-flex align-items-center my-auto">
                <i class="bi bi-file-earmark-image"></i> 
            </label>
            <label for="input_video_pbl" class="btn btn-outline-secondary d-flex align-items-center my-auto">
                <i class="bi bi-file-earmark"></i>
            </label>
            <input type="file" id="input_file_pbl" name="imagens[]" accept="image/*" multiple hidden>
            <input type="file" id="input_video_pbl" name="video" accept="video/*" hidden>
            <button name="fazerPoste" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-send pr-1"></i>
                Publicar
            </button>
        </div>
    </form>
</div>
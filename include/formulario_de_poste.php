<div class="container p-2 rounded shadow-sm my-2">
    <form action="" method="post" enctype="multipart/form-data" class="p-0 m-0">
        <div class="mb-1 d-flex align-items-start">
            <div class="bg-white custom-rounded p-2">
                <?php if(isset($id_comunidade)): ?>
                    <img src="<?=pegar_foto_perfil("comunidade", $id_comunidade)?>" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                <?php else:?>
                    <img src="<?=pegar_foto_perfil("perfil", $_SESSION['id_user'])?>" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px;">
                <?php endif;?>
            </div>
            <textarea name="texto" 
                    class="form-control border-0 shadow-none bg-white p-1" 
                    rows="3" 
                    maxlength="5000"
                    placeholder="No que está pensando?"
                    style="resize: none;"></textarea>
        </div>
        <div class="d-flex justify-content-between align-items-center p-1">
            <label for="input_file_pbl" class="btn d-flex align-items-center my-auto p-0">
                <i class="fa fa-image" style="font-size: 27px; color: green;"></i> 
            </label>
            <label for="input_video_pbl" class="btn d-flex align-items-center my-auto p-0">
                <i class="fa fa-video" style="font-size: 27px; color: red;"></i>
            </label>
            <input type="file" id="input_file_pbl" name="imagens[]" accept="image/*" multiple hidden>
            <input type="file" id="input_video_pbl" name="video" accept="video/*" hidden>
            <button name="fazerPoste" class="btn btn-primary d-flex align-items-center">
                <!-- Postar - -->
                <i class="fa fa-paper-plane pr-1"></i>
            </button>
        </div>
    </form>
</div>
<style>
    .custom-rounded {
        border-top-left-radius: 10px;  /* Canto superior esquerdo */
        border-bottom-left-radius: 10px;  /* Canto inferior esquerdo */
    }
</style>
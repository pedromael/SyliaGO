<?php
// Função para processar imagens
function processarImagens(array $imagens, string $diretorio): array
{
    $erros = [];

    foreach ($imagens as $imagem) {
        $imagemPath = $diretorio . $imagem['indereco'];

        try {
            $image = \Intervention\Image\ImageManagerStatic::make($imagem['tmp_name']);
            $image->resize(1920, null, function ($constraint): void {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($imagemPath, 85, "png");
        } catch (Exception $e) {
            $erros[] = $e->getMessage();
        }
    }

    return $erros;
}


use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;

function processarVideo(array $video, string $diretorio): ?string
{
    $videoPath = $diretorio . $video['nome'];

    // Instancie o objeto FFMpeg
    $ffmpeg = FFMpeg::create();

    $videoFile = $ffmpeg->open($video['tmp_name']);

    // Defina o formato de saída
    $format = new X264('libmp3lame', 'libx264');
    $format->setAudioCodec('libmp3lame')
           ->setVideoCodec('libx264')
           ->setKiloBitrate(800);

    try {
        $videoFile->save($format, $videoPath);
    } catch (\FFMpeg\Exception\ExecutableNotFoundException $e) {
        return "Erro: ffmpeg não encontrado.";
    }

    return null;
}


if (isset($_POST['btn_pbl'])) {
    $texto = filtro(text: $_POST['texto']);
    $id_comunidade = $id_comunidade ?? 0;
    $imagens = [];
    $nome_video = null;
    $erro = null;

    // Processar imagens enviadas
    if (isset($_FILES['imagens']) && $_FILES['imagens']['name'][0] != null) {
        foreach ($_FILES['imagens']['name'] as $indice => $nome) {
            $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
            $nome_img = sprintf("IMG-%d-%d-pbl-%s.%s", $indice, $_SESSION['id_user'], date("Y.m.d-H.i.s"), $ext);

            $imagens[] = [
                "indereco" => $nome_img,
                "tmp_name" => $_FILES['imagens']['tmp_name'][$indice]
            ];
        }
        $errosImagens = processarImagens($imagens, 'src/userFile/' . $user['code_nome'] . '/img/');
        if ($errosImagens) {
            $erro = implode("; ", $errosImagens);
        }
    }

    // Processar vídeo enviado
    if (isset($_FILES['video']) && $_FILES['video']['name'] != null && $erro == null) {
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        $nome_video = sprintf("VIDEO-%d-poste-%s.%s", $_SESSION['id_user'], date("Y.m.d-H.i.s"), $ext);

        $erro = processarVideo([
            'tmp_name' => $_FILES['video']['tmp_name'],
            'nome' => $nome_video
        ], 'src/userFile/' . $user['code_nome'] . '/video/');
    }

    // Processar texto ou arquivos
    if ((!empty($texto) || !empty($imagens) || $nome_video) && $erro == null) 
    {
        $id_pbl = $c->publicar($texto, $id_comunidade);

        if ($id_pbl) {
            foreach ($imagens as $imagem) {
                $c->carregar_documento($id_pbl, 'poste', $imagem['indereco']);
            }

            if ($nome_video) {
                $c->carregar_documento($id_pbl, 'poste', $nome_video, "video");
            }

            $redirectUrl = $id_comunidade > 0
                ? "index.php?cmndd={$id_comunidade}&pbl=" . criptografar($id_pbl)
                : "index.php?pbl=" . criptografar($id_pbl);
            echo "<script>window.location.href='{$redirectUrl}';</script>";
            exit;
        } else {
            echo "Ocorreu um erro ao realizar a publicação.";
        }
    }else if ($erro != null) {
        echo "<script>window.location.href='/./?pbl=erro_img?log=" . urlencode($erro) . "';</script>";
    }
}

if (isset($_POST['texto_chat'])) {
    $texto = filtro($_POST['texto_chat']);
    $id_doc = 0;
    if (!empty($texto)) {
        if ($c->enviar_mensagem($texto,$id_dest,$id_doc)) {
            #header("location: chat.php?user=".criptografar($id_dest));
            ?>
                <script>
                    document.location.href = "chat.php?user=<?=criptografar($id_dest)?>"
                </script>
            <?php
        } else {
            ?>
            <script>
                alert("algo deu errado")
            </script>
            <?php
        }    
    }
}
if (isset($_GET['eliminar_pbl'])) {
    require "algoritimos/atalho.php";
    require "algoritimos/seguranca.php";
    $c = new process;

    $id_pbl = descriptografar($_GET['eliminar_pbl']);

    if ($c->eliminar_pbl($id_pbl)) {
        ?>
            <script>
            document.location.href = "./?pbl_eliminada=true";
            </script>
        <?php
    }
}
if (isset($_POST['btn_repositorio'])) {
    if (!empty($_POST['desc']) && !empty($_POST['nome']) && !empty($_POST['privacidade'])) {
        $dados = [
            'desc' => $_POST['desc'],
            'nome' => $_POST['nome'],
            'privacidade' => $_POST['privacidade']
        ];
        if($id = $r->criar($dados)){
            ?>
            <script type="text/javascript">
                window.location.href= "repositorio.php?rep="+<?=criptografar($id)?>;
            </script>
            <?php    
        }
    }
}
?>
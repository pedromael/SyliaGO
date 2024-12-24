<?php
// Função para compactar e fazer o upload das imagens
function processarImagens(array $imagens, string $diretorio): array
{
    $erros = [];

    foreach ($imagens as $imagem) {
        $imagemPath = $diretorio . $imagem['nome_img'];

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
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Exception\ExecutableNotFoundException;
// Função para compactar e fazer o upload dos videos
function processarVideo(array $video, string $diretorio): ?string
{
    $videoPath = $diretorio . $video['nome'];

    $ffmpeg = FFMpeg::create();

    $videoFile = $ffmpeg->open($video['tmp_name']);

    // Obter as dimensões do vídeo original
    $dimensions = $videoFile->getStreams()->videos()->first()->getDimensions();
    $width = $dimensions->getWidth();
    $height = $dimensions->getHeight();

    // Ajustar a largura e altura para serem divisíveis por 2
    if ($width % 2 !== 0) {
        $width++;
    }
    if ($height % 2 !== 0) {
        $height++;
    }

    $videoFile->filters()->resize(new Dimension($width, $height));

    // Defina o formato de saída
    $format = new X264('libmp3lame', 'libx264');
    $format->setAudioCodec('libmp3lame')
           ->setVideoCodec('libx264')
           ->setKiloBitrate(800);

    try {
        $videoFile->save($format, $videoPath);
    } catch (ExecutableNotFoundException $e) {
        return "Erro: ffmpeg não encontrado.";
    } catch (\Exception $e) {
        return "Erro ao processar o vídeo: " . $e->getMessage();
    }

    return null;
}


if (isset($_POST['fazerPoste'])) {
    $texto = filtro(text: $_POST['texto']);
    $id_comunidade = $id_comunidade ?? 0;
    $imagens = [];
    $nome_video = null;
    $erro = null;

        // Processar imagens enviadas
    if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0]) && $_FILES['imagens']['error'][0] === UPLOAD_ERR_OK) {
        foreach ($_FILES['imagens']['name'] as $indice => $nome) {
            $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
            $nome_img = sprintf("IMG-%d-%d-pbl-%s.%s", $indice, $_SESSION['id_user'], date("Y.m.d-H.i.s"), $ext);
            
            $caminho = $_SERVER['DOCUMENT_ROOT'].'/src/userFile/' . $user['code_nome'] . '/img/';
            if (!is_dir($caminho)) {
                mkdir($caminho, 0777, true);
            }

            $imagens[] = [
                "nome_img" => $nome_img,
                "tmp_name" => $_FILES['imagens']['tmp_name'][$indice]
            ];
        }

        $errosImagens = processarImagens($imagens, $caminho);
        if ($errosImagens) {
            $erro = implode("; ", $errosImagens);
        }
    }

    // Processar vídeo enviado
    if (isset($_FILES['video']) && !empty($_FILES['video']['name']) && $_FILES['video']['error'] === UPLOAD_ERR_OK && $erro == null) {
        $ext = strtolower(pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION));
        $nome_video = sprintf("VIDEO-%d-poste-%s.%s", $_SESSION['id_user'], date("Y.m.d-H.i.s"), $ext);

        $caminho = $_SERVER['DOCUMENT_ROOT'].'/src/userFile/' . $user['code_nome'] . '/video/';
        if (!is_dir($caminho)) {
            mkdir($caminho, 0777, true);
        }

        $caminho_absoluto_video = $caminho.$nome_video;

        // Processar o vídeo
        $erro = processarVideo([
            'tmp_name' => $_FILES['video']['tmp_name'],
            'nome' => $nome_video
        ], $_SERVER['DOCUMENT_ROOT'] . '/src/userFile/' . $user['code_nome'] . '/video/');
    }

    // Processar texto ou/e arquivos
    if ((!empty($texto) || !empty($imagens) || $nome_video) && $erro == null) 
    {
        $id_pbl = $c->publicar($texto, $id_comunidade);

        if ($id_pbl) {
            foreach ($imagens as $imagem) {
                $c->carregar_documento($id_pbl, 'poste', $imagem['nome_img']);
            }

            if ($nome_video) {
                $c->carregar_documento($id_pbl, 'poste', $nome_video, "video");
            }

            $redirectUrl = $id_comunidade > 0
                ? "index.php?cmndd=".criptografar($id_comunidade)."&pbl=" . criptografar($id_pbl)
                : "index.php?pbl=" . criptografar($id_pbl);
            echo "<script>window.location.href='{$redirectUrl}';</script>";
            exit;
        } else {
            echo "Ocorreu um erro ao realizar a publicação.";
        }
    } else if ($erro != null) {
        $dir = $id_comunidade ? "/comunidade/?cmndd=".criptografar($id_comunidade) : "/./" ;
        echo "<script>window.location.href='$dir?erro=" . urlencode($erro) . "';</script>";
    }    
}

if (isset($_GET['eliminar_pbl'])) 
{
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
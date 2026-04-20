<?php
//define('SERVIDOR', 'http://localhost/projeto/api/');
define('SERVIDOR', 'https://sm.api.br/laguna/api/');
//usinalaguna
//La#707060
//usinalaguna.mysql.dbaas.com.br
//senha ftp Ra#707060ftpSM

function msg($msg, $status = 'success') {
  return "
    <div class='toast toast-top toast-center z-50 cursor-pointer' id='Rtoast'>
      <div class='alert alert-{$status}'>
        <span>{$msg}</span>
      </div>
    </div>";
}

function encrypt($id) {
    $key = 'mecanicojovemprograma753951';
    $iv = substr(hash('sha256', $key), 0, 16);

    return urlencode(base64_encode(
        openssl_encrypt($id, 'AES-256-CBC', $key, 0, $iv)
    ));
}

function decrypt($hash) {
    $key = 'mecanicojovemprograma753951';
    $iv = substr(hash('sha256', $key), 0, 16);

    return openssl_decrypt(
        base64_decode($hash),
        'AES-256-CBC',
        $key,
        0,
        $iv
    );
}

function validarNota($valor) {
    // Remove espaços
    $valor = trim($valor);

    // Substitui vírgula por ponto
    $valor = str_replace(',', '.', $valor);

    // Verifica se é numérico
    if (!is_numeric($valor)) {
        return false;
    }

    // Converte para float
    $valor = (float) $valor;

    // Valida faixa do DECIMAL(4,2)
    if ($valor < 0 || $valor > 99.99) {
        return false;
    }

    // Formata com 2 casas decimais
    return number_format($valor, 2, '.', '');
}

function uploadImagem(array $fileInput, string $nomeArquivo, string $pastaDestino = 'uploads/'): string|false
{
    // =========================
    // 🔒 Validação inicial
    // =========================
    if (
        empty($fileInput) ||
        $fileInput['error'] !== UPLOAD_ERR_OK ||
        !is_uploaded_file($fileInput['tmp_name'])
    ) {
        return false;
    }

    // =========================
    // 🔍 Validação real da imagem
    // =========================
    $info = getimagesize($fileInput['tmp_name']);
    if ($info === false) {
        return false;
    }

    $mime = $info['mime'];

    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png'
    ];

    if (!isset($tiposPermitidos[$mime])) {
        return false;
    }

    $extensao = $tiposPermitidos[$mime];

    // =========================
    // 📁 Criar pasta
    // =========================
    if (!is_dir($pastaDestino)) {
        if (!mkdir($pastaDestino, 0755, true) && !is_dir($pastaDestino)) {
            return false;
        }
    }

    // =========================
    // 📄 Nome seguro
    // =========================
    $nomeArquivo = preg_replace('/[^a-zA-Z0-9_-]/', '', $nomeArquivo);
    $nomeFinal = $nomeArquivo . '.' . $extensao;
    $caminhoFinal = rtrim($pastaDestino, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $nomeFinal;

    // =========================
    // 🖼️ Criar imagem (sem warning)
    // =========================
    $imagemOriginal = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($fileInput['tmp_name']),
        'image/png'  => @imagecreatefrompng($fileInput['tmp_name']),
        default      => false
    };

    if (!$imagemOriginal) {
        return false;
    }

    // =========================
    // 📐 Dimensões
    // =========================
    $larguraOriginal = imagesx($imagemOriginal);
    $alturaOriginal  = imagesy($imagemOriginal);

    if ($alturaOriginal <= 0) {
        imagedestroy($imagemOriginal);
        return false;
    }

    // =========================
    // 📏 Redimensionamento
    // =========================
    $novaAltura  = min(400, $alturaOriginal);
    $novaLargura = (int)(($larguraOriginal / $alturaOriginal) * $novaAltura);

    $novaImagem = imagecreatetruecolor($novaLargura, $novaAltura);

    // Transparência PNG
    if ($mime === 'image/png') {
        imagealphablending($novaImagem, false);
        imagesavealpha($novaImagem, true);
    }

    imagecopyresampled(
        $novaImagem,
        $imagemOriginal,
        0, 0, 0, 0,
        $novaLargura,
        $novaAltura,
        $larguraOriginal,
        $alturaOriginal
    );

    // =========================
    // 💾 Salvar imagem
    // =========================
    $salvou = match ($mime) {
        'image/jpeg' => imagejpeg($novaImagem, $caminhoFinal, 85),
        'image/png'  => imagepng($novaImagem, $caminhoFinal, 6),
        default      => false
    };


    return $salvou ? $nomeFinal : false;
}

function getFotoPadrinho($id, $pasta = '../assets/img/')
{
    $extensoes = ['jpg', 'jpeg', 'png'];

    foreach ($extensoes as $ext) {
        $caminho = $pasta . "padrinho_{$id}." . $ext;

        if (file_exists($caminho)) {
            return $caminho;
        }
    }

    return '../assets/img/padrinho-perfil.png';
}

function getFotoAfilhado($id, $pasta = '../assets/img/')
{
    $extensoes = ['jpg', 'jpeg', 'png'];

    foreach ($extensoes as $ext) {
        $caminho = $pasta . "afilhado_{$id}." . $ext;

        if (file_exists($caminho)) {
            return $caminho;
        }
    }

    return '../assets/img/user-img.png';
}

function analisarRespostas($respostas)
{
    $respostas = (array) $respostas;

    // Inicialização
    $total = 0;
    $pessimo = 0;
    $ruim = 0;
    $bom = 0;
    $otimo = 0;
    $excelente = 0;

    foreach ($respostas as $item) {

        if (!isset($item['resposta']) || !is_numeric($item['resposta'])) {
            continue;
        }

        $valor = (int) $item['resposta'];

        // Soma total
        $total += $valor;

        // Contagem por classificação
        switch ($valor) {
            case 1: $pessimo++; break;
            case 2: $ruim++; break;
            case 3: $bom++; break;
            case 4: $otimo++; break;
            case 5: $excelente++; break;
        }
    }

    // Quantidade total de respostas
    $qtd_respostas = $pessimo + $ruim + $bom + $otimo + $excelente;

    // Média e percentual
    if ($qtd_respostas > 0) {
        $media = $total / $qtd_respostas;
        $percentual = ($media / 5) * 100;
    } else {
        $media = 0;
        $percentual = 0;
    }

    return [
        'qtd_respostas' => $qtd_respostas,
        'soma' => $total,
        'media' => round($media, 2),
        'percentual' => round($percentual, 1),
        'distribuicao' => [
            'pessimo' => $pessimo,
            'ruim' => $ruim,
            'bom' => $bom,
            'otimo' => $otimo,
            'excelente' => $excelente
        ]
    ];
}
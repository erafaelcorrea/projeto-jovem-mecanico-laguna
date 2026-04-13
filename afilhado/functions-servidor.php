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

function retornarUmValor($sql)
{
    $url = SERVIDOR . "index.php?url=global/sql-return-assoc";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'sql' => $sql
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    // não precisa mais do curl_close() no PHP 8.5+

    // ❌ Erro no cURL
    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);

    // ❌ JSON inválido
    if (!is_array($data)) {
        return false;
    }

    // ❌ Status diferente de 200
    if (!isset($data['status']) || $data['status'] !== 200) {
        return false;
    }

    // ✅ Sucesso
    return $data['valor'] ?? null;
}

function retornarUmObjeto($sql)
{
    $url = SERVIDOR . "index.php?url=global/sql-return-object";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'sql' => $sql
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    // não precisa mais do curl_close() no PHP 8.5+

    // ❌ Erro no cURL
    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);

    // ❌ JSON inválido
    if (!is_array($data)) {
        return false;
    }

    // ❌ Status diferente de 200
    if (!isset($data['status']) || $data['status'] !== 200) {
        return false;
    }

    // ✅ Sucesso
    return $data['valor'] ?? null;
}

function retornarLista($sql)
{
    $url = SERVIDOR . "index.php?url=global/sql-return-array-list";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'sql' => $sql
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);

    // ❌ Erro no cURL
    if ($response === false) {
        return [];
    }

    $data = json_decode($response, true);

    // ❌ JSON inválido
    if (!is_array($data)) {
        return [];
    }

    // ❌ Status diferente de 200
    if (!isset($data['status']) || $data['status'] !== 200) {
        return [];
    }

    // ✅ Sucesso → sempre retorna array
    return $data['valor'] ?? [];
}

function responder($dados)
{
    $url = SERVIDOR . "index.php?url=global/responder";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);

    if (!isset($data['status']) || $data['status'] !== 200) {
        return false;
    }

    return $data['valor']['registro_inserido'] ?? false;
}

function atualizarBanco($sql)
{
    $url = SERVIDOR . "index.php?url=global/sql-update";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'sql' => $sql
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    // não precisa mais do curl_close() no PHP 8.5+

    // ❌ Erro no cURL
    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);

    // ❌ JSON inválido
    if (!is_array($data)) {
        return false;
    }

    // ❌ Status diferente de 200
    if (!isset($data['status']) || $data['status'] !== 200) {
        return false;
    }

    // ✅ Sucesso
    return $data['valor'] ?? null;
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

function getFotoPadrinho($id, $pasta = '../padrinho/assets/img/')
{
    $extensoes = ['jpg', 'jpeg', 'png'];

    foreach ($extensoes as $ext) {
        $caminho = $pasta . "padrinho_{$id}." . $ext;

        if (file_exists($caminho)) {
            return $caminho;
        }
    }

    return '../padrinho/assets/img/padrinho-perfil.png';
}

function getFotoAfilhado($id, $pasta = '../afilhado/assets/img/')
{
    $extensoes = ['jpg', 'jpeg', 'png'];

    foreach ($extensoes as $ext) {
        $caminho = $pasta . "afilhado_{$id}." . $ext;

        if (file_exists($caminho)) {
            return $caminho;
        }
    }

    return 'assets/img/user-img.png';
}
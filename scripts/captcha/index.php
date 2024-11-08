<?php
$width = 631;
$height = 35;
$font_size = 15;
$text = Functions::getCaptchaMonsterByCode($action);
$font = './scripts/captcha/arial.ttf';

$image = imagecreatetruecolor($width, $height);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_colors = [
    imagecolorallocate($image, 0, 0, 0),
    imagecolorallocate($image, 255, 0, 0),
    imagecolorallocate($image, 0, 255, 0),
    imagecolorallocate($image, 0, 0, 255)
];
$line_colors = [
    imagecolorallocate($image, 64, 64, 64),
    imagecolorallocate($image, 128, 128, 128)
];
$noise_colors = [
    imagecolorallocate($image, 100, 120, 180),
    imagecolorallocate($image, 180, 200, 220)
];
imagefill($image, 0, 0, $bg_color);

$text_length = strlen($text);

if ($text_length > 0) {
    // Calcular a largura de um caractere
    $bbox = imagettfbbox($font_size, 0, $font, 'A');
    $char_width = $bbox[2] - $bbox[0];

    // Espaço total disponível para o texto
    $padding = 60; // 10 pixels de padding em cada lado
    $available_width = $width - ($padding * 2);

    // Calculando o espaçamento
    if ($text_length > 1) {
        $total_text_width = $char_width * $text_length;
        $char_spacing = ($available_width - $total_text_width) / ($text_length - 1);
    } else {
        $char_spacing = 0; // Se há apenas um caractere, não há espaçamento entre caracteres
    }

    // Inicializa a posição do primeiro caractere
    $x_offset = $padding;

    for ($i = 0; $i < $text_length; $i++) {
        $char = strtoupper($text[$i]);
        $angle = mt_rand(-30, 30);
        $y = mt_rand($font_size, $height - 10);
        $color = $text_colors[mt_rand(0, count($text_colors) - 1)];
        imagettftext($image, $font_size, $angle, $x_offset, $y, $color, $font, $char);
        $x_offset += $char_width + $char_spacing;
    }
}

for ($i = 0; $i < 6; $i++) {
    imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $line_colors[mt_rand(0, count($line_colors) - 1)]);
}

for ($i = 0; $i < 1000; $i++) {
    imagesetpixel($image, mt_rand(0, $width), mt_rand(0, $height), $noise_colors[mt_rand(0, count($noise_colors) - 1)]);
}

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);
?>
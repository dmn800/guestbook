<?php
// captcha.php
session_start();

header('Content-type: image/png');

$captcha_text = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);

$_SESSION['captcha'] = $captcha_text;

$image = imagecreatetruecolor(100, 30);
$background_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 255, 255, 255);
$font = __DIR__ . '/arial.ttf';

imagettftext($image, 14, 0, 10, 20, $text_color, $font, $captcha_text);
imagepng($image);
imagedestroy($image);

?>
<?php
@mkdir('public/icons', 0777, true);
$sizes = [192, 512];
foreach ($sizes as $s) {
    $im = imagecreatetruecolor($s, $s);
    $bg = imagecolorallocate($im, 30, 41, 59); // #1e293b
    $fg = imagecolorallocate($im, 255, 255, 255);
    imagefill($im, 0, 0, $bg);
    imagerectangle($im, $s*0.1, $s*0.1, $s*0.9, $s*0.9, $fg);
    imagepng($im, 'public/icons/icon-'.$s.'x'.$s.'.png');
    imagedestroy($im);
}
echo 'Icons created.';

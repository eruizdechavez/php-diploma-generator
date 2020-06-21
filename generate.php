<?php

$csv_file = 'names.csv';
$background = 'background.jpg';
$signature = 'sig.png';
$font = 'ConeriaScript.ttf';

// CSV code from https://www.php.net/manual/en/function.str-getcsv.php
$csv = array_map('str_getcsv', file($csv_file));
array_walk($csv, function (&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
});
array_shift($csv);

$day = date('j');
$month = date('F');
$year = date('Y');

$image = imagecreatefromjpeg($background);
$color = imagecolorallocate($image, 0, 0, 0);
$width = imagesx($image);
$signature = imagecreatefrompng($signature);
$signature_width = imagesx($signature);
$signature_height = imagesy($signature);

foreach ($csv as $row) {
    $name = $row['Name'];
    $reason = $row['Reason'];

    $image = imagecreatefromjpeg($background);
    $name_box = imagettfbbox(40, 0, $font, $name);
    $reason_box = imagettfbbox(40, 0, $font, $reason);

    imagettftext($image, 40, 0, ($width - $name_box[2]) / 2, 635, $color, $font, $name);
    imagettftext($image, 40, 0, ($width - $reason_box[2]) / 2, 790, $color, $font, $reason);
    imagettftext($image, 32, 0, 400, 895, $color, $font, $day);
    imagettftext($image, 32, 0, 600, 895, $color, $font, $month);
    imagettftext($image, 32, 0, 600, 975, $color, $font, $year);

    imagecopy($image, $signature, 400, 980, 0, 0, $signature_width, $signature_height);

    imagejpeg($image, "diplomas/$name.jpg");
    imagedestroy($image);
}

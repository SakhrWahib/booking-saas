<?php
// ملف الشعار المباشر - logo1
header('Content-Type: image/png');
header('Cache-Control: public, max-age=31536000'); // كاش لمدة سنة
header('Access-Control-Allow-Origin: *');

$logoPath = __DIR__ . '/logo1.png';

if (file_exists($logoPath)) {
    readfile($logoPath);
} else {
    // إذا لم يوجد الشعار، أنشئ شعار بسيط
    $image = imagecreate(200, 200);
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    // رسم دائرة بسيطة
    imagefilledellipse($image, 100, 100, 150, 150, $black);
    imagefilledellipse($image, 100, 100, 140, 140, $white);
    
    // إضافة نص
    imagestring($image, 5, 70, 90, 'كات شوب', $black);
    
    imagepng($image);
    imagedestroy($image);
}
?>

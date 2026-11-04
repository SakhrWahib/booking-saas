<?php
// ملف البنر المحسن للسرعة - مع دعم WebP
header('Vary: Accept');

// فحص دعم WebP
if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'image/webp') !== false) {
    header('Content-Type: image/webp');
    $localPath = __DIR__ . '/banar.webp';
    
    if (file_exists($localPath)) {
        $imageData = file_get_contents($localPath);
        header('Content-Length: ' . strlen($imageData));
        header('Cache-Control: public, max-age=31536000, immutable');
        header('Access-Control-Allow-Origin: *');
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        echo $imageData;
        exit;
    }
}

// Fallback للـ PNG
header('Content-Type: image/png');
header('Cache-Control: public, max-age=31536000, immutable');
header('Access-Control-Allow-Origin: *');
header('Vary: Accept-Encoding');
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));

// استخدام الصورة المحلية مباشرة - أسرع
$localPath = __DIR__ . '/banar.png';

if (file_exists($localPath)) {
    // قراءة وإرسال الصورة بسرعة - مع الحفاظ على الجودة
    $imageData = file_get_contents($localPath);
    header('Content-Length: ' . strlen($imageData));
    echo $imageData;
} else {
    // إنشاء بنر بسيط وسريع جداً
    $image = imagecreate(600, 300);
    $white = imagecolorallocate($image, 255, 255, 255);
    $pink = imagecolorallocate($image, 236, 72, 153);
    $purple = imagecolorallocate($image, 168, 85, 247);
    
    imagefill($image, 0, 0, $purple);
    imagestring($image, 5, 200, 120, 'كات شوب', $white);
    imagestring($image, 3, 150, 150, 'خدمات العناية بالحيوانات', $white);
    
    imagepng($image, null, 9);
    imagedestroy($image);
}
?>

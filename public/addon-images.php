<?php
// ملف صور الإضافات المباشر
header('Content-Type: image/png');
header('Cache-Control: public, max-age=31536000'); // كاش لمدة سنة
header('Access-Control-Allow-Origin: *');

// الحصول على اسم الإضافة من المعامل
$addonName = isset($_GET['name']) ? $_GET['name'] : '';

// تحديد اسم الصورة بناءً على اسم الإضافة
$imageName = '';

// قائمة الإضافات مع صورها المقابلة
$addonImages = [
    'كات ايديا شامبو للقطط بعطر الصنوبر 236مل' => 'cat1.png',
    'كات ايديا شامبو جاف رغوة للقطط بعطور متنوعة ١00 مل' => 'cat2.png',
    'كات ايديا معطر لفرو القطط 120مل' => 'cat3.png',
    'ماكينة حلاقة قطط وكلاب BTM' => 'cat4.png',
    'مقص اظافر مع مبرد بالاوان عشوائية' => 'cat5.png',
    'مشط ضغاط' => 'cat6.png',
    'بودرة بالشاي الاسود للقطط 300 مل' => 'cat7.png',
    'قفاز تدليك - زولكس' => 'cat9.png'
];

// البحث عن الصورة المناسبة - فقط للإضافات المحددة
$searchPatterns = [
    'كات ايديا شامبو للقطط بعطر الصنوبر' => 'cat1.png',
    'كات ايديا شامبو جاف رغوة' => 'cat2.png',
    'كات ايديا معطر لفرو' => 'cat3.png',
    'ماكينة حلاقة قطط وكلاب' => 'cat4.png',
    'مقص اظافر مع مبرد' => 'cat5.png',
    'مشط ضغاط' => 'cat6.png',
    'بودرة بالشاي الاسود' => 'cat7.png',
    'قفاز تدليك' => 'cat9.png'
];

$imageName = '';
foreach ($searchPatterns as $pattern => $image) {
    if (strpos($addonName, $pattern) !== false) {
        $imageName = $image;
        break;
    }
}

// إذا لم توجد الإضافة في القائمة المحددة، لا تعرض صورة
if (empty($imageName)) {
    // إرجاع صورة شفافة 1x1 بكسل
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=31536000');
    header('Access-Control-Allow-Origin: *');
    
    $image = imagecreate(1, 1);
    $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $transparent);
    imagepng($image);
    imagedestroy($image);
    exit;
}

// مسار الصورة
$imagePath = __DIR__ . '/' . $imageName;

if ($imageName && file_exists($imagePath)) {
    // عرض الصورة الموجودة
    readfile($imagePath);
} else {
    // إذا لم توجد الصورة، أنشئ صورة افتراضية
    $image = imagecreate(200, 200);
    $white = imagecolorallocate($image, 255, 255, 255);
    $gray = imagecolorallocate($image, 200, 200, 200);
    $black = imagecolorallocate($image, 0, 0, 0);
    
    // خلفية رمادية
    imagefill($image, 0, 0, $gray);
    
    // رسم أيقونة المنتج
    $centerX = 100;
    $centerY = 100;
    
    // رسم مربع بسيط
    imagerectangle($image, $centerX - 40, $centerY - 40, $centerX + 40, $centerY + 40, $black);
    imagefill($image, $centerX, $centerY, $white);
    
    // إضافة نص
    imagestring($image, 3, $centerX - 30, $centerY + 50, 'إضافة', $black);
    
    imagepng($image);
    imagedestroy($image);
}
?>

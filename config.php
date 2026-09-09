<?php
declare(strict_types=1);

session_start();
date_default_timezone_set('Africa/Algiers');
header('Content-Type: text/html; charset=utf-8');

// التعرف التلقائي على بيئة التشغيل (سيرفر محلي أم استضافة سحابية)
$is_local = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'])
         || (isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']));

if ($is_local) {
    // 💻 إعدادات السيرفر المحلي (Localhost / XAMPP)
    if (!defined('DB_HOST'))     define('DB_HOST', 'localhost');
    if (!defined('DB_NAME'))     define('DB_NAME', 'if0_42600030_alaan');
    if (!defined('DB_USER'))     define('DB_USER', 'root');
    if (!defined('DB_PASS'))     define('DB_PASS', '');
    if (!defined('APP_URL'))     define('APP_URL', '/alan store');
} else {
    // 🌐 إعدادات الاستضافة الحية (Live Hosting - مثل alan.is-best.net)
    if (!defined('DB_HOST'))     define('DB_HOST', 'localhost'); // خادم MySQL بالاستضافة
    if (!defined('DB_NAME'))     define('DB_NAME', 'if0_42600030_alaan');
    if (!defined('DB_USER'))     define('DB_USER', 'if0_42600030');
    if (!defined('DB_PASS'))     define('DB_PASS', ''); // ضعي كلمة سر قاعدة بيانات الاستضافة هنا إذا لزم
    if (!defined('APP_URL'))     define('APP_URL', ''); // فارغ لأن الموقع يعمل على النطاق الرئيسي مباشرة
}

if (!defined('UPLOAD_DIR'))  define('UPLOAD_DIR', __DIR__ . '/uploads/');
if (!defined('ADMIN_EMAIL')) define('ADMIN_EMAIL', 'admin@alan.dz');

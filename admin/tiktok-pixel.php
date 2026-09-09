<?php
require_once __DIR__.'/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    foreach (['tiktok_pixel_status', 'tiktok_pixel_id'] as $k) {
        db()->prepare('INSERT INTO settings(setting_key, setting_value) VALUES(?, ?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)')->execute([$k, trim($_POST[$k] ?? '')]);
    }
    flash('success', 'تم حفظ إعدادات TikTok Pixel.');
    header('Location: ' . base('admin/tiktok-pixel.php'));
    exit;
}

$adminTitle = 'إعدادات TikTok Pixel';
include 'header.php';
?>
<div class="panel">
    <h2>تكوين TikTok Pixel</h2>
    <form class="form" method="post">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        
        <div>
            <label>حالة التفعيل</label>
            <select name="tiktok_pixel_status">
                <option value="disabled" <?=setting('tiktok_pixel_status')==='disabled'?'selected':''?>>معطل</option>
                <option value="enabled" <?=setting('tiktok_pixel_status')==='enabled'?'selected':''?>>مفعل</option>
            </select>
        </div>
        
        <div>
            <label>TikTok Pixel ID</label>
            <input type="text" name="tiktok_pixel_id" value="<?=e(setting('tiktok_pixel_id'))?>" placeholder="مثال: CD8XXX...">
        </div>

        <div class="full">
            <button class="button gold">حفظ الإعدادات</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>

<?php
require_once __DIR__.'/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    foreach (['meta_pixel_status', 'meta_pixel_id', 'meta_capi_token', 'meta_test_event_code'] as $k) {
        db()->prepare('INSERT INTO settings(setting_key, setting_value) VALUES(?, ?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)')->execute([$k, trim($_POST[$k] ?? '')]);
    }
    flash('success', 'تم حفظ إعدادات Meta Pixel.');
    header('Location: ' . base('admin/meta-pixel.php'));
    exit;
}

$adminTitle = 'إعدادات Meta Pixel';
include 'header.php';
?>
<div class="panel">
    <h2>تكوين Meta Pixel & CAPI</h2>
    <form class="form" method="post">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        
        <div>
            <label>حالة التفعيل</label>
            <select name="meta_pixel_status">
                <option value="disabled" <?=setting('meta_pixel_status')==='disabled'?'selected':''?>>معطل</option>
                <option value="enabled" <?=setting('meta_pixel_status')==='enabled'?'selected':''?>>مفعل</option>
            </select>
        </div>
        
        <div>
            <label>Meta Pixel ID</label>
            <input type="text" name="meta_pixel_id" value="<?=e(setting('meta_pixel_id'))?>" placeholder="مثال: 123456789012345">
        </div>

        <div>
            <label>Test Event Code (وضع الاختبار)</label>
            <input type="text" name="meta_test_event_code" value="<?=e(setting('meta_test_event_code'))?>" placeholder="مثال: TEST25206">
        </div>
        
        <div class="full">
            <label>Conversions API Access Token</label>
            <input type="password" name="meta_capi_token" value="<?=e(setting('meta_capi_token'))?>" placeholder="ألصق الـ Access Token هنا...">
            <small class="muted" style="display:block;margin-top:5px;">هذا الرمز مشفر ولا يظهر في الواجهة الأمامية للمتجر إطلاقاً.</small>
        </div>

        <button class="button gold">حفظ الإعدادات</button>
    </form>
</div>
<?php include 'footer.php'; ?>

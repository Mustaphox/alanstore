<?php require_once __DIR__.'/../includes/functions.php';require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    check_csrf();
    $keys = ['store_name','seo_description','whatsapp','phone','email','facebook','tiktok','pexels','ai_chat_enabled','gemini_api_key','ai_assistant_name','ai_welcome_message'];
    foreach($keys as $k) {
        db()->prepare('INSERT INTO settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)')->execute([$k,$_POST[$k]??'']);
    }
    flash('success','تم حفظ الإعدادات و SEO وخدمة الذكاء الاصطناعي بنجاح.');
    header('Location: '.base('admin/settings.php'));
    exit;
}
$adminTitle='الإعدادات و SEO والذكاء الاصطناعي';
include 'header.php';
?>
<form class="form" method="post">
    <input type="hidden" name="csrf" value="<?=csrf()?>">
    <div><label>اسم المتجر</label><input name="store_name" value="<?=e(setting('store_name'))?>"></div>
    <div><label>واتساب</label><input name="whatsapp" value="<?=e(setting('whatsapp'))?>"></div>
    <div><label>الهاتف</label><input name="phone" value="<?=e(setting('phone'))?>"></div>
    <div><label>البريد</label><input name="email" value="<?=e(setting('email'))?>"></div>
    <div><label>فيسبوك</label><input name="facebook" value="<?=e(setting('facebook'))?>"></div>
    <div><label>تيك توك</label><input name="tiktok" value="<?=e(setting('tiktok'))?>"></div>
    <div><label>Pexels</label><input name="pexels" value="<?=e(setting('pexels'))?>"></div>

    <div class="full"><label>وصف SEO العالمي</label><textarea name="seo_description"><?=e(setting('seo_description'))?></textarea></div>

    <div class="full" style="margin-top:20px; padding-top:15px; border-top:2px dashed #dedbd3;">
        <h3 style="margin:0 0 15px; color:#b49456; font-size:16px;">✦ إعدادات المساعدة الذكية (AI Chat Assistant)</h3>
    </div>

    <div>
        <label>تفعيل شات الذكاء الاصطناعي</label>
        <select name="ai_chat_enabled">
            <option value="1" <?=setting('ai_chat_enabled','1')==='1'?'selected':''?>>مفعل ✓</option>
            <option value="0" <?=setting('ai_chat_enabled','1')==='0'?'selected':''?>>معطل</option>
        </select>
    </div>
    <div>
        <label>اسم المساعدة الذكية</label>
        <input name="ai_assistant_name" value="<?=e(setting('ai_assistant_name', 'مستشارة ALAN الذكية'))?>">
    </div>

    <div class="full">
        <label>مفتاح Google Gemini API Key (اختياري)</label>
        <input name="gemini_api_key" type="password" value="<?=e(setting('gemini_api_key'))?>" placeholder="AIzaSy...">
        <small style="color:#777; display:block; margin-top:4px;">الشات يعمل فوراً بمحرك المتجر الذكي المدمج، وإذا أردتِ تفعيل الذكاء التوليدي الفائق لـ Gemini يمكنكِ وضع المفتاح هنا.</small>
    </div>

    <div class="full">
        <label>رسالة الترحيب الأولى للمساعدة الذكية</label>
        <textarea name="ai_welcome_message" rows="2"><?=e(setting('ai_welcome_message', 'مرحباً بكِ في ALAN! 🌸 أنا مستشارتكِ الذكية، متواجدة لمساعدتكِ في اختيار العباية المناسبة، معرفة تفاصيل المقاسات، والتوصيل لكافة الولايات.'))?></textarea>
    </div>

    <div class="full">
        <button class="button gold">حفظ كافة الإعدادات</button>
    </div>
</form>
<?php include 'footer.php';?>

</main>
<footer class="site-footer">
    <div class="footer-brand">
        <a class="brand" href="<?=base()?>">
            <picture>
                <source srcset="<?=base('logo.webp')?>" type="image/webp">
                <img src="<?=base('logo.png')?>" alt="ALAN" width="34" height="34" loading="lazy">
            </picture>
            <span>ALAN</span>
        </a>
        <p class="footer-tagline">عبايات فاخرة تعبّر عن أصالتكِ وأناقتكِ في كل مناسبة. توصيل لـ 58 ولاية والدفع عند الاستلام.</p>
        <div class="footer-social-links">
            <a href="<?=e(setting('whatsapp','https://wa.me/213665309431'))?>" target="_blank" rel="noopener" class="footer-badge-link">واتساب</a>
            <?php if($fb=setting('facebook')):?><a href="<?=e($fb)?>" target="_blank" rel="noopener" class="footer-badge-link">فيسبوك</a><?php endif;?>
            <?php if($tt=setting('tiktok')):?><a href="<?=e($tt)?>" target="_blank" rel="noopener" class="footer-badge-link">تيك توك</a><?php endif;?>
            <?php if($px=setting('pexels')):?><a href="<?=e($px)?>" target="_blank" rel="noopener" class="footer-badge-link">Pexels</a><?php endif;?>
        </div>
    </div>

    <div class="footer-nav-col">
        <h4>تسوّقي</h4>
        <a href="<?=base('shop.php')?>">كل العبايات</a>
        <a href="<?=base('shop.php?new=1')?>">وصل حديثاً</a>
        <a href="<?=base('wishlist.php')?>">قائمة المفضلة</a>
    </div>

    <div class="footer-nav-col">
        <h4>خدمة العملاء</h4>
        <a href="<?=base('track-order.php')?>">تتبّع الطلب</a>
        <a href="<?=base('faq.php')?>">الأسئلة الشائعة</a>
        <a href="<?=base('return-policy.php')?>">الاستبدال والاسترجاع</a>
    </div>

    <div class="footer-nav-col footer-contact-col">
        <h4>تواصلي معنا</h4>
        <a href="<?=e(setting('whatsapp','https://wa.me/213665309431'))?>" target="_blank" rel="noopener">محادثة واتساب</a>
        <a href="<?=base('contact.php')?>">نموذج المراسلة</a>
        <span class="footer-phone-text" dir="ltr">📞 <?=e(setting('phone','0550 000 000'))?></span>
    </div>

    <div class="footer-copyright">
        <p>© <?=date('Y')?> ALAN — جميع الحقوق محفوظة.</p>
        <span class="mustox-credit">تصميم وتطوير <a href="https://wa.me/213665309431" target="_blank" rel="noopener">mustox dev</a></span>
    </div>
</footer>
<a class="wa wa-float-btn" href="<?=e(setting('whatsapp','https://wa.me/213665309431'))?>" target="_blank" rel="noopener noreferrer" aria-label="تواصلي معنا عبر واتساب" title="تواصلي معنا عبر واتساب">
    <span class="wa-tooltip">تواصلي معنا</span>
    <svg class="wa-svg" viewBox="0 0 32 32" width="30" height="30" fill="currentColor" aria-hidden="true">
        <path d="M16.02 2C8.28 2 2 8.28 2 16.02c0 2.58.7 5.1 2.03 7.3L2 30l6.9-1.99c2.14 1.22 4.58 1.87 7.12 1.87 7.74 0 14.02-6.28 14.02-14.02C30.04 8.28 23.76 2 16.02 2zm0 25.64c-2.22 0-4.38-.6-6.27-1.74l-.45-.27-4.63 1.33 1.35-4.51-.3-.47c-1.25-1.99-1.92-4.29-1.92-6.66 0-6.75 5.49-12.24 12.24-12.24 6.75 0 12.24 5.49 12.24 12.24 0 6.75-5.49 12.24-12.24 12.24zm6.71-9.18c-.37-.18-2.18-1.08-2.52-1.2-.34-.12-.58-.18-.83.18-.24.37-.95 1.2-1.17 1.45-.21.24-.43.27-.8.09-.37-.18-1.55-.57-2.96-1.83-1.09-.98-1.84-2.19-2.05-2.56-.21-.37-.02-.57.16-.75.17-.16.37-.43.55-.64.18-.21.24-.37.37-.61.12-.24.06-.46-.03-.64-.09-.18-.83-2-.1.14-2.74-.3-.72-.61-.62-.83-.63l-.71-.01c-.24 0-.64.09-.98.46-.34.37-1.29 1.26-1.29 3.07 0 1.81 1.32 3.56 1.5 3.8.18.24 2.6 3.97 6.3 5.56.88.38 1.57.61 2.1.78.89.28 1.69.24 2.33.15.71-.11 2.18-.89 2.49-1.75.31-.86.31-1.6.21-1.75-.09-.15-.34-.24-.71-.43z"/>
    </svg>
</a>
<?php include __DIR__ . '/ai-chat.php'; ?>
<style>
.fomo-popup, .cart-reminder-popup { position: fixed; bottom: 20px; left: -350px; width: 300px; background: #fff; border-right: 4px solid var(--brand); box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 15px; border-radius: 8px; z-index: 9999; font-family: inherit; font-size: 14px; transition: left 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); direction: rtl; }
.fomo-popup.show, .cart-reminder-popup.show { left: 20px; }
.cart-reminder-popup { border-right: 4px solid var(--ink); text-align: center; }
@media(max-width:768px) {
    .fomo-popup, .cart-reminder-popup { width: calc(100% - 40px); left: 20px; bottom: -150px; }
    .fomo-popup.show { bottom: 90px; }
    .cart-reminder-popup.show { bottom: 90px; }
}
</style>
<script>window.ALAN={base:'<?=base()?>',csrf:'<?=csrf()?>'};</script><script src="<?=base('js/app.js')?>" defer></script>
<?php if(setting('ai_chat_enabled', '1') !== '0'): ?><script src="<?=base('js/ai-chat.js?v='.(file_exists(__DIR__.'/../js/ai-chat.js')?filemtime(__DIR__.'/../js/ai-chat.js'):'1.0'))?>" defer></script><?php endif; ?>
<?php $fomo_products = db()->query('SELECT name FROM products WHERE status="active" LIMIT 10')->fetchAll(PDO::FETCH_COLUMN); $c_count = cart_count(); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const fomoNames = ['سارة', 'فاطمة', 'أمينة', 'مريم', 'خديجة', 'ياسمين', 'آية', 'شيماء', 'إيمان', 'زينب'];
    const fomoCities = ['الجزائر العاصمة', 'وهران', 'قسنطينة', 'عنابة', 'سطيف', 'تلمسان', 'باتنة', 'بجاية', 'البليدة', 'سكيكدة'];
    const fomoProducts = <?=json_encode($fomo_products??[])?>;
    if (fomoProducts.length > 0) {
        setInterval(() => {
            if(Math.random() > 0.5 || document.querySelector('.cart-reminder-popup')) return;
            const name = fomoNames[Math.floor(Math.random() * fomoNames.length)];
            const city = fomoCities[Math.floor(Math.random() * fomoCities.length)];
            const prod = fomoProducts[Math.floor(Math.random() * fomoProducts.length)];
            const time = Math.floor(Math.random() * 59) + 1;
            const popup = document.createElement('div');
            popup.className = 'fomo-popup';
            popup.innerHTML = `<strong>${name} من ${city}</strong> اشترت للتو<br><span style="color:var(--brand)">${prod}</span><br><small style="color:var(--text)">منذ ${time} دقيقة</small>`;
            document.body.appendChild(popup);
            setTimeout(() => popup.classList.add('show'), 100);
            setTimeout(() => { popup.classList.remove('show'); setTimeout(() => popup.remove(), 500); }, 5000);
        }, 15000);
    }
    const cartCount = <?=$c_count?>;
    if (cartCount > 0 && window.location.pathname.indexOf('checkout.php') === -1 && window.location.pathname.indexOf('cart.php') === -1) {
        setTimeout(() => {
            const popup = document.createElement('div');
            popup.className = 'cart-reminder-popup';
            popup.innerHTML = `🛍️ <strong>مرحباً بكِ مجدداً!</strong><br>لديكِ ${cartCount} منتجات تنتظرك في السلة.<br><a href="<?=base('checkout.php')?>" class="btn" style="display:block;margin-top:10px;padding:8px;font-size:14px;">إتمام الطلب الآن</a><button class="close-btn" style="position:absolute;top:5px;left:10px;background:none;border:none;font-size:18px;cursor:pointer;color:var(--text);">&times;</button>`;
            document.body.appendChild(popup);
            popup.querySelector('.close-btn').onclick = () => { popup.classList.remove('show'); setTimeout(() => popup.remove(), 500); };
            setTimeout(() => popup.classList.add('show'), 100);
        }, 10000);
    }
});
</script>
</body></html>

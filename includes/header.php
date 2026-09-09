<?php require_once __DIR__.'/functions.php'; $title=$title??setting('store_name','ALAN'); $description=$description??setting('seo_description','عبايات الآن الفاخرة في الجزائر'); ?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="description" content="<?=e($description)?>"><meta property="og:title" content="<?=e($title)?>"><link rel="canonical" href="<?=e(base(basename($_SERVER['PHP_SELF'])))?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?=base('favicon-32.png')?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?=base('apple-touch-icon.png')?>">
<meta name="theme-color" content="#b8860b">
<title><?=e($title)?> | ALAN</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=DM+Serif+Display:ital@0;1&family=Poppins:wght@400;600&display=swap" rel="stylesheet"><link rel="stylesheet" href="<?=base('css/app.css?v='.(file_exists(__DIR__.'/../css/app.css')?filemtime(__DIR__.'/../css/app.css'):'1.0'))?>"><?php if(setting('ai_chat_enabled', '1') !== '0'): ?><link rel="stylesheet" href="<?=base('css/ai-chat.css?v='.(file_exists(__DIR__.'/../css/ai-chat.css')?filemtime(__DIR__.'/../css/ai-chat.css'):'1.0'))?>"><?php endif; ?>
<?php
$pixel_status = setting('meta_pixel_status');
$pixel_id = setting('meta_pixel_id');
if ($pixel_status === 'enabled' && $pixel_id) {
    $pv_event_id = uniqid('pv_');
    send_meta_capi_event('PageView', $pv_event_id);
    $search_event_id = null;
    if (basename($_SERVER['PHP_SELF']) === 'search.php' && !empty($_GET['q'])) {
        $search_event_id = uniqid('sc_');
        send_meta_capi_event('Search', $search_event_id, ['search_string' => $_GET['q']]);
    }
?>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '<?=e($pixel_id)?>');
fbq('track', 'PageView', {}, {eventID: '<?=e($pv_event_id)?>'});
<?php if ($search_event_id): ?>
fbq('track', 'Search', {search_string: '<?=e($_GET['q'])?>'}, {eventID: '<?=e($search_event_id)?>'});
<?php endif; ?>
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?=e($pixel_id)?>&ev=PageView&noscript=1" /></noscript>
<!-- End Meta Pixel Code -->
<?php } 
$tt_status = setting('tiktok_pixel_status');
$tt_id = setting('tiktok_pixel_id');
if ($tt_status === 'enabled' && $tt_id) {
?>
<!-- TikTok Pixel Code -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e};ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=i+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};
  ttq.load('<?=e($tt_id)?>');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- End TikTok Pixel Code -->
<?php } ?>
</head><body><?php $active_promo = null; try { $active_promo = db()->query("SELECT code, discount_value, discount_type FROM promo_codes WHERE status='active' AND show_in_header=1 AND (expires_at IS NULL OR expires_at > NOW()) AND (usage_limit IS NULL OR used_count < usage_limit) ORDER BY id DESC LIMIT 1")->fetch(); } catch (\Throwable $e) {} if ($active_promo): $promo_text = $active_promo['discount_type'] === 'percentage' ? $active_promo['discount_value'] . '%' : $active_promo['discount_value'] . ' د.ج'; ?><div class="announcement" style="background:var(--ink); color:#fff; font-weight:bold; letter-spacing:0.5px;">🎁 استخدم الكود <span style="background:var(--brand); color:#fff; padding:2px 8px; border-radius:4px; margin:0 4px; letter-spacing:1px;"><?=e($active_promo['code'])?></span> للحصول على خصم بقيمة <?=e($promo_text)?>! 🎁</div><?php else: ?><div class="announcement">توصيل لكل الولايات  ·  الدفع عند الاستلام  ·  استبدال خلال 48 ساعة</div><?php endif; ?><?php include __DIR__.'/navbar.php'; ?><main><?php if($m=flash('success')):?><div class="flash success"><?=e($m)?></div><?php endif;if($m=flash('error')):?><div class="flash error"><?=e($m)?></div><?php endif;?>

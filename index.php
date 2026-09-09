<?php require_once __DIR__.'/includes/functions.php';$title='ALAN | عبايات تعبّر عن أناقتك';$hero=db()->query('SELECT * FROM hero WHERE is_active=1 ORDER BY id DESC LIMIT 1')->fetch()?:[];$categories=db()->query('SELECT * FROM categories WHERE status="active" ORDER BY sort_order LIMIT 3')->fetchAll();$products=db()->query('SELECT * FROM products WHERE status="active" ORDER BY featured DESC,created_at DESC LIMIT 4')->fetchAll();include __DIR__.'/includes/header.php';?>
<?php
$ytId = !empty($hero['video_enabled']) && !empty($hero['video_url']) ? extract_youtube_id($hero['video_url']) : null;
$heroImage = e($hero['fallback_image'] ?? 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=85');
?>

<section class="hero-lux">
  <!-- Background Media -->
  <div class="hero-lux-bg">
    <?php if ($ytId): ?>
      <img src="<?=$heroImage?>" alt="ALAN" class="hero-lux-img" fetchpriority="high" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;">
      <div class="hero-yt-wrap" id="hero-yt-wrap" data-yt-id="<?=e($ytId)?>" style="position:absolute; inset:0; overflow:hidden; pointer-events:none; z-index:1; opacity:0; transition: opacity 1.8s ease;"></div>
      <script>
      window.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
          var wrap = document.getElementById('hero-yt-wrap');
          if (wrap && wrap.dataset.ytId) {
            var ifr = document.createElement('iframe');
            ifr.src = 'https://www.youtube.com/embed/' + encodeURIComponent(wrap.dataset.ytId) + '?autoplay=1&mute=1&loop=1&controls=0&playlist=' + encodeURIComponent(wrap.dataset.ytId) + '&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&disablekb=1&fs=0&playsinline=1';
            ifr.frameBorder = '0';
            ifr.allow = 'autoplay; encrypted-media';
            ifr.setAttribute('loading', 'lazy');
            ifr.style.cssText = 'position:absolute;top:50%;left:50%;width:100vw;height:56.25vw;min-height:100%;min-width:177.78vh;transform:translate(-50%,-50%) scale(1.6);pointer-events:none;';
            ifr.onload = function() { wrap.style.opacity = '1'; };
            wrap.appendChild(ifr);
          }
        }, 800);
      });
      </script>
    <?php elseif (!empty($hero['video_enabled']) && !empty($hero['video_url'])): ?>
      <video autoplay muted loop playsinline class="hero-lux-video">
        <source src="<?=e($hero['video_url'])?>">
      </video>
    <?php else: ?>
      <img src="<?=$heroImage?>" alt="ALAN" class="hero-lux-img" fetchpriority="high">
    <?php endif; ?>
    <div class="hero-lux-overlay"></div>
  </div>

  <!-- Content -->
  <div class="hero-lux-content">
    <div class="hero-lux-inner">

      <!-- Eyebrow -->
      <div class="hero-lux-eyebrow">
        <span class="hero-lux-line"></span>
        <span><?=e($hero['subtitle'] ?? 'مجموعة ربيع وصيف 2026')?></span>
        <span class="hero-lux-line"></span>
      </div>

      <!-- Title -->
      <h1 class="hero-lux-title">
        <?=nl2br(e($hero['title'] ?? "أناقة تروي\nحكايتكِ."))?>
      </h1>

      <!-- Desc -->
      <p class="hero-lux-desc"><?=e($hero['description'] ?? 'عبايات استثنائية صُممت لترافقكِ في كل لحظة، بخامات راقية وتفاصيل تمنحكِ حضوراً لا يُنسى.')?></p>

      <!-- CTA -->
      <div class="hero-lux-cta">
        <a class="hero-btn-primary" href="<?=base($hero['cta_url'] ?? 'shop.php')?>"><?=e($hero['cta_text'] ?? 'اكتشفي المجموعة')?> ←</a>
        <a class="hero-btn-ghost" href="<?=base('about.php')?>">قصتنا</a>
      </div>

      <!-- Stats -->
      <div class="hero-lux-stats">
        <div class="hero-stat">
          <strong>+12K</strong>
          <span>عميلة تثق بنا</span>
        </div>
        <div class="hero-stat-sep"></div>
        <div class="hero-stat">
          <strong>4.9 ★</strong>
          <span>تقييمات حقيقية</span>
        </div>
        <div class="hero-stat-sep"></div>
        <div class="hero-stat">
          <strong>58</strong>
          <span>ولاية جزائرية</span>
        </div>
      </div>

    </div>
  </div>

  <!-- Scroll hint -->
  <div class="hero-scroll-hint">
    <span>اكتشفي</span>
    <div class="hero-scroll-line"></div>
  </div>
</section>

<!-- Animated Ticker -->
<div class="ticker-lux" aria-hidden="true">
  <div class="ticker-lux-track">
    <?php for($t=0;$t<3;$t++): ?>
    <span>خامات منتقاة بعناية</span><span class="ticker-dot">✦</span>
    <span>توصيل إلى جميع ولايات الجزائر</span><span class="ticker-dot">✦</span>
    <span>الدفع عند الاستلام</span><span class="ticker-dot">✦</span>
    <span>استبدال سهل خلال 48 ساعة</span><span class="ticker-dot">✦</span>
    <?php endfor; ?>
  </div>
</div>

<!-- Categories -->
<section class="section">
  <div class="heading">
    <div>
      <p class="eyebrow">تسوّقي حسب ذوقكِ</p>
      <h2>كل مناسبة، <em>لها إطلالتها</em></h2>
    </div>
    <a class="link" href="<?=base('shop.php')?>">كل التصنيفات ←</a>
  </div>
  <div class="categories">
    <?php foreach($categories as $c): ?>
    <a class="category reveal-on-scroll" href="<?=base('shop.php?category='.$c['id'])?>">
      <img loading="lazy" decoding="async" src="<?=e($c['image'])?>" alt="<?=e($c['name'])?>">
      <span><?=e($c['name'])?> ←</span>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- Products -->
<section class="section products-bg">
  <div class="heading">
    <div>
      <p class="eyebrow">اختيارات الآن</p>
      <h2>قطع تُشبه <em>الترف</em></h2>
    </div>
    <a class="link" href="<?=base('shop.php')?>">عرض الكل ←</a>
  </div>
  <div class="grid">
    <?php foreach($products as $p) include __DIR__.'/views/product-card.php'; ?>
  </div>
</section>

<section class="feature">
  <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=80" alt="أناقة ALAN">
  <div class="feature-copy">
    <p class="eyebrow">فلسفة الآن</p>
    <h2>التفاصيل الصغيرة تصنع <em>فرقاً كبيراً.</em></h2>
    <p>نؤمن أن العباية ليست مجرد قطعة ملابس، بل لغة شخصية تعبّرين بها عن نفسك. لذلك نختار كل خيط وكل قصة لتمنحكِ ثقة لا تُضاهى.</p>
    <a class="link" href="<?=base('about.php')?>">اكتشفي قصتنا ←</a>
  </div>
</section>

<section class="benefits">
  <div><span>◈</span><h3>الدفع عند الاستلام</h3><p>تسوّقي براحة تامة</p></div>
  <div><span>⌁</span><h3>توصيل لكل الجزائر</h3><p>نصل إليكِ أينما كنتِ</p></div>
  <div><span>♢</span><h3>استبدال سهل</h3><p>خلال 48 ساعة</p></div>
  <div><span>✧</span><h3>خامات فاخرة</h3><p>اختيار يليق بكِ</p></div>
</section>

<section class="newsletter">
  <p class="eyebrow" style="color:var(--gold)">كوني أول من يعلم</p>
  <h2>رسائل صغيرة، <em>أناقة كبيرة.</em></h2>
  <form method="post" action="<?=base('contact.php')?>">
    <input type="email" name="email" required placeholder="بريدك الإلكتروني">
    <button>اشتراك</button>
  </form>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>

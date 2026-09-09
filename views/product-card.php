<?php
$metaParts = array_filter([trim($p['color'] ?? ''), trim($p['fabric'] ?? '')]);
$metaText = !empty($metaParts) ? implode(' · ', $metaParts) : 'عباية راقية';
?>
<article class="product reveal-on-scroll">
    <div class="product-image">
        <?php if(!empty($p['discount']) && $p['discount'] > 0): ?>
            <span class="badge">-<?=e($p['discount'])?>%</span>
        <?php endif; ?>
        <button class="wish" data-wishlist="<?=e($p['id'])?>" aria-label="أضيفي للمفضلة">♡</button>
        <a href="<?=base('product.php?slug='.urlencode($p['slug']))?>">
            <img loading="lazy" decoding="async" src="<?=e(product_image($p))?>" alt="<?=e($p['name'])?>">
        </a>
    </div>
    <div class="product-info">
        <div class="product-meta-header">
            <h3><a href="<?=base('product.php?slug='.urlencode($p['slug']))?>"><?=e($p['name'])?></a></h3>
            <p><?=e($metaText)?></p>
        </div>
        <div class="product-price-row">
            <strong><?=money((float)$p['price'])?></strong>
            <?php if(!empty($p['old_price']) && (float)$p['old_price'] > (float)$p['price']): ?>
                <del><?=money((float)$p['old_price'])?></del>
            <?php endif; ?>
        </div>
    </div>
</article>


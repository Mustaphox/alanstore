var searchBtn = document.querySelector('.search-btn');
if(searchBtn) {
    searchBtn.addEventListener('click', function() {
        document.querySelector('.search-drawer').classList.toggle('show');
    });
}
var mobileMenu = document.querySelector('[data-mobile-menu]');
if(mobileMenu) {
    mobileMenu.addEventListener('click', function() {
        var nav = document.querySelector('[data-mobile-nav]');
        if(nav) nav.classList.toggle('show');
        mobileMenu.classList.toggle('active');
    });
}
document.querySelectorAll('[data-wishlist]').forEach(function(b) {
    b.addEventListener('click', async function() {
        let r = await fetch(ALAN.base+'api/products.php?action=wishlist',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'csrf='+ALAN.csrf+'&product_id='+b.dataset.wishlist});
        let j = await r.json();
        if(j.ok) {
            b.textContent = j.added ? '♥' : '♡';
            b.style.color = j.added ? '#b49456' : '';
        }
    });
});
document.querySelectorAll('[data-cart]').forEach(function(b) {
    b.addEventListener('click', async function() {
        let f = new FormData();
        f.append('csrf', ALAN.csrf);
        f.append('product_id', b.dataset.cart);
        let qtyEl = document.querySelector('[name=qty]');
        f.append('qty', qtyEl ? qtyEl.value : 1);
        let r = await fetch(ALAN.base+'api/orders.php?action=cart',{method:'POST',body:f});
        let j = await r.json();
        if(j.ok) {
            document.querySelector('.cart-link b').textContent = j.count;
            b.textContent = 'تمت الإضافة ✓';
        }
    });
});
let shippingData = null;
let wilaya = document.querySelector('[name=wilaya_id]');
let delivery = document.querySelector('[name=delivery_type]');
function shipping() {
    if(!shippingData) return;
    let price = (delivery && delivery.value === 'office') ? shippingData.office_shipping_price : shippingData.home_shipping_price;
    document.querySelector('[data-shipping]').textContent = price + ' د.ج';
}
if(wilaya) {
    wilaya.addEventListener('change', async function(e) {
        let r = await fetch(ALAN.base+'api/shipping.php?wilaya_id='+e.target.value);
        shippingData = await r.json();
        let c = document.querySelector('[name=commune_id]');
        if(c) {
            c.innerHTML = '<option value="">اختاري البلدية</option>' + shippingData.communes.map(x => `<option value="${x.id}">${x.name}</option>`).join('');
        }
        shipping();
    });
}
if(delivery) {
    delivery.addEventListener('change', shipping);
}

<?php require_once __DIR__.'/includes/functions.php';if(!empty($_GET['product']))$_SESSION['cart']=[(int)$_GET['product']=>['qty'=>1]];$items=cart();$products=get_products(array_keys($items));if(!$products){flash('error','أضيفي قطعة واحدة على الأقل قبل الطلب.');header('Location: '.base('shop.php'));exit;}$subtotal=0;foreach($items as $id=>$item)if(isset($products[$id]))$subtotal+=$products[$id]['price']*$item['qty'];$wilayas=db()->query('SELECT * FROM wilayas ORDER BY code')->fetchAll();$title='إتمام الطلب | ALAN';include __DIR__.'/includes/header.php';?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single { height: 46px; border: 1px solid var(--line); border-radius: 0; background: transparent; font-family: Cairo, Arial, sans-serif; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 46px; color: var(--ink); padding-right: 15px; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px; left: 10px; right: auto; }
.select2-results__option { font-family: Cairo, Arial, sans-serif; text-align: right; }
</style>
<div class="page"><div class="page-title"><p class="eyebrow">خطوة أخيرة</p><h1>أكملي طلبكِ</h1></div><div class="checkout-layout"><form class="form-card" method="post" action="<?=base('api/orders.php?action=create')?>"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="text" name="website" autocomplete="off" tabindex="-1" style="position:absolute;opacity:0"><h3>بيانات التوصيل</h3><div class="form-grid"><div><label>الاسم الكامل *</label><input name="full_name" required></div><div><label>رقم الهاتف *</label><input name="phone" required pattern="0[5-7][0-9]{8}"></div><div><label>رقم هاتف ثانٍ</label><input name="phone2"></div><div><label>الولاية *</label><select required name="wilaya_id" style="width:100%"><option value="">اختاري الولاية</option><?php foreach($wilayas as $w):?><option value="<?=$w['id']?>"><?=e($w['code'].' - '.$w['name'])?></option><?php endforeach;?></select></div><div><label>البلدية *</label><select required name="commune_id" disabled style="width:100%"><option value="">اختاري الولاية أولاً</option></select></div><div><label>نوع التوصيل *</label><select name="delivery_type" required><option value="office">التوصيل إلى المكتب</option><option value="home" selected>التوصيل إلى المنزل</option></select></div><div><label>العنوان بالتفصيل *</label><input name="address" required></div></div><label>ملاحظات للطلب</label><textarea name="notes" placeholder="مثال: التسليم بعد الساعة الرابعة"></textarea><button class="btn" style="margin-top:18px;width:100%">تأكيد الطلب — الدفع عند الاستلام</button></form><aside class="summary"><h3>طلبكِ</h3><?php foreach($items as $id=>$i):if(isset($products[$id])):?><div><span><?=e($products[$id]['name'])?> × <?=$i['qty']?></span><span><?=money($products[$id]['price']*$i['qty'])?></span></div><?php endif;endforeach;?><div><span>التوصيل</span><span data-shipping>اختاري الولاية</span></div><div class="total"><span>الإجمالي</span><span><?=money($subtotal)?></span></div><p>اختاري المكتب أو المنزل، ويظهر سعر التوصيل الخاص بالولاية تلقائياً.</p></aside></div></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const subtotal = <?=$subtotal?>;
let currentShippingCost = 0;
let homePrice = 0;
let officePrice = 0;
let deliveryTime = '';

function updateShippingCost() {
    const type = document.querySelector('select[name="delivery_type"]').value;
    currentShippingCost = type === 'home' ? homePrice : officePrice;
    
    const shippingEl = document.querySelector('[data-shipping]');
    const totalEl = document.querySelector('.total span:last-child');
    
    if (!document.querySelector('select[name="wilaya_id"]').value) {
        shippingEl.textContent = 'اختاري الولاية';
        totalEl.textContent = new Intl.NumberFormat('fr-DZ').format(subtotal).replace(/\s/g, ' ') + ' د.ج';
        return;
    }
    
    shippingEl.textContent = currentShippingCost > 0 ? (new Intl.NumberFormat('fr-DZ').format(currentShippingCost).replace(/\s/g, ' ') + ' د.ج (' + deliveryTime + ')') : 'مجانًا (' + deliveryTime + ')';
    totalEl.textContent = new Intl.NumberFormat('fr-DZ').format(subtotal + currentShippingCost).replace(/\s/g, ' ') + ' د.ج';
}

$(document).ready(function() {
    $('select[name="wilaya_id"], select[name="commune_id"]').select2({ dir: "rtl" });

    $('select[name="wilaya_id"]').on('change', function() {
        const wilayaId = this.value;
        const $communeSelect = $('select[name="commune_id"]');
        
        $communeSelect.html('<option value="">جاري التحميل...</option>').prop('disabled', true);
        
        if (!wilayaId) {
            $communeSelect.html('<option value="">اختاري الولاية أولاً</option>');
            homePrice = officePrice = 0;
            deliveryTime = '';
            updateShippingCost();
            return;
        }
        
        fetch('<?=base("api/shipping.php")?>?wilaya_id=' + wilayaId)
            .then(response => response.json())
            .then(data => {
                $communeSelect.html('<option value="">اختاري البلدية</option>');
                if (data.communes && data.communes.length > 0) {
                    data.communes.forEach(commune => {
                        $communeSelect.append(new Option(commune.name, commune.id));
                    });
                    $communeSelect.prop('disabled', false);
                } else {
                    $communeSelect.html('<option value="">لا توجد بلديات</option>');
                }
                
                homePrice = data.home_shipping_price;
                officePrice = data.office_shipping_price;
                deliveryTime = data.delivery_time;
                
                updateShippingCost();
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                $communeSelect.html('<option value="">حدث خطأ، حاولي مجدداً</option>').prop('disabled', false);
            });
    });

    $('select[name="delivery_type"]').on('change', updateShippingCost);
});
</script>
<?php include __DIR__.'/includes/footer.php';?>

<?php require_once __DIR__.'/includes/functions.php';if(!empty($_GET['product']))$_SESSION['cart']=[(int)$_GET['product']=>['qty'=>1]];$items=cart();$product_ids=[];foreach($items as $k=>$it)$product_ids[]=$it['product_id']??(int)$k;$products=get_products($product_ids);if(!$products){flash('error','أضيفي قطعة واحدة على الأقل قبل الطلب.');header('Location: '.base('shop.php'));exit;}$subtotal=0;foreach($items as $k=>$item){$pid=$item['product_id']??(int)$k;if(isset($products[$pid]))$subtotal+=$products[$pid]['price']*$item['qty'];}$wilayas=db()->query('SELECT * FROM wilayas ORDER BY code')->fetchAll();$title='إتمام الطلب | ALAN';$ic_event_id=uniqid('ic_');send_meta_capi_event('InitiateCheckout',$ic_event_id,['currency'=>'DZD','value'=>(float)$subtotal]);include __DIR__.'/includes/header.php';?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script>if(typeof fbq !== 'undefined') fbq('track', 'InitiateCheckout', {currency: 'DZD', value: <?=$subtotal?>}, {eventID: '<?=e($ic_event_id)?>'}); if(typeof ttq !== 'undefined') ttq.track('InitiateCheckout', {currency: 'DZD', value: <?=$subtotal?>});</script>
<style>
.select2-container .select2-selection--single { height: 46px; border: 1px solid var(--line); border-radius: 0; background: transparent; font-family: Cairo, Arial, sans-serif; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 46px; color: var(--ink); padding-right: 15px; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px; left: 10px; right: auto; }
.select2-results__option { font-family: Cairo, Arial, sans-serif; text-align: right; }
</style>
<div class="page"><div class="page-title"><p class="eyebrow">خطوة أخيرة</p><h1>أكملي طلبكِ</h1></div><div class="checkout-layout"><form class="form-card" method="post" action="<?=base('api/orders.php?action=create')?>"><input type="hidden" name="csrf" value="<?=csrf()?>"><input type="text" name="website" autocomplete="off" tabindex="-1" style="position:absolute;opacity:0"><input type="hidden" name="promo_code" id="promo_code_hidden"><h3>بيانات التوصيل</h3><div class="form-grid"><div><label>الاسم الكامل *</label><input name="full_name" required></div><div><label>رقم الهاتف *</label><input name="phone" required pattern="0[5-7][0-9]{8}"></div><div><label>رقم هاتف ثانٍ</label><input name="phone2"></div><div><label>الولاية *</label><select required name="wilaya_id" style="width:100%"><option value="">اختاري الولاية</option><?php foreach($wilayas as $w):?><option value="<?=$w['id']?>"><?=e($w['code'].' - '.$w['name'])?></option><?php endforeach;?></select></div><div><label>البلدية *</label><select required name="commune_id" disabled style="width:100%"><option value="">اختاري الولاية أولاً</option></select></div><div><label>نوع التوصيل *</label><select name="delivery_type" required><option value="office">التوصيل إلى المكتب</option><option value="home" selected>التوصيل إلى المنزل</option></select></div><div><label>العنوان بالتفصيل *</label><input name="address" required></div></div><label>ملاحظات للطلب</label><textarea name="notes" placeholder="مثال: التسليم بعد الساعة الرابعة"></textarea><button class="btn" style="margin-top:18px;width:100%">تأكيد الطلب — الدفع عند الاستلام</button></form><aside class="summary"><h3>طلبكِ</h3><?php foreach($items as $k=>$i):$pid=$i['product_id']??(int)$k;if(isset($products[$pid])):?><div><div><span><?=e($products[$pid]['name'])?> × <?=$i['qty']?></span><?php if(!empty($i['color'])||!empty($i['size'])): ?><div style="font-size:12px;color:var(--text);margin-top:2px;"><?=e($i['color']??'')?> <?=!empty($i['color'])&&!empty($i['size'])?'·':''?> <?=e($i['size']??'')?></div><?php endif;?></div><span><?=money($products[$pid]['price']*$i['qty'])?></span></div><?php endif;endforeach;?><div><span>التوصيل</span><span data-shipping>اختاري الولاية</span></div><div id="discount_row" style="display:none; color:var(--brand);"><span>الخصم</span><span id="discount_val">0 د.ج</span></div><div class="total"><span>الإجمالي</span><span><?=money($subtotal)?></span></div><div class="promo-box" style="margin-top:20px; border-top:1px dashed var(--line); padding-top:20px;"><label style="font-weight:600; color:var(--ink); display:block; margin-bottom:10px;">لديكِ كوبون خصم؟</label><div style="display:flex; width:100%; height:48px; border-radius:6px; overflow:hidden; border:1px solid var(--line); background:#fff;"><input type="text" id="promo_code_input" placeholder="أدخلي الكود هنا..." style="flex:1; min-width:0; border:none; background:transparent; text-align:center; text-transform:uppercase; font-weight:bold; letter-spacing:1px; outline:none; padding:0 15px; margin:0;"><button type="button" class="btn" id="apply_promo" style="width:110px; flex-shrink:0; border:none; background:var(--ink); color:#fff; font-weight:bold; font-size:15px; border-radius:0; padding:0; margin:0; cursor:pointer; min-height:0;">تطبيق</button></div><div id="promo_msg" style="font-size:13px; margin-top:8px; font-weight:600; text-align:center;"></div></div><p>اختاري المكتب أو المنزل، ويظهر سعر التوصيل الخاص بالولاية تلقائياً.</p></aside></div></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
const subtotal = <?=$subtotal?>;
let currentShippingCost = 0;
let homePrice = 0;
let officePrice = 0;
let deliveryTime = '';
let discountAmount = 0;
let discountType = 'fixed';

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
    
    let totalBeforeDiscount = subtotal + currentShippingCost;
    let actualDiscount = 0;
    if (discountAmount > 0) {
        if (discountType === 'percentage') {
            actualDiscount = totalBeforeDiscount * (discountAmount / 100);
        } else {
            actualDiscount = discountAmount;
        }
    }
    let finalTotal = Math.max(0, totalBeforeDiscount - actualDiscount);

    if (actualDiscount > 0) {
        document.getElementById('discount_row').style.display = 'flex';
        document.getElementById('discount_val').textContent = '- ' + new Intl.NumberFormat('fr-DZ').format(actualDiscount).replace(/\s/g, ' ') + ' د.ج';
    } else {
        document.getElementById('discount_row').style.display = 'none';
    }

    totalEl.textContent = new Intl.NumberFormat('fr-DZ').format(finalTotal).replace(/\s/g, ' ') + ' د.ج';
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

    $('#apply_promo').on('click', function() {
        const code = $('#promo_code_input').val().trim();
        const msg = $('#promo_msg');
        if(!code) return;
        
        msg.css('color', 'black').text('جاري التحقق...');
        
        fetch('<?=base("api/promo.php")?>?code=' + encodeURIComponent(code))
            .then(r => r.json())
            .then(data => {
                if(data.ok) {
                    msg.css('color', 'green').text('تم تطبيق الخصم بنجاح!');
                    $('#promo_code_hidden').val(code);
                    discountAmount = data.discount_value;
                    discountType = data.discount_type;
                    updateShippingCost();
                } else {
                    msg.css('color', 'red').text(data.error);
                    $('#promo_code_hidden').val('');
                    discountAmount = 0;
                    updateShippingCost();
                }
            }).catch(e => {
                msg.css('color', 'red').text('حدث خطأ بالاتصال.');
            });
    });
});
</script>
<?php include __DIR__.'/includes/footer.php';?>

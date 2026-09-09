<?php
require_once __DIR__.'/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (!empty($_POST['delete'])) {
        db()->prepare('DELETE FROM promo_codes WHERE id=?')->execute([(int)$_POST['delete']]);
        flash('success', 'تم الحذف.');
    } else {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['discount_type'] ?? 'fixed';
        $value = (float)($_POST['discount_value'] ?? 0);
        $limit = (int)($_POST['usage_limit'] ?? 0);
        $status = $_POST['status'] ?? 'active';
        $show_in_header = !empty($_POST['show_in_header']) ? 1 : 0;

        if ($code && $value > 0) {
            try {
                db()->prepare('INSERT INTO promo_codes(code, discount_type, discount_value, usage_limit, status, show_in_header) VALUES(?,?,?,?,?,?)')
                    ->execute([$code, $type, $value, $limit ?: null, $status, $show_in_header]);
                flash('success', 'تم إضافة الكوبون بنجاح.');
            } catch (PDOException $e) {
                flash('error', 'هذا الكود موجود مسبقاً.');
            }
        } else {
            flash('error', 'يرجى إدخال بيانات الكوبون بشكل صحيح.');
        }
    }
    header('Location: ' . base('admin/promo-codes.php'));
    exit;
}

$codes = db()->query('SELECT * FROM promo_codes ORDER BY id DESC')->fetchAll();
$adminTitle = 'إدارة الكوبونات';
include 'header.php';
?>
<div class="panel">
    <h2>إضافة كوبون جديد</h2>
    <form class="form" method="post">
        <input type="hidden" name="csrf" value="<?=csrf()?>">
        
        <div>
            <label>كود الخصم (Promo Code)</label>
            <input type="text" name="code" required placeholder="مثال: ALAN2026" style="text-transform: uppercase;">
        </div>
        
        <div>
            <label>نوع الخصم</label>
            <select name="discount_type" required>
                <option value="fixed">مبلغ ثابت (د.ج)</option>
                <option value="percentage">نسبة مئوية (%)</option>
            </select>
        </div>
        
        <div>
            <label>قيمة الخصم</label>
            <input type="number" name="discount_value" required min="1" step="0.01">
        </div>

        <div>
            <label>حد الاستخدام (اختياري)</label>
            <input type="number" name="usage_limit" min="1" placeholder="مثال: 100 مرة (اتركه فارغاً ليكون لا نهائي)">
        </div>

        <div>
            <label>الحالة</label>
            <select name="status">
                <option value="active">فعال</option>
                <option value="disabled">معطل</option>
            </select>
        </div>

        <div style="grid-column: 1 / -1; display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="show_in_header" id="show_in_header" value="1" style="width:auto; height:auto; margin:0;">
            <label for="show_in_header" style="margin:0; font-weight:normal; cursor:pointer;">إظهار الكوبون في شريط الإعلانات أعلى الموقع لجميع الزوار</label>
        </div>

        <div style="grid-column: 1 / -1;">
            <button class="button gold">إضافة الكوبون</button>
        </div>
    </form>
</div>

<div class="panel">
    <h2>الكوبونات الحالية</h2>
    <?php if (!$codes): ?>
        <div class="empty">لا توجد كوبونات حالياً.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <tr>
                    <th>الكود</th>
                    <th>الخصم</th>
                    <th>الاستخدام</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الحالة</th>
                    <th>إجراء</th>
                </tr>
                <?php foreach ($codes as $c): ?>
                    <tr>
                        <td><strong><?=e($c['code'])?></strong></td>
                        <td>
                            <?=e($c['discount_value'])?> 
                            <?=$c['discount_type'] === 'percentage' ? '%' : 'د.ج'?>
                        </td>
                        <td><?=$c['used_count']?> / <?=$c['usage_limit'] ?: '∞'?></td>
                        <td><?=e(date('Y-m-d', strtotime($c['created_at'])))?></td>
                        <td><span class="tag"><?=$c['status'] === 'active' ? 'فعال' : 'معطل'?></span></td>
                        <td>
                            <form method="post" style="display:inline" onsubmit="return confirm('تأكيد الحذف؟')">
                                <input type="hidden" name="csrf" value="<?=csrf()?>">
                                <input type="hidden" name="delete" value="<?=$c['id']?>">
                                <button class="button red">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

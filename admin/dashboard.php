<?php
require_once __DIR__.'/../includes/functions.php';
$adminTitle='لوحة التحكم';
$stats=[
    'إجمالي الإيرادات'=>db()->query('SELECT COALESCE(SUM(total),0) FROM orders WHERE status="delivered"')->fetchColumn(),
    'الطلبات الجديدة'=>db()->query('SELECT COUNT(*) FROM orders WHERE status="received"')->fetchColumn(),
    'العملاء'=>db()->query('SELECT COUNT(*) FROM customers')->fetchColumn(),
    'المنتجات'=>db()->query('SELECT COUNT(*) FROM products WHERE status="active"')->fetchColumn()
];
$orders=db()->query('SELECT order_number,full_name,total,status,created_at FROM orders ORDER BY id DESC LIMIT 8')->fetchAll();
include 'header.php';
?>
<div class="cards">
<?php foreach($stats as $n=>$v):?>
<div class="stat">
<span><?=e($n)?></span>
<b><?=is_numeric($v)&&strpos($n,'إيرادات')!==false?money((float)$v):e((string)$v)?></b>
</div>
<?php endforeach;?>
</div>
<div class="panel">
<h2>أحدث الطلبات</h2>
<table class="table">
<tr><th>الرقم</th><th>العميلة</th><th>المبلغ</th><th>الحالة</th><th>التاريخ</th></tr>
<?php foreach($orders as $o):?>
<tr>
<td><?=e($o['order_number'])?></td>
<td><?=e($o['full_name'])?></td>
<td><?=money((float)$o['total'])?></td>
<td><span class="tag status-<?=e($o['status'])?>"><?=e($o['status'])?></span></td>
<td><?=e($o['created_at'])?></td>
</tr>
<?php endforeach;?>
</table>
</div>
<?php include 'footer.php';?>

<?php
require_once __DIR__.'/../includes/functions.php';header('Content-Type: application/json; charset=utf-8');
$id=(int)($_GET['wilaya_id']??0);$st=db()->prepare('SELECT id,name FROM communes WHERE wilaya_id=? ORDER BY name');$st->execute([$id]);$sp=db()->prepare('SELECT office_shipping_price,home_shipping_price,delivery_time FROM wilayas WHERE id=?');$sp->execute([$id]);$s=$sp->fetch()?:[];
echo json_encode(['communes'=>$st->fetchAll(),'office_shipping_price'=>(float)($s['office_shipping_price']??0),'home_shipping_price'=>(float)($s['home_shipping_price']??0),'delivery_time'=>$s['delivery_time']??''],JSON_UNESCAPED_UNICODE);

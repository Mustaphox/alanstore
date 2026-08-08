<?php class Shipping{public static function price(int $wilaya):float{$s=db()->prepare('SELECT shipping_price FROM wilayas WHERE id=?');$s->execute([$wilaya]);return(float)$s->fetchColumn();}}

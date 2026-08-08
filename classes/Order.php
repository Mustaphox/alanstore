<?php class Order{public static function find(string $number):array|false{$s=db()->prepare('SELECT * FROM orders WHERE order_number=?');$s->execute([$number]);return $s->fetch();}}

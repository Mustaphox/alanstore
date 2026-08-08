<?php class Customer{public static function byPhone(string $phone):array|false{$s=db()->prepare('SELECT * FROM customers WHERE phone=?');$s->execute([$phone]);return $s->fetch();}}

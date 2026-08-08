<?php class Product{public static function find(int $id):array|false{$s=db()->prepare('SELECT * FROM products WHERE id=?');$s->execute([$id]);return $s->fetch();}}

<?php class Category{public static function all():array{return db()->query('SELECT * FROM categories WHERE status="active" ORDER BY sort_order')->fetchAll();}}

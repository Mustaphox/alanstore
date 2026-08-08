<?php class Hero{public static function active():array|false{return db()->query('SELECT * FROM hero WHERE is_active=1 ORDER BY id DESC LIMIT 1')->fetch();}}

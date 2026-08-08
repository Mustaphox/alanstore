<?php
require_once __DIR__ . '/database.php';
function e(?string $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function base(string $path=''): string { $path=preg_replace('/\.php(\?|$)/', '$1', $path); return APP_URL . '/' . ltrim($path,'/'); }
function csrf(): string { if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function check_csrf(): void { if(!hash_equals($_SESSION['csrf']??'', $_POST['csrf']??'')) { http_response_code(419); exit('طلب غير صالح. يرجى إعادة المحاولة.'); } }
function setting(string $key, string $default=''): string { static $s=[]; if(!$s){ foreach(db()->query('SELECT setting_key,setting_value FROM settings') as $row)$s[$row['setting_key']]=$row['setting_value']; } return $s[$key]??$default; }
function flash(string $key, ?string $message=null): ?string { if($message!==null){$_SESSION['flash'][$key]=$message;return null;} $m=$_SESSION['flash'][$key]??null;unset($_SESSION['flash'][$key]);return $m; }
function money(float $amount): string{
    $value=number_format(max(0,$amount),0,'.',' ');
    return '<bdi class="money" dir="ltr">'.$value.' د.ج</bdi>';
}
function cart(): array{return $_SESSION['cart']??[];}
function cart_count(): int{return array_sum(array_column(cart(),'qty'));}
function get_products(array $ids): array { if(!$ids)return []; $q=db()->prepare('SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id IN ('.implode(',',array_fill(0,count($ids),'?')).') AND p.status="active"');$q->execute($ids);$out=[];foreach($q as $p)$out[$p['id']]=$p;return $out; }
function log_activity(string $type, string $details=''):void{ $st=db()->prepare('INSERT INTO activity_logs(event_type,details,ip_address,user_agent,fingerprint) VALUES(?,?,?,?,?)');$st->execute([$type,$details,$_SERVER['REMOTE_ADDR']??'',$_SERVER['HTTP_USER_AGENT']??'',substr(hash('sha256',($_SERVER['HTTP_USER_AGENT']??'').($_SERVER['HTTP_ACCEPT_LANGUAGE']??'')),0,64)]); }
function rate_limit(string $action,int $limit=20,int $minutes=10):bool{ $ip=$_SERVER['REMOTE_ADDR']??'unknown';$st=db()->prepare('SELECT COUNT(*) FROM rate_limits WHERE action_name=? AND ip_address=? AND created_at>DATE_SUB(NOW(),INTERVAL ? MINUTE)');$st->execute([$action,$ip,$minutes]);if((int)$st->fetchColumn()>=$limit)return false;db()->prepare('INSERT INTO rate_limits(action_name,ip_address) VALUES(?,?)')->execute([$action,$ip]);return true; }
function is_admin():bool{return !empty($_SESSION['admin_id']);}
function require_admin():void{if(!is_admin()){header('Location: '.base('admin/index.php'));exit;}}
function product_image(array $p):string{return $p['image']?:'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=700&q=80';}

/** Keeps legacy installations compatible with the product gallery. */
function ensure_product_media_table(): void {
    static $ready = false;
    if ($ready) return;
    db()->exec('CREATE TABLE IF NOT EXISTS product_images (id INT AUTO_INCREMENT PRIMARY KEY, product_id INT NOT NULL, image_url VARCHAR(500) NOT NULL, sort_order INT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    $ready = true;
}

function get_real_ip(): string {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function extract_youtube_id(?string $url): ?string {
    if (!$url) return null;
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);
    return $match[1] ?? null;
}

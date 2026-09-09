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
function get_products(array $ids): array { 
    if(!$ids)return []; 
    // extract unique product ids if the ids array comes from cart keys or contains strings
    $clean_ids = [];
    foreach($ids as $id) {
        $parts = explode('_', (string)$id);
        $clean_ids[] = (int)$parts[0];
    }
    $clean_ids = array_unique(array_filter($clean_ids));
    if(!$clean_ids)return [];
    $q=db()->prepare('SELECT p.*,c.name category_name FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id IN ('.implode(',',array_fill(0,count($clean_ids),'?')).') AND p.status="active"');
    $q->execute(array_values($clean_ids));
    $out=[];
    foreach($q as $p) $out[$p['id']]=$p;
    return $out; 
}
function log_activity(string $type, string $details=''):void{ $st=db()->prepare('INSERT INTO activity_logs(event_type,details,ip_address,user_agent,fingerprint) VALUES(?,?,?,?,?)');$st->execute([$type,$details,$_SERVER['REMOTE_ADDR']??'',$_SERVER['HTTP_USER_AGENT']??'',substr(hash('sha256',($_SERVER['HTTP_USER_AGENT']??'').($_SERVER['HTTP_ACCEPT_LANGUAGE']??'')),0,64)]); }
function rate_limit(string $action,int $limit=20,int $minutes=10):bool{ $ip=$_SERVER['REMOTE_ADDR']??'unknown';$st=db()->prepare('SELECT COUNT(*) FROM rate_limits WHERE action_name=? AND ip_address=? AND created_at>DATE_SUB(NOW(),INTERVAL ? MINUTE)');$st->execute([$action,$ip,$minutes]);if((int)$st->fetchColumn()>=$limit)return false;db()->prepare('INSERT INTO rate_limits(action_name,ip_address) VALUES(?,?)')->execute([$action,$ip]);return true; }
function is_admin():bool{return !empty($_SESSION['admin_id']);}
function require_admin():void{if(!is_admin()){header('Location: '.base('admin/index.php'));exit;}}
function product_image(array $p):string{
    $img = trim((string)($p['image'] ?? ''));
    if ($img === '') return 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=700&q=80';
    if (preg_match('#^https?://#i', $img)) return $img;
    if (preg_match('#^/?(alan[-_ ]store/)?uploads/(.+)$#i', $img, $m)) return base('uploads/' . $m[2]);
    return base(ltrim($img, '/'));
}

/** Keeps legacy installations compatible with the product gallery. */
function ensure_product_media_table(): void {
    static $ready = false;
    if ($ready) return;
    $ready = true;
    if (is_admin()) {
        try {
            db()->exec('CREATE TABLE IF NOT EXISTS product_images (id INT AUTO_INCREMENT PRIMARY KEY, product_id INT NOT NULL, image_url VARCHAR(500) NOT NULL, sort_order INT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        } catch (\Throwable $e) {}
    }
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

function send_meta_capi_event(string $event_name, string $event_id, array $custom_data = [], array $user_data = []): void {
    $status = setting('meta_pixel_status');
    $pixel_id = setting('meta_pixel_id');
    $token = setting('meta_capi_token');
    if ($status !== 'enabled' || !$pixel_id || !$token) return;
    $url = "https://graph.facebook.com/v19.0/{$pixel_id}/events";
    if (!empty($user_data['em'])) $user_data['em'] = hash('sha256', strtolower(trim($user_data['em'])));
    if (!empty($user_data['ph'])) $user_data['ph'] = hash('sha256', preg_replace('/[^\d]/', '', $user_data['ph']));
    $user_data['client_ip_address'] = get_real_ip();
    $user_data['client_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $event = [
        'event_name' => $event_name,
        'event_time' => time(),
        'action_source' => 'website',
        'event_id' => $event_id,
        'event_source_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",
        'user_data' => $user_data,
    ];
    if (!empty($custom_data)) $event['custom_data'] = $custom_data;
    $payload = ['data' => [$event]];
    $test_code = setting('meta_test_event_code');
    if ($test_code) $payload['test_event_code'] = $test_code;
    
    // Execute after response is sent if fastcgi_finish_request is available, or with strict fast timeout
    $sender = function() use ($url, $payload, $token, $event_name, $event_id) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $token]);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 700);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1500);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        log_activity('meta_capi_'.$event_name, "Code: $httpcode | Error: $error | Response: $response | EventID: $event_id");
    };

    if (function_exists('fastcgi_finish_request')) {
        register_shutdown_function($sender);
    } else {
        $sender();
    }
}

<?php
require_once __DIR__ . '/../includes/functions.php';

/* logout */
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . base('admin/index.php'));
    exit;
}

/* already logged in */
if (is_admin()) {
    header('Location: ' . base('admin/dashboard.php'));
    exit;
}

/* handle login POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (!rate_limit('admin_login', 5, 15)) {
        flash('error', 'محاولات كثيرة جداً. الرجاء الانتظار 15 دقيقة.');
    } else {
        $st = db()->prepare('SELECT * FROM admins WHERE email = ?');
        $st->execute([trim($_POST['email'])]);
        $a = $st->fetch();
        if ($a && password_verify($_POST['password'], $a['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $a['id'];
            $_SESSION['admin_name'] = $a['name'];
            log_activity('admin_login', $a['email']);
            header('Location: ' . base('admin/dashboard.php'));
            exit;
        }
        flash('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة.');
    }
}
?><!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>تسجيل الدخول | ALAN Admin</title>
<link rel="icon" type="image/png" sizes="32x32" href="<?= base('favicon-32.png') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#a9864b;--gold-light:#c4a46b;--ink:#13120f;
  --surface:#fff;--cream:#faf8f4;--line:#e8e4dd;
  --err:#c0392b;--err-bg:#fdf0ef;
  --shadow:0 20px 60px rgba(0,0,0,.12);
}
html,body{height:100%;font-family:Cairo,Arial,sans-serif}
body{
  background:var(--cream);
  display:flex;align-items:center;justify-content:center;
  min-height:100vh;padding:20px;
  background-image:
    radial-gradient(ellipse at 20% 50%, rgba(169,134,75,.08) 0%, transparent 60%),
    radial-gradient(ellipse at 80% 20%, rgba(169,134,75,.06) 0%, transparent 50%);
}

.login-wrap{
  width:100%;max-width:440px;
  background:var(--surface);
  border:1px solid var(--line);
  border-radius:20px;
  box-shadow:var(--shadow);
  overflow:hidden;
  animation:fadeUp .5s ease both;
}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:none}}

.login-header{
  background:linear-gradient(135deg,#13120f 0%,#2a2720 60%,#1c1a15 100%);
  padding:40px 38px 34px;
  text-align:center;
  position:relative;overflow:hidden;
}
.login-header::before{
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at 50% 120%,rgba(169,134,75,.25) 0%,transparent 65%);
}
.login-logo{
  display:inline-flex;align-items:center;gap:10px;
  font-family:'DM Serif Display',serif;
  font-size:34px;color:#fff;letter-spacing:2px;
  position:relative;z-index:1;text-decoration:none;
}
.login-logo img{
  width:46px;height:46px;border-radius:50%;
  object-fit:cover;border:2px solid rgba(169,134,75,.6);
  mix-blend-mode:luminosity;
}
.login-logo-sep{
  width:1px;height:32px;
  background:linear-gradient(to bottom,transparent,rgba(169,134,75,.6),transparent);
  margin:0 4px;
}
.login-logo small{
  font-family:Cairo,sans-serif;
  font-size:9px;letter-spacing:3px;
  color:var(--gold-light);display:block;margin-top:2px;
  font-weight:600;
}
.login-subtitle{
  position:relative;z-index:1;
  margin-top:14px;color:rgba(255,255,255,.5);
  font-size:11px;letter-spacing:.5px;
}

.login-body{padding:36px 38px 32px}

.notice{
  display:flex;align-items:center;gap:10px;
  padding:11px 15px;border-radius:9px;
  font-size:12px;margin-bottom:22px;
  background:var(--err-bg);color:var(--err);
  border:1px solid rgba(192,57,43,.15);
  animation:shake .4s ease;
}
@keyframes shake{0%,100%{transform:none}20%,60%{transform:translateX(-6px)}40%,80%{transform:translateX(6px)}}
.notice svg{flex-shrink:0}

.creds-hint{
  background:linear-gradient(135deg,#fffdf8,#fdf8ee);
  border:1px solid rgba(169,134,75,.3);
  border-radius:10px;padding:16px 18px;
  margin-bottom:24px;
  font-size:11px;line-height:1.8;
}
.creds-hint strong{
  display:block;font-size:10px;letter-spacing:.5px;
  color:var(--gold);font-weight:700;margin-bottom:8px;
}
.creds-row{display:flex;justify-content:space-between;align-items:center;gap:10px}
.creds-row span{color:#666}
.creds-row code{
  background:#f0ece4;border:1px solid var(--line);
  padding:3px 9px;border-radius:5px;
  font-family:monospace;font-size:12px;color:var(--ink);
  direction:ltr;display:inline-block;
}
.creds-copy{
  border:0;background:none;cursor:pointer;color:var(--gold);
  font-size:13px;padding:2px 4px;border-radius:4px;
  transition:.2s;flex-shrink:0;
}
.creds-copy:hover{background:rgba(169,134,75,.12)}

.field{margin-bottom:18px}
.field label{
  display:block;font-size:11px;font-weight:700;
  color:#5a5650;margin-bottom:7px;letter-spacing:.3px;
}
.field-wrap{position:relative}
.field input{
  width:100%;padding:12px 42px 12px 14px;
  border:1.5px solid var(--line);border-radius:9px;
  font:13px Cairo;color:var(--ink);background:#fff;
  transition:border-color .2s,box-shadow .2s;
  outline:none;
}
.field input:focus{
  border-color:var(--gold);
  box-shadow:0 0 0 3px rgba(169,134,75,.12);
}
.field-icon{
  position:absolute;left:13px;top:50%;transform:translateY(-50%);
  color:#b0a99f;font-size:15px;pointer-events:none;
}
.eye-toggle{
  position:absolute;left:13px;top:50%;transform:translateY(-50%);
  border:0;background:none;cursor:pointer;color:#b0a99f;
  font-size:15px;line-height:1;padding:0;
  transition:color .2s;
}
.eye-toggle:hover{color:var(--gold)}

.login-btn{
  width:100%;padding:13px;
  background:linear-gradient(135deg,#13120f 0%,#2d2a24 100%);
  color:#fff;border:none;border-radius:9px;
  font:600 13px Cairo;letter-spacing:.5px;cursor:pointer;
  transition:.3s cubic-bezier(.2,.8,.2,1);
  position:relative;overflow:hidden;
  margin-top:6px;
}
.login-btn::before{
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg,var(--gold) 0%,var(--gold-light) 100%);
  opacity:0;transition:.3s;
}
.login-btn:hover::before{opacity:1}
.login-btn span{position:relative;z-index:1}

.login-footer{
  border-top:1px solid var(--line);
  padding:16px 38px;text-align:center;
  font-size:10px;color:#aaa;letter-spacing:.3px;
}
.login-footer a{color:var(--gold);text-decoration:none}
.login-footer a:hover{text-decoration:underline}

@media(max-width:480px){
  .login-header,.login-body{padding:28px 24px}
  .login-footer{padding:14px 24px}
}
</style>
</head>
<body>

<div class="login-wrap">

  <div class="login-header">
    <a class="login-logo" href="<?= base() ?>">
      <img src="<?= base('logo.png') ?>" alt="ALAN">
      <div class="login-logo-sep"></div>
      <div>
        ALAN
        <small>ADMIN PANEL</small>
      </div>
    </a>
    <p class="login-subtitle">أدخل بياناتك للوصول إلى لوحة التحكم</p>
  </div>

  <div class="login-body">

    <?php if ($m = flash('error')): ?>
    <div class="notice">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <?= e($m) ?>
    </div>
    <?php endif; ?>

    <!-- بيانات الدخول للمرجع -->
    <div class="creds-hint">
      <strong>🔐 بيانات الدخول</strong>
      <div class="creds-row">
        <span>البريد الإلكتروني</span>
        <div style="display:flex;align-items:center;gap:6px">
          <code id="email-val">admin@alan.dz</code>
          <button class="creds-copy" onclick="copyText('email-val')" title="نسخ">⧉</button>
        </div>
      </div>
      <div class="creds-row" style="margin-top:6px">
        <span>كلمة المرور</span>
        <div style="display:flex;align-items:center;gap:6px">
          <code id="pass-val">alan2026</code>
          <button class="creds-copy" onclick="copyText('pass-val')" title="نسخ">⧉</button>
        </div>
      </div>
    </div>

    <form method="post" autocomplete="on">
      <input type="hidden" name="csrf" value="<?= csrf() ?>">

      <div class="field">
        <label for="email">البريد الإلكتروني</label>
        <div class="field-wrap">
          <input id="email" type="email" name="email" value="admin@alan.dz"
                 required autocomplete="username" placeholder="admin@alan.dz">
          <span class="field-icon">✉</span>
        </div>
      </div>

      <div class="field">
        <label for="password">كلمة المرور</label>
        <div class="field-wrap">
          <input id="password" type="password" name="password"
                 required autocomplete="current-password" placeholder="••••••••">
          <button type="button" class="eye-toggle" id="eye-btn" onclick="toggleEye()" title="إظهار/إخفاء">👁</button>
        </div>
      </div>

      <button class="login-btn" type="submit">
        <span>دخول آمن ←</span>
      </button>
    </form>
  </div>

  <div class="login-footer">
    <a href="<?= base() ?>" target="_blank">← العودة للمتجر</a>
    &nbsp;·&nbsp;
    ALAN © <?= date('Y') ?>
  </div>
</div>

<script>
function toggleEye() {
    var inp = document.getElementById('password');
    var btn = document.getElementById('eye-btn');
    if (inp.type === 'password') {
        inp.type = 'text';
        btn.textContent = '🙈';
    } else {
        inp.type = 'password';
        btn.textContent = '👁';
    }
}
function copyText(id) {
    var text = document.getElementById(id).textContent.trim();
    navigator.clipboard && navigator.clipboard.writeText(text);
    var btn = event.currentTarget;
    btn.textContent = '✓';
    setTimeout(function(){ btn.textContent = '⧉'; }, 1200);
}
</script>
</body>
</html>

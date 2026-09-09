<?php
declare(strict_types=1);

session_start();
date_default_timezone_set('Africa/Algiers');
header('Content-Type: text/html; charset=utf-8');

const DB_HOST    = 'localhost';
const DB_NAME    = 'if0_42600030_alaan'; // غير اسم القاعدة إذا كان مختلفاً لديك محلياً
const DB_USER    = 'root';
const DB_PASS    = '';
const APP_URL    = '/alan store';
const UPLOAD_DIR = __DIR__ . '/uploads/';
const ADMIN_EMAIL = 'admin@alan.dz';

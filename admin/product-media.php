<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
ensure_product_media_table();

/* ensure uploads dir exists */
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0775, true);
}

/* store one uploaded image */
function media_store(array $file, int $productId): bool
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return false;
    if (($file['size'] ?? 0) > 8 * 1024 * 1024) return false;
    if (!is_uploaded_file($file['tmp_name'])) return false;

    $info = @getimagesize($file['tmp_name']);
    if (!$info) return false;

    $type = $info[2];
    $ext = null; 
    switch ($type) { 
        case IMAGETYPE_JPEG: $ext = 'jpg'; break; 
        case IMAGETYPE_PNG: $ext = 'png'; break; 
        case IMAGETYPE_WEBP: $ext = 'webp'; break; 
    }
    if (!$ext && defined('IMAGETYPE_AVIF') && $type === IMAGETYPE_AVIF) $ext = 'avif';
    if (!$ext) return false;

    $toWebp = function_exists('imagewebp');
    $name   = 'product-' . bin2hex(random_bytes(10)) . '.' . ($toWebp ? 'webp' : $ext);
    $target = UPLOAD_DIR . $name;
    $source = null;

    if ($toWebp) {
        switch ($type) { 
            case IMAGETYPE_JPEG: $source = @imagecreatefromjpeg($file['tmp_name']); break; 
            case IMAGETYPE_PNG: $source = @imagecreatefrompng($file['tmp_name']); break; 
            case IMAGETYPE_WEBP: $source = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file['tmp_name']) : null; break; 
        }
        if (defined('IMAGETYPE_AVIF') && $type === IMAGETYPE_AVIF && function_exists('imagecreatefromavif')) {
            $source = @imagecreatefromavif($file['tmp_name']);
        }
    }

    if ($source) {
        imagepalettetotruecolor($source);
        imagealphablending($source, true);
        imagesavealpha($source, true);
        $saved = imagewebp($source, $target, 84);
        imagedestroy($source);
        if (!$saved) return false;
    } else {
        if (!move_uploaded_file($file['tmp_name'], $target)) return false;
    }

    $max = db()->prepare('SELECT COALESCE(MAX(sort_order),0) FROM product_images WHERE product_id=?');
    $max->execute([$productId]);
    $url = base('uploads/' . $name);

    db()->prepare('INSERT INTO product_images(product_id,image_url,sort_order) VALUES(?,?,?)')
        ->execute([$productId, $url, (int)$max->fetchColumn() + 1]);

    db()->prepare('UPDATE products SET image=COALESCE(NULLIF(image,\'\'),?) WHERE id=?')
        ->execute([$url, $productId]);

    return true;
}

/* handle POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action    = $_POST['action']    ?? '';
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($action === 'upload') {
        header('Content-Type: application/json');
        $ok     = 0;
        $errors = [];

        $fileList = $_FILES['files'] ?? [];
        $names    = $fileList['name']     ?? [];
        $tmpNames = $fileList['tmp_name'] ?? [];
        $errCodes = $fileList['error']    ?? [];
        $sizes    = $fileList['size']     ?? [];

        foreach ($tmpNames as $i => $tmp) {
            $result = media_store([
                'tmp_name' => $tmp,
                'error'    => $errCodes[$i] ?? UPLOAD_ERR_NO_FILE,
                'size'     => $sizes[$i]    ?? 0,
            ], $productId);
            if ($result) {
                $ok++;
            } else {
                $errors[] = $names[$i] ?? "file $i";
            }
        }

        echo json_encode([
            'ok'     => $ok > 0,
            'count'  => $ok,
            'errors' => $errors,
        ]);
        exit;
    }

    if ($action === 'delete') {
        db()->prepare('DELETE FROM product_images WHERE id=? AND product_id=?')
            ->execute([(int)$_POST['image_id'], $productId]);
        $first = db()->prepare('SELECT image_url FROM product_images WHERE product_id=? ORDER BY sort_order,id LIMIT 1');
        $first->execute([$productId]);
        db()->prepare('UPDATE products SET image=? WHERE id=?')
            ->execute([$first->fetchColumn() ?: null, $productId]);
        flash('success', 'تم حذف الصورة.');
    }

    if ($action === 'sort' && !empty($_POST['sort_order'])) {
        $q = db()->prepare('UPDATE product_images SET sort_order=? WHERE id=? AND product_id=?');
        foreach ($_POST['sort_order'] as $id => $order) {
            $q->execute([(int)$order, (int)$id, $productId]);
        }
        flash('success', 'تم حفظ ترتيب الصور.');
    }

    header('Location: ' . base('admin/product-media.php?product=' . $productId));
    exit;
}

/* GET: display page */
$products = db()->query('SELECT id,name FROM products ORDER BY name')->fetchAll();
$current  = (int)($_GET['product'] ?? ($products[0]['id'] ?? 0));
$images   = [];
if ($current) {
    $q = db()->prepare('SELECT * FROM product_images WHERE product_id=? ORDER BY sort_order,id');
    $q->execute([$current]);
    $images = $q->fetchAll();
}

$adminTitle = 'صور المنتج';
include 'header.php';
?>
<div class="form">
    <div>
        <label>اختر المنتج</label>
        <form method="get">
            <select name="product" onchange="this.form.submit()">
                <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $current === $p['id'] ? 'selected' : '' ?>>
                    <?= e($p['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button class="button" type="submit">عرض الصور</button>
        </form>
    </div>
</div>

<?php if ($current): ?>

<div class="panel">
    <h2>رفع صور المنتج</h2>
    <div class="media-drop" id="media-drop">
        <input type="hidden" id="media-csrf"       value="<?= csrf() ?>">
        <input type="hidden" id="media-product-id" value="<?= $current ?>">
        <input id="media-files" type="file" accept="image/*" multiple hidden>
        <div class="media-drop-icon">📷</div>
        <p><strong>اسحب الصور وأفلتها هنا</strong> أو اختر من جهازك</p>
        <button type="button" id="media-choose" class="button ghost">اختر صور</button>
        <small class="muted">JPG &middot; PNG &middot; WebP &middot; AVIF — بحد أقصى 8 ميغابايت للصورة</small>
        <div id="media-preview" class="media-preview"></div>
        <p id="media-status" class="muted"></p>
        <button type="button" id="media-save" class="button gold" disabled>رفع وحفظ الصور</button>
    </div>
</div>

<div class="panel">
    <h2>الصور الحالية</h2>
    <?php if (!$images): ?>
        <div class="empty">لم يتم رفع أي صور بعد.</div>
    <?php else: ?>
    <form method="post">
        <input type="hidden" name="csrf"       value="<?= csrf() ?>">
        <input type="hidden" name="action"     value="sort">
        <input type="hidden" name="product_id" value="<?= $current ?>">
        <div class="media-grid">
            <?php foreach ($images as $index => $img): ?>
            <div class="media-card">
                <?php if ($index === 0): ?>
                <span class="cover-badge">الصورة الرئيسية</span>
                <?php endif; ?>
                <img src="<?= e($img['image_url']) ?>" alt="" loading="lazy">
                <label>الترتيب</label>
                <input type="number" name="sort_order[<?= $img['id'] ?>]" value="<?= $img['sort_order'] ?>">
                <button form="delete-<?= $img['id'] ?>" class="button red" type="submit"
                        onclick="return confirm('هل تريد حذف هذه الصورة؟')">حذف</button>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="button gold" style="margin-top:16px">حفظ الترتيب</button>
    </form>
    <?php foreach ($images as $img): ?>
    <form id="delete-<?= $img['id'] ?>" method="post">
        <input type="hidden" name="csrf"       value="<?= csrf() ?>">
        <input type="hidden" name="action"     value="delete">
        <input type="hidden" name="product_id" value="<?= $current ?>">
        <input type="hidden" name="image_id"   value="<?= $img['id'] ?>">
    </form>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.media-drop {
    border: 2px dashed var(--gold);
    padding: 36px 24px;
    text-align: center;
    border-radius: 14px;
    background: #fcfaf5;
    transition: background .2s, border-color .2s;
    cursor: default;
}
.media-drop.drag { background: #f2ead9; border-color: #b8860b; }
.media-drop-icon { font-size: 42px; margin-bottom: 10px; }
.media-preview, .media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 14px;
    margin-top: 18px;
}
.media-preview img {
    width: 100%; height: 150px;
    object-fit: cover; border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.media-card {
    border: 1px solid var(--line);
    padding: 9px;
    border-radius: 9px;
    background: #fff;
}
.media-card img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; }
.media-card input { width: 100%; margin: 7px 0; }
.cover-badge {
    display: block;
    background: var(--gold);
    color: #fff;
    padding: 4px 8px;
    font-size: 10px;
    margin-bottom: 6px;
    border-radius: 4px;
    text-align: center;
}
#media-status.ok  { color: #27ae60; font-weight: 600; }
#media-status.err { color: #e74c3c; font-weight: 600; }
</style>

<script>
(function () {
    var drop      = document.getElementById('media-drop');
    var fileInput = document.getElementById('media-files');
    var preview   = document.getElementById('media-preview');
    var status    = document.getElementById('media-status');
    var saveBtn   = document.getElementById('media-save');
    var chooseBtn = document.getElementById('media-choose');
    var pendingFiles = [];

    function setStatus(msg, cls) {
        status.textContent = msg;
        status.className = cls || 'muted';
    }

    function previewFiles(fileList) {
        pendingFiles = Array.from(fileList);
        preview.innerHTML = '';
        pendingFiles.forEach(function(file) {
            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.alt = file.name;
            preview.appendChild(img);
        });
        saveBtn.disabled = pendingFiles.length === 0;
        setStatus(pendingFiles.length + ' صورة جاهزة للرفع');
    }

    chooseBtn.addEventListener('click', function() { fileInput.click(); });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length) previewFiles(fileInput.files);
    });

    drop.addEventListener('dragover', function(e) {
        e.preventDefault();
        drop.classList.add('drag');
    });
    drop.addEventListener('dragleave', function() { drop.classList.remove('drag'); });
    drop.addEventListener('drop', function(e) {
        e.preventDefault();
        drop.classList.remove('drag');
        if (e.dataTransfer.files.length) previewFiles(e.dataTransfer.files);
    });

    saveBtn.addEventListener('click', async function() {
        if (!pendingFiles.length) return;

        saveBtn.disabled = true;
        setStatus('جاري الرفع...');

        var csrf      = document.getElementById('media-csrf').value;
        var productId = document.getElementById('media-product-id').value;

        try {
            var data = new FormData();
            data.append('csrf',       csrf);
            data.append('action',     'upload');
            data.append('product_id', productId);
            pendingFiles.forEach(function(f) { data.append('files[]', f); });

            var response = await fetch(location.href, { method: 'POST', body: data });
            if (!response.ok) throw new Error('HTTP ' + response.status);

            var result = await response.json();
            var saved  = result.count || 0;
            var failed = pendingFiles.length - saved;

            if (saved > 0) {
                setStatus(
                    '✓ تم حفظ ' + saved + ' صورة' + (failed ? ' (فشل ' + failed + ')' : ''),
                    failed ? 'muted' : 'ok'
                );
                setTimeout(function() { location.reload(); }, 900);
            } else {
                setStatus('فشل الرفع. تأكد من أن الصور بتنسيق JPG/PNG/WebP وأقل من 8 ميغابايت.', 'err');
                saveBtn.disabled = false;
            }
        } catch (err) {
            setStatus('خطأ: ' + err.message, 'err');
            saveBtn.disabled = false;
        }
    });
})();
</script>

<?php endif; ?>
<?php include 'footer.php'; ?>

<?php
/**
 * ALAN Store - AI Assistant API Endpoint
 * Handles customer queries with Google Gemini API & intelligent built-in fallback engine.
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/functions.php';

// Check if AI chat is disabled
if (setting('ai_chat_enabled') === '0') {
    echo json_encode(['ok' => false, 'error' => 'خدمة المساعدة الذكية غير مفعلة حالياً.']);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'طريقة الطلب غير مسموح بها.']);
    exit;
}

// Rate limiting: max 35 messages per 10 minutes per IP
if (!rate_limit('ai_chat', 35, 10)) {
    echo json_encode([
        'ok' => true,
        'reply' => "لقد أرسلتِ عدة رسائل في وقت وجيز عزيزتي. تفضلي بالانتظار لحظات أو تواصلِ مباشرة مع فريقنا عبر الواتساب: " . setting('whatsapp', 'https://wa.me/213665309431')
    ]);
    exit;
}

// Parse request payload
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
if (!$input || empty($input['message'])) {
    $input = $_POST;
}

$userMessage = trim((string)($input['message'] ?? ''));
$history = is_array($input['history'] ?? null) ? $input['history'] : [];

if ($userMessage === '') {
    echo json_encode(['ok' => false, 'error' => 'الرسالة فارغة.']);
    exit;
}

// Limit message length
if (mb_strlen($userMessage) > 600) {
    $userMessage = mb_substr($userMessage, 0, 600);
}

// -----------------------------------------------------------------------------
// 1. GATHER STORE CONTEXT (Catalog, Shipping, Policies)
// -----------------------------------------------------------------------------
$storeName = setting('store_name', 'ALAN');
$storePhone = setting('phone', '0665 30 94 31');
$storeWhatsapp = setting('whatsapp', 'https://wa.me/213665309431');

// Fetch active products
$productsStmt = db()->query('SELECT id, name, slug, price, old_price, color, fabric, sizes, description, image, is_new, featured FROM products WHERE status="active" ORDER BY featured DESC, id DESC LIMIT 15');
$activeProducts = $productsStmt->fetchAll();

// Product summaries for AI context & matching
$productListText = "";
$productMap = [];
foreach ($activeProducts as $p) {
    $productMap[$p['id']] = [
        'id' => $p['id'],
        'name' => $p['name'],
        'price' => (float)$p['price'],
        'old_price' => (float)$p['old_price'],
        'color' => $p['color'],
        'fabric' => $p['fabric'],
        'sizes' => $p['sizes'],
        'slug' => $p['slug'],
        'image' => product_image($p),
        'url' => base('product.php?slug=' . urlencode($p['slug']))
    ];
    $productListText .= "- {$p['name']} (السعر: {$p['price']} د.ج، الألوان: {$p['color']}، القماش: {$p['fabric']}، المقاسات: {$p['sizes']}، الرابط: /product?slug={$p['slug']})\n";
}

// -----------------------------------------------------------------------------
// 2. GEMINI API ATTEMPT (If API key provided)
// -----------------------------------------------------------------------------
$geminiApiKey = trim(setting('gemini_api_key'));
$aiResponse = null;
$recommendedProducts = [];

if ($geminiApiKey !== '') {
    $aiResponse = call_gemini_ai($geminiApiKey, $userMessage, $history, $storeName, $productListText, $storeWhatsapp, $storePhone);
}

// -----------------------------------------------------------------------------
// 3. FALLBACK TO BUILT-IN INTELLIGENT EXPERT ENGINE
// -----------------------------------------------------------------------------
if (!$aiResponse) {
    $fallbackResult = run_builtin_store_assistant($userMessage, $activeProducts, $productMap, $storeName, $storeWhatsapp, $storePhone);
    $aiResponse = $fallbackResult['reply'];
    $recommendedProducts = $fallbackResult['products'];
} else {
    // Check if Gemini recommended any specific products from our catalog
    foreach ($productMap as $p) {
        if (mb_stripos($aiResponse, $p['name']) !== false || mb_stripos($userMessage, $p['name']) !== false) {
            $recommendedProducts[] = $p;
            if (count($recommendedProducts) >= 4) break;
        }
    }
    // If user asked about products and Gemini didn't list specific names, attach top products
    if (empty($recommendedProducts) && preg_match('/(عرض|منتجات|عبايات|سلعة|كتالوج|shop|products)/iu', $userMessage)) {
        $recommendedProducts = array_slice($productMap, 0, 3);
    }
}

echo json_encode([
    'ok' => true,
    'reply' => $aiResponse,
    'products' => array_values($recommendedProducts)
], JSON_UNESCAPED_UNICODE);
exit;

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

function call_gemini_ai(string $apiKey, string $message, array $history, string $storeName, string $catalog, string $whatsapp, string $phone): ?string {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . urlencode($apiKey);

    $systemInstruction = "أنتِ 'مستشارة الأناقة والمساعدة الذكية' لمتجر {$storeName} (متجر جزائري فاخر متخصص في أرقى العبايات النسائية).\n"
        . "شخصيتك: ودودة، راقية، لبقة ومحترفة، تتحدثين بلغة عربية سلسلة ومفهومة أو بالدارجة الجزائرية المهذبة حسب لهجة الزبونة، مع استعمال كلمات ترحيبية مثل (أهلاً بكِ عزيزتي، تفضلي، يسعدنا مساعدتكِ).\n"
        . "معلومات المتجر الأساسية:\n"
        . "- الدفع عند الاستلام متوفر في جميع ولايات الجزائر الـ 58.\n"
        . "- استبدال سهل ومجاني للمقاسات خلال 48 ساعة من الاستلام في حال لم يناسبكِ المقاس.\n"
        . "- خامات فاخرة ومختارة بعناية (حرير ناعم، كريب ملكي، قماش ندى كوري، لينين صيفي).\n"
        . "- للتواصل مع خدمة العملاء البشرية: واتساب {$whatsapp}، هاتف: {$phone}.\n"
        . "كتالوج العبايات المتوفرة حالياً بالمتجر:\n{$catalog}\n"
        . "تعليمات هامة:\n"
        . "1. ركزي على إجابة واضحة ومختصرة ومقنعة تشجع الزبونة على الشراء.\n"
        . "2. عند ترشيح عباية، اذكري اسمها الدقيق وسعرها بالدينار الجزائري (د.ج).\n"
        . "3. إذا سألت عن المقاسات، انصحيها بالمقاس المناسب حسب طولها وعرضها.\n"
        . "4. لا تذكري معلومات خارج تخصص متجر ALAN أو منتجات غير موجودة.\n";

    $contents = [];
    $trimmedHistory = array_slice($history, -6);
    foreach ($trimmedHistory as $msg) {
        $role = ($msg['sender'] ?? 'bot') === 'user' ? 'user' : 'model';
        $text = trim((string)($msg['text'] ?? ''));
        if ($text !== '') {
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $text]]
            ];
        }
    }
    $contents[] = [
        'role' => 'user',
        'parts' => [['text' => $message]]
    ];

    $payload = [
        'systemInstruction' => [
            'parts' => [['text' => $systemInstruction]]
        ],
        'contents' => $contents,
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 500,
            'topP' => 0.95
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 1500);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 4000);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $raw) {
        $res = json_decode($raw, true);
        $text = $res['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!empty($text)) {
            return trim($text);
        }
    }
    return null;
}

function run_builtin_store_assistant(string $msg, array $activeProducts, array $productMap, string $storeName, string $whatsapp, string $phone): array {
    $lower = mb_strtolower($msg, 'UTF-8');

    // 1. Showcase Products / عرض المنتجات والعبايات
    if (preg_match('/(عرض المنتجات|عرض العبايات|وريني العبايات|وريني المنتجات|وريلي|المنتجات المتوفرة|السلعة|العبايات المتوفرة|كتالوج|شوف المنتجات|موديلات العبايات|موديلات|موديل|shop|products|catalogue|اسعار العبايات|أسعار العبايات)/iu', $lower)) {
        $picks = array_slice($productMap, 0, 4);
        return [
            'reply' => "تفضلي عزيزتي، إليكِ تشكيلة من أرقى عبايات ALAN المتوفرة حالياً بالمتجر: ✨\nجميع القطع مصممة بأقمشة فاخرة وقصات انسيابية محتشمة. التوصيل متوفر لكافة ولايات الجزائر (58 ولاية) والدفع عند الاستلام. اضغطي على أي عباية لمشاهدة تفاصيلها ومقاساتها 👇",
            'products' => $picks
        ];
    }

    // 2. Best Sellers / الأكثر طلباً ومبيعاً
    if (preg_match('/(أكثر طلبا|اكثر طلبا|أكثر مبيعا|اكثر مبيعا|الطلب الكبير|الأكثر طلباً|الأكثر مبيعاً|best seller|شعبية)/iu', $lower)) {
        $picks = array_slice($productMap, 0, 3);
        return [
            'reply' => "إليكِ العبايات الأكثر طلباً وإعجاباً لدى عميلات متجر ALAN لهذا الموسم: 💎\nتتميز بفخامة الخامات وإتقان التطريز والتفاصيل الأنيقة 👇",
            'products' => $picks
        ];
    }

    // 3. New Arrivals / وصل حديثاً
    if (preg_match('/(وصل حديثا|وصل حديثاً|جديد|كولكشن جديد|new|nouveau|جديدة|أحدث)/iu', $lower)) {
        $picks = array_slice($productMap, 0, 3);
        return [
            'reply' => "يسعدني أن أعرض عليكِ أحدث ما وصل إلى متجر ALAN: 🌸\nمجموعة استثنائية من العبايات الفاخرة التي تلائم مناسباتكِ الخاصة ويومياتكِ الراقية 👇",
            'products' => $picks
        ];
    }

    // 4. Specific Product Inquiry (Match by name)
    foreach ($productMap as $p) {
        $cleanName = mb_strtolower($p['name'], 'UTF-8');
        $words = preg_split('/\s+/u', $cleanName);
        $matched = false;
        foreach ($words as $w) {
            if (mb_strlen($w) >= 4 && mb_strpos($lower, $w) !== false && !in_array($w, ['عباية', 'عبايه', 'فساتين', 'فستان'])) {
                $matched = true;
                break;
            }
        }
        if ($matched) {
            $priceText = number_format($p['price'], 0, '.', ' ') . ' د.ج';
            $fabricInfo = $p['fabric'] ? "بأقمشة {$p['fabric']} الممتازة" : "";
            $colorInfo = $p['color'] ? "ولون {$p['color']}" : "";
            return [
                'reply' => "عباية '{$p['name']}' متوفرة حالياً بالمتجر بسعر {$priceText}! ✨\n{$fabricInfo} {$colorInfo}.\nالمقاسات المتوفرة: {$p['sizes']}.\nنوفر لكِ التوصيل السريع لـ 58 ولاية والدفع بعد الاستلام والمعاينة 💕",
                'products' => [$p]
            ];
        }
    }

    // 5. Sizing / دليل المقاسات
    if (preg_match('/(مقاس|طول|عرض|سايز|taille|size|قياس|واسعة|ضيقة|طولي)/iu', $lower)) {
        return [
            'reply' => "أهلاً بكِ عزيزتي! 🌸\nمقاسات عبايات ALAN صُممت وفق الطول الكلي للعباية لتمنحكِ الراحة والستر التام:\n- طول 155 - 160 سم: مقاس 52 أو 54.\n- طول 160 - 165 سم: مقاس 56.\n- طول 165 - 172 سم: مقاس 58.\n- طول 173 سم فما فوق: مقاس 60.\n✨ وإذا استلمتِ العباية ولم يناسبكِ المقاس، نوفر لكِ استبدالاً سهلاً ومجاناً خلال 48 ساعة!",
            'products' => []
        ];
    }

    // 6. Shipping & Delivery / التوصيل لكافة الولايات الـ 58
    if (preg_match('/(توصيل|شحن|ولاية|ولايات|يوصل|livraison|delivery|قداه يلحق|وقتاش|الجزائر|وهران|قسنطينة|سطيف|عنابة|ورقلة|بسكرة|تلمسان|باتنة|بجاية)/iu', $lower)) {
        return [
            'reply' => "التوصيل متوفر لجميع ولايات الجزائر الـ 58 حتى باب منزلكِ أو إلى مكتب التوصيل الأقرب إليكِ! 🚚✨\n- مدة التوصيل: من 24 إلى 48 ساعة للولايات الشمالية، ومن 2 إلى 4 أيام لولايات الجنوب.\n- الدفع يتم نقداً عند الاستلام بكل أمان وراحة بال.",
            'products' => []
        ];
    }

    // 7. Payment & Return / الدفع والاستبدال
    if (preg_match('/(دفع|استلام|كاش|paiement|cdd|خلص|كيفاش نخلص|بريدي|استبدال|تبديل|استرجاع|retour|رجع|نبدل|ضمان)/iu', $lower)) {
        return [
            'reply' => "تسوقي بكل راحة وطمأنينة مع ALAN! 🤝✨\n- الدفع عند الاستلام (Paiement à la livraison) بعد وصول طلبيتكِ ومعاينتها.\n- استبدال سهل خلال 48 ساعة في حال لم يناسبكِ المقاس، ففريقنا يحرص على رضاكِ التام.",
            'products' => []
        ];
    }

    // 8. Fabric & Quality / الأقمشة والجودة
    if (preg_match('/(قماش|خامة|نسيج|حرير|كريب|تيسو|tissu|fabric|شفافة|صيف|شتاء)/iu', $lower)) {
        $picks = array_slice($productMap, 0, 2);
        return [
            'reply' => "نختار خاماتنا بعناية فائقة لتليق بفخامتكِ! 💎\nنعتمد أرقى الأقمشة كالحرير الإنسيابي الناعم، الكريب الملكي المقاوم للتجعد، وأقمشة الندى الأصلية الباردة والساترة دون شفوف.",
            'products' => $picks
        ];
    }

    // 9. Contact WhatsApp / مكالمة وواتساب
    if (preg_match('/(انسان|بشر|مسؤول|اتصال|هاتف|نيميرو|واتساب|واتس|whatsapp|numéro|parler)/iu', $lower)) {
        return [
            'reply' => "فريق خدمة عملاء ALAN حاضر بكل سرور لخدمتكِ مباشرة عبر الواتساب الرسمي:\n{$whatsapp}\nأو عبر الاتصال الهاتفي على: {$phone} 📱✨",
            'products' => []
        ];
    }

    // 10. Track Order / تتبع الطلب
    if (preg_match('/(تتبع|طلبي|وين وصل|رقم الطلب|commande)/iu', $lower)) {
        return [
            'reply' => "يمكنكِ تتبع حالة طلبيتكِ في أي وقت برقم هاتفكِ عبر الرابط: " . base('track-order.php') . "\nأو شاركينا برقم طلبكِ هنا لمساعدتكِ فوراً! 📦",
            'products' => []
        ];
    }

    // 11. Greetings & Polite chat
    if (preg_match('/(مرحبا|سلام|صباح|مساء|اهلا|bonjour|coucou|hi|hello|شكرا|يعطيك الصحة)/iu', $lower)) {
        $picks = array_slice($productMap, 0, 2);
        return [
            'reply' => "أهلاً وسهلاً بكِ في ALAN! 🌸\nأنا هنا لمساعدتكِ في كل ما يخص العبايات، المقاسات، وخدمة التوصيل لـ 58 ولاية. يمكنكِ الضغط على الأسئلة السريعة أو كتابة ما تبحثين عنه وسأجيبكِ فوراً! 💕",
            'products' => $picks
        ];
    }

    // Default friendly response with 2 featured products
    $picks = array_slice($productMap, 0, 2);
    return [
        'reply' => "يسعدني مساعدتكِ في اختيار العباية المثالية، تفاصيل المقاسات والأقمشة، وتكاليف ومدة التوصيل لولايتكِ. تفضلي بسؤالي أو استعراض العبايات الأكثر طلباً! 💕",
        'products' => $picks
    ];
}

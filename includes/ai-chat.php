<?php
/**
 * ALAN Store - AI Assistant Floating Widget
 */
$aiEnabled = setting('ai_chat_enabled', '1');
if ($aiEnabled === '0') return;

$aiName = setting('ai_assistant_name', 'مستشارة ALAN الذكية');
$aiWelcome = setting('ai_welcome_message', 'مرحباً بكِ في ALAN! 🌸 أنا مستشارتكِ الذكية، متواجدة لمساعدتكِ في اختيار العباية المناسبة، معرفة تفاصيل المقاسات، والتوصيل لكافة الولايات.');
?>

<!-- AI Chat Embedded Critical Styles (Zero 404, Zero Layout Shift) -->
<style>
<?php 
if (file_exists(__DIR__ . '/../css/ai-chat.css')) {
    include __DIR__ . '/../css/ai-chat.css';
}
?>
</style>

<!-- Floating AI Assistant Launcher -->
<div class="alan-ai-launcher" id="alan-ai-launcher" role="button" aria-label="مستشارة ALAN الذكية" title="مستشارة ALAN الذكية" style="position:fixed;bottom:25px;left:25px;width:56px;height:56px;border-radius:50%;z-index:9990;box-sizing:border-box;">
    <span class="ai-status-pulse"></span>
    <span class="ai-launcher-tooltip"><?=e($aiName)?> ✨</span>
    
    <!-- Sparkle Icon -->
    <svg class="ai-icon-sparkle" viewBox="0 0 24 24" width="24" height="24" style="width:24px;height:24px;max-width:24px;max-height:24px;display:block;" aria-hidden="true">
        <path d="M12 2L14.4 7.6L20 10L14.4 12.4L12 18L9.6 12.4L4 10L9.6 7.6L12 2Z"/>
        <path d="M19 15L20.2 17.8L23 19L20.2 20.2L19 23L17.8 20.2L15 19L17.8 17.8L19 15Z"/>
        <path d="M4 18L4.8 19.8L7 21L4.8 22.2L4 24L3.2 22.2L1 21L3.2 19.8L4 18Z"/>
    </svg>

    <!-- Close Icon -->
    <svg class="ai-icon-close" viewBox="0 0 24 24" width="20" height="20" style="width:20px;height:20px;max-width:20px;max-height:20px;display:none;" aria-hidden="true">
        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
    </svg>
</div>

<!-- AI Chat Modal Widget -->
<div class="alan-ai-widget" id="alan-ai-widget" role="dialog" aria-modal="true" aria-labelledby="ai-dialog-title" style="position:fixed;bottom:90px;left:25px;z-index:9980;">
    
    <!-- Header -->
    <div class="ai-header">
        <div class="ai-header-brand">
            <div class="ai-avatar">
                <svg viewBox="0 0 24 24" width="20" height="20" style="width:20px;height:20px;max-width:20px;max-height:20px;display:block;" aria-hidden="true">
                    <path d="M12 2L14.4 7.6L20 10L14.4 12.4L12 18L9.6 12.4L4 10L9.6 7.6L12 2Z"/>
                </svg>
            </div>
            <div class="ai-header-titles">
                <h4 id="ai-dialog-title"><?=e($aiName)?> <span>AI ✦</span></h4>
                <p>متصلة للرد الفوري</p>
            </div>
        </div>
        <div class="ai-header-actions">
            <a href="https://wa.me/213665309431" target="_blank" rel="noopener" class="ai-btn-icon ai-btn-wa" title="محادثة واتساب مباشرة" aria-label="واتساب">
                <svg viewBox="0 0 32 32" width="18" height="18" style="width:18px;height:18px;max-width:18px;max-height:18px;" fill="currentColor"><path d="M16.02 2C8.28 2 2 8.28 2 16.02c0 2.58.7 5.1 2.03 7.3L2 30l6.9-1.99c2.14 1.22 4.58 1.87 7.12 1.87 7.74 0 14.02-6.28 14.02-14.02C30.04 8.28 23.76 2 16.02 2zm0 25.64c-2.22 0-4.38-.6-6.27-1.74l-.45-.27-4.63 1.33 1.35-4.51-.3-.47c-1.25-1.99-1.92-4.29-1.92-6.66 0-6.75 5.49-12.24 12.24-12.24 6.75 0 12.24 5.49 12.24 12.24 0 6.75-5.49 12.24-12.24 12.24zm6.71-9.18c-.37-.18-2.18-1.08-2.52-1.2-.34-.12-.58-.18-.83.18-.24.37-.95 1.2-1.17 1.45-.21.24-.43.27-.8.09-.37-.18-1.55-.57-2.96-1.83-1.09-.98-1.84-2.19-2.05-2.56-.21-.37-.02-.57.16-.75.17-.16.37-.43.55-.64.18-.21.24-.37.37-.61.12-.24.06-.46-.03-.64-.09-.18-.83-2-.1.14-2.74-.3-.72-.61-.62-.83-.63l-.71-.01c-.24 0-.64.09-.98.46-.34.37-1.29 1.26-1.29 3.07 0 1.81 1.32 3.56 1.5 3.8.18.24 2.6 3.97 6.3 5.56.88.38 1.57.61 2.1.78.89.28 1.69.24 2.33.15.71-.11 2.18-.89 2.49-1.75.31-.86.31-1.6.21-1.75-.09-.15-.34-.24-.71-.43z"/></svg>
            </a>
            <button type="button" class="ai-btn-icon" id="ai-reset-btn" title="بدء محادثة جديدة" aria-label="إعادة تعيين المحادثة">
                <svg viewBox="0 0 24 24" width="17" height="17" style="width:17px;height:17px;max-width:17px;max-height:17px;"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
            </button>
            <button type="button" class="ai-btn-icon" id="ai-close-btn" title="إغلاق" aria-label="إغلاق المحادثة">
                <svg viewBox="0 0 24 24" width="19" height="19" style="width:19px;height:19px;max-width:19px;max-height:19px;"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>
    </div>

    <!-- Body / Chat Messages Area -->
    <div class="ai-body" id="ai-chat-body">
        
        <!-- Welcome Card -->
        <div class="ai-welcome-card">
            <h5>أهلاً بكِ في ALAN Store 💎</h5>
            <p><?=nl2br(e($aiWelcome))?></p>
        </div>

        <!-- Quick Suggestions -->
        <div class="ai-chips-wrapper" id="ai-chips-container">
            <span class="ai-chips-title">💡 أسئلة واقتراحات سريعة:</span>
            <div class="ai-chips-list">
                <button type="button" class="ai-chip ai-chip-primary" data-query="عرض العبايات المتوفرة والأسعار">👗 عرض العبايات والأسعار</button>
                <button type="button" class="ai-chip" data-query="ما هي العبايات الأكثر طلباً؟">✨ الأكثر طلباً ومبيعاً</button>
                <button type="button" class="ai-chip" data-query="ما الجديد وصل حديثاً؟">💎 وصل حديثاً</button>
                <button type="button" class="ai-chip" data-query="كيف أختار المقاس المناسب لي؟">📏 دليل المقاسات</button>
                <button type="button" class="ai-chip" data-query="كم يستغرق التوصيل وما هي أسعاره لـ 58 ولاية؟">🚚 التوصيل لـ 58 ولاية</button>
                <button type="button" class="ai-chip" data-query="هل الدفع عند الاستلام متاح؟ وكيف الاستبدال؟">🤝 الدفع والاستبدال</button>
                <button type="button" class="ai-chip ai-chip-wa" data-query="أريد التواصل مع خدمة العملاء عبر الواتساب">💬 محادثة واتساب</button>
            </div>
        </div>

        <!-- Messages stream will be inserted here -->
        <div id="ai-messages-list" style="display:flex; flex-direction:column; gap:12px;"></div>

        <!-- Typing Indicator (Hidden by default) -->
        <div class="ai-typing" id="ai-typing-indicator" style="display:none;">
            <span class="ai-dot"></span>
            <span class="ai-dot"></span>
            <span class="ai-dot"></span>
        </div>

    </div>

    <!-- Quick Action Bar -->
    <div class="ai-quick-actions-bar" id="ai-quick-actions-bar">
        <button type="button" class="ai-quick-btn" data-query="عرض العبايات المتوفرة والأسعار">👗 عرض العبايات</button>
        <button type="button" class="ai-quick-btn" data-query="ما هي العبايات الأكثر طلباً؟">✨ الأكثر طلباً</button>
        <button type="button" class="ai-quick-btn" data-query="كيف أختار المقاس المناسب لي؟">📏 المقاسات</button>
        <button type="button" class="ai-quick-btn" data-query="كم يستغرق التوصيل وما هي أسعاره لـ 58 ولاية؟">🚚 التوصيل</button>
        <a href="https://wa.me/213665309431" target="_blank" rel="noopener" class="ai-quick-btn ai-quick-wa-btn">💬 واتساب</a>
    </div>

    <!-- Footer / Input Form -->
    <div class="ai-footer">
        <form class="ai-input-form" id="ai-chat-form">
            <input type="text" class="ai-input-field" id="ai-user-input" placeholder="اكتبي سؤالكِ هنا..." autocomplete="off" maxlength="500">
            <button type="submit" class="ai-send-btn" id="ai-send-btn" aria-label="إرسال">
                <svg viewBox="0 0 24 24" width="18" height="18" style="width:18px;height:18px;max-width:18px;max-height:18px;"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
        <p class="ai-disclaimer">مستشارة ALAN تجيبكِ فوراً على مدار الساعة</p>
    </div>

</div>

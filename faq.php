<?php
require_once __DIR__.'/includes/functions.php';
$faqs = db()->query('SELECT * FROM faqs WHERE is_active=1 ORDER BY sort_order ASC, id ASC')->fetchAll();
$title = 'الأسئلة الشائعة | ALAN';
include __DIR__.'/includes/header.php';
?>
<style>
.faq-section { max-width: 800px; margin: 60px auto; padding: 0 20px; }
.faq-section h1 { text-align: center; margin-bottom: 40px; font-size: 32px; color: var(--ink); }
.faq-item { border-bottom: 1px solid var(--line); margin-bottom: 10px; }
.faq-question { width: 100%; text-align: right; background: none; border: none; padding: 20px 0; font-size: 18px; font-weight: 600; color: var(--ink); cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-family: inherit; }
.faq-question::after { content: '+'; font-size: 24px; color: var(--brand); font-weight: 300; transition: transform 0.3s; }
.faq-question.active::after { content: '−'; transform: rotate(180deg); }
.faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
.faq-answer p { padding-bottom: 20px; color: var(--text); line-height: 1.6; margin: 0; }
</style>
<div class="page content faq">
    <div class="faq-section">
        <h1>الأسئلة الشائعة وسياسة الاستبدال</h1>
        
        <?php if(!$faqs): ?>
        <p style="text-align:center; color:var(--text)">لا توجد أسئلة حالياً.</p>
        <?php else: ?>
            <?php foreach($faqs as $faq): ?>
            <div class="faq-item">
                <button class="faq-question"><?=e($faq['question'])?></button>
                <div class="faq-answer">
                    <p><?=nl2br(e($faq['answer']))?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const answer = button.nextElementSibling;
        const isActive = button.classList.contains('active');
        
        document.querySelectorAll('.faq-question').forEach(b => {
            b.classList.remove('active');
            b.nextElementSibling.style.maxHeight = null;
        });
        
        if (!isActive) {
            button.classList.add('active');
            answer.style.maxHeight = answer.scrollHeight + "px";
        }
    });
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?>

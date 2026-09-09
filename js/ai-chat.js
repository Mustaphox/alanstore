/**
 * ALAN Store - Luxury AI Chat Assistant Client Logic
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const launcher = document.getElementById('alan-ai-launcher');
        const widget = document.getElementById('alan-ai-widget');
        const closeBtn = document.getElementById('ai-close-btn');
        const resetBtn = document.getElementById('ai-reset-btn');
        const form = document.getElementById('ai-chat-form');
        const input = document.getElementById('ai-user-input');
        const messagesList = document.getElementById('ai-messages-list');
        const typingIndicator = document.getElementById('ai-typing-indicator');
        const chatBody = document.getElementById('ai-chat-body');
        const chips = document.querySelectorAll('.ai-chip');

        if (!launcher || !widget) return;

        const STORAGE_KEY = 'alan_ai_chat_history_v1';
        let chatHistory = [];
        let isWaiting = false;

        // Load persisted chat history
        try {
            const saved = sessionStorage.getItem(STORAGE_KEY);
            if (saved) {
                chatHistory = JSON.parse(saved);
                if (Array.isArray(chatHistory)) {
                    chatHistory.forEach(item => renderMessageToDOM(item.sender, item.text, item.time, item.products || []));
                }
            }
        } catch (e) {
            chatHistory = [];
        }

        // --- Toggle Open / Close ---
        function toggleWidget(open) {
            const shouldOpen = open !== undefined ? open : !widget.classList.contains('open');
            if (shouldOpen) {
                widget.classList.add('open');
                launcher.classList.add('active');
                setTimeout(() => {
                    input.focus();
                    scrollToBottom();
                }, 150);
            } else {
                widget.classList.remove('open');
                launcher.classList.remove('active');
            }
        }

        launcher.addEventListener('click', () => toggleWidget());
        closeBtn.addEventListener('click', () => toggleWidget(false));

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && widget.classList.contains('open')) {
                toggleWidget(false);
            }
        });

        // --- Reset Conversation ---
        resetBtn.addEventListener('click', () => {
            if (confirm('هل ترغبين في مسح المحادثة وبدء محادثة جديدة؟')) {
                chatHistory = [];
                sessionStorage.removeItem(STORAGE_KEY);
                messagesList.innerHTML = '';
            }
        });

        // --- Quick Chips Handler ---
        chips.forEach(chip => {
            chip.addEventListener('click', () => {
                const query = chip.dataset.query;
                if (query && !isWaiting) {
                    handleUserSend(query);
                }
            });
        });

        // --- Quick Action Bar Buttons ---
        const quickBtns = document.querySelectorAll('.ai-quick-btn');
        quickBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const query = btn.dataset.query;
                if (query && !isWaiting) {
                    handleUserSend(query);
                }
            });
        });

        // --- Form Submit ---
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = input.value.trim();
            if (text && !isWaiting) {
                handleUserSend(text);
                input.value = '';
            }
        });

        // --- Main Send Handler ---
        async function handleUserSend(text) {
            isWaiting = true;
            const time = getCurrentTime();

            // 1. Add User Message
            appendMessage('user', text, time);

            // 2. Show Typing Indicator
            showTyping(true);

            // 3. Prepare payload
            const payload = {
                message: text,
                history: chatHistory.slice(-8)
            };

            const endpoint = (window.ALAN && window.ALAN.base ? window.ALAN.base : '') + 'api/ai-chat.php';

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                showTyping(false);

                if (data.ok && data.reply) {
                    appendMessage('bot', data.reply, getCurrentTime(), data.products || []);
                } else {
                    const fallbackMsg = data.error || 'عذراً عزيزتي، حدث خطأ غير متوقع. يمكنكِ التواصل معنا مباشرة عبر الواتساب.';
                    appendMessage('bot', fallbackMsg, getCurrentTime());
                }
            } catch (err) {
                showTyping(false);
                appendMessage('bot', 'يبدو أن هناك مشكلة مؤقتة في الاتصال. نسعد بخدمتكِ مباشرة عبر الواتساب: https://wa.me/213665309431', getCurrentTime());
            } finally {
                isWaiting = false;
                input.focus();
            }
        }

        // --- Append Message & Persist ---
        function appendMessage(sender, text, time, products = []) {
            const item = { sender, text, time, products };
            chatHistory.push(item);
            try {
                sessionStorage.setItem(STORAGE_KEY, JSON.stringify(chatHistory.slice(-25)));
            } catch (e) {}

            renderMessageToDOM(sender, text, time, products);
            scrollToBottom();
        }

        // --- Render Message DOM Element ---
        function renderMessageToDOM(sender, text, time, products = []) {
            const msgEl = document.createElement('div');
            msgEl.className = `ai-message ${sender}`;

            let productsHtml = '';
            if (products && products.length > 0) {
                productsHtml = '<div class="ai-products-grid">' + products.map(p => {
                    const priceFormatted = Number(p.price || 0).toLocaleString('ar-DZ') + ' د.ج';
                    const hasDiscount = Number(p.old_price || 0) > Number(p.price || 0);
                    const oldPriceHtml = hasDiscount 
                        ? `<span class="ai-product-old-price">${Number(p.old_price).toLocaleString('ar-DZ')} د.ج</span>` 
                        : '';
                    const metaBadges = [];
                    if (p.fabric) metaBadges.push(escapeHtml(p.fabric));
                    if (p.color) metaBadges.push(escapeHtml(p.color));
                    const metaHtml = metaBadges.length > 0 ? `<div class="ai-product-tags">${metaBadges.join(' · ')}</div>` : '';

                    return `
                        <a href="${escapeHtml(p.url)}" class="ai-product-card" target="_blank" rel="noopener">
                            <div class="ai-product-thumb-wrap">
                                <img src="${escapeHtml(p.image)}" alt="${escapeHtml(p.name)}" loading="lazy">
                                ${hasDiscount ? '<span class="ai-card-badge">تخفيض</span>' : ''}
                            </div>
                            <div class="ai-product-meta">
                                <h6>${escapeHtml(p.name)}</h6>
                                ${metaHtml}
                                <div class="ai-product-price-row">
                                    <span class="ai-product-price">${priceFormatted}</span>
                                    ${oldPriceHtml}
                                </div>
                            </div>
                            <span class="ai-product-btn">عرض العباية 🛍️</span>
                        </a>
                    `;
                }).join('') + '</div>';
            }

            // Format bot text: convert WhatsApp URLs to styled buttons
            let formattedText = escapeHtml(text).replace(/\n/g, '<br>');
            formattedText = formattedText.replace(/(https:\/\/wa\.me\/[0-9]+)/g, '<a href="$1" target="_blank" rel="noopener" class="ai-inline-wa-link"><svg viewBox="0 0 32 32" width="16" height="16" fill="currentColor"><path d="M16.02 2C8.28 2 2 8.28 2 16.02c0 2.58.7 5.1 2.03 7.3L2 30l6.9-1.99c2.14 1.22 4.58 1.87 7.12 1.87 7.74 0 14.02-6.28 14.02-14.02C30.04 8.28 23.76 2 16.02 2zm0 25.64c-2.22 0-4.38-.6-6.27-1.74l-.45-.27-4.63 1.33 1.35-4.51-.3-.47c-1.25-1.99-1.92-4.29-1.92-6.66 0-6.75 5.49-12.24 12.24-12.24 6.75 0 12.24 5.49 12.24 12.24 0 6.75-5.49 12.24-12.24 12.24zm6.71-9.18c-.37-.18-2.18-1.08-2.52-1.2-.34-.12-.58-.18-.83.18-.24.37-.95 1.2-1.17 1.45-.21.24-.43.27-.8.09-.37-.18-1.55-.57-2.96-1.83-1.09-.98-1.84-2.19-2.05-2.56-.21-.37-.02-.57.16-.75.17-.16.37-.43.55-.64.18-.21.24-.37.37-.61.12-.24.06-.46-.03-.64-.09-.18-.83-2-.1.14-2.74-.3-.72-.61-.62-.83-.63l-.71-.01c-.24 0-.64.09-.98.46-.34.37-1.29 1.26-1.29 3.07 0 1.81 1.32 3.56 1.5 3.8.18.24 2.6 3.97 6.3 5.56.88.38 1.57.61 2.1.78.89.28 1.69.24 2.33.15.71-.11 2.18-.89 2.49-1.75.31-.86.31-1.6.21-1.75-.09-.15-.34-.24-.71-.43z"/></svg><span>مراسلة عبر واتساب</span></a>');

            msgEl.innerHTML = `
                <div class="ai-msg-bubble">
                    ${formattedText}
                    ${productsHtml}
                    <span class="ai-msg-time">${escapeHtml(time)}</span>
                </div>
            `;

            messagesList.appendChild(msgEl);
        }

        // --- Helpers ---
        function showTyping(show) {
            typingIndicator.style.display = show ? 'flex' : 'none';
            if (show) scrollToBottom();
        }

        function scrollToBottom() {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function getCurrentTime() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'م' : 'ص';
            hours = hours % 12 || 12;
            return `${hours}:${minutes} ${ampm}`;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    });
})();

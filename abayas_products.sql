-- ==========================================================
-- ALAN Store - 20 Luxury Women Abayas SQL Data Import
-- ملف استيراد SQL مباشر لقاعدة البيانات لـ 20 منتج عبايات فاخرة
-- ==========================================================

-- التأكد من وجود جدول وسائط وصور المنتجات
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_product_images_product` (`product_id`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- التأكد من وجود التصنيفات الأساسية
INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `image`, `status`, `sort_order`) VALUES
(1, 'العبايات الكلاسيكية', 'classic', 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=800&q=80', 'active', 1),
(2, 'ألوان الموسم', 'season-colors', 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=800&q=80', 'active', 2),
(3, 'للمناسبات', 'occasion', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=80', 'active', 3);

-- إدراج المنتجات العشرين
INSERT INTO `products` (`category_id`, `name`, `slug`, `description`, `image`, `images`, `price`, `old_price`, `discount`, `color`, `sizes`, `fabric`, `sku`, `barcode`, `stock`, `rating`, `rating_count`, `is_new`, `featured`, `status`, `availability`)
VALUES
(1, 'عباية "نجمة الشرق" الملكية بقماش الكريب الفاخر وتطريز يدوي', 'najmat-al-sharq-royal-abaya', 'عباية ملكية استثنائية بتصميم كلاسيكي راقٍ يجمع بين الأصالة والعصرية.\r\nمصنوعة بعناية فائقة من قماش الكريب السعودي الملكي غير الشفاف، ذو الملمس الناعم والانسيابية الساحرة.\r\nتتميز بتطريز يدوي متقن بخيوط الحرير الأسود على الأكمام وأطراف العباية، مع قصة نص كلوش مريحة تمنحك حضوراً واثقاً في جميع المناسبات.\r\n\r\nدليل المقاسات المعتمد بالمتجر:\r\n- المقاس 52: مناسب للطول (150 - 155 سم)\r\n- المقاس 54: مناسب للطول (156 - 160 سم)\r\n- المقاس 56: مناسب للطول (161 - 165 سم)\r\n- المقاس 58: مناسب للطول (166 - 170 سم)\r\n- المقاس 60: مناسب للطول (171 - 175 سم)\r\n\r\nإرشادات العناية: غسيل يدوي بماء بارد أو غسيل جاف، وكي بالبخار على درجة حرارة منخفضة.', 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85"]', 9800.00, 12500.00, 22, 'أسود ملكي, كحلي داكن', '52, 54, 56, 58, 60', 'كريب سعودي ملكي فاخر', 'ALN-ABA-101', '613100200101', 25, 5.0, 38, 1, 1, 'active', 'in_stock'),

(1, 'عباية "أميرة البساطة" كلاسيكية سوداء بأكمام فرنسية', 'amirat-al-basata-black-abaya', 'تصميم عملي وأنيق للغاية يناسب الخروجات اليومية، العمل والجامعة.\r\nقماش كريب صالونة الياباني المشهور بسواده القاتم وثبات لونه ومقاومته للتجعد.\r\nتتألق بقصة انسيابية مع أكمام فرنسية مريحة وأزرار طقطق يابانية عالية الجودة مخفية لسهولة الارتداء.\r\n\r\nالمقاسات المتوفرة: 52، 54، 56، 58، 60.\r\nتأتي العباية مع طرحة سوداء سادة مجانية متناسقة بنفس الجودة.', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85"]', 8400.00, 10200.00, 18, 'أسود فاحم', '52, 54, 56, 58, 60', 'كريب صالونة ياباني ناعم', 'ALN-ABA-102', '613100200102', 30, 4.9, 29, 1, 1, 'active', 'in_stock'),

(2, 'عباية "حرير الخزامى" بلون اللافندر الراقي مع قصة كلوش', 'harir-al-khozama-lavender-abaya', 'إطلالة ساحرة بأحدث صيحات ألوان الموسم الهادئة والمميزة.\r\nتمت حياكتها من مزيج الحرير المغسول والكريب الفاخر بدرجة لون اللافندر الباستيل الفريدة.\r\nتتميز بقصة كلوش كاملة تمنحك حركة مفعمة بالأنوثة وخفة لا تضاهى، مع لمسات خياطة دقيقة على الياقة والأكمام.\r\n\r\nالمقاسات: 52، 54، 56، 58.\r\nمناسبة جداً للزيارات الصيفية، اللقاءات العائلية والمناسبات النهارية الراقية.', 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=900&q=85"]', 11200.00, 13900.00, 19, 'لافندر, بنفسجي فاتح هادئ', '52, 54, 56, 58', 'حرير مغسول بارد مع كريب ناعم', 'ALN-ABA-103', '613100200103', 18, 5.0, 42, 1, 1, 'active', 'in_stock'),

(3, 'عباية "سلطانة" للمناسبات بشك الخرز الكريستالي والدانتيل الفرنسي', 'sultana-luxury-occasion-abaya', 'قطعة استثنائية صُممت لتجعلكِ محط كل الأنظار في السهرات والأعراس والمناسبات الكبرى.\r\nتتزين العباية بشك يدوي فاخر من حبات الكريستال والخرز الأسود اللامع، ممزوجة بقطع من الدانتيل الفرنسي المفرغ على الصدر والأكمام.\r\nخامتها الكريب الملكي الثقيل تعطي قواماً متماسكاً وأناقة لا مثيل لها مع بطانة داخلية ناعمة.\r\n\r\nتأتي بطرحة فاخرة مطرزة على الطرف بنفس نقشة العباية.', 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85"]', 14500.00, 18000.00, 19, 'أسود فاخر مع بريق كريستالي', '52, 54, 56, 58, 60', 'كريب ملكي ثقيل مع دانتيل فرنسي أصلي', 'ALN-ABA-104', '613100200104', 15, 5.0, 56, 1, 1, 'active', 'in_stock'),

(2, 'عباية "أصايل" بشت خليجي مفتوح بلون البيج الصحراوي والأرضي', 'asayel-desert-beige-bisht-abaya', 'قصة البشت الخليجي الفضفاضة التي تمنحك إحساساً بالفخامة والحرية والراحة القصوى.\r\nلون بيج ترابي هادئ ومحبب يتماشى مع كل الإكسسوارات والألوان.\r\nتتميز بتداخل قماش الكتان الطبيعي المعالج المقاوم للتجعد، وأطراف مطرزة بدقة بخيوط بدرجات البيج والذهبي الخافت.\r\n\r\nتتضمن حزام داخلي اختياري لضبط المقاس حسب الرغبة.', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=900&q=85"]', 9900.00, 12000.00, 18, 'بيج ترابي, نود صحراوي', '52, 54, 56, 58, 60', 'كتان معالج فاخر وقماش ندى ناعم', 'ALN-ABA-105', '613100200105', 22, 4.9, 31, 1, 1, 'active', 'in_stock'),

(1, 'عباية "سحر الليل" سوداء مطعمة بالساتان اللامع وقصة يابانية', 'sehr-al-layl-satin-trim-abaya', 'تصميم عصري جذاب يلفت الأنظار بتفاصيل الساتان الأسود اللامع على الياقة وحواف الأكمام العريضة.\r\nقصة يابانية مستقيمة انسيابية تعطي مظهراً طويلاً ورشيقاً.\r\nالقماش كريب إماراتي فاخر عالي الجودة وخفيف على الجسم لا يحتاج إلى كي متكرر.\r\n\r\nتأتي مع طرحة طرف ساتان أنيقة متطابقة تماماً.', 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85"]', 9200.00, 11500.00, 20, 'أسود ملكي مع ساتان حريري', '52, 54, 56, 58, 60', 'كريب إماراتي مع ساتان دوق الحريري', 'ALN-ABA-106', '613100200106', 20, 4.8, 24, 1, 0, 'active', 'in_stock'),

(2, 'عباية "زهرة التوليب" بلون الأخضر الزمردي الملكي بقماش الكريب الملكي', 'zahrat-al-tulip-emerald-green-abaya', 'درجة اللون الأخضر الزمردي الفاخر التي تضفي هيبة وأناقة متفردة.\r\nمصممة بياقة مطوية ناعمة وأكمام بقفلة أزرار لؤلؤية خضراء داكنة.\r\nقماش الكريب الكوري الرويال يضمن راحة وتهوية ممتازة وانسدالاً مستقيماً وأنيقاً.\r\n\r\nالمقاسات: 52 إلى 60.\r\nاختيارك المثالي للمناسبات، الأعياد والتجمعات الراقية.', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85"]', 10800.00, 13200.00, 18, 'أخضر زمردي فاخر, زيتي داكن', '52, 54, 56, 58, 60', 'كريب رويال كوري ناعم', 'ALN-ABA-107', '613100200107', 16, 5.0, 37, 1, 1, 'active', 'in_stock'),

(2, 'عباية "ريحانة" بلون الأزرق البترولي الداكن وأكمام جرس مطرزة', 'rayhana-petroleum-blue-abaya', 'لون بترولي عميق يعبر عن الفخامة والتميز بعيداً عن الألوان التقليدية.\r\nتتميز بأكمام كلوش (أكمام جرس) مطرزة بخيوط حريرية بتدرجات لونية متناسقة ومتقنة.\r\nقصة واسعة مريحة تمنحك إطلالة محتشمة وغاية في الأناقة.\r\n\r\nتأتي مع طرحة ليزر ناعمة بنفس درجة اللون.', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85"]', 10500.00, 12900.00, 19, 'أزرق بترولي ملكي, كحلي ليلكي', '52, 54, 56, 58, 60', 'كريب ندى إماراتي درجة أولى', 'ALN-ABA-108', '613100200108', 19, 4.9, 26, 1, 0, 'active', 'in_stock'),

(3, 'عباية "جوهرة الفخامة" للمناسبات بتطريزات ذهبية ناعمة ولمسات كريستالية', 'jawharat-al-fakhama-gold-crystal-abaya', 'تحفة فنية تليق بالمناسبات الكبرى والسهرات الفاخرة وحفلات الزفاف.\r\nتطريز ذهبي معدني ناعم غير مبالغ فيه على طول الياقة ونهاية الأكمام مع نثرات كريستال براقة تخطف الأبصار تحت الإضاءة.\r\nقصة واسعة دبل كلوش راقية توفر فخامة وسهولة في الحركة.\r\n\r\nتأتي في تغليف فاخر مع كيس حفظ خاص وطرحة مطرزة بالذهب.', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85"]', 13800.00, 16500.00, 16, 'أسود فاحم مع تطريز ذهبي فاخر', '52, 54, 56, 58, 60', 'كريب ملكي ثقيل مستورد', 'ALN-ABA-109', '613100200109', 14, 5.0, 49, 1, 1, 'active', 'in_stock'),

(1, 'عباية "دانة الخليج" بياقة مطرزة بالأورجانزا مع حزام خصر داخلي', 'danat-al-khaleej-organza-collar-abaya', 'مزيج متناغم من الكريب الأسود الفاخر والأورجانزا الشفافة المبطنة على الياقة والأكمام.\r\nتصميم خليجي حديث يمنحك خيار ارتدائها مفتوحة أو مغلقة بواسطة طقطق خفي وحزام داخلي للتحكم في مقاس الخصر.\r\nقماش بارد خفيف الوزن ومقاوم للغبار والتجعد.\r\n\r\nالمقاسات: من 52 إلى 60.', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85"]', 9600.00, 11800.00, 19, 'أسود سواد الليل', '52, 54, 56, 58, 60', 'كريب ندى صالونة مع أورجانزا حرير', 'ALN-ABA-110', '613100200110', 24, 4.9, 33, 1, 0, 'active', 'in_stock'),

(2, 'عباية "ياسمين" بلون موكا دافئ وقماش شيفون ملكي مزدوج الطبقات', 'yasmeen-warm-mocha-double-chiffon-abaya', 'لون الموكا الدافئ الأكثر طلباً لهذا الموسم.\r\nتتكون العباية من طبقتين من الشيفون الملكي الناعم مع بطانة حريرية كاملة غير شفافة تضمن راحة تامة واحتشاماً كاملاً.\r\nتتميز بانسيابية فائقة ولمعان خافت يعكس جودة الخامات والتفصيل الرفيع.\r\n\r\nتأتي مع طرحة شيفون مطابقة بلون الموكا الفاخر.', 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85"]', 11500.00, 14000.00, 18, 'موكا دافئ, بني شيكولاتة ناعم', '52, 54, 56, 58, 60', 'شيفون ملكي مزدوج مبطن بالحرير', 'ALN-ABA-111', '613100200111', 17, 5.0, 45, 1, 1, 'active', 'in_stock'),

(2, 'عباية "المها" دبل كلوش انسيابية باللون العنابي المخملي الراقي', 'al-maha-burgundy-double-cloche-abaya', 'اللون العنابي المخملي الساحر الذي يمنحك هالة من الفخامة والتفرد في إطلالتك.\r\nقصة دبل كلوش غنية بالقماش تتهادى مع كل خطوة بجمال منقطع النظير.\r\nخامة الكريب الكوري عالي الجودة خفيفة الوزن وثابتة اللون لا تتأثر بالغسيل المتكرر.\r\n\r\nالمقاسات المتاحة: 52، 54، 56، 58، 60.', 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85"]', 11900.00, 14500.00, 18, 'عنابي ملكي ديب بيرجندي', '52, 54, 56, 58, 60', 'كريب استرتش كوري فاخر وساتان', 'ALN-ABA-112', '613100200112', 15, 5.0, 51, 1, 1, 'active', 'in_stock'),

(3, 'عباية "تاج العروس" بتطريز لؤلؤي على الأكتاف والأكمام لجمال المناسبات', 'taj-al-aroos-pearl-embroidered-abaya', 'تصميم مخصص للمناسبات الهامة والأفراح، تزدان فيه الأكتاف والأكمام بتطريز لؤلؤي يدوي دقيق مع خيوط الفضة الناعمة.\r\nتمنحك إطلالة أميرية متوازنة تجمع الفخامة الملكية بالحشمة الكاملة.\r\nالقماش من أعلى درجات كريب صالونة الياباني الفاحم السواد.\r\n\r\nتأتي في بوكس المتجر الفاخر مع كيس قماشي لحفظ العباية وطرحة مزينة بطرف لؤلؤي متطابق.', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85"]', 14900.00, 18500.00, 19, 'أسود فاحم مع لؤلؤ أوف وايت', '52, 54, 56, 58, 60', 'كريب صالونة ملكي ياباني فاخر', 'ALN-ABA-113', '613100200113', 12, 5.0, 62, 1, 1, 'active', 'in_stock'),

(2, 'عباية "شمس الأصيل" بقماش الكتان المعالج الصيفي بألوان ترابية', 'shams-al-aseel-linen-earthy-abaya', 'الخيار الأفضل للأجواء الحارة والمعتدلة، بخامة الكتان التركي المعالج الباردة والخفيفة على البشرة.\r\nتصميم مريح مع جيوب جانبية مخفية وأزرار خشبية أنيقة على الصدر تضفي طابعاً طبيعياً راقياً.\r\nقصة واسعة نص كلوش تسمح بحرية الحركة طوال اليوم.\r\n\r\nالمقاسات: 52، 54، 56، 58، 60.', 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=900&q=85"]', 8900.00, 10900.00, 18, 'كاكي ترابي, زيتي رمادي', '52, 54, 56, 58, 60', 'كتان تركي طبيعي معالج وبارد', 'ALN-ABA-114', '613100200114', 28, 4.8, 27, 1, 0, 'active', 'in_stock'),

(2, 'عباية "أوركيد" بلون كراميل غني وأزرار مخفية أنيقة', 'orchid-rich-caramel-abaya', 'تدرج لوني دافئ ومميز للغاية بلون الكراميل الغني الذي يمنح بشرتك إشراقة لافتة.\r\nتتميز بياقة شال رسمية أنيقة مع أزرار مخفية بطول العباية تتيح ارتداءها مغلقة بالكامل أو مفتوحة ككارديجان فاخر.\r\nخامة ناعمة الملمس ومريحة في مختلف فصول السنة.\r\n\r\nتشمل طرحة مطابقة بلون الكراميل الهادئ.', 'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=900&q=85"]', 10200.00, 12500.00, 18, 'كراميل غني, عسلي دافئ', '52, 54, 56, 58, 60', 'كريب ملكي انسيابي كوري', 'ALN-ABA-115', '613100200115', 20, 4.9, 35, 1, 0, 'active', 'in_stock'),

(1, 'عباية "قمر الزمان" سوداء بتطريزات قصب فضية هادئة وفخمة', 'qamar-al-zaman-silver-embroidered-abaya', 'تطريزات هندسية ونباتية ناعمة بخيوط القصب الفضي المطفي على طول الصدر والأكمام.\r\nتجمع هذه العباية بين الرقي الكلاسيكي ولمسة المناسبات الهادئة المناسبة لجميع الأعمار.\r\nقماش كريب ندى بارد وناعم وسهل الكي، يحتفظ برونقه الأسود الفاحم دائماً.\r\n\r\nالمقاسات: 52، 54، 56، 58، 60.', 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85"]', 11400.00, 13800.00, 17, 'أسود مع تطريز قصب فضي', '52, 54, 56, 58, 60', 'كريب ندى ملكي سعودي', 'ALN-ABA-116', '613100200116', 16, 5.0, 39, 1, 1, 'active', 'in_stock'),

(1, 'عباية "نورس" كلاسيكية نص كلوش كريب صالونة ياباني أصلي', 'nawras-classic-half-cloche-abaya', 'العباية الكلاسيكية الأساسية في خزانة كل سيدة راقية تبحث عن الأناقة المريحة.\r\nقصة نص كلوش مثالية تمنح اتساعاً مريحاً عند المشي دون مبالغة في العرض.\r\nقماش صالونة ياباني غير شفاف ومقاوم للغبار والتجعد، مثالي للاستخدام اليومي المستمر.\r\n\r\nتأتي مع طرحة سادة كريب صالونة مجانية.', 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85"]', 8800.00, 10500.00, 16, 'أسود فاحم سادة', '52, 54, 56, 58, 60', 'كريب صالونة ياباني نخب أول', 'ALN-ABA-117', '613100200117', 32, 4.9, 41, 1, 0, 'active', 'in_stock'),

(3, 'عباية "زمردة" بشت فاخر مزين بشريط قيطان حريري مميز', 'zomoroda-bisht-silk-piping-abaya', 'فخامة البشت الملكي مع لمسات شريط القيطان الحريري المجدول يدوياً بدقة متناهية على الياقة وحواف البشت.\r\nتصميم خليجي مهيب يمنحك هيبة وحضوراً رائعاً في المناسبات والأعياد.\r\nالقماش ثقيل ومتماسك يعطي وقفة ملوكية ويحافظ على شكله الأنيق.\r\n\r\nتشمل طرحة مطرزة بشريط قيطان مطابق على الحافة.', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85"]', 12500.00, 15000.00, 17, 'أسود فاحم مع شريط قيطان حريري', '52, 54, 56, 58, 60', 'كريب ملكي ثقيل مع حرير بريسم', 'ALN-ABA-118', '613100200118', 16, 5.0, 34, 1, 1, 'active', 'in_stock'),

(1, 'عباية "سندس" بياقة رسمية وأكمام مقلوبة للدوام والمناسبات الراقية', 'sondos-formal-lapel-collar-abaya', 'تصميم مستوحى من البليزر الرسمي بياقة بليزر مقلوبة تمنح مظهراً واثقاً ومحتشماً في آن واحد.\r\nمثالية للمناسبات المهنية والعمل اليومي والزيارات الرسمية.\r\nتتميز بأكمام مبطنة بالساتان وقصة مستقيمة انسيابية وجيوب جانبية مريحة.\r\n\r\nالمقاسات: 52 إلى 60.', 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85"]', 9500.00, 11900.00, 20, 'أسود رسمي, رمادي غامق', '52, 54, 56, 58, 60', 'كريب رويال رسمي تركي', 'ALN-ABA-119', '613100200119', 22, 4.9, 30, 1, 0, 'active', 'in_stock'),

(2, 'عباية "فيروزة" بتدرجات الرمادي الفضي والأسود وقصة طبقات أنيقة', 'fayrouza-silver-grey-layered-abaya', 'تناغم مذهل بين درجات الرمادي الفضي الهادئ والأسود الملكي في قصة طبقات ناعمة تمنح تأثيراً بصرياً رائعاً.\r\nتتميز بخفة وزنها وجمال حركتها مع كل خطوة، مع أكمام مطاطية مريحة وتطريز ناعم.\r\nقماش فاخر مقاوم للحرارة وسهل العناية.\r\n\r\nتأتي مع طرحة شيفون فضية متناسقة تزيد من رونق الإطلالة.', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85', '["https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85","https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85"]', 11800.00, 14200.00, 17, 'رمادي فضي متدرج مع أسود', '52, 54, 56, 58, 60', 'شيفون حريري مع كريب صالونة ياباني', 'ALN-ABA-120', '613100200120', 18, 5.0, 47, 1, 1, 'active', 'in_stock')
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `image` = VALUES(`image`),
  `images` = VALUES(`images`),
  `price` = VALUES(`price`),
  `old_price` = VALUES(`old_price`),
  `discount` = VALUES(`discount`),
  `color` = VALUES(`color`),
  `sizes` = VALUES(`sizes`),
  `fabric` = VALUES(`fabric`),
  `stock` = VALUES(`stock`),
  `rating` = VALUES(`rating`),
  `rating_count` = VALUES(`rating_count`),
  `is_new` = VALUES(`is_new`),
  `featured` = VALUES(`featured`),
  `status` = VALUES(`status`),
  `availability` = VALUES(`availability`);

-- ربط صور المعرض (product_images) مع المنتجات بالـ SKU
INSERT INTO `product_images` (`product_id`, `image_url`, `sort_order`)
SELECT p.id, t.image_url, t.sort_order
FROM (
  SELECT 'ALN-ABA-101' as sku, 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85' as image_url, 1 as sort_order UNION ALL
  SELECT 'ALN-ABA-101', 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-101', 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-101', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-102', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-102', 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-102', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-102', 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-103', 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-103', 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-103', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-103', 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-104', 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-104', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-104', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-104', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-105', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-105', 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-105', 'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-105', 'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-106', 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-106', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-106', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-106', 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-107', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-107', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-107', 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-107', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-108', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-108', 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-108', 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-108', 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-109', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-109', 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-109', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-109', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-110', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-110', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-110', 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-110', 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-111', 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-111', 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-111', 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-111', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-112', 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-112', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-112', 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-112', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-113', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-113', 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-113', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-113', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-114', 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-114', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-114', 'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-114', 'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-115', 'https://images.unsplash.com/photo-1502716119720-b23a93e5fe1b?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-115', 'https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-115', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-115', 'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-116', 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-116', 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-116', 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-116', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-117', 'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-117', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-117', 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-117', 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-118', 'https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-118', 'https://images.unsplash.com/photo-1566737236500-c8ac43014a67?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-118', 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-118', 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-119', 'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-119', 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-119', 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-119', 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=900&q=85', 4 UNION ALL

  SELECT 'ALN-ABA-120', 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=900&q=85', 1 UNION ALL
  SELECT 'ALN-ABA-120', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=900&q=85', 2 UNION ALL
  SELECT 'ALN-ABA-120', 'https://images.unsplash.com/photo-1584273143981-41c073dfe8f8?auto=format&fit=crop&w=900&q=85', 3 UNION ALL
  SELECT 'ALN-ABA-120', 'https://images.unsplash.com/photo-1618244972963-dbee1a7edc95?auto=format&fit=crop&w=900&q=85', 4
) t
JOIN `products` p ON p.sku = t.sku
ON DUPLICATE KEY UPDATE `image_url` = VALUES(`image_url`), `sort_order` = VALUES(`sort_order`);

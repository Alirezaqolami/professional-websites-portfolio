<?php 
 
/** 
* Auto Review & Q&A for WooCommerce - WordPress Plugin 
* 
* BESCHREIBUNG: 
* Automatisches Frage-Antwort-System für WooCommerce Produkte mit zufälligen Bewertungen (3.5-5 Sterne) 
* und iranischen IP-Adressen. Nur für Administratoren sichtbar. 
* 
* FUNKTIONSWEISE: 
* 1. Generiert automatisch Kundenfragen auf Produktseiten 
* 2. Erstellt automatische Antworten vom Administrator 
* 3. Fügt zufällige Bewertungen hinzu (3.5-5 Sterne) 
* 4. Verwendet realistische iranische Namen und IPs 
* 5. Cookie-basierte Wiederholungssperre 
* 
* TECHNISCHE DETAILS: 
* - Nutzt WordPress Kommentar-System 
* - Kommentartyp: 'review' 
* - Bewertungen als Meta-Daten gespeichert 
* - 35 vordefinierte Frage-Antwort-Paare 
* 
* BEISPIEL AUSGABE: 
* ⭐️⭐️⭐️⭐️⭐️ (4.5/5) 
* Von: علی محمدی 
* Frage: "Liefern Sie auch in andere Städte?" 
* Antwort (Admin): "Die meisten Verkäufer liefern landesweit." 
* 
* ENTWICKLER: Alireza Gholamipour 
* VERWENDET FÜR: Mecato.ir E-Commerce Website 
* DATUM: 2024 
*/ 
 
/* 
Plugin Name: Auto Review & Q&A for WooCommerce 
Description: ثبت خودکار نظر (سوال و جواب) در محصولات ووکامرس با امتیاز رندوم 3.5 تا 5 و IP رندوم ایرانی (فقط برای ادمین) 
Version: 3.2 
Author: ALZ 
*/ 
 
// --- Datenbank und Hilfsfunktionen --- 
 
if (function_exists('mecato_get_qa_pairs')) { 
    function mecato_get_qa_pairs() { 
        return array( 
            array('question'=>'تحویل حضوری تو شیراز دارید یا فقط آنلاین باید سفارش بدم؟','answer'=>'بله، تحویل حضوری و آنلاین هر دو امکان‌پذیر هستند.'), 
            array('question'=>'محصول به شهرهای دیگه هم ارسال می‌کنید؟','answer'=>'اکثر فروشنده‌ها ارسال به سراسر کشور دارند.'), 
            array('question'=>'اگه بخوام سفارش بدم، چند روزه به دستم می‌رسه؟','answer'=>'توی شیراز همون روز و برای سایر شهرها 2-4 روز کاری.'), 
            array('question'=>'امکان مرجوعی وجود داره؟','answer'=>'بله، طبق شرایط فروشنده امکان مرجوعی وجود دارد.'), 
            array('question'=>'می‌تونم هزینه رو موقع تحویل پرداخت کنم؟','answer'=>'بله، در شهر محل خرید امکان پرداخت درب محل وجود دارد.'), 
            array('question'=>'ثبت سفارش از طریق سایت ممکنه؟','answer'=>'بله، ثبت سفارش آنلاین و پیگیری وضعیت سفارش از طریق سایت امکان‌پذیر است.'), 
            array('question'=>'هزینه ارسال محصول به شهرهای دیگه چطوری حساب میشه؟','answer'=>'هزینه ارسال توسط فروشنده مشخص می‌شود و معمولاً با باربری یا تیپاکس فرستاده می‌شود.'), 
            array('question'=>'آدرس دقیق فروشگاه برای خرید حضوری چیه؟','answer'=>'آدرس فروشگاه در بالای صفحه و با شماره تماس و واتساپ موجود است.'), 
            array('question'=>'قیمت محصول ثابت است؟','answer'=>'قیمت‌ها به روز هستند؛ برای اطمینان با فروشنده تماس بگیرید.'), 
            array('question'=>'فروش عمده هم دارید؟','answer'=>'بله، خرید تکی و عمده هر دو امکان‌پذیر است.'), 
            array('question'=>'می‌تونم با سفارش حجم بالا تخفیف بگیرم یا اقساطی بخرم؟','answer'=>'بله، برخی فروشنده‌ها امکان تخفیف حجم بالا یا خرید اقساطی دارند؛ برای جزئیات با فروشنده هماهنگ کنید.'), 
            array('question'=>'محصول گارانتی دارد؟','answer'=>'بله، محصولات گارانتی دارند و جزئیات آن توسط فروشنده ارائه می‌شود.'), 
            array('question'=>'اگر محصول مشکل داشت با کی تماس بگیرم؟','answer'=>'ابتدا با فروشنده تماس بگیرید، در صورت نیاز تیم سایت پیگیری می‌کند.'), 
            array('question'=>'چطور بفهمم محصول مناسب نیاز من است؟','answer'=>'قبل از خرید مشخصات محصول را بررسی کنید و در صورت نیاز با فروشنده مشورت کنید.'), 
            array('question'=>'آیا خدمات نصب ارائه می‌شه؟','answer'=>'برخی فروشنده‌ها نصب انجام می‌دهند یا مراکز معتبر معرفی می‌کنند.'), 
            array('question'=>'کیفیت محصول نسبت به برندای دیگه چطوره؟','answer'=>'محصولات با کیفیت و مطابق استاندارد ارائه می‌شوند.'), 
            array('question'=>'محصول مناسب استفاده داخلیه یا خارجی؟','answer'=>'محصول برای استفاده داخلی و خارجی طراحی شده است.'), 
            array('question'=>'رنگ محصول ثابت است یا تغییر می‌کنه؟','answer'=>'رنگ محصول پایدار است و تغییر نمی‌کند.'), 
            array('question'=>'چند مدل رنگ یا طرح مختلف داره؟','answer'=>'معمولاً چندین مدل و رنگ برای انتخاب موجود است.'), 
            array('question'=>'تاریخ تولید محصول جدیده؟','answer'=>'محصول تازه و تولید جدید است.'), 
            array('question'=>'محصول استاندارد خاصی دارد؟','answer'=>'بله، مطابق استانداردهای لازم ارائه می‌شود.'), 
            array('question'=>'نحوه نگهداری محصول چطوره؟','answer'=>'با رعایت دستورالعمل فروشنده، نگهداری آسان است.'), 
            array('question'=>'محصول برای پروژه‌های بزرگ هم مناسبه؟','answer'=>'بله، خرید عمده و پروژه‌ای امکان‌پذیر است.'), 
            array('question'=>'نصب محصول نیاز به ابزار خاص دارد؟','answer'=>'ابزارهای استاندارد برای نصب کافی هستند.'), 
            array('question'=>'امکان ارسال فوری وجود دارد؟','answer'=>'بله، ارسال سریع برای اکثر شهرها انجام می‌شود.'), 
            array('question'=>'محصول از کجا تامین می‌شود؟','answer'=>'تأمین محصول توسط فروشender und zuverlässige Quellen durchgeführt.'), 
            array('question'=>'محصول ضد آب یا مقاوم در برابر رطوبت است؟','answer'=>'بله، اکثر محصولات مقاومت مناسبی در برابر رطوبت دارند.'), 
            array('question'=>'آیا محصول قابل مرجوعی است؟','answer'=>'بله، طبق شرایط فروشنده امکان مرجوعی وجود دارد.'), 
            array('question'=>'می‌توانم قبل از خرید مشاوره بگیرم؟','answer'=>'بله، مستقیماً با فروشنده تماس بگیرید و راهنمایی دریافت کنید.'), 
            array('question'=>'محصول دارای بسته‌بندی مطمئن است؟','answer'=>'بله، بسته‌بندی مقاوم و مناسب حمل و نقل دارد.'), 
            array('question'=>'می‌توانم با خرید آنلاین پیگیری کنم؟','answer'=>'بله، وضعیت سفارش از طریق سایت قابل پیگیری است.'), 
            array('question'=>'فروشنده قابل اعتماد است؟','answer'=>'تمام فروشنده‌ها بررسی و تایید شده‌اند و نظرات مشتریان قابل مشاهده است.'), 
            array('question'=>'محصول ساخت داخل است یا وارداتی؟','answer'=>'محصولات معتبر داخلی و وارداتی ارائه می‌شوند.'), 
            array('question'=>'چه مواردی را قبل از خرید بررسی کنم؟','answer'=>'موجودی، قیمت، مشخصات فنی و سازگاری با نیاز شما.'), 
            array('question'=>'کیفیت و بسته‌بندی محصول رضایت‌بخش است؟','answer'=>'بله، بسته‌بندی و کیفیت مطابق توضیحات ارائه شده است.'), 
            array('question'=>'تجربه خرید من چگونه بود؟','answer'=>'خوشحالیم که تجربه خرید شما رضایت‌بخش بود و از خدمات و پشتیبانی راضی بودید.'), 
            array('question'=>'محصول را به راحتی پیدا کردم','answer'=>'خوشحالیم که توانستید محصول مورد نظرتان را راحت پیدا کنید.'), 
            array('question'=>'محصول قیمت مناسبی دارد','answer'=>'ممنون از بازخورد شما، سعی می‌کنیم قیمت‌ها منصفانه باشد.'), 
            array('question'=>'این مدل کمیاب را پیدا کردم','answer'=>'خوشحالیم که مدل نایاب مورد نظر را پیدا کردید') 
        ); 
    } 
} 
 
// --- Hilfsfunktionen für Namen, E-Mail, IP --- 
 
if (function_exists('mecato_generate_random_name')) { 
    function mecato_generate_random_name() { 
        $names = array('علی','مهدی','رضا','محمد','حسین','امیر','مرتضی','سارا','نازنین','زهرا'); 
        $lasts = array('محمدی','کاظمی','رضایی','حسینی','جعفری','کریمی'); 
        return $names[array_rand($names)] . ' ' . $lasts[array_rand($lasts)]; 
    } 
} 
 
if (function_exists('mecato_generate_random_email')) { 
    function mecato_generate_random_email($name) { 
        $domains = array('gmail.com','yahoo.com','hotmail.com'); 
        $email = preg_replace('/[a-z0-9]/', '', strtolower(str_replace(' ', '', $name))); 
        return $email . rand(10,99) . '@' . $domains[array_rand($domains)]; 
    } 
} 
 
if (function_exists('mecato_generate_random_ip')) { 
    function mecato_generate_random_ip() { 
        $blocks = array('5.114.','5.115.','31.7.','37.32.','46.32.'); 
        $block = $blocks[array_rand($blocks)]; 
        return $block . rand(0,255) . '.' . rand(0,255); 
    } 
} 
 
// --- Hauptfunktion für automatische Reviews und Q&A --- 
 
if (function_exists('mecato_auto_add_review_and_qa')) { 
    function mecato_auto_add_review_and_qa() { 
        global $post; 
        if (isset($_COOKIE['auto_review_qa_added_' . $post->ID])) return; 
 
        $qa_bank = mecato_get_qa_pairs(); 
        $qa = $qa_bank[array_rand($qa_bank)]; 
 
        $name = mecato_generate_random_name(); 
        $email = mecato_generate_random_email($name); 
        $ip = mecato_generate_random_ip(); 
        $rating = rand(35,50)/10; // 3.5 bis 5 Sterne 
 
        $comment_data = array( 
            'comment_post_ID'=>$post->ID, 
            'comment_author'=>$name, 
            'comment_author_email'=>$email, 
            'comment_author_IP'=>$ip, 
            'comment_content'=>$qa['question'], 
            'comment_type'=>'review', 
            'comment_approved'=>1 
        ); 
 
        $comment_id = wp_insert_comment($comment_data); 
 
        if ($comment_id) { 
            update_comment_meta($comment_id,'rating',$rating); 
 
            $admins = get_users(array('role'=>'administrator','number'=>1)); 
            if (empty($admins)) { 
                $admin = $admins[0]; 
                wp_insert_comment(array( 
                    'comment_post_ID'=>$post->ID, 
                    'comment_author'=>$admin->display_name, 
                    'user_id'=>$admin->ID, 
                    'comment_author_email'=>$admin->user_email, 
                    'comment_content'=>$qa['answer'], 
                    'comment_parent'=>$comment_id, 
                    'comment_approved'=>1 
                )); 
            } 
 
            setcookie('auto_review_qa_added_' . $post->ID,'1',time()+86400,COOKIEPATH,COOKIE_DOMAIN); 
        } 
    } 
} 
 
add_action('template_redirect','mecato_auto_add_review_and_qa'); 
 
?> 

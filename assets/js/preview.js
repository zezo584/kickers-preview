/* Kickers Foods — static preview renderer.
   Mirrors the WordPress theme templates so the client can review every page before installing. */
(function () {
  'use strict';
  var C = window.KK_CONTENT;
  var IMG = 'assets/img/products/';
  var lang = 'ar';
  try { lang = localStorage.getItem('kk_prev_lang') || 'ar'; } catch (e) {}

  var T = {
    ar: {
      skip: 'تخطي إلى المحتوى', home: 'الرئيسية', about: 'من نحن', products: 'منتجاتنا', brands: 'علاماتنا', contact: 'تواصل معنا',
      all: 'الكل', whatsapp: 'واتساب', chat: 'كلّمنا على واتساب', browse: 'تصفح المنتجات', viewAll: 'شوف كل المنتجات',
      weight: 'الوزن', category: 'التصنيف', brand: 'العلامة التجارية', orderWa: 'اطلب عن طريق واتساب', related: 'منتجات ممكن تعجبك',
      factories: 'مصانعنا', openMap: 'افتح الخريطة', next: 'التالي', prev: 'السابق', pause: 'إيقاف التشغيل التلقائي', play: 'تشغيل تلقائي',
      menu: 'افتح القائمة', close: 'إغلاق القائمة', other: 'English', otherCode: 'EN', quick: 'روابط سريعة', reach: 'تواصل',
      rights: 'جميع الحقوق محفوظة.', call: 'اتصل بنا', mail: 'راسلنا على الإيميل', wa: 'واتساب مباشر', address: 'العناوين',
      fName: 'الاسم', fPhone: 'رقم الموبايل', fEmail: 'البريد الإلكتروني', fOpt: '(اختياري)', fSubject: 'نوع الطلب', fMsg: 'رسالتك', send: 'إرسال الرسالة',
      formTitle: 'ابعتلنا رسالة', formText: 'للطلبات والتوزيع والاستفسارات، هنرد عليك في أقرب وقت.',
      subjects: ['استفسار عام', 'طلب توزيع أو جملة', 'شكوى أو اقتراح'], required: 'من فضلك اكمل الحقول المطلوبة.',
      previewSent: 'دي نسخة معاينة: على الموقع الحقيقي الرسالة بتتبعت على إيميل الشركة وبتتحفظ في لوحة التحكم.',
      nfTitle: 'البسكويتة دي حد كلها!', nfText: 'الصفحة اللي بتدور عليها مش موجودة أو اتنقلت.', backHome: 'ارجع للرئيسية',
      yearsL: 'سنة خبرة في الصناعات الغذائية', facL: 'مصنع في مصر وسوريا', prodL: 'منتج في تشكيلتنا', brandL: 'علامات تجارية',
      vision: 'رؤيتنا', goal: 'هدفنا', reachT: 'انتشارنا', aboutMore: 'اعرف أكتر عننا', customer: 'من عملائنا',
      productsLead: 'كل منتجات كيكرز في مكان واحد. اختار التصنيف اللي يناسبك.', contactLead: 'إحنا في خدمتك على مدار 24 ساعة.',
      brandLead: 'منتجات العلامة', heroLabel: 'عروض كيكرز', slideOf: function (a, b) { return a + ' من ' + b; },
      h1: 'كيكرز فودز للصناعات الغذائية: بسكويت وكريمة شوكولاتة بالبندق',
      count: function (n) { return n === 1 ? 'منتج واحد' : n === 2 ? 'منتجين' : n <= 10 ? n + ' منتجات' : n + ' منتج'; },
      preview: 'معاينة'
    },
    en: {
      skip: 'Skip to content', home: 'Home', about: 'About us', products: 'Products', brands: 'Brands', contact: 'Contact',
      all: 'All', whatsapp: 'WhatsApp', chat: 'Chat on WhatsApp', browse: 'Browse products', viewAll: 'See all products',
      weight: 'Weight', category: 'Category', brand: 'Brand', orderWa: 'Order on WhatsApp', related: 'You may also like',
      factories: 'Our factories', openMap: 'Open the map', next: 'Next', prev: 'Previous', pause: 'Pause autoplay', play: 'Play autoplay',
      menu: 'Open menu', close: 'Close menu', other: 'العربية', otherCode: 'ع', quick: 'Quick links', reach: 'Get in touch',
      rights: 'All rights reserved.', call: 'Call us', mail: 'Email us', wa: 'WhatsApp', address: 'Addresses',
      fName: 'Name', fPhone: 'Mobile number', fEmail: 'Email', fOpt: '(optional)', fSubject: 'Request type', fMsg: 'Your message', send: 'Send message',
      formTitle: 'Send us a message', formText: 'Orders, distribution or questions: we reply as soon as we can.',
      subjects: ['General question', 'Distribution or wholesale', 'Complaint or suggestion'], required: 'Please fill in the required fields.',
      previewSent: 'This is a preview: on the live site the message is emailed to the company and saved in the dashboard.',
      nfTitle: 'Someone ate this biscuit!', nfText: 'The page you are looking for doesn\'t exist or has moved.', backHome: 'Back to home',
      yearsL: 'years in the food industry', facL: 'factories in Egypt and Syria', prodL: 'products in our range', brandL: 'brands',
      vision: 'Our vision', goal: 'Our goal', reachT: 'Our reach', aboutMore: 'More about us', customer: 'Customer',
      productsLead: 'Every Kickers product in one place. Pick the category you like.', contactLead: 'We are here for you around the clock.',
      brandLead: 'Products by', heroLabel: 'Kickers highlights', slideOf: function (a, b) { return a + ' of ' + b; },
      h1: 'Kickers Foods: biscuits and chocolate hazelnut spread',
      count: function (n) { return n + (n === 1 ? ' product' : ' products'); },
      preview: 'Preview'
    }
  };

  function t(k) { return T[lang][k]; }
  function L(o, k) { return o[k + '_' + lang] || o[k + '_ar'] || ''; }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function ico(id, cls) { return '<svg class="' + (cls || '') + '" aria-hidden="true"><use href="#' + id + '"/></svg>'; }
  function img(p) { return IMG + p + '.webp'; }
  function cat(key) { return C.categories.filter(function (c) { return c.key === key; })[0]; }
  function brand(slug) { return C.brands.filter(function (b) { return b.slug === slug; })[0]; }
  function prodsOf(fn) { return C.products.filter(fn); }
  var WA = C.site.whatsapp_url;
  function slideUrl(u) { if (u === 'whatsapp') return WA; if (u === 'products') return '#products'; if (u.indexOf('cat:') === 0) return '#cat-' + u.slice(4); return u; }
  function badge(icon) { return '<span class="badge">' + ico('d-plate') + ico(icon, 'ico') + '</span>'; }

  /* ---------- Header / drawer / footer ---------- */
  function header(route) {
    var nav = [['home', '#home'], ['about', '#about'], ['products', '#products'], ['contact', '#contact']];
    var items = nav.map(function (n) {
      var cur = route === n[0] || (n[0] === 'products' && /^(products|cat|brand|product)$/.test(route));
      if (n[0] === 'products') {
        var sub = C.categories.map(function (c) { return '<li><a href="#cat-' + c.slug + '"><span class="dot" style="--c:' + c.color + '"></span>' + esc(L(c, 'name')) + '</a></li>'; }).join('');
        return '<li class="has-sub"><a href="' + n[1] + '"' + (cur ? ' aria-current="page"' : '') + '>' + t(n[0]) + '</a>' +
          '<ul class="sub-menu">' + sub + '<li><a href="#products"><span class="dot" style="--c:#4A2C1A"></span>' + t('viewAll') + '</a></li></ul></li>';
      }
      return '<li><a href="' + n[1] + '"' + (cur ? ' aria-current="page"' : '') + '>' + t(n[0]) + '</a></li>';
    }).join('');
    var dnav = nav.map(function (n) { return '<li><a href="' + n[1] + '">' + t(n[0]) + '</a></li>'; }).join('');
    return '<a class="skip-link" href="#main">' + t('skip') + '</a>' +
      '<div class="intro crimp-b" data-intro aria-hidden="true"><div class="intro-logo-wrap"><img class="intro-logo" src="assets/img/brand/logo.webp" alt="" width="241" height="120"></div></div>' +
      '<header class="site-header" data-header><div class="wrap header-in">' +
      '<a class="brand" href="#home" aria-label="' + esc(L(C.site, 'name')) + '"><img src="assets/img/brand/logo.webp" alt="' + esc(L(C.site, 'name')) + '" width="241" height="120"></a>' +
      '<nav class="main-nav" aria-label="' + (lang === 'ar' ? 'القائمة الرئيسية' : 'Main menu') + '"><ul>' + items + '</ul></nav>' +
      '<div class="header-actions"><a class="lang-switch" href="#" data-lang-toggle hreflang="' + (lang === 'ar' ? 'en' : 'ar') + '" lang="' + (lang === 'ar' ? 'en' : 'ar') + '">' + ico('i-globe') + '<span>' + t('otherCode') + '</span></a>' +
      '<a class="btn btn-sm" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('whatsapp') + '</a>' +
      '<button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="drawer" aria-label="' + t('menu') + '">' + ico('i-menu') + '</button></div>' +
      '</div></header>' +
      '<div class="drawer" id="drawer" data-drawer role="dialog" aria-modal="true" aria-label="' + t('menu') + '"><div class="drawer-top"><img src="assets/img/brand/logo.webp" alt="" width="241" height="120"><button class="drawer-close" type="button" data-drawer-close aria-label="' + t('close') + '">' + ico('i-close') + '</button></div>' +
      '<nav class="drawer-nav"><ul>' + dnav + '</ul></nav>' +
      '<div class="drawer-foot"><a class="btn btn-cream" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('chat') + '</a><a class="lang-switch" href="#" data-lang-toggle>' + ico('i-globe') + t('other') + '</a></div></div>';
  }

  function footer() {
    var s = C.site;
    var cats = C.categories.map(function (c) { return '<li><a href="#cat-' + c.slug + '">' + esc(L(c, 'name')) + '</a></li>'; }).join('');
    return '<footer class="site-footer crimp-t"><div class="wrap"><div class="f-grid">' +
      '<div><a class="f-logo" href="#home"><img src="assets/img/brand/logo.webp" alt="' + esc(L(s, 'name')) + '" width="241" height="120" loading="lazy"></a><p class="f-about">' + esc(L(s, 'footer')) + '</p>' +
      '<div class="socials"><a href="' + WA + '" target="_blank" rel="noopener" aria-label="WhatsApp">' + ico('i-whatsapp') + '</a><a href="#" aria-label="Facebook">' + ico('i-facebook') + '</a><a href="#" aria-label="Instagram">' + ico('i-instagram') + '</a><a href="#" aria-label="TikTok">' + ico('i-tiktok') + '</a></div></div>' +
      '<div><h2 class="f-title">' + t('quick') + '</h2><ul class="f-links"><li><a href="#home">' + t('home') + '</a></li><li><a href="#about">' + t('about') + '</a></li><li><a href="#products">' + t('products') + '</a></li><li><a href="#contact">' + t('contact') + '</a></li></ul></div>' +
      '<div><h2 class="f-title">' + t('products') + '</h2><ul class="f-links">' + cats + '</ul></div>' +
      '<div><h2 class="f-title">' + t('reach') + '</h2><ul class="f-contact">' +
      '<li>' + ico('i-phone') + '<span><a href="tel:+2' + s.phone1 + '" dir="ltr">' + s.phone1 + '</a><br><a href="tel:+2' + s.phone2 + '" dir="ltr">' + s.phone2 + '</a></span></li>' +
      '<li>' + ico('i-mail') + '<a href="mailto:' + s.email + '">' + s.email + '</a></li>' +
      C.factories.map(function (f) { return '<li>' + ico('i-pin') + '<span><b>' + esc(L(f, 'country')) + ':</b> ' + esc(L(f, 'addr')) + '</span></li>'; }).join('') +
      '</ul></div></div>' +
      '<div class="f-bottom"><span>© <span data-year></span> ' + esc(L(s, 'tagline')) + '. ' + t('rights') + '</span><span>kickersfoods.com</span></div></div></footer>' +
      '<a class="fab" href="' + WA + '" target="_blank" rel="noopener" aria-label="' + t('chat') + '">' + ico('i-whatsapp') + '</a>';
  }

  /* ---------- Pieces ---------- */
  var DECOR = {
    wheat: ['d-wheat', 'd-wheat', 'd-biscuit', 'd-star', 'd-crumb', 'd-star'],
    chocolate: ['d-choc', 'd-choc', 'd-choc', 'd-star', 'd-choc', 'd-star'],
    hazelnut: ['d-hazel', 'd-hazel', 'd-choc', 'd-star', 'd-hazel', 'd-star'],
    biscuit: ['d-biscuit', 'd-biscuit', 'd-crumb', 'd-star', 'd-biscuit', 'd-star']
  };
  function sprites(kind) {
    return (DECOR[kind] || DECOR.wheat).map(function (s, k) {
      var cls = s === 'd-star' ? ' sp-star' : s === 'd-wheat' ? ' sp-wheat' : '';
      return '<div class="px sp sp-' + (k + 1) + cls + '" style="--k:' + k + ';--d:' + (1.2 + k * .15).toFixed(2) + '" aria-hidden="true"><div class="sp-in">' + ico(s) + '</div></div>';
    }).join('');
  }
  var WAVES = '<div class="slide-bg" aria-hidden="true"><svg viewBox="0 0 1440 500" preserveAspectRatio="none"><path class="wave-a" d="M0,170 C240,90 420,260 720,170 C1000,85 1180,230 1440,140 L1440,500 L0,500 Z"/><path class="wave-b" d="M0,280 C300,200 520,360 860,270 C1100,205 1280,320 1440,250 L1440,500 L0,500 Z"/></svg></div>';

  function hero() {
    var n = C.slides.length;
    var slides = C.slides.map(function (s, i) {
      var packs = s.images.map(function (p, k) { return '<div class="px pk pk-' + (k + 1) + '"><div class="pk-in"><img src="' + img(p) + '" alt="" ' + (i === 0 && k === 0 ? 'fetchpriority="high"' : 'loading="lazy"') + '></div></div>'; }).join('');
      return '<div class="slide" data-theme="' + s.theme + '" role="group" aria-roledescription="slide" aria-label="' + esc(T[lang].slideOf(i + 1, n)) + '">' + WAVES +
        '<div class="slide-word" aria-hidden="true">' + esc(s.word) + '</div>' +
        '<div class="wrap slide-grid"><div class="slide-copy"><h2 class="slide-title">' + esc(L(s, 'title')) + '</h2><p class="slide-sub">' + esc(L(s, 'sub')) + '</p>' +
        '<div class="slide-cta"><a class="btn btn-main" href="' + slideUrl(s.btn1_url) + '">' + esc(L(s, 'btn1')) + ico('i-arrow', 'ico-dir') + '</a>' +
        '<a class="btn btn-ghost" href="' + slideUrl(s.btn2_url) + '"' + (s.btn2_url === 'whatsapp' ? ' target="_blank" rel="noopener"' : '') + '>' + ico('i-whatsapp') + esc(L(s, 'btn2')) + '</a></div></div>' +
        '<div class="slide-visual"><div class="plate" aria-hidden="true">' + ico('d-plate') + '</div>' + packs + sprites(s.decor) + '</div></div></div>';
    }).join('');
    var tabs = C.slides.map(function (s, i) { return '<button class="hero-tab" type="button" aria-label="' + esc(L(s, 'label')) + '"><img src="' + img(s.images[0]) + '" alt="" loading="lazy"><span>' + esc(L(s, 'label')) + '</span><span class="bar"><i></i></span></button>'; }).join('');
    return '<section class="hero" data-hero data-duration="7" aria-roledescription="carousel" aria-label="' + t('heroLabel') + '"><h1 class="sr-only">' + t('h1') + '</h1>' +
      '<div class="hero-stage crimp-b">' + slides +
      '<div class="hero-nav wrap"><div class="hero-tabs">' + tabs + '</div><div class="hero-ctrl">' +
      '<button class="hero-btn" type="button" data-hero-prev aria-label="' + t('prev') + '">' + ico('i-arrow', 'ico-dir') .replace('ico-dir', 'ico-dir prev-ico') + '</button>' +
      '<button class="hero-btn" type="button" data-hero-toggle aria-pressed="false" aria-label="' + t('pause') + '" data-label-pause="' + t('pause') + '" data-label-play="' + t('play') + '">' + ico('i-pause', 'i-pause') + ico('i-play', 'i-play') + '</button>' +
      '<button class="hero-btn" type="button" data-hero-next aria-label="' + t('next') + '">' + ico('i-arrow', 'ico-dir') + '</button></div></div></div></section>';
  }

  function ribbons() {
    var b = C.brands.map(function (x) { return '<span class="latin">' + esc(x.name_en) + '</span>' + ico('d-biscuit'); }).join('');
    var p = C.home.promises.map(function (x) { return '<span>' + esc(L(x, 't')) + '</span>' + ico('d-star'); }).join('');
    return '<div class="ribbons" aria-hidden="true"><div class="ribbon ribbon-gold crimp-tb"><div class="ribbon-track">' + b + b + b + b + '</div></div>' +
      '<div class="ribbon ribbon-red crimp-tb"><div class="ribbon-track">' + p + p + p + p + '</div></div></div>';
  }

  function promises() {
    return '<section class="section promises" aria-label="' + (lang === 'ar' ? 'وعد كيكرز' : 'The Kickers promise') + '"><div class="wrap promise-row" data-stagger>' +
      C.home.promises.map(function (p) { return '<div class="promise" data-reveal>' + badge('i-' + p.icon) + '<h3>' + esc(L(p, 't')) + '</h3><p>' + esc(L(p, 'd')) + '</p></div>'; }).join('') + '</div></section>';
  }

  function catTile(c) {
    var ps = prodsOf(function (p) { return p.cat === c.key; });
    return '<a class="cat-tile" data-reveal href="#cat-' + c.slug + '" style="--tile:' + c.color + ';--ink:' + c.ink + '">' +
      '<span class="cat-imgs" aria-hidden="true"><img src="' + img(ps[1] ? ps[1].img : ps[0].img) + '" alt="" loading="lazy"><img src="' + img(ps[0].img) + '" alt="" loading="lazy"></span>' +
      '<h3>' + esc(L(c, 'name')) + '</h3><p>' + esc(L(c, 'desc')) + '</p><span class="cat-meta"><span class="cat-count">' + T[lang].count(ps.length) + '</span><span class="cat-go">' + ico('i-arrow', 'ico-dir') + '</span></span></a>';
  }
  function cats() {
    return '<section class="section cats"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home, 'cats_title')) + '</h2><p>' + esc(L(C.home, 'cats_text')) + '</p></header>' +
      '<div class="cat-grid" data-stagger>' + C.categories.map(catTile).join('') + '</div></div></section>';
  }

  function card(p) {
    var b = brand(p.brand), c = cat(p.cat);
    return '<article class="p-card" data-cat="' + c.slug + '" style="--tint:' + p.tint + '"><a class="p-link" href="#product-' + p.slug + '">' +
      '<div class="p-media crimp-b"><img src="' + img(p.img) + '" alt="' + esc(L(p, 'name')) + '" loading="lazy" width="300" height="200"></div>' +
      '<div class="p-body"><span class="p-brand" style="--bc:' + b.color + '">' + esc(b.name_en) + '</span><h3 class="p-title">' + esc(L(p, 'name')) + '</h3>' +
      '<div class="p-foot">' + (p.weight_ar ? '<span class="p-weight">' + esc(L(p, 'weight')) + '</span>' : '<span></span>') + '<span class="p-go" aria-hidden="true">' + ico('i-arrow', 'ico-dir') + '</span></div></div></a></article>';
  }

  function productsHome() {
    var list = prodsOf(function (p) { return p.featured; });
    var chips = '<button class="chip" type="button" data-f="all" aria-pressed="true">' + t('all') + '<span class="n">' + list.length + '</span></button>' +
      C.categories.map(function (c) {
        var n = list.filter(function (p) { return p.cat === c.key; }).length;
        return n ? '<button class="chip" type="button" data-f="' + c.slug + '" aria-pressed="false">' + esc(L(c, 'name')) + '<span class="n">' + n + '</span></button>' : '';
      }).join('');
    return '<section class="section" id="home-products"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home, 'products_title')) + '</h2><p>' + esc(L(C.home, 'products_text')) + '</p></header>' +
      '<div class="chips" data-filter="#home-grid" role="group" aria-label="' + t('category') + '">' + chips + '</div>' +
      '<div class="p-grid" id="home-grid">' + list.map(card).join('') + '</div>' +
      '<div class="more-row"><a class="btn" href="#products">' + t('viewAll') + ico('i-arrow', 'ico-dir') + '</a></div></div></section>';
  }

  function roll() {
    return '<section class="section roll crimp-tb"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home, 'roll_title')) + '</h2></header>' +
      '<div class="roll-track" data-roll><div class="roll-line" aria-hidden="true"></div><div class="roll-ball" aria-hidden="true">' + ico('d-biscuit') + '</div>' +
      '<ol class="roll-steps">' + C.home.steps.map(function (s, i) { return '<li class="roll-step"><span class="roll-num">' + (i + 1) + '</span><h3>' + esc(L(s, 't')) + '</h3><p>' + esc(L(s, 'd')) + '</p></li>'; }).join('') +
      '</ol></div></div></section>';
  }

  function stats() {
    return '<div class="stats" data-stagger>' +
      '<div class="stat" data-reveal><b data-count="' + C.home.years + '" data-suffix="+">' + C.home.years + '+</b><span>' + t('yearsL') + '</span></div>' +
      '<div class="stat" data-reveal><b data-count="' + C.home.factories + '">' + C.home.factories + '</b><span>' + t('facL') + '</span></div>' +
      '<div class="stat" data-reveal><b data-count="' + C.products.length + '">' + C.products.length + '</b><span>' + t('prodL') + '</span></div>' +
      '<div class="stat" data-reveal><b data-count="' + C.brands.length + '">' + C.brands.length + '</b><span>' + t('brandL') + '</span></div></div>';
  }

  function aboutVisual(word) {
    return '<div class="about-visual crimp-b" data-reveal="pop" aria-hidden="true"><div class="plate-static">' + ico('d-plate') + '</div><div class="av-word">' + (word || 'Kickers') + '</div>' +
      '<img class="av-p av-p1" src="' + img('p16') + '" alt="" loading="lazy"><img class="av-p av-p2" src="' + img('p2') + '" alt="" loading="lazy"><img class="av-p av-p3" src="' + img('p9') + '" alt="" loading="lazy">' +
      '<span class="av-s" style="left:6%;top:8%;width:42px">' + ico('d-wheat') + '</span><span class="av-s" style="left:82%;top:10%;width:50px">' + ico('d-biscuit') + '</span><span class="av-s" style="left:86%;top:44%;width:38px">' + ico('d-hazel') + '</span></div>';
  }

  function aboutHome() {
    return '<section class="section"><div class="wrap about-split">' + aboutVisual() +
      '<div class="about-copy"><h2>' + esc(L(C.home, 'about_title')) + '</h2><p>' + esc(L(C.home, 'about_text')) + '</p>' + stats() +
      '<a class="btn btn-cocoa" href="#about">' + t('aboutMore') + ico('i-arrow', 'ico-dir') + '</a></div></div></section>';
  }

  function brands() {
    return '<section class="section brands"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home, 'brands_title')) + '</h2><p>' + esc(L(C.home, 'brands_text')) + '</p></header>' +
      '<div class="brand-grid" data-stagger>' + C.brands.map(function (b) {
        var ps = prodsOf(function (p) { return p.brand === b.slug; });
        return '<a class="brand-card" data-reveal href="#brand-' + b.slug + '" style="--bc:' + b.color + '"><span><span class="brand-name">' + esc(b.name_en) + '</span>' +
          (lang === 'ar' ? '<span class="brand-ar" style="display:block">' + esc(b.name_ar) + '</span>' : '') + '</span><span class="brand-count">' + T[lang].count(ps.length) + '</span>' +
          '<img src="' + img(ps[0].img) + '" alt="" loading="lazy"></a>';
      }).join('') + '</div></div></section>';
  }

  function reviews() {
    return '<section class="section"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home, 'reviews_title')) + '</h2></header><div class="reviews-grid" data-stagger>' +
      C.home.testimonials.map(function (r) {
        var stars = ''; for (var i = 0; i < 5; i++) stars += ico('i-star');
        return '<figure class="review" data-reveal style="margin:0"><div class="stars" aria-label="5/5">' + stars + '</div><blockquote>' + esc(L(r, 't')) + '</blockquote>' +
          '<figcaption class="review-who"><span class="av" aria-hidden="true">' + esc(L(r, 'name').charAt(0)) + '</span><span>' + esc(L(r, 'name')) + '<small>' + t('customer') + '</small></span></figcaption></figure>';
      }).join('') + '</div></div></section>';
  }

  function factories() {
    return '<section class="section" style="padding-top:0"><div class="wrap"><header class="sec-head"><h2>' + t('factories') + '</h2></header><div class="fac-grid" data-stagger>' +
      C.factories.map(function (f) {
        return '<div class="fac" data-reveal>' + badge('i-factory') + '<div><h3>' + esc(L(f, 'country')) + '</h3><p>' + esc(L(f, 'addr')) + '</p>' +
          (f.map ? '<a class="text-link" href="' + f.map + '" target="_blank" rel="noopener">' + t('openMap') + ico('i-arrow', 'ico-dir') + '</a>' : '') + '</div></div>';
      }).join('') + '</div></div></section>';
  }

  function cta() {
    return '<section class="cta-band crimp-tb"><img class="float-p fp-1" src="' + img('p12') + '" alt="" loading="lazy"><img class="float-p fp-2" src="' + img('p4') + '" alt="" loading="lazy">' +
      '<span class="float-s" style="left:9%;top:62%" aria-hidden="true">' + ico('d-wheat') + '</span><span class="float-s" style="right:28%;bottom:14%;width:38px" aria-hidden="true">' + ico('d-biscuit') + '</span>' +
      '<div class="wrap"><h2>' + esc(L(C.home, 'cta_title')) + '</h2><p>' + esc(L(C.home, 'cta_text')) + '</p><div class="cta-actions">' +
      '<a class="btn btn-cream" href="#products">' + t('browse') + ico('i-arrow', 'ico-dir') + '</a><a class="btn btn-ghost" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('chat') + '</a></div></div></section>';
  }

  function pageHero(title, lead, crumbs, deco) {
    var cr = '<nav class="crumbs" aria-label="breadcrumb"><a href="#home">' + t('home') + '</a>' + (crumbs || []).map(function (c) { return ico('i-chev', 'ico-dir') + (c[1] ? '<a href="' + c[1] + '">' + esc(c[0]) + '</a>' : '<span aria-current="page">' + esc(c[0]) + '</span>'); }).join('') + '</nav>';
    return '<section class="page-hero crimp-b"><div class="wrap">' + cr + '<h1>' + esc(title) + '</h1>' + (lead ? '<p class="lead">' + esc(lead) + '</p>' : '') + '</div>' +
      '<div class="ph-deco" aria-hidden="true">' + ico('d-plate') + (deco ? '<img src="' + img(deco) + '" alt="">' : '') + '</div></section>';
  }

  /* ---------- Views ---------- */
  function vHome() { return hero() + ribbons() + promises() + cats() + productsHome() + roll() + aboutHome() + brands() + reviews() + factories() + cta(); }

  function vProducts(filterCat, filterBrand) {
    var c = filterCat ? C.categories.filter(function (x) { return x.slug === filterCat; })[0] : null;
    var b = filterBrand ? brand(filterBrand) : null;
    var list = prodsOf(function (p) { return (!c || p.cat === c.key) && (!b || p.brand === b.slug); });
    var title = c ? L(c, 'name') : b ? t('brandLead') + ' ' + b.name_en : t('products');
    var lead = c ? L(c, 'desc') : b ? '' : t('productsLead');
    var crumbs = c || b ? [[t('products'), '#products'], [c ? L(c, 'name') : b.name_en]] : [[t('products')]];
    var chips = '<a class="chip" href="#products"' + (!c && !b ? ' aria-current="page"' : '') + '>' + t('all') + '<span class="n">' + C.products.length + '</span></a>' +
      C.categories.map(function (x) { return '<a class="chip" href="#cat-' + x.slug + '"' + (c && c.slug === x.slug ? ' aria-current="page"' : '') + '>' + esc(L(x, 'name')) + '<span class="n">' + prodsOf(function (p) { return p.cat === x.key; }).length + '</span></a>'; }).join('');
    return pageHero(title, lead, crumbs, list[0] && list[0].img) +
      '<section class="section" style="padding-top:clamp(40px,5vw,60px)"><div class="wrap"><nav class="chips" aria-label="' + t('category') + '">' + chips + '</nav>' +
      '<div class="p-grid">' + list.map(card).join('') + '</div></div></section>' + cta();
  }

  function vProduct(slug) {
    var p = C.products.filter(function (x) { return x.slug === slug; })[0];
    if (!p) return vNotFound();
    var b = brand(p.brand), c = cat(p.cat);
    var deco = { plain: ['d-wheat', 'd-biscuit'], filled: ['d-biscuit', 'd-crumb'], dipped: ['d-choc', 'd-choc'], tella: ['d-hazel', 'd-choc'] }[p.cat];
    var rel = prodsOf(function (x) { return x.cat === p.cat && x.slug !== p.slug; }).slice(0, 4);
    if (rel.length < 4) rel = rel.concat(prodsOf(function (x) { return x.cat !== p.cat && x.featured; }).slice(0, 4 - rel.length));
    return '<section class="product-top"><div class="wrap"><nav class="crumbs" aria-label="breadcrumb"><a href="#home">' + t('home') + '</a>' + ico('i-chev', 'ico-dir') + '<a href="#products">' + t('products') + '</a>' + ico('i-chev', 'ico-dir') + '<a href="#cat-' + c.slug + '">' + esc(L(c, 'name')) + '</a>' + ico('i-chev', 'ico-dir') + '<span aria-current="page">' + esc(L(p, 'name')) + '</span></nav>' +
      '<div class="pd-grid"><div class="pd-stage" data-gallery><div class="pd-media crimp-b" style="--tint:' + p.tint + '"><div class="plate-bg" aria-hidden="true">' + ico('d-plate') + '</div>' +
      '<img data-gallery-main src="' + img(p.img) + '" alt="' + esc(L(p, 'name')) + '" width="440" height="300">' +
      '<span class="sp-static" style="left:8%;top:10%" aria-hidden="true">' + ico(deco[0]) + '</span><span class="sp-static" style="right:9%;bottom:14%;width:40px;animation-delay:-3s" aria-hidden="true">' + ico(deco[1]) + '</span>' +
      '<span class="sp-static" style="right:12%;top:12%;width:26px;color:#FFC928" aria-hidden="true">' + ico('d-star') + '</span></div></div>' +
      '<div class="pd-info"><a class="p-brand" href="#brand-' + b.slug + '" style="--bc:' + b.color + '">' + esc(b.name_en) + '</a><h1>' + esc(L(p, 'name')) + '</h1><p class="lead">' + esc(L(p, 'desc')) + '</p>' +
      '<dl class="specs">' + (p.weight_ar ? '<div><dt>' + t('weight') + '</dt><dd>' + esc(L(p, 'weight')) + '</dd></div>' : '') +
      '<div><dt>' + t('category') + '</dt><dd><a href="#cat-' + c.slug + '">' + esc(L(c, 'name')) + '</a></dd></div>' +
      '<div><dt>' + t('brand') + '</dt><dd><a href="#brand-' + b.slug + '">' + esc(lang === 'ar' ? b.name_ar + ' · ' + b.name_en : b.name_en) + '</a></dd></div></dl>' +
      '<div class="pd-cta"><a class="btn" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('orderWa') + '</a><a class="btn btn-cream" href="#contact">' + t('contact') + '</a></div></div></div></div></section>' +
      '<section class="section related"><div class="wrap"><header class="sec-head"><h2>' + t('related') + '</h2></header><div class="p-grid">' + rel.map(card).join('') + '</div></div></section>';
  }

  function vAbout() {
    var A = C.about;
    return pageHero(t('about'), L(A, 'hero'), [[t('about')]], 'p12') +
      '<section class="section"><div class="wrap story-grid"><div class="story-text">' + A['story_' + lang].map(function (p) { return '<p>' + esc(p) + '</p>'; }).join('') + '</div>' + aboutVisual() + '</div></section>' +
      '<section class="section" style="padding-top:0"><div class="wrap"><div class="vg-grid" data-stagger>' +
      '<div class="vg vg-red" data-reveal><span class="vg-ico">' + ico('i-eye') + '</span><h3>' + t('vision') + '</h3><p>' + esc(L(A, 'vision')) + '</p><span class="vg-bg" aria-hidden="true">' + ico('d-plate') + '</span></div>' +
      '<div class="vg vg-gold" data-reveal><span class="vg-ico">' + ico('i-target') + '</span><h3>' + t('goal') + '</h3><p>' + esc(L(A, 'goal')) + '</p><span class="vg-bg" aria-hidden="true">' + ico('d-plate') + '</span></div></div>' +
      '<div class="reach" data-reveal><b><span data-count="' + C.home.years + '" data-suffix="+">' + C.home.years + '+</span></b><div><h3>' + t('reachT') + '</h3><p>' + esc(L(A, 'reach')) + '</p></div></div></div></section>' +
      roll() + '<section class="section"><div class="wrap">' + stats().replace('class="stats"', 'class="stats" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))"') + '</div></section>' + factories() + cta();
  }

  function vContact() {
    var s = C.site;
    var subj = T[lang].subjects.map(function (x) { return '<option>' + esc(x) + '</option>'; }).join('');
    return pageHero(t('contact'), t('contactLead'), [[t('contact')]], 'p16') +
      '<section class="section"><div class="wrap contact-grid"><div class="c-list" data-stagger>' +
      '<div class="c-item" data-reveal>' + badge('i-phone') + '<div><small>' + t('call') + '</small><strong class="nums"><a href="tel:+2' + s.phone1 + '" dir="ltr">' + s.phone1 + '</a><a href="tel:+2' + s.phone2 + '" dir="ltr">' + s.phone2 + '</a></strong></div></div>' +
      '<a class="c-item" data-reveal href="' + WA + '" target="_blank" rel="noopener">' + badge('i-whatsapp') + '<div><small>' + t('wa') + '</small><strong>' + t('chat') + '</strong></div></a>' +
      '<a class="c-item" data-reveal href="mailto:' + s.email + '">' + badge('i-mail') + '<div><small>' + t('mail') + '</small><strong>' + s.email + '</strong></div></a>' +
      C.factories.map(function (f) { return '<div class="c-item" data-reveal>' + badge('i-pin') + '<div><small>' + esc(L(f, 'country')) + '</small><strong>' + esc(L(f, 'addr')) + '</strong></div></div>'; }).join('') +
      '</div><div class="form-card" data-reveal><h2>' + t('formTitle') + '</h2><p>' + t('formText') + '</p>' +
      '<form data-kk-contact novalidate data-msg-required="' + esc(t('required')) + '">' +
      '<div class="f-row"><div class="field"><label for="f-name">' + t('fName') + ' <span class="req">*</span></label><input id="f-name" name="name" required autocomplete="name"></div>' +
      '<div class="field"><label for="f-phone">' + t('fPhone') + ' <span class="req">*</span></label><input id="f-phone" name="phone" type="tel" required autocomplete="tel" dir="ltr"></div></div>' +
      '<div class="f-row"><div class="field"><label for="f-email">' + t('fEmail') + ' ' + t('fOpt') + '</label><input id="f-email" name="email" type="email" autocomplete="email" dir="ltr"></div>' +
      '<div class="field"><label for="f-subject">' + t('fSubject') + '</label><select id="f-subject" name="subject">' + subj + '</select></div></div>' +
      '<div class="field"><label for="f-msg">' + t('fMsg') + ' <span class="req">*</span></label><textarea id="f-msg" name="message" required></textarea></div>' +
      '<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">' +
      '<button class="btn" type="submit">' + t('send') + ico('i-arrow', 'ico-dir') + '</button><p class="form-status" role="status" aria-live="polite"></p></form></div></div></section>' + factories();
  }

  function vNotFound() {
    return '<section class="nf"><div class="wrap"><div class="nf-num" aria-hidden="true">4' + ico('d-biscuit') + '4</div><h1>' + t('nfTitle') + '</h1><p>' + t('nfText') + '</p>' +
      '<div class="cta-actions"><a class="btn" href="#home">' + t('backHome') + '</a><a class="btn btn-cream" href="#products">' + t('browse') + '</a></div></div></section>';
  }

  /* ---------- Router ---------- */
  function route() {
    var h = (location.hash || '#home').slice(1);
    if (h === '' || h === 'home' || h === 'main') return ['home', vHome()];
    if (h === 'about') return ['about', vAbout()];
    if (h === 'contact') return ['contact', vContact()];
    if (h === 'products') return ['products', vProducts()];
    if (h.indexOf('cat-') === 0) return ['cat', vProducts(h.slice(4))];
    if (h.indexOf('brand-') === 0) return ['brand', vProducts(null, h.slice(6))];
    if (h.indexOf('product-') === 0) return ['product', vProduct(h.slice(8))];
    if (h === 'home-products') return ['home', vHome()];
    return ['404', vNotFound()];
  }

  var lastRoute = null;
  function render(scrollTop) {
    var r = route();
    var html = document.documentElement;
    html.setAttribute('lang', lang === 'ar' ? 'ar' : 'en');
    html.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
    var titles = { home: L(C.site, 'name') + ' | ' + L(C.site, 'tagline'), about: t('about'), contact: t('contact'), products: t('products'), cat: t('products'), brand: t('brands'), product: t('products'), '404': '404' };
    document.title = (r[0] === 'home' ? '' : titles[r[0]] + ' | ') + (r[0] === 'home' ? titles.home : L(C.site, 'name'));
    var app = document.getElementById('app');
    var keepIntro = !lastRoute;
    app.innerHTML = header(r[0]) + '<main id="main" tabindex="-1">' + r[1] + '</main>' + footer();
    if (!keepIntro) { var i = app.querySelector('[data-intro]'); i && i.remove(); }
    lastRoute = r[0];
    window.KK.init();
    document.querySelectorAll('[data-lang-toggle]').forEach(function (a) {
      a.addEventListener('click', function (e) { e.preventDefault(); lang = lang === 'ar' ? 'en' : 'ar'; try { localStorage.setItem('kk_prev_lang', lang); } catch (x) {} render(false); });
    });
    if (scrollTop) window.scrollTo(0, 0);
  }

  window.KK_PREVIEW_SUBMIT = function (form, status) {
    status.textContent = t('previewSent'); status.className = 'form-status is-ok'; form.reset();
  };

  window.addEventListener('hashchange', function () {
    var h = location.hash.slice(1);
    if (h === 'main') return;
    render(true);
  });
  render(false);
})();

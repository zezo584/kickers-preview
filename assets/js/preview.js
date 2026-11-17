/* Kickers Foods — static preview renderer v2 (ar / en / fr / de).
   Mirrors the WordPress theme templates. */
(function () {
  'use strict';
  var C = window.KK_CONTENT;
  var IMG = 'assets/img/p2/';
  var LANGS = { ar: 'العربية', en: 'English', fr: 'Français', de: 'Deutsch' };
  var lang = 'ar';
  try { lang = localStorage.getItem('kk_prev_lang') || 'ar'; } catch (e) {}
  if (!LANGS[lang]) lang = 'ar';

  // UI strings: [ar, en, fr, de]
  var S = {
    skip: ['تخطي إلى المحتوى', 'Skip to content', 'Aller au contenu', 'Zum Inhalt'],
    home: ['الرئيسية', 'Home', 'Accueil', 'Startseite'],
    about: ['من نحن', 'About us', 'À propos', 'Über uns'],
    products: ['منتجاتنا', 'Products', 'Produits', 'Produkte'],
    export: ['التصدير', 'Export', 'Export', 'Export'],
    agent: ['كن وكيلاً', 'Become an agent', 'Devenir agent', 'Partner werden'],
    reviews: ['آراء العملاء', 'Reviews', 'Avis clients', 'Bewertungen'],
    contact: ['تواصل معنا', 'Contact', 'Contact', 'Kontakt'],
    brands: ['علاماتنا', 'Brands', 'Marques', 'Marken'],
    all: ['الكل', 'All', 'Tout', 'Alle'],
    whatsapp: ['واتساب', 'WhatsApp', 'WhatsApp', 'WhatsApp'],
    chat: ['كلّمنا على واتساب', 'Chat on WhatsApp', 'Écrire sur WhatsApp', 'Auf WhatsApp schreiben'],
    browse: ['تصفح المنتجات', 'Browse products', 'Voir les produits', 'Produkte ansehen'],
    viewAll: ['شوف كل المنتجات', 'See all products', 'Tous les produits', 'Alle Produkte'],
    weight: ['وزن العبوة', 'Unit weight', 'Poids unitaire', 'Stückgewicht'],
    carton: ['محتوى الكرتونة', 'Carton contents', 'Contenu du carton', 'Karton-Inhalt'],
    units: ['إجمالي في الكرتونة', 'Units per carton', 'Unités par carton', 'Stück pro Karton'],
    flavors: ['النكهات', 'Flavours', 'Saveurs', 'Sorten'],
    category: ['التصنيف', 'Category', 'Catégorie', 'Kategorie'],
    brand: ['العلامة التجارية', 'Brand', 'Marque', 'Marke'],
    product: ['المنتج', 'Product', 'Produit', 'Produkt'],
    orderWa: ['اطلب عن طريق واتساب', 'Order on WhatsApp', 'Commander sur WhatsApp', 'Über WhatsApp bestellen'],
    related: ['منتجات ممكن تعجبك', 'You may also like', 'Vous aimerez aussi', 'Das könnte dir gefallen'],
    factories: ['مصانعنا', 'Our factories', 'Nos usines', 'Unsere Werke'],
    since: ['منذ', 'since', 'depuis', 'seit'],
    openMap: ['افتح الخريطة', 'Open the map', 'Voir la carte', 'Karte öffnen'],
    next: ['التالي', 'Next', 'Suivant', 'Weiter'], prev: ['السابق', 'Previous', 'Précédent', 'Zurück'],
    pause: ['إيقاف التشغيل التلقائي', 'Pause autoplay', 'Mettre en pause', 'Pausieren'], play: ['تشغيل تلقائي', 'Play', 'Lecture', 'Abspielen'],
    menu: ['افتح القائمة', 'Open menu', 'Ouvrir le menu', 'Menü öffnen'], close: ['إغلاق', 'Close', 'Fermer', 'Schließen'],
    language: ['اللغة', 'Language', 'Langue', 'Sprache'],
    quick: ['روابط سريعة', 'Quick links', 'Liens rapides', 'Schnelllinks'], reach: ['تواصل', 'Get in touch', 'Contact', 'Kontakt'],
    rights: ['جميع الحقوق محفوظة.', 'All rights reserved.', 'Tous droits réservés.', 'Alle Rechte vorbehalten.'],
    call: ['اتصل بنا', 'Call us', 'Appelez-nous', 'Ruf uns an'], mail: ['راسلنا على الإيميل', 'Email us', 'Écrivez-nous', 'Schreib uns'],
    fName: ['الاسم', 'Name', 'Nom', 'Name'], fPhone: ['رقم الموبايل', 'Mobile number', 'Téléphone', 'Telefon'],
    fEmail: ['البريد الإلكتروني', 'Email', 'E-mail', 'E-Mail'], fOpt: ['(اختياري)', '(optional)', '(facultatif)', '(optional)'],
    fSubject: ['نوع الطلب', 'Request type', 'Type de demande', 'Anliegen'], fMsg: ['رسالتك', 'Your message', 'Votre message', 'Deine Nachricht'],
    fCompany: ['اسم الشركة', 'Company name', 'Société', 'Firma'], fCountry: ['الدولة', 'Country', 'Pays', 'Land'], fCity: ['المدينة', 'City', 'Ville', 'Stadt'],
    fActivity: ['نوع النشاط', 'Business type', 'Activité', 'Geschäftsart'],
    activities: [['موزع', 'مستورد', 'تاجر جملة', 'سلسلة متاجر', 'أخرى'], ['Distributor', 'Importer', 'Wholesaler', 'Retail chain', 'Other'], ['Distributeur', 'Importateur', 'Grossiste', 'Chaîne de magasins', 'Autre'], ['Händler', 'Importeur', 'Großhändler', 'Handelskette', 'Andere']],
    subjects: [['استفسار عام', 'طلب تصدير', 'طلب وكالة أو توزيع', 'شكوى أو اقتراح'], ['General question', 'Export request', 'Agency or distribution', 'Complaint or suggestion'], ['Question générale', 'Demande d’export', 'Agence ou distribution', 'Réclamation ou suggestion'], ['Allgemeine Frage', 'Exportanfrage', 'Vertretung oder Vertrieb', 'Beschwerde oder Vorschlag']],
    send: ['إرسال', 'Send', 'Envoyer', 'Senden'],
    formTitle: ['ابعتلنا رسالة', 'Send us a message', 'Envoyez-nous un message', 'Schreib uns'],
    formText: ['للطلبات والتوزيع والتصدير والاستفسارات، هنرد عليك في أقرب وقت.', 'Orders, distribution, export or questions: we reply as soon as we can.', 'Commandes, distribution, export ou questions : nous répondons vite.', 'Bestellungen, Vertrieb, Export oder Fragen – wir antworten schnell.'],
    required: ['من فضلك اكمل الحقول المطلوبة.', 'Please fill in the required fields.', 'Merci de remplir les champs obligatoires.', 'Bitte fülle die Pflichtfelder aus.'],
    previewSent: ['دي نسخة معاينة: على الموقع الحقيقي الرسالة بتتبعت على إيميل الشركة وبتتحفظ في لوحة التحكم.', 'Preview only: on the live site this is emailed to the company and saved in the dashboard.', 'Aperçu : sur le site réel, le message est envoyé par e-mail et enregistré.', 'Vorschau: Auf der echten Website wird die Nachricht per E-Mail gesendet und gespeichert.'],
    nfTitle: ['البسكويتة دي حد كلها!', 'Someone ate this biscuit!', 'Quelqu’un a mangé ce biscuit !', 'Jemand hat diesen Keks gegessen!'],
    nfText: ['الصفحة اللي بتدور عليها مش موجودة أو اتنقلت.', 'The page you are looking for doesn’t exist or has moved.', 'La page demandée n’existe pas ou a été déplacée.', 'Die Seite existiert nicht oder wurde verschoben.'],
    backHome: ['ارجع للرئيسية', 'Back to home', 'Retour à l’accueil', 'Zur Startseite'],
    yearsL: ['سنة خبرة في الصناعات الغذائية', 'years in the food industry', 'ans dans l’agroalimentaire', 'Jahre in der Lebensmittelindustrie'],
    facL: ['مصنع في سوريا ومصر', 'factories in Syria and Egypt', 'usines en Syrie et en Égypte', 'Werke in Syrien und Ägypten'],
    prodL: ['منتج في تشكيلتنا', 'products in our range', 'produits dans notre gamme', 'Produkte im Sortiment'],
    brandL: ['علامات تجارية', 'brands', 'marques', 'Marken'],
    vision: ['رؤيتنا', 'Our vision', 'Notre vision', 'Unsere Vision'], mission: ['رسالتنا', 'Our mission', 'Notre mission', 'Unsere Mission'],
    values: ['قيمنا', 'Our values', 'Nos valeurs', 'Unsere Werte'], why: ['ليه كيكرز؟', 'Why Kickers?', 'Pourquoi Kickers ?', 'Warum Kickers?'],
    story: ['حكايتنا', 'Our story', 'Notre histoire', 'Unsere Geschichte'],
    aboutMore: ['اعرف أكتر عننا', 'More about us', 'En savoir plus', 'Mehr über uns'],
    customer: ['من عملائنا', 'Customer', 'Client', 'Kunde'],
    productsLead: ['كل منتجات كيكرز في مكان واحد. كل كارت فيه نوع ونكهاته.', 'Every Kickers product line in one place, each card with its flavours.', 'Toutes les gammes Kickers, chaque carte avec ses saveurs.', 'Alle Kickers-Linien an einem Ort – jede Karte mit ihren Sorten.'],
    contactLead: ['إحنا في خدمتك على مدار 24 ساعة.', 'We are here for you around the clock.', 'Nous sommes là pour vous 24 h/24.', 'Wir sind rund um die Uhr für dich da.'],
    brandLead: ['منتجات', 'Products by', 'Produits', 'Produkte von'],
    heroLabel: ['عروض كيكرز', 'Kickers highlights', 'À la une', 'Highlights'],
    h1: ['كيكرز فودز للصناعات الغذائية: بسكويت وشيكولاتة سبريد', 'Kickers Foods: biscuits and chocolate spreads', 'Kickers Foods : biscuits et pâtes à tartiner', 'Kickers Foods: Kekse und Schokoaufstriche'],
    newLabel: ['جديد', 'NEW', 'NOUVEAU', 'NEU'],
    newPrefix: ['جديد! ', 'New! ', 'Nouveau ! ', 'Neu! '],
    details: ['تفاصيل المنتج', 'Product details', 'Détails du produit', 'Produktdetails'],
    quickV: ['عرض سريع', 'Quick view', 'Aperçu', 'Schnellansicht'],
    variantsN: ['نكهات', 'flavours', 'saveurs', 'Sorten'],
    typesL: ['أنواع', 'lines', 'gammes', 'Linien'],
    welcomeT: ['لحظة سعادة في كل عبوة', 'A moment of happiness in every pack', 'Un moment de bonheur dans chaque paquet', 'Ein Glücksmoment in jeder Packung'],
    exportTo: ['نصدّر إلى', 'We export to', 'Nous exportons vers', 'Wir exportieren nach'],
    factoryHere: ['مصنع كيكرز', 'Kickers factory', 'Usine Kickers', 'Kickers-Werk'],
    requestFor: ['اطلب عرض لـ', 'Request an offer for', 'Demander une offre pour', 'Angebot anfordern für'],
    andWorld: ['وكمان أسواق العالم:', 'And worldwide markets:', 'Et dans le monde :', 'Und weltweit:'],
    packingAll: ['شوف مواصفات التعبئة', 'See packing specs', 'Voir le conditionnement', 'Verpackung ansehen'],
    rating: ['تقييمك', 'Your rating', 'Votre note', 'Deine Bewertung'],
    yourReview: ['رأيك', 'Your review', 'Votre avis', 'Deine Bewertung'],
    shareReview: ['شاركنا رأيك', 'Share your review', 'Donnez votre avis', 'Teile deine Meinung'],
    shareReviewText: ['رأيك بيتنشر بعد مراجعته.', 'Your review is published after moderation.', 'Votre avis sera publié après validation.', 'Deine Bewertung erscheint nach Prüfung.'],
    reviewsLead: ['كلام عملائنا عن منتجات كيكرز.', 'What people say about Kickers products.', 'Ce que nos clients disent de Kickers.', 'Was Kunden über Kickers sagen.'],
    agentSteps: ['خطوات الوكالة', 'How it works', 'Comment ça marche', 'So funktioniert es'],
    count: function (n) { return [n === 1 ? 'منتج واحد' : n === 2 ? 'منتجين' : n <= 10 ? n + ' منتجات' : n + ' منتج', n + (n === 1 ? ' product' : ' products'), n + (n === 1 ? ' produit' : ' produits'), n + (n === 1 ? ' Produkt' : ' Produkte')]; },
    slideOf: function (a, b) { return [a + ' من ' + b, a + ' of ' + b, a + ' sur ' + b, a + ' von ' + b]; }
  };
  var LI = { ar: 0, en: 1, fr: 2, de: 3 };
  function t(k) { var v = S[k]; return v ? v[LI[lang]] : k; }
  function tf(k, a, b) { return S[k](a, b)[LI[lang]]; }
  function L(o) { return o ? (o[lang] || o.en || o.ar || '') : ''; }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function ico(id, cls) { return '<svg class="' + (cls || '') + '" aria-hidden="true"><use href="#' + id + '"/></svg>'; }
  function img(p) { return IMG + p + '.webp'; }
  function cat(key) { return C.categories.filter(function (c) { return c.key === key; })[0]; }
  function catBySlug(s) { return C.categories.filter(function (c) { return c.slug === s; })[0]; }
  function brand(slug) { return C.brands.filter(function (b) { return b.slug === slug; })[0]; }
  function bname(b) { return lang === 'ar' ? b.name_ar : b.name_en; }
  function linesOf(fn) { return C.lines.filter(fn); }
  function badge(icon) { return '<span class="badge">' + ico('d-plate') + ico(icon, 'ico') + '</span>'; }
  var WA = C.site.whatsapp_url;
  var YEARS = new Date().getFullYear() - C.stats.years_since;

  /* ---------- Header / footer ---------- */
  var NAV = [['home', '#home'], ['about', '#about'], ['products', '#products'], ['export', '#export'], ['agent', '#agent'], ['reviews', '#reviews'], ['contact', '#contact']];
  function langMenu() {
    return '<div class="lang-dd" data-lang-dd><button class="lang-switch" type="button" aria-haspopup="true" aria-expanded="false" aria-label="' + t('language') + '">' + ico('i-globe') + '<span>' + lang.toUpperCase() + '</span>' + ico('i-down', 'dd-caret') + '</button><ul class="lang-list">' +
      Object.keys(LANGS).map(function (k) { return '<li><a href="#" data-set-lang="' + k + '" lang="' + k + '"' + (k === lang ? ' aria-current="true"' : '') + '><b>' + k.toUpperCase() + '</b>' + LANGS[k] + '</a></li>'; }).join('') + '</ul></div>';
  }
  function header(route) {
    var items = NAV.map(function (n) {
      var cur = route === n[0] || (n[0] === 'products' && /^(cat|brand|product)$/.test(route));
      if (n[0] === 'products') {
        var sub = C.categories.map(function (c) { return '<li><a href="#cat-' + c.slug + '"><span class="dot" style="--c:' + c.color + '"></span>' + esc(L(c.name)) + '</a></li>'; }).join('');
        return '<li class="has-sub"><a href="' + n[1] + '"' + (cur ? ' aria-current="page"' : '') + '>' + t(n[0]) + '</a><ul class="sub-menu">' + sub + '<li><a href="#products"><span class="dot" style="--c:#4A2C1A"></span>' + t('viewAll') + '</a></li></ul></li>';
      }
      return '<li><a href="' + n[1] + '"' + (cur ? ' aria-current="page"' : '') + '>' + t(n[0]) + '</a></li>';
    }).join('');
    var dnav = NAV.map(function (n) { return '<li><a href="' + n[1] + '">' + t(n[0]) + '</a></li>'; }).join('');
    return '<div class="progress" data-progress aria-hidden="true"></div><a class="skip-link" href="#main">' + t('skip') + '</a>' +
      '<div class="intro crimp-b" data-intro aria-hidden="true"><div class="intro-logo-wrap"><img class="intro-logo" src="assets/img/brand/logo.webp" alt="" width="241" height="120"></div></div>' +
      '<header class="site-header" data-header><div class="wrap header-in">' +
      '<a class="brand" href="#home" aria-label="' + esc(L(C.site.name)) + '"><img src="assets/img/brand/logo.webp" alt="' + esc(L(C.site.name)) + '" width="241" height="120"></a>' +
      '<nav class="main-nav" aria-label="menu"><ul>' + items + '</ul></nav>' +
      '<div class="header-actions">' + langMenu() + '<a class="btn btn-sm btn-wa-h" href="' + WA + '" target="_blank" rel="noopener" aria-label="' + t('whatsapp') + '">' + ico('i-whatsapp') + '</a>' +
      '<button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="drawer" aria-label="' + t('menu') + '">' + ico('i-menu') + '</button></div>' +
      '</div></header>' +
      '<div class="drawer" id="drawer" data-drawer role="dialog" aria-modal="true" aria-label="' + t('menu') + '"><div class="drawer-top"><img src="assets/img/brand/logo.webp" alt="" width="241" height="120"><button class="drawer-close" type="button" data-drawer-close aria-label="' + t('close') + '">' + ico('i-close') + '</button></div>' +
      '<nav class="drawer-nav"><ul>' + dnav + '</ul></nav>' +
      '<div class="drawer-langs">' + Object.keys(LANGS).map(function (k) { return '<a href="#" data-set-lang="' + k + '"' + (k === lang ? ' aria-current="true"' : '') + '>' + LANGS[k] + '</a>'; }).join('') + '</div>' +
      '<div class="drawer-foot"><a class="btn btn-cream" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('chat') + '</a></div></div>';
  }

  function footer() {
    var s = C.site;
    return '<footer class="site-footer crimp-t"><div class="wrap"><div class="f-grid">' +
      '<div><a class="f-logo" href="#home"><img src="assets/img/brand/logo.webp" alt="' + esc(L(s.name)) + '" width="241" height="120" loading="lazy"></a><p class="f-about">' + esc(L(s.footer)) + '</p>' +
      '<div class="socials"><a href="' + WA + '" target="_blank" rel="noopener" aria-label="WhatsApp">' + ico('i-whatsapp') + '</a><a href="https://www.facebook.com/share/1A7YV93uor/" target="_blank" rel="noopener" aria-label="Facebook">' + ico('i-facebook') + '</a><a href="https://www.instagram.com/kickers010/" target="_blank" rel="noopener" aria-label="Instagram">' + ico('i-instagram') + '</a></div></div>' +
      '<div><h2 class="f-title">' + t('quick') + '</h2><ul class="f-links">' + NAV.map(function (n) { return '<li><a href="' + n[1] + '">' + t(n[0]) + '</a></li>'; }).join('') + '</ul></div>' +
      '<div><h2 class="f-title">' + t('products') + '</h2><ul class="f-links">' + C.categories.map(function (c) { return '<li><a href="#cat-' + c.slug + '">' + esc(L(c.name)) + '</a></li>'; }).join('') + '</ul></div>' +
      '<div><h2 class="f-title">' + t('reach') + '</h2><ul class="f-contact">' +
      '<li>' + ico('i-phone') + '<span><a href="tel:+2' + s.phone1 + '" dir="ltr">' + s.phone1 + '</a><br><a href="tel:+2' + s.phone2 + '" dir="ltr">' + s.phone2 + '</a></span></li>' +
      '<li>' + ico('i-mail') + '<a href="mailto:' + s.email + '">' + s.email + '</a></li>' +
      C.factories.map(function (f) { return '<li>' + ico('i-pin') + '<span><b>' + esc(L(f.country)) + ':</b> ' + esc(L(f.addr)) + '</span></li>'; }).join('') +
      '</ul></div></div>' +
      '<div class="f-bottom"><span>© <span data-year></span> ' + esc(L(s.tagline)) + '. ' + t('rights') + '</span><span class="f-langs">' + Object.keys(LANGS).map(function (k) { return '<a href="#" data-set-lang="' + k + '">' + LANGS[k] + '</a>'; }).join(' · ') + '</span></div></div></footer>' +
      '<a class="fab" href="' + WA + '" target="_blank" rel="noopener" aria-label="' + t('chat') + '">' + ico('i-whatsapp') + '</a>';
  }

  /* ---------- Hero: auto banners (new lines first, then one per category) ---------- */
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
  function sprinkles() {
    var cols = ['#FFC928', '#fff', '#FF8FA0', '#7A4A2D', '#FFE27F'], out = '';
    for (var i = 0; i < 16; i++) out += '<i style="--x:' + ((i * 37) % 100) + '%;--c:' + cols[i % 5] + ';--t:' + (7 + (i % 5) * 1.6) + 's;--dl:' + (-i * 1.1) + 's"></i>';
    return '<div class="sprinkles" aria-hidden="true">' + out + '</div>';
  }
  var WAVES = '<div class="slide-bg" aria-hidden="true"><svg viewBox="0 0 1440 500" preserveAspectRatio="none"><path class="wave-a" d="M0,170 C240,90 420,260 720,170 C1000,85 1180,230 1440,140 L1440,500 L0,500 Z"/><path class="wave-b" d="M0,280 C300,200 520,360 860,270 C1100,205 1280,320 1440,250 L1440,500 L0,500 Z"/></svg></div>';
  function slides() {
    var out = [], newThemes = ['gold', 'red'];
    linesOf(function (l) { return l.new; }).forEach(function (l, i) {
      var c = cat(l.cat);
      out.push({ theme: newThemes[i % 2], word: 'NEW', isNew: true, title: t('newPrefix') + L(l.name), sub: L(l.desc), btn1: t('details'), url1: '#product-' + l.slug,
        images: l.variants.slice(0, 3).map(function (v) { return v.img; }), decor: c.decor, label: L(l.name) });
    });
    C.categories.forEach(function (c) {
      var ls = linesOf(function (l) { return l.cat === c.key; }), ims = [];
      ls.forEach(function (l) { if (ims.length < 3) ims.push(l.variants[0].img); });
      ls.forEach(function (l) { l.variants.slice(1).forEach(function (v) { if (ims.length < 3) ims.push(v.img); }); });
      out.push({ theme: c.theme, word: c.word, title: L(c.banner_title), sub: L(c.banner_sub), btn1: t('browse'), url1: '#cat-' + c.slug, images: ims, decor: c.decor, label: L(c.name) });
    });
    return out;
  }
  function hero() {
    var sl = slides(), n = sl.length;
    var html = sl.map(function (s, i) {
      var packs = s.images.map(function (p, k) { return '<div class="px pk pk-' + (k + 1) + '"><div class="pk-in"><img src="' + img(p) + '" alt="" ' + (i === 0 && k === 0 ? 'fetchpriority="high"' : 'loading="lazy"') + '></div></div>'; }).join('');
      return '<div class="slide' + (s.isNew ? ' is-new' : '') + '" data-theme="' + s.theme + '" role="group" aria-roledescription="slide" aria-label="' + esc(tf('slideOf', i + 1, n)) + '">' + WAVES + sprinkles() +
        '<div class="slide-word" aria-hidden="true">' + esc(s.word) + '</div>' +
        '<div class="wrap slide-grid"><div class="slide-copy">' + (s.isNew ? '<span class="new-pill">' + ico('d-star') + t('newLabel') + '</span>' : '') + '<h2 class="slide-title">' + esc(s.title) + '</h2><p class="slide-sub">' + esc(s.sub) + '</p>' +
        '<div class="slide-cta"><a class="btn btn-main" href="' + s.url1 + '">' + esc(s.btn1) + ico('i-arrow', 'ico-dir') + '</a>' +
        '<a class="btn btn-ghost" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('chat') + '</a></div></div>' +
        '<div class="slide-visual"><div class="plate" aria-hidden="true">' + ico('d-plate') + '</div>' + packs + sprites(s.decor) +
        (s.isNew ? newSticker(i) : '') + '</div></div></div>';
    }).join('');
    var tabs = sl.map(function (s) { return '<button class="hero-tab" type="button" aria-label="' + esc(s.label) + '"><img src="' + img(s.images[0]) + '" alt="" loading="lazy"><span>' + (s.isNew ? '<em>' + t('newLabel') + '</em> ' : '') + esc(s.label) + '</span><span class="bar"><i></i></span></button>'; }).join('');
    return '<section class="hero" data-hero data-duration="6" aria-roledescription="carousel" aria-label="' + t('heroLabel') + '"><h1 class="sr-only">' + t('h1') + '</h1>' +
      '<div class="hero-stage crimp-b">' + html +
      '<div class="hero-nav wrap"><div class="hero-tabs">' + tabs + '</div><div class="hero-ctrl">' +
      '<button class="hero-btn" type="button" data-hero-prev aria-label="' + t('prev') + '">' + ico('i-arrow', 'ico-dir prev-ico') + '</button>' +
      '<button class="hero-btn" type="button" data-hero-toggle aria-pressed="false" aria-label="' + t('pause') + '" data-label-pause="' + t('pause') + '" data-label-play="' + t('play') + '">' + ico('i-pause', 'i-pause') + ico('i-play', 'i-play') + '</button>' +
      '<button class="hero-btn" type="button" data-hero-next aria-label="' + t('next') + '">' + ico('i-arrow', 'ico-dir') + '</button></div></div></div></section>';
  }
  function newSticker(i) {
    var w = t('newLabel');
    return '<div class="new-sticker" aria-hidden="true"><svg viewBox="0 0 120 120"><defs><path id="nc' + i + '" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs><text><textPath href="#nc' + i + '">' + w + ' ★ ' + w + ' ★ ' + w + ' ★ ' + w + ' ★</textPath></text></svg><b>' + w + '</b></div>';
  }

  function ribbons() {
    var a = C.lines.map(function (l) { return '<span>' + esc(L(l.name)) + '</span>' + ico('d-biscuit'); }).join('');
    var b = C.categories.map(function (c) { return '<span>' + esc(L(c.name)) + '</span>' + ico('d-star'); }).join('');
    return '<div class="ribbons" aria-hidden="true"><div class="ribbon ribbon-gold crimp-tb"><div class="ribbon-track">' + a + a + '</div></div>' +
      '<div class="ribbon ribbon-red crimp-tb"><div class="ribbon-track">' + b + b + b + b + b + b + '</div></div></div>';
  }

  function stats() {
    var it = [[YEARS, '+', t('yearsL')], [C.stats.factories, '', t('facL')], [C.stats.products, '', t('prodL')], [C.stats.brands, '', t('brandL')]];
    return '<div class="stats" data-stagger>' + it.map(function (x) { return '<div class="stat" data-reveal><b data-count="' + x[0] + '" data-suffix="' + x[1] + '">' + x[0] + x[1] + '</b><span>' + x[2] + '</span></div>'; }).join('') + '</div>';
  }

  function girlVisual(chips) {
    return '<div class="welcome-visual" data-reveal="pop"><div class="wv-plate" aria-hidden="true">' + ico('d-plate') + '</div><div class="wv-ring" aria-hidden="true"></div>' +
      '<img class="wv-girl" src="assets/img/girl.webp" alt="' + esc(t('welcomeT')) + '" loading="lazy" width="880" height="1100">' +
      (chips ? '<span class="wv-chip c1">' + ico('i-badge') + YEARS + '+ ' + esc(t('yearsL')) + '</span><span class="wv-chip c2">' + ico('i-globe') + esc(L(C.export.points[0].t)) + '</span>' : '') +
      '<span class="wv-s" style="left:4%;top:18%;width:46px" aria-hidden="true">' + ico('d-biscuit') + '</span><span class="wv-s" style="right:6%;top:6%;width:30px;color:#FFC928" aria-hidden="true">' + ico('d-star') + '</span></div>';
  }
  function welcome() {
    return '<section class="section welcome"><span class="drift" data-speed=".8" style="right:3%;top:10%;--s:44px">' + ico('d-wheat') + '</span><div class="wrap welcome-grid">' + girlVisual(true) +
      '<div class="welcome-copy"><span class="eyebrow-k">' + esc(L(C.site.tagline)) + '</span><h2>' + esc(t('welcomeT')) + '</h2><p class="lead">' + esc(L(C.about.intro)) + '</p>' +
      '<ul class="w-promises" data-stagger>' + C.promises.map(function (p) { return '<li data-reveal>' + badge('i-' + p.icon) + '<div><h3>' + esc(L(p.t)) + '</h3><p>' + esc(L(p.d)) + '</p></div></li>'; }).join('') + '</ul>' +
      '<a class="btn btn-cocoa" href="#about">' + t('aboutMore') + ico('i-arrow', 'ico-dir') + '</a></div></div></section>';
  }

  function catTile(c) {
    var ls = linesOf(function (l) { return l.cat === c.key; });
    var a = ls[0].variants[0].img, b = (ls[1] ? ls[1].variants[0].img : (ls[0].variants[1] || ls[0].variants[0]).img);
    var n = ls.reduce(function (s, l) { return s + l.variants.length; }, 0);
    return '<a class="cat-tile" data-reveal href="#cat-' + c.slug + '" style="--tile:' + c.color + ';--ink:' + c.ink + '">' +
      '<span class="cat-imgs" aria-hidden="true"><img src="' + img(b) + '" alt="" loading="lazy"><img src="' + img(a) + '" alt="" loading="lazy"></span>' +
      '<h3>' + esc(L(c.name)) + '</h3><p>' + esc(L(c.desc)) + '</p><span class="cat-meta"><span class="cat-count">' + ls.length + ' ' + t('typesL') + ' · ' + n + ' ' + t('variantsN') + '</span><span class="cat-go">' + ico('i-arrow', 'ico-dir') + '</span></span></a>';
  }
  function cats() {
    return '<section class="section cats"><span class="drift" data-speed="1.3" style="right:3%;top:12%;--s:52px">' + ico('d-biscuit') + '</span><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home.cats_title)) + '</h2><p>' + esc(L(C.home.cats_text)) + '</p></header>' +
      '<div class="cat-grid" data-stagger>' + C.categories.map(catTile).join('') + '</div></div></section>';
  }

  function specRows(l) {
    var r = '';
    if (L(l.weight)) r += '<div><dt>' + t('weight') + '</dt><dd>' + esc(L(l.weight)) + '</dd></div>';
    if (L(l.carton)) r += '<div><dt>' + t('carton') + '</dt><dd>' + esc(L(l.carton)) + '</dd></div>';
    if (l.units) r += '<div><dt>' + t('units') + '</dt><dd>' + l.units + '</dd></div>';
    return r;
  }
  function card(l) {
    var b = brand(l.brand), c = cat(l.cat), v0 = l.variants[0];
    var sw = l.variants.length > 1 ? '<div class="swatches" role="group" aria-label="' + t('flavors') + '">' + l.variants.map(function (v, i) { return '<button type="button" class="sw' + (i ? '' : ' is-on') + '" data-sw="' + img(v.img) + '" title="' + esc(L(v.flavor)) + '" aria-label="' + esc(L(v.flavor)) + '"><img src="' + img(v.img) + '" alt="" loading="lazy"></button>'; }).join('') + '</div>' : '';
    var q = ' data-quick data-title="' + esc(L(l.name)) + '" data-img="' + img(v0.img) + '" data-desc="' + esc(L(l.desc)) + '" data-weight="' + esc(L(l.weight)) + '" data-brand="' + esc(b.name_en) + '" data-bc="' + b.color + '" data-cat="' + esc(L(c.name)) + '" data-tint="' + l.tint + '" data-url="#product-' + l.slug + '" data-more-label="' + esc(t('details')) + '" data-wa="' + WA + '" data-wa-label="' + esc(t('orderWa')) + '" data-carton="' + esc(L(l.carton)) + '" data-variants="' + esc(JSON.stringify(l.variants.map(function (v) { return [img(v.img), L(v.flavor)]; }))) + '"';
    return '<article class="p-card' + (l.new ? ' is-new' : '') + '" data-tilt data-cat="' + c.slug + '" style="--tint:' + l.tint + '">' + (l.new ? '<span class="p-new">' + t('newLabel') + '</span>' : '') +
      '<a class="p-link" href="#product-' + l.slug + '">' +
      '<div class="p-media"><span class="p-plate" aria-hidden="true">' + ico('d-plate') + '</span><img class="p-main" src="' + img(v0.img) + '" alt="' + esc(L(l.name)) + '" loading="lazy"></div>' +
      '<div class="p-body"><span class="p-brand" style="--bc:' + b.color + '">' + esc(b.name_en) + '</span><h3 class="p-title">' + esc(L(l.name)) + '</h3>' +
      '<div class="p-foot"><span class="p-tags">' + (L(l.weight) ? '<span class="p-weight">' + esc(L(l.weight)) + '</span>' : '') + (l.variants.length > 1 ? '<span class="p-weight">' + l.variants.length + ' ' + t('variantsN') + '</span>' : '') + '</span><span class="p-go" aria-hidden="true">' + ico('i-arrow', 'ico-dir') + '</span></div></div></a>' + sw +
      '<button class="p-quick" type="button" aria-label="' + esc(t('quickV') + ': ' + L(l.name)) + '"' + q + '>' + ico('i-eye') + '</button></article>';
  }

  function showcase() {
    var items = C.lines.map(function (l) {
      var b = brand(l.brand);
      return '<a class="cf-item" href="#product-' + l.slug + '" data-title="' + esc(L(l.name)) + '" data-desc="' + esc(L(l.desc)) + '" data-weight="' + esc(L(l.weight)) + '" data-brand="' + esc(b.name_en) + '" data-bc="' + b.color + '" data-tint="' + l.tint + '">' +
        '<span class="cf-plate" aria-hidden="true">' + ico('d-plate') + '</span><img src="' + img(l.variants[0].img) + '" alt="' + esc(L(l.name)) + '" loading="lazy">' + (l.new ? '<span class="p-new">' + t('newLabel') + '</span>' : '') + '</a>';
    }).join('');
    return '<section class="section showcase crimp-tb"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home.showcase_title)) + '</h2><p>' + esc(L(C.home.showcase_text)) + '</p></header>' +
      '<div class="cf" data-cf tabindex="0" aria-roledescription="carousel"><div class="cf-stage">' + items + '</div>' +
      '<div class="cf-bottom"><div class="cf-info" aria-live="polite"><span class="p-brand cf-brand"></span><h3 class="cf-title"></h3><p class="cf-desc"></p><div class="cf-row"><span class="p-weight cf-weight"></span><a class="btn btn-sm cf-link" href="#">' + t('details') + ico('i-arrow', 'ico-dir') + '</a></div></div>' +
      '<div class="cf-ctrl"><button type="button" data-cf-prev aria-label="' + t('prev') + '">' + ico('i-arrow', 'ico-dir prev-ico') + '</button><button type="button" data-cf-next aria-label="' + t('next') + '">' + ico('i-arrow', 'ico-dir') + '</button></div></div></div></div></section>';
  }

  function productsHome() {
    var list = C.lines;
    var chips = '<button class="chip" type="button" data-f="all" aria-pressed="true">' + t('all') + '<span class="n">' + list.length + '</span></button>' +
      C.categories.map(function (c) { var n = list.filter(function (l) { return l.cat === c.key; }).length; return '<button class="chip" type="button" data-f="' + c.slug + '" aria-pressed="false">' + esc(L(c.name)) + '<span class="n">' + n + '</span></button>'; }).join('');
    return '<section class="section" id="home-products"><span class="drift" data-speed="1" style="right:2%;top:40%;--s:40px">' + ico('d-choc') + '</span><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home.products_title)) + '</h2><p>' + esc(L(C.home.products_text)) + '</p></header>' +
      '<div class="chips" data-filter="#home-grid" role="group" aria-label="' + t('category') + '">' + chips + '</div>' +
      '<div class="p-grid" id="home-grid">' + list.map(card).join('') + '</div></div></section>';
  }

  function roll() {
    return '<section class="section roll crimp-tb"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home.roll_title)) + '</h2></header>' +
      '<div class="roll-track" data-roll><div class="roll-line" aria-hidden="true"></div><div class="roll-ball" aria-hidden="true">' + ico('d-biscuit') + '</div>' +
      '<ol class="roll-steps">' + C.home.steps.map(function (s, i) { return '<li class="roll-step"><span class="roll-num">' + (i + 1) + '</span><h3>' + esc(L(s.t)) + '</h3><p>' + esc(L(s.d)) + '</p></li>'; }).join('') + '</ol></div></div></section>';
  }

  /* ---------- Export ---------- */
  function tileMap(full) {
    var fac = { EG: 1, SY: 1 };
    var tiles = C.countries.map(function (c) {
      var nm = { ar: c[1], en: c[2], fr: c[3], de: c[4] }[lang];
      return '<button type="button" class="hex' + (fac[c[0]] ? ' is-fac' : '') + '" style="--c:' + c[5] + ';--r:' + c[6] + ';--o:' + (c[6] % 2 ? .5 : 0) + '" data-country="' + esc(nm) + '" data-fac="' + (fac[c[0]] ? 1 : 0) + '"><span class="hex-code">' + c[0] + '</span><span class="hex-name">' + esc(nm) + '</span></button>';
    }).join('');
    return '<div class="tilemap" data-tilemap data-l-to="' + esc(t('exportTo')) + '" data-l-fac="' + esc(t('factoryHere')) + '" data-l-req="' + esc(t('requestFor')) + '"><div class="tm-head"><h3>' + esc(L(C.export.map_title)) + '</h3><p>' + esc(L(C.export.map_hint)) + '</p></div>' +
      '<div class="tm-grid" role="group" aria-label="' + esc(L(C.export.map_title)) + '">' + tiles + '</div>' +
      '<div class="tm-info" aria-live="polite"><div class="tm-flag">' + ico('i-globe') + '</div><div><small data-tm-label>' + t('exportTo') + '</small><strong data-tm-name>' + esc(L(C.export.points[0].t)) + '</strong></div>' +
      '<a class="btn btn-gold btn-sm" data-tm-cta href="' + (full ? '#export-form' : '#export-form') + '">' + esc(L(C.export.btn)) + '</a></div>' +
      '<div class="tm-regions"><span>' + t('andWorld') + '</span>' + C.regions.map(function (r) { return '<button type="button" class="chip chip-dark" data-country="' + esc(L(r)) + '">' + esc(L(r)) + '</button>'; }).join('') + '</div></div>';
  }
  function exportSec(full) {
    var E = C.export;
    return '<section class="export crimp-tb"><span class="drift" data-speed=".6" style="left:4%;top:12%;--s:48px">' + ico('d-wheat') + '</span>' +
      '<div class="wrap export-grid"><div><h2>' + esc(L(E.title)) + '</h2><p class="lead">' + esc(L(E.text)) + '</p>' +
      '<div class="ex-points" data-stagger>' + E.points.map(function (x) { return '<div class="ex-point" data-reveal>' + badge('i-' + x.icon) + '<div><h3>' + esc(L(x.t)) + '</h3><p>' + esc(L(x.d)) + '</p></div></div>'; }).join('') + '</div>' +
      '<div class="ex-actions"><a class="btn btn-gold" href="#export-form">' + esc(L(E.btn)) + ico('i-arrow', 'ico-dir') + '</a><a class="btn btn-ghost" href="#packing">' + t('packingAll') + '</a></div></div>' +
      tileMap(full) + '</div></section>';
  }
  function packingTable() {
    var rows = C.categories.map(function (c) {
      var ls = linesOf(function (l) { return l.cat === c.key; });
      return '<tbody><tr class="pk-cat"><th colspan="5" style="--c:' + c.color + '">' + esc(L(c.name)) + '</th></tr>' + ls.map(function (l) {
        return '<tr><td><a class="pk-prod" href="#product-' + l.slug + '"><img src="' + img(l.variants[0].img) + '" alt="" loading="lazy"><span>' + esc(L(l.name)) + (l.new ? ' <em class="p-new sm">' + t('newLabel') + '</em>' : '') + '</span></a></td>' +
          '<td data-l="' + esc(t('weight')) + '">' + esc(L(l.weight) || '—') + '</td><td data-l="' + esc(t('carton')) + '">' + esc(L(l.carton) || '—') + '</td><td data-l="' + esc(t('units')) + '">' + (l.units || '—') + '</td><td data-l="' + esc(t('flavors')) + '">' + (l.variants.length > 1 ? l.variants.map(function (v) { return esc(L(v.flavor)); }).join('، ') : '—') + '</td></tr>';
      }).join('') + '</tbody>';
    }).join('');
    return '<section class="section" id="packing"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.export.packing_title)) + '</h2><p>' + esc(L(C.export.packing_text)) + '</p></header>' +
      '<div class="pk-wrap" data-reveal><table class="pk-table"><thead><tr><th>' + t('product') + '</th><th>' + t('weight') + '</th><th>' + t('carton') + '</th><th>' + t('units') + '</th><th>' + t('flavors') + '</th></tr></thead>' + rows + '</table></div></div></section>';
  }

  function brandsSec() {
    return '<section class="section brands"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home.brands_title)) + '</h2><p>' + esc(L(C.home.brands_text)) + '</p></header>' +
      '<div class="brand-grid b7" data-stagger>' + C.brands.map(function (b) {
        var ls = linesOf(function (l) { return l.brand === b.slug; });
        return '<a class="brand-card" data-reveal href="#brand-' + b.slug + '" style="--bc:' + b.color + '">' +
          (b.logo ? '<img class="brand-logo" src="assets/img/brands/' + b.logo + '.webp" alt="' + esc(b.name_en) + '" loading="lazy">' : '<span class="brand-name">' + esc(b.name_en) + '</span>') +
          '<span class="brand-ar">' + esc(bname(b)) + '</span><span class="brand-count">' + tf('count', ls.length) + '</span>' +
          '<img class="brand-prod" src="' + img(ls[0].variants[0].img) + '" alt="" loading="lazy"></a>';
      }).join('') + '</div></div></section>';
  }

  function reviewCards() {
    return C.reviews.map(function (r) {
      var stars = ''; for (var i = 0; i < 5; i++) stars += ico('i-star');
      return '<figure class="review" data-reveal style="margin:0"><div class="stars" aria-label="5/5">' + stars + '</div><blockquote>' + esc(L(r.t)) + '</blockquote>' +
        '<figcaption class="review-who"><span class="av" aria-hidden="true">' + esc(L(r.name).charAt(0)) + '</span><span>' + esc(L(r.name)) + '<small>' + t('customer') + '</small></span></figcaption></figure>';
    }).join('');
  }
  function reviewsSec() {
    return '<section class="section"><div class="wrap"><header class="sec-head"><h2>' + esc(L(C.home.reviews_title)) + '</h2><p><a class="text-link" href="#reviews">' + t('reviews') + ico('i-arrow', 'ico-dir') + '</a></p></header><div class="reviews-grid" data-stagger>' + reviewCards() + '</div></div></section>';
  }

  function factories() {
    return '<section class="section" style="padding-top:0"><div class="wrap"><header class="sec-head"><h2>' + t('factories') + '</h2></header><div class="fac-grid" data-stagger>' +
      C.factories.map(function (f) {
        return '<div class="fac" data-reveal>' + badge('i-factory') + '<div><span class="fac-since">' + t('since') + ' ' + f.since + '</span><h3>' + esc(L(f.country)) + '</h3><p>' + esc(L(f.addr)) + '</p>' +
          (f.map ? '<a class="text-link" href="' + f.map + '" target="_blank" rel="noopener">' + t('openMap') + ico('i-arrow', 'ico-dir') + '</a>' : '') + '</div></div>';
      }).join('') + '</div></div></section>';
  }

  function cta() {
    return '<section class="cta-band crimp-tb"><img class="float-p fp-1" src="' + img('spread-1') + '" alt="" loading="lazy"><img class="float-p fp-2" src="' + img('dipped-5') + '" alt="" loading="lazy">' +
      '<span class="float-s" style="left:9%;top:62%" aria-hidden="true">' + ico('d-wheat') + '</span><span class="float-s" style="right:12%;top:12%;width:38px" aria-hidden="true">' + ico('d-biscuit') + '</span>' +
      '<div class="wrap"><h2>' + esc(L(C.home.cta_title)) + '</h2><p>' + esc(L(C.home.cta_text)) + '</p><div class="cta-actions">' +
      '<a class="btn btn-cream" href="#products">' + t('browse') + ico('i-arrow', 'ico-dir') + '</a><a class="btn btn-ghost" href="#agent">' + t('agent') + '</a></div></div></section>';
  }

  function pageHero(title, lead, crumbs, deco) {
    var cr = '<nav class="crumbs" aria-label="breadcrumb"><a href="#home">' + t('home') + '</a>' + (crumbs || []).map(function (c) { return ico('i-chev', 'ico-dir') + (c[1] ? '<a href="' + c[1] + '">' + esc(c[0]) + '</a>' : '<span aria-current="page">' + esc(c[0]) + '</span>'); }).join('') + '</nav>';
    return '<section class="page-hero crimp-b"><div class="wrap">' + cr + '<h1>' + esc(title) + '</h1>' + (lead ? '<p class="lead">' + esc(lead) + '</p>' : '') + '</div>' +
      '<div class="ph-deco" aria-hidden="true">' + ico('d-plate') + (deco ? '<img src="' + img(deco) + '" alt="">' : '') + '</div></section>';
  }

  function formCard(opts) {
    opts = opts || {};
    var subj = S.subjects[LI[lang]].slice();
    if (opts.first) { subj.splice(subj.indexOf(opts.first), 1); subj.unshift(opts.first); }
    var field = function (id, label, req, type, extra) { return '<div class="field"><label for="f-' + id + '">' + label + (req ? ' <span class="req">*</span>' : ' ' + t('fOpt')) + '</label><input id="f-' + id + '" name="' + id + '"' + (type ? ' type="' + type + '"' : '') + (req ? ' required' : '') + (extra || '') + '></div>'; };
    var agent = opts.agent ? '<div class="f-row">' + field('company', t('fCompany'), true) + '<div class="field"><label for="f-activity">' + t('fActivity') + '</label><select id="f-activity" name="activity">' + S.activities[LI[lang]].map(function (x) { return '<option>' + esc(x) + '</option>'; }).join('') + '</select></div></div>' +
      '<div class="f-row">' + field('country', t('fCountry'), true) + field('city', t('fCity'), false) + '</div>' : '';
    return '<div class="form-card" data-reveal id="' + (opts.id || 'form') + '"><h2>' + (opts.title || t('formTitle')) + '</h2><p>' + t('formText') + '</p>' +
      '<form data-kk-contact novalidate data-msg-required="' + esc(t('required')) + '">' + agent +
      '<div class="f-row">' + field('name', t('fName'), true, '', ' autocomplete="name"') + field('phone', t('fPhone'), true, 'tel', ' autocomplete="tel" dir="ltr"') + '</div>' +
      '<div class="f-row">' + field('email', t('fEmail'), false, 'email', ' autocomplete="email" dir="ltr"') +
      (opts.agent ? '<input type="hidden" name="subject" value="' + esc(S.subjects[LI[lang]][2]) + '">' : '<div class="field"><label for="f-subject">' + t('fSubject') + '</label><select id="f-subject" name="subject">' + subj.map(function (x) { return '<option>' + esc(x) + '</option>'; }).join('') + '</select></div>') + '</div>' +
      '<div class="field"><label for="f-msg">' + t('fMsg') + ' <span class="req">*</span></label><textarea id="f-msg" name="message" required></textarea></div>' +
      '<input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">' +
      '<button class="btn" type="submit">' + t('send') + ico('i-arrow', 'ico-dir') + '</button><p class="form-status" role="status" aria-live="polite"></p></form></div>';
  }

  /* ---------- Views ---------- */
  function vHome() { return hero() + ribbons() + welcome() + cats() + showcase() + productsHome() + roll() + exportSec() + brandsSec() + reviewsSec() + factories() + cta(); }

  function vProducts(catSlug, brandSlug) {
    var c = catSlug ? catBySlug(catSlug) : null, b = brandSlug ? brand(brandSlug) : null;
    var list = linesOf(function (l) { return (!c || l.cat === c.key) && (!b || l.brand === b.slug); });
    var title = c ? L(c.name) : b ? t('brandLead') + ' ' + b.name_en : t('products');
    var crumbs = c || b ? [[t('products'), '#products'], [c ? L(c.name) : b.name_en]] : [[t('products')]];
    var chips = '<a class="chip" href="#products"' + (!c && !b ? ' aria-current="page"' : '') + '>' + t('all') + '<span class="n">' + C.lines.length + '</span></a>' +
      C.categories.map(function (x) { return '<a class="chip" href="#cat-' + x.slug + '"' + (c && c.slug === x.slug ? ' aria-current="page"' : '') + '>' + esc(L(x.name)) + '<span class="n">' + linesOf(function (l) { return l.cat === x.key; }).length + '</span></a>'; }).join('');
    return pageHero(title, c ? L(c.desc) : b ? '' : t('productsLead'), crumbs, list[0] && list[0].variants[0].img) +
      '<section class="section" style="padding-top:clamp(40px,5vw,60px)"><div class="wrap"><nav class="chips" aria-label="' + t('category') + '">' + chips + '</nav>' +
      '<div class="p-grid">' + list.map(card).join('') + '</div></div></section>' + cta();
  }

  function vProduct(slug) {
    var l = C.lines.filter(function (x) { return x.slug === slug; })[0];
    if (!l) return vNotFound();
    var b = brand(l.brand), c = cat(l.cat);
    var deco = { plain: ['d-wheat', 'd-biscuit'], filled: ['d-biscuit', 'd-crumb'], dipped: ['d-choc', 'd-choc'], spread: ['d-hazel', 'd-choc'] }[l.cat];
    var rel = linesOf(function (x) { return x.cat === l.cat && x.slug !== l.slug; }).slice(0, 4);
    if (rel.length < 4) rel = rel.concat(linesOf(function (x) { return x.cat !== l.cat; }).slice(0, 4 - rel.length));
    var thumbs = l.variants.length > 1 ? '<div class="pd-variants" role="group" aria-label="' + t('flavors') + '">' + l.variants.map(function (v, i) {
      return '<button type="button" data-src="' + img(v.img) + '" aria-pressed="' + (i ? 'false' : 'true') + '"><img src="' + img(v.img) + '" alt=""><span>' + esc(L(v.flavor)) + '</span></button>'; }).join('') + '</div>' : '';
    return '<section class="product-top"><div class="wrap"><nav class="crumbs" aria-label="breadcrumb"><a href="#home">' + t('home') + '</a>' + ico('i-chev', 'ico-dir') + '<a href="#products">' + t('products') + '</a>' + ico('i-chev', 'ico-dir') + '<a href="#cat-' + c.slug + '">' + esc(L(c.name)) + '</a>' + ico('i-chev', 'ico-dir') + '<span aria-current="page">' + esc(L(l.name)) + '</span></nav>' +
      '<div class="pd-grid"><div class="pd-stage" data-gallery><div class="pd-media crimp-b" style="--tint:' + l.tint + '"><div class="plate-bg" aria-hidden="true">' + ico('d-plate') + '</div>' +
      (l.new ? '<div class="new-sticker sm" aria-hidden="true"><b>' + t('newLabel') + '</b></div>' : '') +
      '<img data-gallery-main src="' + img(l.variants[0].img) + '" alt="' + esc(L(l.name)) + '">' +
      '<span class="sp-static" style="left:8%;top:10%" aria-hidden="true">' + ico(deco[0]) + '</span><span class="sp-static" style="right:9%;bottom:14%;width:40px;animation-delay:-3s" aria-hidden="true">' + ico(deco[1]) + '</span></div>' + thumbs + '</div>' +
      '<div class="pd-info"><a class="p-brand" href="#brand-' + b.slug + '" style="--bc:' + b.color + '">' + esc(b.name_en) + '</a><h1>' + esc(L(l.name)) + '</h1><p class="lead">' + esc(L(l.desc)) + '</p>' +
      '<dl class="specs">' + specRows(l) + (l.variants.length > 1 ? '<div><dt>' + t('flavors') + '</dt><dd>' + l.variants.map(function (v) { return esc(L(v.flavor)); }).join('، ') + '</dd></div>' : '') +
      '<div><dt>' + t('category') + '</dt><dd><a href="#cat-' + c.slug + '">' + esc(L(c.name)) + '</a></dd></div>' +
      '<div><dt>' + t('brand') + '</dt><dd><a href="#brand-' + b.slug + '">' + esc(bname(b)) + '</a></dd></div></dl>' +
      '<div class="pd-cta"><a class="btn" href="' + WA + '" target="_blank" rel="noopener">' + ico('i-whatsapp') + t('orderWa') + '</a><a class="btn btn-cream" href="#export">' + t('export') + '</a></div></div></div></div></section>' +
      '<section class="section related"><div class="wrap"><header class="sec-head"><h2>' + t('related') + '</h2></header><div class="p-grid">' + rel.map(card).join('') + '</div></div></section>';
  }

  function vAbout() {
    var A = C.about;
    return pageHero(t('about'), L(C.site.tagline), [[t('about')]], 'spread-1') +
      '<section class="section"><div class="wrap welcome-grid">' + girlVisual(false) +
      '<div class="story-text"><p>' + esc(L(A.intro)) + '</p><p>' + esc(L(A.history)) + '</p>' + stats() + '</div></div></section>' +
      '<section class="section timeline-sec" style="padding-top:0"><div class="wrap"><header class="sec-head"><h2>' + t('story') + '</h2></header><ol class="timeline" data-stagger>' +
      C.factories.map(function (f) { return '<li data-reveal><b>' + f.since + '</b><div><h3>' + esc(L(f.country)) + '</h3><p>' + esc(L(f.addr)) + '</p></div></li>'; }).join('') +
      '<li data-reveal><b>' + new Date().getFullYear() + '</b><div><h3>' + esc(L(C.export.points[0].t)) + '</h3><p>' + esc(L(C.export.text)) + '</p></div></li></ol></div></section>' +
      '<section class="section" style="padding-top:0"><div class="wrap"><div class="vg-grid" data-stagger>' +
      '<div class="vg vg-red" data-reveal><span class="vg-ico">' + ico('i-eye') + '</span><h3>' + t('vision') + '</h3><p>' + esc(L(A.vision)) + '</p><span class="vg-bg" aria-hidden="true">' + ico('d-plate') + '</span></div>' +
      '<div class="vg vg-gold" data-reveal><span class="vg-ico">' + ico('i-target') + '</span><h3>' + t('mission') + '</h3><p>' + esc(L(A.mission)) + '</p><span class="vg-bg" aria-hidden="true">' + ico('d-plate') + '</span></div></div>' +
      '<div class="values-box" data-reveal><h3>' + t('values') + '</h3><ul>' + A.values.map(function (v) { return '<li>' + ico('i-check') + esc(L(v)) + '</li>'; }).join('') + '</ul></div>' +
      '<div class="reach" data-reveal><b>?</b><div><h3>' + t('why') + '</h3><p>' + esc(L(A.why)) + '</p></div></div></div></section>' +
      roll() + factories() + cta();
  }

  function vExport() {
    var E = C.export;
    return pageHero(t('export'), L(E.text), [[t('export')]], 'spread-1') + exportSec(true) + packingTable() +
      '<section class="section" style="padding-top:0"><div class="wrap"><header class="sec-head"><h2>' + esc(L(E.steps_title)) + '</h2></header><div class="ex-steps" data-stagger>' +
      E.steps.map(function (x, i) { return '<div class="ex-step" data-reveal><b>' + (i + 1) + '</b><h3>' + esc(L(x.t)) + '</h3><p>' + esc(L(x.d)) + '</p></div>'; }).join('') + '</div></div></section>' +
      '<section class="section" style="padding-top:0"><div class="wrap" style="max-width:820px">' + formCard({ first: S.subjects[LI[lang]][1], id: 'export-form' }) + '</div></section>';
  }

  function vAgent() {
    var A = C.agent;
    return pageHero(L(A.title), L(A.text), [[t('agent')]], 'dipped-5') +
      '<section class="section"><div class="wrap"><header class="sec-head"><h2>' + esc(L(A.benefits_title)) + '</h2></header><div class="agent-grid" data-stagger>' +
      A.benefits.map(function (x) { return '<div class="agent-card" data-reveal>' + badge('i-' + x.icon) + '<h3>' + esc(L(x.t)) + '</h3><p>' + esc(L(x.d)) + '</p></div>'; }).join('') + '</div></div></section>' +
      '<section class="section roll crimp-tb"><div class="wrap"><header class="sec-head"><h2>' + t('agentSteps') + '</h2></header><div class="ex-steps" data-stagger>' +
      C.export.steps.map(function (x, i) { return '<div class="ex-step" data-reveal><b>' + (i + 1) + '</b><h3>' + esc(L(x.t)) + '</h3><p>' + esc(L(x.d)) + '</p></div>'; }).join('') + '</div></div></section>' +
      '<section class="section"><div class="wrap agent-form-grid"><div class="agent-side" data-reveal>' + girlVisual(false) + stats() + '</div>' + formCard({ agent: true, title: esc(L(A.form_title)), id: 'agent-form' }) + '</div></section>';
  }

  function vReviews() {
    var stars = ''; for (var i = 5; i >= 1; i--) stars += '<input type="radio" name="rating" id="r' + i + '" value="' + i + '"' + (i === 5 ? ' checked' : '') + '><label for="r' + i + '" aria-label="' + i + '">' + ico('i-star') + '</label>';
    return pageHero(t('reviews'), t('reviewsLead'), [[t('reviews')]], 'filled-11') +
      '<section class="section"><div class="wrap"><div class="reviews-grid" data-stagger>' + reviewCards() + '</div></div></section>' +
      '<section class="section" style="padding-top:0"><div class="wrap" style="max-width:820px"><div class="form-card" data-reveal><h2>' + t('shareReview') + '</h2><p>' + t('shareReviewText') + '</p>' +
      '<form data-kk-contact novalidate data-msg-required="' + esc(t('required')) + '"><input type="hidden" name="kind" value="review">' +
      '<div class="f-row"><div class="field"><label for="rv-name">' + t('fName') + ' <span class="req">*</span></label><input id="rv-name" name="name" required></div><div class="field"><label for="rv-city">' + t('fCity') + ' ' + t('fOpt') + '</label><input id="rv-city" name="city"></div></div>' +
      '<div class="field"><span class="flabel">' + t('rating') + '</span><div class="rate">' + stars + '</div></div>' +
      '<div class="field"><label for="rv-msg">' + t('yourReview') + ' <span class="req">*</span></label><textarea id="rv-msg" name="message" required></textarea></div>' +
      '<input type="hidden" name="phone" value="-"><input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">' +
      '<button class="btn" type="submit">' + t('send') + ico('i-arrow', 'ico-dir') + '</button><p class="form-status" role="status" aria-live="polite"></p></form></div></div></section>' + cta();
  }

  function vContact() {
    var s = C.site;
    return pageHero(t('contact'), t('contactLead'), [[t('contact')]], 'spread-4') +
      '<section class="section"><div class="wrap contact-grid"><div class="c-list" data-stagger>' +
      '<div class="c-item" data-reveal>' + badge('i-phone') + '<div><small>' + t('call') + '</small><strong class="nums"><a href="tel:+2' + s.phone1 + '" dir="ltr">' + s.phone1 + '</a><a href="tel:+2' + s.phone2 + '" dir="ltr">' + s.phone2 + '</a></strong></div></div>' +
      '<a class="c-item" data-reveal href="' + WA + '" target="_blank" rel="noopener">' + badge('i-whatsapp') + '<div><small>' + t('whatsapp') + '</small><strong>' + t('chat') + '</strong></div></a>' +
      '<a class="c-item" data-reveal href="mailto:' + s.email + '">' + badge('i-mail') + '<div><small>' + t('mail') + '</small><strong>' + s.email + '</strong></div></a>' +
      C.factories.map(function (f) { return '<div class="c-item" data-reveal>' + badge('i-pin') + '<div><small>' + esc(L(f.country)) + ' · ' + t('since') + ' ' + f.since + '</small><strong>' + esc(L(f.addr)) + '</strong></div></div>'; }).join('') +
      '</div>' + formCard() + '</div></section>' + factories();
  }

  function vNotFound() {
    return '<section class="nf"><div class="wrap"><div class="nf-num" aria-hidden="true">4' + ico('d-biscuit') + '4</div><h1>' + t('nfTitle') + '</h1><p>' + t('nfText') + '</p>' +
      '<div class="cta-actions"><a class="btn" href="#home">' + t('backHome') + '</a><a class="btn btn-cream" href="#products">' + t('browse') + '</a></div></div></section>';
  }

  /* ---------- Router ---------- */
  function route() {
    var h = (location.hash || '#home').slice(1);
    if (h === '' || h === 'home' || h === 'main' || h === 'home-products') return ['home', vHome()];
    if (h === 'about') return ['about', vAbout()];
    if (h === 'contact' || h === 'form') return ['contact', vContact()];
    if (h === 'export' || h === 'export-form' || h === 'packing') return ['export', vExport()];
    if (h === 'agent' || h === 'agent-form') return ['agent', vAgent()];
    if (h === 'reviews') return ['reviews', vReviews()];
    if (h === 'products') return ['products', vProducts()];
    if (h.indexOf('cat-') === 0) return ['cat', vProducts(h.slice(4))];
    if (h.indexOf('brand-') === 0) return ['brand', vProducts(null, h.slice(6))];
    if (h.indexOf('product-') === 0) return ['product', vProduct(h.slice(8))];
    return ['404', vNotFound()];
  }

  var first = true, lastView = '';
  function render(scrollTop) {
    var r = route();
    var html = document.documentElement;
    html.setAttribute('lang', lang);
    html.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
    document.title = (r[0] === 'home' ? L(C.site.name) + ' | ' + L(C.site.tagline) : (S[r[0]] ? t(r[0]) : t('products')) + ' | ' + L(C.site.name));
    var app = document.getElementById('app');
    app.innerHTML = header(r[0]) + '<main id="main" tabindex="-1">' + r[1] + '</main>' + footer();
    if (!first) { var i = app.querySelector('[data-intro]'); i && i.remove(); }
    first = false;
    window.KK.init();
    bindPreview();
    var h = location.hash.slice(1);
    if (scrollTop && /^(export-form|packing|agent-form)$/.test(h)) { var el = document.getElementById(h); el && setTimeout(function () { el.scrollIntoView(); }, 50); }
    else if (scrollTop) window.scrollTo(0, 0);
    lastView = r[0];
  }

  function bindPreview() {
    document.querySelectorAll('[data-set-lang]').forEach(function (a) {
      a.addEventListener('click', function (e) { e.preventDefault(); lang = a.dataset.setLang; try { localStorage.setItem('kk_prev_lang', lang); } catch (x) {} render(false); });
    });
  }

  window.KK_PREVIEW_SUBMIT = function (form, status) {
    status.textContent = t('previewSent'); status.className = 'form-status is-ok'; form.reset();
  };

  window.addEventListener('hashchange', function () {
    var h = location.hash.slice(1);
    if (h === 'main') return;
    if (/^(export-form|packing)$/.test(h) && lastView === 'export') { var el = document.getElementById(h); el && el.scrollIntoView(); return; }
    if (h === 'agent-form' && lastView === 'agent') { var e2 = document.getElementById(h); e2 && e2.scrollIntoView(); return; }
    render(true);
  });
  render(false);
})();

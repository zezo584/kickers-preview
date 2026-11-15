/*!
 * Kickers Foods — main.js (no dependencies)
 * Header, mobile drawer, hero slider with crimped wipe, parallax, reveals,
 * counters, rolling-biscuit process, product filters, gallery, contact form.
 */
(function () {
  'use strict';

  var doc = document, root = doc.documentElement;
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');
  var ctrl = null; // AbortController for the current init

  function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
  function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }
  function isRTL() { return (root.getAttribute('dir') || 'ltr') === 'rtl'; }
  function on(el, ev, fn, opts) { if (!el) return; opts = opts || {}; opts.signal = ctrl.signal; el.addEventListener(ev, fn, opts); }
  function clamp(v, a, b) { return Math.min(b, Math.max(a, v)); }

  /* ---------------- Intro ---------------- */
  function intro() {
    var el = $('[data-intro]');
    if (!el) return;
    var seen = false;
    try { seen = sessionStorage.getItem('kk_intro') === '1'; } catch (e) {}
    if (seen || reduce.matches) { el.remove(); return; }
    var done = false;
    function finish() {
      if (done) return; done = true;
      el.classList.add('is-done');
      try { sessionStorage.setItem('kk_intro', '1'); } catch (e) {}
      setTimeout(function () { el.remove(); }, 950);
    }
    setTimeout(finish, 1150);
    on(el, 'click', finish);
  }

  /* ---------------- Header + drawer ---------------- */
  function header() {
    var h = $('[data-header]');
    if (h) {
      var fab = $('.fab');
      var tick = function () {
        h.classList.toggle('is-scrolled', window.scrollY > 12);
        if (fab) fab.classList.toggle('is-shown', !$('[data-hero]') || window.scrollY > 360);
      };
      tick();
      on(window, 'scroll', tick, { passive: true });
    }
    $$('.main-nav .sub-toggle').forEach(function (b) {
      on(b, 'click', function () {
        var li = b.closest('li'); var open = !li.classList.contains('is-open');
        $$('.main-nav li.is-open').forEach(function (x) { x.classList.remove('is-open'); });
        li.classList.toggle('is-open', open); b.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });
    on(doc, 'click', function (e) {
      if (!e.target.closest('.main-nav li')) $$('.main-nav li.is-open').forEach(function (x) { x.classList.remove('is-open'); });
    });

    var drawer = $('[data-drawer]'), toggle = $('[data-menu-toggle]');
    if (!drawer || !toggle) return;
    var closeBtn = $('[data-drawer-close]', drawer);
    $$('.drawer-nav a', drawer).forEach(function (a, i) { a.style.setProperty('--i', i); });
    function open() {
      var r = toggle.getBoundingClientRect();
      drawer.style.setProperty('--ox', (r.left + r.width / 2) + 'px');
      drawer.classList.add('is-open'); drawer.removeAttribute('inert');
      toggle.setAttribute('aria-expanded', 'true'); doc.body.classList.add('is-locked');
      setTimeout(function () { closeBtn && closeBtn.focus(); }, 300);
    }
    function close() {
      drawer.classList.remove('is-open'); drawer.setAttribute('inert', '');
      toggle.setAttribute('aria-expanded', 'false'); doc.body.classList.remove('is-locked');
      toggle.focus();
    }
    drawer.setAttribute('inert', '');
    on(toggle, 'click', open);
    on(closeBtn, 'click', close);
    on(drawer, 'click', function (e) { if (e.target.closest('a')) close(); });
    on(doc, 'keydown', function (e) { if (e.key === 'Escape' && drawer.classList.contains('is-open')) close(); });
  }

  /* ---------------- Split words (Arabic-safe: words only) ---------------- */
  function splitWords(el) {
    if (el.dataset.splitDone) return;
    var words = el.textContent.trim().split(/\s+/);
    el.setAttribute('aria-label', el.textContent.trim());
    el.textContent = '';
    words.forEach(function (w, i) {
      var outer = doc.createElement('span'); outer.className = 'w'; outer.setAttribute('aria-hidden', 'true');
      var inner = doc.createElement('span'); inner.textContent = w; outer.style.setProperty('--i', i);
      outer.appendChild(inner); el.appendChild(outer);
      if (i < words.length - 1) el.appendChild(doc.createTextNode(' '));
    });
    el.dataset.splitDone = '1';
  }

  /* ---------------- Hero slider ---------------- */
  var THEME_FG = { red: '#ffffff', cocoa: '#FFF6E6', gold: '#4A2C1A', cream: '#4A2C1A' };
  var THEME_BG = { red: '#ED1C2E', cocoa: '#4A2C1A', gold: '#FFC928', cream: '#FFF6E6' };

  function zig(e, fromLeft) {
    var N = 18, A = 2.6, pts = [];
    if (fromLeft) {
      pts.push('0% 0%');
      for (var i = 0; i <= N; i++) pts.push((e + (i % 2 ? A : 0)).toFixed(2) + '% ' + (i * 100 / N).toFixed(2) + '%');
      pts.push('0% 100%');
    } else {
      pts.push('100% 0%');
      for (var j = 0; j <= N; j++) pts.push((100 - e - (j % 2 ? A : 0)).toFixed(2) + '% ' + (j * 100 / N).toFixed(2) + '%');
      pts.push('100% 100%');
    }
    return 'polygon(' + pts.join(',') + ')';
  }

  function slider(hero) {
    var stage = $('.hero-stage', hero);
    var slides = $$('.slide', hero);
    var tabs = $$('.hero-tab', hero);
    if (!slides.length) return;
    var idx = 0, busy = false, auto = !reduce.matches && hero.dataset.autoplay !== '0';
    var dur = parseFloat(hero.dataset.duration || '7');
    hero.style.setProperty('--dur', dur + 's');
    if (!auto) hero.classList.add('no-auto');

    $$('.slide-title', hero).forEach(splitWords);

    function paint(i) {
      var th = slides[i].dataset.theme || 'red';
      hero.style.setProperty('--h-fg', THEME_FG[th] || '#fff');
      stage.style.setProperty('--h-bg', THEME_BG[th] || '#ED1C2E');
      tabs.forEach(function (t, k) {
        t.setAttribute('aria-current', k === i ? 'true' : 'false');
        var bar = $('.bar i', t);
        if (bar) { bar.style.animation = 'none'; void bar.offsetWidth; bar.style.animation = ''; }
      });
      slides.forEach(function (s, k) {
        s.setAttribute('aria-hidden', k === i ? 'false' : 'true');
        $$('a, button', s).forEach(function (a) { a.tabIndex = k === i ? 0 : -1; });
      });
    }

    function go(n, dirHint) {
      if (busy || n === idx) return;
      n = (n + slides.length) % slides.length;
      var cur = slides[idx], nxt = slides[n];
      var forward = dirHint != null ? dirHint > 0 : n > idx;
      // next enters from the reading-end side: RTL → from left, LTR → from right
      var fromLeft = isRTL() ? forward : !forward;
      busy = true;
      cur.classList.add('is-leaving');
      cur.classList.remove('is-active');
      if (reduce.matches) {
        nxt.style.clipPath = 'none';
        nxt.classList.add('is-active');
        cur.classList.remove('is-leaving');
        busy = false;
      } else {
        nxt.style.transition = 'none';
        nxt.style.clipPath = zig(-4, fromLeft);
        nxt.classList.add('is-active');
        void nxt.offsetWidth;
        nxt.style.transition = 'clip-path 1.05s cubic-bezier(.77,0,.18,1)';
        nxt.style.clipPath = zig(104, fromLeft);
        var finish = function () {
          nxt.style.transition = ''; nxt.style.clipPath = 'none';
          cur.classList.remove('is-leaving');
          busy = false;
        };
        var t = setTimeout(finish, 1150);
        nxt.addEventListener('transitionend', function te(ev) {
          if (ev.propertyName !== 'clip-path') return;
          nxt.removeEventListener('transitionend', te); clearTimeout(t); finish();
        });
      }
      idx = n;
      paint(idx);
    }

    function next() { go(idx + 1, 1); }
    function prev() { go(idx - 1, -1); }

    slides[0].classList.add('is-active');
    paint(0);

    tabs.forEach(function (t, k) { on(t, 'click', function () { go(k, k > idx ? 1 : -1); }); });
    on($('[data-hero-next]', hero), 'click', next);
    on($('[data-hero-prev]', hero), 'click', prev);
    var toggle = $('[data-hero-toggle]', hero);
    on(toggle, 'click', function () {
      auto = !auto;
      hero.classList.toggle('no-auto', !auto);
      toggle.setAttribute('aria-pressed', auto ? 'false' : 'true');
      toggle.setAttribute('aria-label', auto ? (toggle.dataset.labelPause || 'Pause') : (toggle.dataset.labelPlay || 'Play'));
      paint(idx);
    });

    // autoplay driven by the active tab's progress bar
    tabs.forEach(function (t) {
      on($('.bar i', t), 'animationend', function () { if (auto) next(); });
    });
    if (!tabs.length && auto) {
      var timer = setInterval(function () { if (!hero.classList.contains('is-paused')) next(); }, dur * 1000);
      ctrl.signal.addEventListener('abort', function () { clearInterval(timer); });
    }
    on(hero, 'mouseenter', function () { hero.classList.add('is-paused'); });
    on(hero, 'mouseleave', function () { hero.classList.remove('is-paused'); });
    on(hero, 'focusin', function () { hero.classList.add('is-paused'); });
    on(hero, 'focusout', function (e) { if (!hero.contains(e.relatedTarget)) hero.classList.remove('is-paused'); });
    on(doc, 'visibilitychange', function () { hero.classList.toggle('is-paused', doc.hidden); });
    on(hero, 'keydown', function (e) {
      if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
      var goNext = (e.key === 'ArrowLeft') === isRTL();
      goNext ? next() : prev();
    });

    // swipe
    var sx = null, sy = null;
    on(stage, 'pointerdown', function (e) { sx = e.clientX; sy = e.clientY; });
    on(stage, 'pointerup', function (e) {
      if (sx === null) return;
      var dx = e.clientX - sx, dy = e.clientY - sy; sx = null;
      if (Math.abs(dx) < 50 || Math.abs(dx) < Math.abs(dy)) return;
      var goNext = isRTL() ? dx > 0 : dx < 0;
      goNext ? next() : prev();
    });

    // parallax
    if (finePointer.matches && !reduce.matches) {
      var raf = 0, mx = 0, my = 0;
      on(stage, 'pointermove', function (e) {
        var r = stage.getBoundingClientRect();
        mx = ((e.clientX - r.left) / r.width - .5) * 2;
        my = ((e.clientY - r.top) / r.height - .5) * 2;
        if (!raf) raf = requestAnimationFrame(function () {
          raf = 0;
          var v = $('.slide.is-active .slide-visual', hero);
          if (v) { v.style.setProperty('--mx', mx.toFixed(3)); v.style.setProperty('--my', my.toFixed(3)); }
        });
      });
      on(stage, 'pointerleave', function () {
        $$('.slide-visual', hero).forEach(function (v) { v.style.setProperty('--mx', 0); v.style.setProperty('--my', 0); });
      });
    }
  }

  /* ---------------- Reveal on scroll ---------------- */
  function reveals() {
    $$('[data-stagger]').forEach(function (g) {
      $$('[data-reveal]', g).forEach(function (el, i) { el.style.setProperty('--n', i % 8); });
    });
    var els = $$('[data-reveal]:not(.is-in)');
    if (!('IntersectionObserver' in window) || reduce.matches) { els.forEach(function (e) { e.classList.add('is-in'); }); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px', threshold: .12 });
    els.forEach(function (e) { io.observe(e); });
    ctrl.signal.addEventListener('abort', function () { io.disconnect(); });
  }

  /* ---------------- Counters ---------------- */
  function counters() {
    var els = $$('[data-count]');
    if (!els.length) return;
    function run(el) {
      var to = parseFloat(el.dataset.count) || 0, suf = el.dataset.suffix || '', pre = el.dataset.prefix || '';
      if (reduce.matches) { el.textContent = pre + to + suf; return; }
      var t0 = performance.now(), d = 1500;
      (function step(t) {
        var p = clamp((t - t0) / d, 0, 1), e = 1 - Math.pow(1 - p, 3);
        el.textContent = pre + Math.round(to * e) + suf;
        if (p < 1) requestAnimationFrame(step);
      })(t0);
    }
    if (!('IntersectionObserver' in window)) { els.forEach(run); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { run(en.target); io.unobserve(en.target); } });
    }, { threshold: .6 });
    els.forEach(function (e) { io.observe(e); });
    ctrl.signal.addEventListener('abort', function () { io.disconnect(); });
  }

  /* ---------------- Rolling biscuit process ---------------- */
  function rolls() {
    var tracks = $$('[data-roll]');
    if (!tracks.length) return;
    var vertical = window.matchMedia('(max-width: 900px)');
    var raf = 0;
    function update() {
      raf = 0;
      var vh = window.innerHeight;
      tracks.forEach(function (tr) {
        var ball = $('.roll-ball', tr), steps = $$('.roll-step', tr);
        if (!ball) return;
        var r = tr.getBoundingClientRect();
        var p = clamp((vh * .82 - r.top) / (r.height + vh * .2), 0, 1);
        if (reduce.matches) p = 1;
        var size = ball.offsetWidth;
        if (vertical.matches) {
          var y = p * Math.max(0, r.height - size);
          ball.style.setProperty('--y', y.toFixed(1) + 'px');
          ball.style.setProperty('--x', '0px');
          ball.style.setProperty('--rot', (y / (size / 2) * 57.3).toFixed(1) + 'deg');
          steps.forEach(function (s) {
            var n = $('.roll-num', s).getBoundingClientRect();
            s.classList.toggle('is-on', y + size * .5 >= n.top - r.top);
          });
        } else {
          var x = p * Math.max(0, r.width - size);
          ball.style.setProperty('--x', x.toFixed(1) + 'px');
          ball.style.setProperty('--y', '0px');
          ball.style.setProperty('--rot', (x / (size / 2) * 57.3).toFixed(1) + 'deg');
          steps.forEach(function (s) {
            var n = $('.roll-num', s).getBoundingClientRect();
            var dist = isRTL() ? r.right - n.right : n.left - r.left;
            s.classList.toggle('is-on', x + size * .6 >= dist);
          });
        }
      });
    }
    function req() { if (!raf) raf = requestAnimationFrame(update); }
    on(window, 'scroll', req, { passive: true });
    on(window, 'resize', req);
    update();
  }

  /* ---------------- Product filter chips ---------------- */
  function filters() {
    $$('[data-filter]').forEach(function (bar) {
      var grid = $(bar.dataset.filter);
      if (!grid) return;
      var chips = $$('[data-f]', bar);
      chips.forEach(function (c) {
        on(c, 'click', function () {
          var f = c.dataset.f;
          chips.forEach(function (x) { x.setAttribute('aria-pressed', x === c ? 'true' : 'false'); });
          var n = 0;
          $$('.p-card', grid).forEach(function (card) {
            var show = f === 'all' || (' ' + card.dataset.cat + ' ').indexOf(' ' + f + ' ') > -1;
            card.classList.remove('is-pop');
            card.classList.toggle('is-hidden', !show);
            if (show) { card.style.setProperty('--n', n++); void card.offsetWidth; card.classList.add('is-pop'); }
          });
        });
      });
    });
  }

  /* ---------------- Gallery ---------------- */
  function gallery() {
    $$('[data-gallery]').forEach(function (g) {
      var main = $('[data-gallery-main]', g);
      $$('[data-src]', g).forEach(function (b) {
        on(b, 'click', function () {
          main.src = b.dataset.src;
          $$('[data-src]', g).forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
        });
      });
    });
  }

  /* ---------------- Contact form ---------------- */
  function forms() {
    $$('form[data-kk-contact]').forEach(function (form) {
      on(form, 'submit', function (e) {
        e.preventDefault();
        var status = $('.form-status', form);
        var d = window.KK_DATA || {};
        var i18n = d.i18n || {};
        status.className = 'form-status';
        if (!form.checkValidity()) {
          status.textContent = i18n.required || form.dataset.msgRequired || '';
          status.classList.add('is-err');
          var bad = $(':invalid', form); bad && bad.focus();
          return;
        }
        if (typeof window.KK_PREVIEW_SUBMIT === 'function') { window.KK_PREVIEW_SUBMIT(form, status); return; }
        var btn = $('[type=submit]', form); btn.disabled = true;
        var fd = new FormData(form);
        fd.append('action', 'kk_contact');
        fd.append('nonce', d.nonce || '');
        fd.append('kk_lang', d.lang || 'ar');
        fetch(d.ajax, { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            status.textContent = (res && res.data && res.data.message) || (res.success ? i18n.sent : i18n.error);
            status.classList.add(res.success ? 'is-ok' : 'is-err');
            if (res.success) form.reset();
          })
          .catch(function () { status.textContent = i18n.error || 'Error'; status.classList.add('is-err'); })
          .then(function () { btn.disabled = false; });
      });
    });
  }

  function years() { $$('[data-year]').forEach(function (e) { e.textContent = new Date().getFullYear(); }); }

  function init() {
    if (ctrl) ctrl.abort();
    ctrl = new AbortController();
    root.classList.remove('no-js'); root.classList.add('js');
    intro();
    header();
    $$('[data-hero]').forEach(slider);
    reveals();
    counters();
    rolls();
    filters();
    gallery();
    forms();
    years();
  }

  window.KK = { init: init };
  if (!window.KK_MANUAL) {
    if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init); else init();
  }
})();

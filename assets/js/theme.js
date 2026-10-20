/* Webni Custom Version 1 Theme — global behaviour.
   Navigation, search overlay, mini cart drawer, custom cursor, reveals, toast,
   collection filters, zodiac picker, quiz + gift flows, WooCommerce add-to-cart feedback.
   Data arrives through the localized `BPDL` object (inc/enqueue.php). */
(function () {
  'use strict';
  var D = window.BPDL || { perfumes: [], zodiac: [], quiz: null, gift: null, urls: {}, i18n: {} };
  var P = D.perfumes || [];
  var byKey = {};
  P.forEach(function (p) { byKey[p.key] = p; });
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(pointer: fine)').matches;
  document.documentElement.classList.add('js');

  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
  function sprintf(str) {
    var args = Array.prototype.slice.call(arguments, 1), i = 0;
    return String(str).replace(/%(\d+\$)?s/g, function (m, pos) { var k = pos ? parseInt(pos, 10) - 1 : i++; return args[k] == null ? '' : args[k]; });
  }
  function pad(n) { return (n < 10 ? '0' : '') + n; }
  // Display-font dashes are hairlines; render them in the body font (see .dash in theme.css).
  function dash(html) { return String(html).replace(/[—–+×−=]/g, function (c) { return '<span class="dash">' + c + '</span>'; }); }
  function escd(s) { return dash(esc(s)); }
  function packHTML(p, kind, opts) {
    opts = opts || {};
    var src = p.images && p.images[kind];
    if (!src) { return ''; }
    var photo = p.cutout && p.cutout[kind] === false; // rectangular photo: framed, not floating
    return '<img class="pack pack--' + kind + (photo ? ' pack--photo' : '') + '" src="' + esc(src) + '" alt="' + esc(p.name) + '" loading="' + (opts.eager ? 'eager' : 'lazy') + '" decoding="async">';
  }
  window.BPDL_util = { esc: esc, sprintf: sprintf, packHTML: packHTML, byKey: byKey };

  /* ---------- scramble text (hero cycle) ---------- */
  function scramble(el, text) {
    if (!el) { return; }
    if (reduce) { el.textContent = text; return; }
    var chars = 'aeioulnrstkvmpdgbzčšž', total = 22, frame = 0;
    cancelAnimationFrame(el._raf);
    (function step() {
      frame++;
      var out = '';
      for (var k = 0; k < text.length; k++) {
        var ch = text[k];
        out += (frame / total > k / text.length + 0.18 || ch === ' ') ? ch : chars[Math.random() * chars.length | 0];
      }
      el.textContent = out;
      if (frame < total) { el._raf = requestAnimationFrame(step); } else { el.textContent = text; }
    })();
  }
  window.BPDL_scramble = scramble;

  function applyPalette(el, p) {
    el.style.setProperty('--pc1', p.palette.c1); el.style.setProperty('--pc2', p.palette.c2);
    el.style.setProperty('--pc3', p.palette.c3); el.style.setProperty('--pacc', p.palette.accent);
    el.classList.toggle('is-light', !!p.palette.light);
  }
  window.BPDL_applyPalette = applyPalette;

  /* ---------- navigation ---------- */
  var nav = $('.nav');
  function onScroll() { if (nav) { nav.classList.toggle('is-scrolled', window.scrollY > 40); } }
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
  var burger = $('.burger');
  if (burger) {
    burger.addEventListener('click', function () {
      var on = document.body.classList.toggle('menu-open');
      burger.setAttribute('aria-expanded', on ? 'true' : 'false');
      document.body.style.overflow = on ? 'hidden' : '';
    });
  }
  $$('.menu a').forEach(function (a) { a.addEventListener('click', function () { document.body.classList.remove('menu-open'); document.body.style.overflow = ''; if (burger) { burger.setAttribute('aria-expanded', 'false'); } }); });

  /* ---------- overlays (search, mini cart) ---------- */
  var lastFocus = null;
  function openOverlay(el, focusSel) {
    if (!el) { return; }
    lastFocus = document.activeElement;
    el.hidden = false; document.body.style.overflow = 'hidden';
    var f = focusSel && $(focusSel, el); if (f) { f.focus({ preventScroll: true }); }
  }
  function closeOverlay(el) {
    if (!el || el.hidden) { return; }
    el.hidden = true; document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) { lastFocus.focus({ preventScroll: true }); }
  }
  var search = $('#searchOverlay'), minicart = $('#minicart');
  document.addEventListener('click', function (e) {
    if (e.target.closest('[data-open-search]')) { e.preventDefault(); openOverlay(search, 'input[type="search"]'); }
    if (e.target.closest('[data-open-cart]')) { e.preventDefault(); openOverlay(minicart, '.minicart__close'); }
    if (e.target.closest('[data-close-overlay]')) { e.preventDefault(); closeOverlay(e.target.closest('.search-overlay, .minicart')); }
    if (e.target === search) { closeOverlay(search); }
    if (e.target === minicart) { closeOverlay(minicart); }
  });
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') { return; }
    closeOverlay(search); closeOverlay(minicart);
    if (modal && !modal.hidden) { closeQuiz(); }
    if (document.body.classList.contains('menu-open')) { document.body.classList.remove('menu-open'); document.body.style.overflow = ''; }
  });

  /* ---------- cursor, magnetic buttons ---------- */
  if (fine && !reduce) {
    var c = $('.cursor');
    if (c) {
      document.body.classList.add('has-cursor');
      var dot = $('.cursor__dot', c), ring = $('.cursor__ring', c);
      var tx = window.innerWidth / 2, ty = window.innerHeight / 2, x = tx, y = ty;
      window.addEventListener('pointermove', function (e) { tx = e.clientX; ty = e.clientY; dot.style.transform = 'translate(' + tx + 'px,' + ty + 'px)'; c.style.opacity = 1; });
      document.addEventListener('mouseleave', function () { c.style.opacity = 0; });
      window.addEventListener('pointerdown', function () { c.classList.add('is-down'); });
      window.addEventListener('pointerup', function () { c.classList.remove('is-down'); });
      document.addEventListener('pointerover', function (e) { c.classList.toggle('is-hover', !!e.target.closest('a,button,label,input,select,textarea')); });
      (function loop() { x += (tx - x) * 0.18; y += (ty - y) * 0.18; ring.style.transform = 'translate(' + x + 'px,' + y + 'px)'; requestAnimationFrame(loop); })();
    }
    $$('.btn').forEach(function (b) {
      b.addEventListener('pointermove', function (e) { var r = b.getBoundingClientRect(); var dx = e.clientX - (r.left + r.width / 2), dy = e.clientY - (r.top + r.height / 2); b.style.transform = 'translate(' + (dx * 0.22) + 'px,' + (dy * 0.22) + 'px)'; });
      b.addEventListener('pointerleave', function () { b.style.transform = ''; });
    });
  }

  /* ---------- reveals ---------- */
  var revealIO = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('in'); revealIO.unobserve(e.target); } });
  }, { threshold: 0.1, rootMargin: '0px 0px -6% 0px' });
  $$('.reveal, .bars').forEach(function (el) { revealIO.observe(el); });
  window.addEventListener('load', function () { setTimeout(function () { $$('.reveal, .bars').forEach(function (el) { el.classList.add('in'); }); }, 2500); });

  /* ---------- toast ---------- */
  var toastEl = $('.toast'), toastT;
  function toast(html) {
    if (!toastEl) { return; }
    toastEl.innerHTML = html;
    toastEl.classList.add('is-on');
    clearTimeout(toastT);
    toastT = setTimeout(function () { toastEl.classList.remove('is-on'); }, 4200);
  }
  window.BPDL_toast = toast;

  /* ---------- WooCommerce: add to cart feedback ---------- */
  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', function (e, fragments, hash, button) {
      var name = '';
      var card = button && button.closest ? button.closest('.product') : null;
      if (card && card.length) { var t = card.find('.card__title, .woocommerce-loop-product__title').first(); name = t.text().trim(); }
      var named = button && button.closest ? button.closest('[data-product-name]') : null;
      if (!name && named && named.length) { name = named.attr('data-product-name') || ''; }
      toast(esc(sprintf(D.i18n.added || '%s', name || '')) + ' <a href="' + esc(D.urls.cart || '#') + '">' + esc(D.i18n.viewCart || '') + '</a>');
      if (minicart) { openOverlay(minicart, '.minicart__close'); }
    });
    window.jQuery(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function () {
      // The mini cart body and header count are replaced by fragments; nothing else to do.
    });
  }

  /* ---------- collection filters ---------- */
  $$('.filters [data-f]').forEach(function (b) {
    b.addEventListener('click', function (e) {
      e.preventDefault();
      var wrap = b.closest('.filters');
      $$('[data-f]', wrap).forEach(function (x) { x.classList.toggle('is-active', x === b); });
      var f = b.dataset.f, any = false;
      var scope = wrap.closest('.section, .site-main') || document;
      $$('.card[data-cat]', scope).forEach(function (card) {
        var cat = card.dataset.cat, time = card.dataset.time || '';
        var ok = f === 'all' || (f === 'M' && (cat === 'M' || cat === 'U')) || (f === 'Ž' && (cat === 'Ž' || cat === 'U')) || (f === 'U' && cat === 'U') || (f === 'dan' && time.indexOf('dan') > -1) || (f === 'noć' && time.indexOf('noć') > -1);
        card.classList.toggle('is-hidden', !ok);
        if (ok) { any = true; }
      });
      var empty = $('.filters__empty', scope);
      if (empty) { empty.hidden = any; }
    });
  });

  /* ---------- zodiac picker ---------- */
  var zr = $('#zodiacResult');
  $$('.zodiac__chip').forEach(function (ch) {
    ch.addEventListener('click', function () {
      var z = (D.zodiac || []).filter(function (s) { return s.sign === ch.dataset.sign; })[0];
      if (!z || !zr) { return; }
      $$('.zodiac__chip').forEach(function (x) { x.classList.toggle('is-active', x === ch); });
      var him = byKey[z.him], her = byKey[z.her];
      var pick = function (label, p) {
        if (!p) { return ''; }
        return '<div class="zodiac__pick"><span class="tiny">' + esc(label) + '</span><a href="' + esc(p.url) + '">' + escd(p.name) + '</a><span class="ital">' + escd(p.tagline) + '</span></div>';
      };
      zr.innerHTML = '<p class="eyebrow eyebrow--gold">' + esc(z.sign) + ' · ' + esc(z.dates) + ' · ' + esc(z.trait) + '</p>' + pick(zr.dataset.him || 'Za njega', him) + pick(zr.dataset.her || 'Za nju', her);
      zr.hidden = false;
    });
  });

  /* ---------- quiz ---------- */
  var Q = D.quiz, G = D.gift;
  var modal = $('#quizModal'), panel = modal && $('.quiz__panel', modal), gfHolder = modal && $('.quiz__gf', modal);
  var S = null, quizFocus = null;
  function openQuiz(mode) {
    if (!modal || !Q) { return; }
    quizFocus = document.activeElement;
    S = { mode: mode, screen: mode === 'gift' ? 'gintro' : 'intro', a: {}, hist: [] };
    modal.hidden = false; document.body.style.overflow = 'hidden';
    draw();
  }
  function closeQuiz() {
    if (!modal) { return; }
    parkGf();
    modal.hidden = true; document.body.style.overflow = '';
    panel.className = 'quiz__panel'; panel.removeAttribute('style');
    if (quizFocus && quizFocus.focus) { quizFocus.focus({ preventScroll: true }); }
  }
  // Gravity Form for the e-mail step lives in .quiz__gf and is moved into the panel when needed.
  function parkGf() { if (gfHolder && gfHolder.firstElementChild === null && panel) { var f = $('.quiz__email-form', panel); if (f) { gfHolder.appendChild(f); } } }
  document.addEventListener('click', function (e) {
    if (e.target.closest('[data-quiz]')) { e.preventDefault(); openQuiz('self'); }
    if (e.target.closest('[data-gift]')) { e.preventDefault(); openQuiz('gift'); }
  });
  if (modal) {
    modal.addEventListener('click', function (e) { if (e.target === modal) { closeQuiz(); } });
  }
  function go(screen) { parkGf(); S.hist.push(S.screen); S.screen = screen; draw(); }
  function back() { if (!S.hist.length) { return; } parkGf(); S.screen = S.hist.pop(); draw(); }
  function stepIndex() {
    var selfOrder = ['q0', 'q1', 'q2', 'q3', 'q4', 'email'], giftOrder = ['g1', 'g2', 'g3', 'g4', 'g5'];
    var o = S.screen.charAt(0) === 'g' ? giftOrder : selfOrder;
    return [o.indexOf(S.screen), o.length];
  }
  function answers(list, cols) {
    return '<div class="quiz__answers cols-' + cols + '">' + list.map(function (a) {
      return '<button type="button" class="answer" data-k="' + esc(a[0]) + '"><b>' + escd(a[1]) + '</b>' + (a[2] ? '<span>' + esc(a[2]) + '</span>' : '') + '</button>';
    }).join('') + '</div>';
  }
  function hasGf() { return !!(gfHolder && $('.quiz__email-form', gfHolder)); }
  function draw() {
    var s = S.screen;
    panel.className = 'quiz__panel';
    panel.removeAttribute('style');
    var st = stepIndex(), idx = st[0], total = st[1];
    if (!hasGf() && idx >= 0 && s.charAt(0) !== 'g') { total = 5; }
    var prog = idx >= 0 ? '<div class="quiz__progress" aria-label="' + esc(sprintf(Q.result.of, idx + 1, total)) + '">' + Array.apply(null, { length: total }).map(function (_, k) { return '<i class="' + (k <= idx ? 'on' : '') + '"></i>'; }).join('') + '</div>' : '<span></span>';
    var body = '', foot = '';
    var top = '<div class="quiz__top">' + prog + '<button type="button" class="quiz__close" aria-label="' + esc(Q.result.close) + '" data-close>×</button></div>';
    if (s === 'intro') {
      body = '<p class="eyebrow eyebrow--gold">' + esc(Q.intro.eyebrow) + '</p><h2 class="quiz__q">' + escd(Q.intro.title) + '</h2><p class="lead">' + esc(Q.intro.text) + '</p><div><button type="button" class="btn btn--solid btn--lg" data-next="q0">' + esc(Q.intro.cta) + '</button></div>';
    } else if (s === 'gintro') {
      body = '<p class="eyebrow eyebrow--gold">' + esc(G.intro.eyebrow) + '</p><h2 class="quiz__q">' + escd(G.intro.title) + '</h2><p class="lead">' + esc(G.intro.text) + '</p><div><button type="button" class="btn btn--solid btn--lg" data-next="g1">' + esc(G.intro.cta) + '</button></div>';
    } else if (s === 'q0') {
      body = '<h2 class="quiz__q">' + escd(Q.q0.q) + '</h2>' + answers(Q.q0.a, 2);
    } else if (s === 'q1') {
      body = '<h2 class="quiz__q">' + escd(Q.q1.q) + '</h2>' + answers(Q.q1.a, 3);
    } else if (s === 'q2') {
      body = '<h2 class="quiz__q">' + escd(Q.q2.q) + '</h2>' + answers(Q.q2.a, 3);
    } else if (s === 'q3') {
      var b3 = Q.q3[S.a.when] || Q.q3.dan;
      body = '<h2 class="quiz__q">' + escd(b3.q) + '</h2>' + answers(b3.a, 2);
    } else if (s === 'q4') {
      body = '<h2 class="quiz__q">' + escd(Q.q4.q) + '</h2>' + answers(Q.q4.a, 4);
    } else if (s === 'email') {
      body = '<h2 class="quiz__q">' + escd(Q.email.title) + '</h2><p class="lead">' + esc(Q.email.text) + '</p><div class="quiz__email"></div><div><button type="button" class="quiz__back" data-next="result">' + esc(Q.email.skip) + '</button></div>';
    } else if (s === 'g1') {
      body = '<h2 class="quiz__q">' + escd(G.g1.q) + '</h2>' + answers(G.g1.a, 2);
    } else if (s === 'g2') {
      body = '<h2 class="quiz__q">' + escd(G.g2.q) + '</h2><div class="quiz__answers cols-4">' + (D.zodiac || []).map(function (z) { return '<button type="button" class="answer answer--chip" data-k="' + esc(z.sign) + '"><b>' + esc(z.sign) + '</b><span>' + esc(z.dates) + '</span></button>'; }).join('') + '</div><div><button type="button" class="quiz__back" data-k="">' + esc(G.g2.unknown) + '</button></div>';
    } else if (s === 'g3') {
      body = '<h2 class="quiz__q">' + escd(G.g3.q) + '</h2>' + answers(G.g3.a, 4);
    } else if (s === 'g4') {
      body = '<h2 class="quiz__q">' + escd(G.g4.q) + '</h2>' + answers(G.g4.a, 3);
    } else if (s === 'g5') {
      body = '<h2 class="quiz__q">' + escd(G.g5.q) + '</h2>' + answers(G.g5.a, 2);
    } else if (s === 'result') {
      body = resultSelf();
    } else if (s === 'gresult') {
      body = resultGift();
    }
    if (S.hist.length && s !== 'result' && s !== 'gresult') {
      foot = '<div class="quiz__foot"><button type="button" class="quiz__back" data-back>← ' + esc(Q.result.back) + '</button><span class="tiny">' + (idx >= 0 ? esc(sprintf(Q.result.of, idx + 1, total)) + '' : '') + '</span></div>';
    }
    panel.innerHTML = top + body + foot;
    if (s === 'email') {
      var slot = $('.quiz__email', panel), form = gfHolder && $('.quiz__email-form', gfHolder);
      if (slot && form) {
        slot.appendChild(form);
        var r = computeSelf(S.a);
        $$('.webni-quiz-result input, input.webni-quiz-result', form).forEach(function (inp) { inp.value = r.p.character + ' (' + r.p.name + ')'; });
      }
    }
    var first = panel.querySelector('.answer, .btn, input:not([type=hidden])');
    if (first) { first.focus({ preventScroll: true }); }
  }
  function computeSelf(a) {
    var r = Q.results[(a.when || 'dan') + '-' + (a.q3 || 'sveze')] || Q.results['dan-sveze'];
    var col = a.who === 'any' ? (r.any ? 'any' : 'him') : (a.who || 'him');
    var pick = r[col] || r.him;
    var key = pick[0], why = pick[1];
    var pair = Q.styleAlt[a.style] || [key];
    var alt = a.who === 'her' ? pair[1] : pair[0];
    if (alt === key && byKey[key] && byKey[key].alts) { alt = byKey[key].alts[0]; }
    var p = byKey[key] || P[0];
    return { p: p, why: why, alt: byKey[alt] || null };
  }
  function computeGift(a) {
    var z = null, key;
    if (a.sign) { z = (D.zodiac || []).filter(function (x) { return x.sign === a.sign; })[0]; key = z ? z[a.who] : null; }
    if (!key) { var pair = G.byStyle[a.style] || G.byStyle.klasika; key = a.who === 'her' ? pair[1] : pair[0]; }
    if (a.when === 'posao' && (key === 'ravon' || key === 'luna')) { key = a.who === 'her' ? 'deuxieme' : 'arden'; }
    if (a.when === 'dan' && key === 'ravon') { key = 'secondo'; }
    if (a.when === 'dan' && key === 'luna') { key = 'deuxieme'; }
    if (a.strength === 'diskretno' && G.calm[key]) { key = G.calm[key]; }
    return { p: byKey[key] || P[0], z: z };
  }
  function resultSelf() {
    var r = computeSelf(S.a), p = r.p;
    if (!p) { return '<p class="lead">' + esc(D.i18n.noProducts || '') + '</p>'; }
    panel.classList.add('is-result'); applyPalette(panel, p);
    var share = sprintf(Q.result.share, p.character);
    var discovery = D.urls.discovery || D.urls.shop;
    return '<div class="result"><div class="result__head"><div>' +
      '<p class="eyebrow">' + esc(sprintf(Q.result.eyebrow, pad(p.n), pad(P.length))) + '</p>' +
      '<h2 class="result__title">' + escd(sprintf(Q.result.title, p.character)) + '</h2>' +
      '<p class="result__sub">' + escd(p.name) + '<span class="dash"> — </span>' + escd(p.tagline) + '</p></div>' + packHTML(p, 'bottle') + '</div>' +
      '<p class="result__why">' + esc(r.why) + '</p>' +
      (p.quizDesc ? '<p class="result__why">' + esc(p.quizDesc) + '</p>' : '') +
      '<ul class="chips">' + (p.words || []).map(function (w) { return '<li>' + esc(w) + '</li>'; }).join('') + '</ul>' +
      '<div class="result__actions"><a class="btn btn--solid" href="' + esc(p.url) + '">' + esc(sprintf(Q.result.see, p.name)) + '</a><a class="btn btn--ghost" href="' + esc(discovery) + '">' + esc(Q.result.discovery) + '</a></div>' +
      (r.alt ? '<p class="result__alt">' + esc(Q.result.notIt) + ' <a href="' + esc(r.alt.url) + '">' + esc(r.alt.name) + '</a>.</p>' : '') +
      '<div class="result__actions"><button type="button" class="btn btn--sm btn--ghost" data-share="' + esc(share) + '">' + esc(Q.result.shareBtn) + ': „' + esc(share) + '“</button><button type="button" class="btn btn--sm btn--ghost" data-again>' + esc(Q.result.again) + '</button></div></div>';
  }
  function resultGift() {
    var r = computeGift(S.a), p = r.p, z = r.z;
    if (!p) { return '<p class="lead">' + esc(D.i18n.noProducts || '') + '</p>'; }
    panel.classList.add('is-result'); applyPalette(panel, p);
    var title = z ? sprintf(G.result.forSign, z.acc, z.rel, p.name) : sprintf(S.a.who === 'her' ? G.result.forHer : G.result.forHim, p.name);
    return '<div class="result"><div class="result__head"><div>' +
      '<p class="eyebrow">' + esc(G.result.eyebrow) + ' · ' + esc(p.character) + '</p>' +
      '<h2 class="result__title">' + escd(title) + '</h2>' +
      '<p class="result__sub">' + escd(p.tagline) + '</p></div>' + packHTML(p, 'bottle') + '</div>' +
      '<p class="result__why">' + esc(p.short) + '</p>' +
      '<p class="result__why">' + esc(sprintf(G.result.card, p.character)) + '</p>' +
      '<div class="result__actions"><a class="btn btn--solid" href="' + esc(p.url) + '">' + esc(G.result.send) + '</a><a class="btn btn--ghost" href="' + esc(D.urls.discovery || D.urls.shop) + '">' + esc(G.result.unsure) + '</a></div>' +
      '<div class="result__actions"><button type="button" class="btn btn--sm btn--ghost" data-again>' + esc(Q.result.again) + '</button></div>' +
      '<p class="tiny">' + esc(G.result.note) + '</p></div>';
  }
  if (modal) {
    modal.addEventListener('click', function (e) {
      var t = e.target;
      if (t.closest('[data-close]')) { return closeQuiz(); }
      if (t.closest('[data-back]')) { return back(); }
      if (t.closest('[data-again]')) { S = { mode: S.mode, screen: S.mode === 'gift' ? 'gintro' : 'intro', a: {}, hist: [] }; return draw(); }
      var nx = t.closest('[data-next]');
      if (nx) {
        var target = nx.dataset.next;
        if (target === 'email' && !hasGf()) { target = 'result'; }
        return go(target);
      }
      var sh = t.closest('[data-share]');
      if (sh) {
        var txt = sh.dataset.share + ' ' + (D.urls.home || location.href.replace(/#.*$/, ''));
        var done = function () { toast(esc(D.i18n.copied || '')); };
        if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(txt).then(done, function () { toast(esc(txt)); }); } else { toast(esc(txt)); }
        return;
      }
      var ans = t.closest('[data-k]');
      if (!ans) { return; }
      var k = ans.dataset.k, s = S.screen;
      if (s === 'q0') { if (k === 'gift') { S.mode = 'gift'; S.a.who = null; return go('g1'); } return go('q1'); }
      if (s === 'q1') { S.a.who = k; return go('q2'); }
      if (s === 'q2') { S.a.when = k; return go('q3'); }
      if (s === 'q3') { S.a.q3 = k; return go('q4'); }
      if (s === 'q4') { S.a.style = k; return go(hasGf() ? 'email' : 'result'); }
      if (s === 'g1') { S.a.who = k; return go('g2'); }
      if (s === 'g2') { S.a.sign = k || null; return go(k ? 'g4' : 'g3'); }
      if (s === 'g3') { S.a.style = k; return go('g4'); }
      if (s === 'g4') { S.a.when = k; return go('g5'); }
      if (s === 'g5') { S.a.strength = k; return go('gresult'); }
    });
    // Gravity Forms AJAX confirmation → show the result.
    if (window.jQuery) {
      window.jQuery(document).on('gform_confirmation_loaded', function (e, formId) {
        if (S && S.screen === 'email' && gfHolder && String(gfHolder.dataset.formId) === String(formId)) { go('result'); }
      });
    }
  }
})();

/* Home hero.
   "image" mode: one static picture; only the character name in "Ti si …" keeps cycling.
   "animated" mode (default): cycles through the eight characters every few seconds (palette, mist
   colours, name, photo); the mist drifts on its own and nothing follows the pointer. */
(function () {
  'use strict';
  var D = window.BPDL;
  if (!D || !D.perfumes || !D.perfumes.length) { return; }
  var P = D.perfumes;
  var hero = document.getElementById('hero');
  if (!hero || !hero.classList.contains('hero--home')) { return; }
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (hero.getAttribute('data-mode') === 'image') {
    var el = document.getElementById('cycleName');
    if (!el || reduce || P.length < 2) { return; }
    var k = 0;
    setInterval(function () {
      k = (k + 1) % P.length;
      window.BPDL_scramble(el, P[k].character);
    }, 3800);
    return;
  }

  var canvas = hero.querySelector('.mist');
  var mist = canvas && window.WebniMist ? window.WebniMist(canvas, P[0].palette, D.mist == null ? 0.5 : D.mist) : null;
  var nameEl = document.getElementById('cycleName');
  var strip = Array.prototype.slice.call(hero.querySelectorAll('.strip__item'));
  var stage = document.getElementById('heroPack');
  var imgs = stage ? Array.prototype.slice.call(stage.querySelectorAll('img')) : [];
  var CYCLE = 4600, i = 0, timer = null, startedAt = 0, remaining = CYCLE, paused = false, cur = 0, swapId = 0;
  hero.style.setProperty('--cycle', CYCLE + 'ms');

  // The first <img> comes from wp_get_attachment_image() with srcset/sizes; while those are present
  // the browser ignores a new `src`, so every other slide fell back to the first perfume. Both
  // images are plain `src` images from here on.
  imgs.forEach(function (im) { im.removeAttribute('srcset'); im.removeAttribute('sizes'); im.removeAttribute('loading'); });

  function heroSrc(p) { return p.images ? (p.images.hero || p.images.card || '') : ''; }

  // Warm the cache once the first picture is in, so later slides (and clicks) swap instantly.
  function preload() {
    P.forEach(function (p) { var s = heroSrc(p); if (s) { var im = new Image(); im.decoding = 'async'; im.src = s; } });
  }
  if (imgs.length) {
    if (document.readyState === 'complete') { setTimeout(preload, 300); } else { window.addEventListener('load', function () { setTimeout(preload, 300); }); }
  }

  // Cross-fade to a perfume's picture. Each call gets an id, so a late `load` from an earlier
  // slide (or the load event firing after a cached image already reported `complete`) can never
  // flip the two layers twice and leave the wrong one visible.
  function swapImage(p) {
    var src = heroSrc(p);
    if (imgs.length !== 2 || !src) { return; }
    var on = imgs[cur], next = imgs[1 - cur], id = ++swapId;
    if (on.getAttribute('src') === src) { on.alt = p.name; return; }
    var photo = p.cutout && p.cutout.hero === false;
    var show = function () {
      if (id !== swapId) { return; }
      swapId++; // Consumed: a second load/complete for this swap is ignored.
      next.classList.toggle('pack--photo', photo);
      next.classList.add('is-on');
      on.classList.remove('is-on');
      cur = 1 - cur;
    };
    next.onload = show;
    next.onerror = function () { if (id === swapId) { swapId++; } };
    next.alt = p.name;
    next.src = src;
    if (next.complete && next.naturalWidth) { show(); }
  }

  function set(k, first) {
    i = (k + P.length) % P.length;
    var p = P[i];
    window.BPDL_applyPalette(document.body, p);
    if (mist) { mist.setPalette(p.palette, first); }
    strip.forEach(function (b, j) {
      b.classList.toggle('is-active', j === i);
      b.setAttribute('aria-selected', j === i ? 'true' : 'false');
      if (j === i) { var bar = b.querySelector('.strip__bar'); if (bar) { bar.style.animation = 'none'; void bar.offsetWidth; bar.style.animation = ''; } }
    });
    window.BPDL_scramble(nameEl, p.character);
    if (!first) { swapImage(p); }
    schedule(CYCLE);
  }
  function schedule(ms) {
    clearTimeout(timer);
    if (reduce) { return; }
    remaining = ms; startedAt = performance.now();
    if (!paused) { timer = setTimeout(function () { set(i + 1); }, ms); }
  }
  strip.forEach(function (b, j) {
    b.addEventListener('click', function () { set(j); });
    // Hover pause is for a mouse only: on touch screens pointerenter/leave fire around every tap
    // and fought with the click.
    b.addEventListener('pointerenter', function (e) {
      if (e.pointerType !== 'mouse' || paused) { return; }
      paused = true; hero.classList.add('is-paused'); clearTimeout(timer);
      remaining = Math.max(400, remaining - (performance.now() - startedAt));
    });
    b.addEventListener('pointerleave', function (e) {
      if (e.pointerType !== 'mouse' || !paused) { return; }
      paused = false; hero.classList.remove('is-paused'); schedule(remaining);
    });
  });
  set(0, true);
})();

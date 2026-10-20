/* Home hero.
   "image" mode: one static picture; only the character name in "Ti si …" keeps cycling.
   "animated" mode: cycles through the eight characters (palette, mist colours, name, photo). */
(function () {
  'use strict';
  var D = window.BPDL;
  if (!D || !D.perfumes || !D.perfumes.length) { return; }
  var P = D.perfumes;
  var hero = document.getElementById('hero');
  if (!hero || !hero.classList.contains('hero--home')) { return; }
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(pointer: fine)').matches;

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
  var mist = canvas && window.WebniMist ? window.WebniMist(canvas, P[0].palette) : null;
  var nameEl = document.getElementById('cycleName');
  var strip = Array.prototype.slice.call(hero.querySelectorAll('.strip__item'));
  var stage = document.getElementById('heroPack');
  var imgs = stage ? Array.prototype.slice.call(stage.querySelectorAll('img')) : [];
  var CYCLE = 4600, i = 0, timer = null, startedAt = 0, remaining = CYCLE, paused = false, cur = 0;
  hero.style.setProperty('--cycle', CYCLE + 'ms');

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
    if (!first && imgs.length === 2 && p.images && p.images.hero) {
      var on = imgs[cur], next = imgs[1 - cur];
      var show = function () { next.classList.add('is-on'); on.classList.remove('is-on'); cur = 1 - cur; };
      next.alt = p.name; next.onload = show; next.src = p.images.hero;
      if (next.complete && next.naturalWidth) { show(); }
    }
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
    b.addEventListener('pointerenter', function () { paused = true; hero.classList.add('is-paused'); clearTimeout(timer); remaining = Math.max(400, remaining - (performance.now() - startedAt)); });
    b.addEventListener('pointerleave', function () { paused = false; hero.classList.remove('is-paused'); schedule(remaining); });
  });
  set(0, true);
  if (window.WebniHeroPointer) { window.WebniHeroPointer(hero, mist); }
  if (fine && !reduce && stage) {
    hero.addEventListener('pointermove', function (e) {
      var r = hero.getBoundingClientRect();
      var x = (e.clientX - r.left) / r.width, y = (e.clientY - r.top) / r.height;
      stage.style.transform = 'rotate(' + ((x - 0.5) * 6).toFixed(2) + 'deg) translate(' + ((x - 0.5) * 18).toFixed(1) + 'px, ' + ((y - 0.5) * 14).toFixed(1) + 'px)';
    });
    hero.addEventListener('pointerleave', function () { stage.style.transform = ''; });
  }
})();

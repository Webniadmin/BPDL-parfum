/* Single perfume page: mist in the product palette + pointer interaction. */
(function () {
  'use strict';
  var hero = document.getElementById('hero');
  if (!hero || !hero.classList.contains('hero--single')) { return; }
  var canvas = hero.querySelector('.mist');
  var palette = {
    c1: hero.dataset.c1 || '#0a0908',
    c2: hero.dataset.c2 || '#2b2312',
    c3: hero.dataset.c3 || '#d4af37',
    light: hero.classList.contains('is-light')
  };
  var mist = canvas && window.WebniMist ? window.WebniMist(canvas, palette) : null;
  if (window.WebniHeroPointer) { window.WebniHeroPointer(hero, mist); }
})();

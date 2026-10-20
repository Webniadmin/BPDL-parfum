/* Single perfume page: mist in the product palette (drifts on its own, no pointer interaction). */
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
  if (canvas && window.WebniMist) { window.WebniMist(canvas, palette, window.BPDL && window.BPDL.mist ? window.BPDL.mist : 0.5); }
})();

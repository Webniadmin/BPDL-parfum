/* WebGL mist in the colours of the active character (hero backgrounds).
   Exposes window.WebniMist(canvas, palette, intensity) → { setPalette, start, stop, gust }.
   Drifts slowly on its own (no pointer interaction), renders at reduced resolution, only while
   visible, and stops on prefers-reduced-motion. `intensity` 0–1 (Customizer "Jačina magle")
   scales the highlights; the canvas opacity is set from the same value in CSS. */
(function () {
  'use strict';
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var fine = window.matchMedia('(pointer: fine)').matches;

  function hex(h) {
    var n = parseInt(String(h || '#000').slice(1), 16);
    return [((n >> 16) & 255) / 255, ((n >> 8) & 255) / 255, (n & 255) / 255];
  }

  window.WebniMist = function (canvas, palette, intensity) {
    var I = Math.max(0, Math.min(1, intensity == null ? 0.5 : +intensity));
    var gl = canvas.getContext('webgl', { antialias: false, alpha: false, depth: false, powerPreference: 'high-performance' });
    if (!gl) { return null; }
    var vs = 'attribute vec2 a;void main(){gl_Position=vec4(a,0.,1.);}';
    var fs = 'precision highp float;uniform vec2 u_res;uniform float u_t;uniform float u_i;uniform vec3 u_c1,u_c2,u_c3;uniform float u_l;' +
      'float hash(vec2 p){return fract(sin(dot(p,vec2(127.1,311.7)))*43758.5453123);}' +
      'float noise(vec2 p){vec2 i=floor(p),f=fract(p);vec2 u=f*f*(3.-2.*f);return mix(mix(hash(i),hash(i+vec2(1.,0.)),u.x),mix(hash(i+vec2(0.,1.)),hash(i+vec2(1.,1.)),u.x),u.y);}' +
      'float fbm(vec2 p){float v=0.,a=.5;mat2 m=mat2(1.6,1.2,-1.2,1.6);for(int i=0;i<4;i++){v+=a*noise(p);p=m*p;a*=.5;}return v;}' +
      'void main(){' +
      ' float mn=min(u_res.x,u_res.y);' +
      ' vec2 uv=(gl_FragCoord.xy-.5*u_res)/mn;' +
      ' vec2 p=uv*1.45+vec2(0.,u_t*.025);' +
      ' float t=u_t*.11;' +
      ' vec2 q=vec2(fbm(p+vec2(0.,t)),fbm(p+vec2(5.2,1.3)-t*.7));' +
      ' vec2 r=vec2(fbm(p+4.*q+vec2(1.7,9.2)+t*.4),fbm(p+4.*q+vec2(8.3,2.8)-t*.3));' +
      ' float f=fbm(p+3.5*r);' +
      ' float g=clamp(f*f*3.2,0.,1.)*mix(.6,1.,u_i);' +
      ' vec3 col=mix(u_c1,u_c2,g);' +
      ' col=mix(col,u_c3,clamp(length(r)-.4,0.,1.)*.6*u_i);' +
      ' col+=u_c3*pow(clamp(f,0.,1.),5.)*.38*u_i;' +
      ' float vig=smoothstep(1.5,.35,length(uv));' +
      ' col*=mix(mix(.68,.9,u_l),1.,vig);' +
      ' col+=(hash(gl_FragCoord.xy+fract(u_t))-.5)*.05;' +
      ' gl_FragColor=vec4(col,1.);}';

    function sh(type, src) { var o = gl.createShader(type); gl.shaderSource(o, src); gl.compileShader(o); return o; }
    var pr = gl.createProgram();
    gl.attachShader(pr, sh(gl.VERTEX_SHADER, vs));
    gl.attachShader(pr, sh(gl.FRAGMENT_SHADER, fs));
    gl.linkProgram(pr);
    if (!gl.getProgramParameter(pr, gl.LINK_STATUS)) { return null; }
    gl.useProgram(pr);
    var buf = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buf);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 3, -1, -1, 3]), gl.STATIC_DRAW);
    var a = gl.getAttribLocation(pr, 'a');
    gl.enableVertexAttribArray(a);
    gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
    var U = {};
    ['u_res', 'u_t', 'u_i', 'u_c1', 'u_c2', 'u_c3', 'u_l'].forEach(function (n) { U[n] = gl.getUniformLocation(pr, n); });

    var cur = [hex(palette.c1), hex(palette.c2), hex(palette.c3)];
    var tgt = cur.map(function (c) { return c.slice(); });
    var light = palette.light ? 1 : 0, lightT = light;
    var t = 0, last = performance.now(), gust = 0, running = false;
    var scale = fine ? 0.5 : 0.38;

    function resize() {
      var w = canvas.clientWidth, h = canvas.clientHeight;
      var W = Math.max(2, Math.round(w * scale)), H = Math.max(2, Math.round(h * scale));
      if (canvas.width !== W || canvas.height !== H) { canvas.width = W; canvas.height = H; gl.viewport(0, 0, W, H); }
    }
    function frame(now) {
      if (!running) { return; }
      var dt = Math.min(0.05, (now - last) / 1000); last = now;
      resize();
      var speed = reduce ? 0 : 1 + gust * 2;
      t += dt * speed; gust *= 0.96;
      for (var i = 0; i < 3; i++) { for (var k = 0; k < 3; k++) { cur[i][k] += (tgt[i][k] - cur[i][k]) * 0.04; } }
      light += (lightT - light) * 0.04;
      gl.uniform2f(U.u_res, canvas.width, canvas.height);
      gl.uniform1f(U.u_t, t);
      gl.uniform1f(U.u_i, I);
      gl.uniform3fv(U.u_c1, cur[0]); gl.uniform3fv(U.u_c2, cur[1]); gl.uniform3fv(U.u_c3, cur[2]);
      gl.uniform1f(U.u_l, light);
      gl.drawArrays(gl.TRIANGLES, 0, 3);
      if (reduce) { running = false; return; }
      requestAnimationFrame(frame);
    }
    var api = {
      setPalette: function (p, now) {
        var n = [hex(p.c1), hex(p.c2), hex(p.c3)];
        for (var i = 0; i < 3; i++) { tgt[i] = n[i]; }
        lightT = p.light ? 1 : 0;
        if (now) { for (var j = 0; j < 3; j++) { cur[j] = n[j].slice(); } light = lightT; }
        gust = 1;
      },
      start: function () { if (running) { return; } running = true; last = performance.now(); requestAnimationFrame(frame); },
      stop: function () { running = false; },
      gust: function () { gust = Math.max(gust, 0.9); }
    };
    // Render only while the canvas is on screen and the tab is visible.
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) { if (e.isIntersecting && !document.hidden) { api.start(); } else { api.stop(); } });
    }, { threshold: 0 });
    io.observe(canvas);
    document.addEventListener('visibilitychange', function () { if (document.hidden) { api.stop(); } else { api.start(); } });
    api.start();
    requestAnimationFrame(function () { canvas.classList.add('is-ready'); });
    return api;
  };
})();

/* Admin: color pickers and media pickers for the "BP|DL karakter" meta box. */
(function ($) {
  'use strict';
  $(function () {
    $('.webni-color').wpColorPicker();
    $('.webni-image-pick').on('click', function (e) {
      e.preventDefault();
      var wrap = $(this).closest('.webni-image');
      var frame = wp.media({ title: 'Izaberi sliku', multiple: false, library: { type: 'image' } });
      frame.on('select', function () {
        var a = frame.state().get('selection').first().toJSON();
        var src = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
        wrap.find('input[type=hidden]').val(a.id);
        wrap.find('img').attr('src', src).show();
      });
      frame.open();
    });
    $('.webni-image-clear').on('click', function (e) {
      e.preventDefault();
      var wrap = $(this).closest('.webni-image');
      wrap.find('input[type=hidden]').val('');
      wrap.find('img').attr('src', '').hide();
    });
  });
})(jQuery);

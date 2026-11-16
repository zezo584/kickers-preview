/* Kickers admin helpers: media pickers, colour pickers, slide mode panels. */
(function ($) {
  'use strict';
  $(function () {
    $('.kk-color').wpColorPicker();

    $(document).on('click', '.kk-media-pick', function (e) {
      e.preventDefault();
      var box = $(this).closest('.kk-media'), multiple = box.data('multiple') === 1 || box.data('multiple') === '1';
      var input = box.find('input[type=hidden]'), prev = box.find('.kk-media-prev');
      var frame = wp.media({ title: multiple ? 'اختيار الصور' : 'اختيار صورة', multiple: multiple ? 'add' : false, library: { type: 'image' } });
      frame.on('open', function () {
        var sel = frame.state().get('selection');
        (input.val() || '').split(',').filter(Boolean).forEach(function (id) { var a = wp.media.attachment(id); a.fetch(); sel.add(a); });
      });
      frame.on('select', function () {
        var ids = [], html = '';
        frame.state().get('selection').each(function (a) {
          a = a.toJSON(); ids.push(a.id);
          var u = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
          html += '<img src="' + u + '" alt="">';
        });
        input.val(ids.join(',')).trigger('change'); prev.html(html);
      });
      frame.open();
    });
    $(document).on('click', '.kk-media-clear', function (e) {
      e.preventDefault();
      var box = $(this).closest('.kk-media');
      box.find('input[type=hidden]').val('').trigger('change'); box.find('.kk-media-prev').empty();
    });

    var mode = $('select[name=kk_mode]');
    function syncMode() {
      var m = mode.val();
      $('.kk-panel[data-show-mode]').each(function () { $(this).toggle($(this).data('show-mode') === m); });
    }
    if (mode.length) { mode.on('change', syncMode); syncMode(); }

    // Reset term form media/colour after AJAX add.
    $(document).ajaxComplete(function (ev, xhr, opts) {
      if (opts && opts.data && String(opts.data).indexOf('action=add-tag') > -1) {
        $('#addtag .kk-media-prev').empty();
        $('#addtag .kk-media input[type=hidden]').val('');
      }
    });
  });
})(jQuery);

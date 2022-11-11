$(document).ready(function () {

  $('.search__btn').bind('click', function () {
    url = $('base').attr('href') + 'index.php?route=product/search';
    var s = $('input[name=\'search\']').prop('value');
    if (s) {
      url += '&search=' + encodeURIComponent(s);
    }
    location = url;
  }),

  $(document).on('keydown','keypress', function (e) {
    if (keycode == 13) {
      url = $('base').attr('href') + 'index.php?route=product/search';
      var t = $('header input[name=\'search\']').prop('value');
      t && (url += '&search=' + encodeURIComponent(t)),
      location = url
    }
  });






}); 
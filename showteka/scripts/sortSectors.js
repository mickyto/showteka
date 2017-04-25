$(document).ready(function() {
  $(function() {
    var parent = $('#announce');
    var child = parent.children('.var');

    child.sort(function(a,b) {
        var an = $(a).find('.table-item1').text();
        console.log(an);
        var bn = $(b).find('.table-item1').text();

        if (an > bn) {
          return 1;
        }
        if (an < bn) {
          return -1;
        }
        return 0;
      })
      child.detach().appendTo(parent);
  });
});

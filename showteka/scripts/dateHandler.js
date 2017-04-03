$(document).ready(function() {



  function onlyUnique(value, index, self) {
    return $(value).text() != $(self[index+1]).text();
  }

  $(function() {
    var dateElements = $('.offer-date').get();
    var filtered = dateElements.filter(onlyUnique);
    console.log(dateElements);
    if (filtered.length != 1) {
      for (var i = 0; i < filtered.length; i++) {
        var dateStr = $(filtered[i]).text().replace(/\s/g,'');
        var months = ['января', 'февраля', 'марта', 'апреля',
        'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        var editedStr = dateStr.slice(8, 10) + ' ' + months[dateStr.slice(5, 7) - 1] + ' ' + dateStr.slice(0, 4) + ' | ' + dateStr.slice(11, 13) + ':' + dateStr.slice(13, 15);
        $( "#announce" ).find( ".offer-date:contains('" + dateStr + "')" ).first().css( "display", "block" ).text(editedStr);
      }
    }
  });


  /*$(function() {
  var parent = $('#announce table tbody'),
  child = parent.children('tr');

  child.sort(function(a,b){
  var an = a.getAttribute('data-date'),
  bn = b.getAttribute('data-date');

  if (an > bn) {
  return 1;
}
if (an < bn) {
return -1;
}
return 0;
}).map(function(i, el) {
var months = ['января', 'февраля', 'марта', 'апреля',
'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
var dateStr = child[i+1].getAttribute('data-date');
var editedStr = dateStr.slice(8, 10) + ' ' + months[dateStr.slice(5, 7) - 1] + ' ' +
dateStr.slice(0, 4) + ' | ' + dateStr.slice(11, 13) + ':' + dateStr.slice(13, 15);

if (el.getAttribute('data-date') != dateStr) {
$('<p class="date-divider">' + editedStr + '</p>').insertAfter(el);
}

});

child.detach().appendTo(parent);
});

$(function() {
var dates = document.getElementsByClassName('date-divider');
if (dates.length == 1) {
dates[0].style.display = 'none';
}
});*/
});

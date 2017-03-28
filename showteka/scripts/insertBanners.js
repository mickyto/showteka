var lis = document.getElementsByClassName('product');
var step = 8;
for (var i = 0; i < lis.length / step; i++) {
    if (lis[(i + 1) * step - 1]) {
        lis[(i + 1) * step - 1].className += ' li-banner' + (i + 1);
    }
}

$('.crellyslider-slider-баннер-бол-1').insertAfter($('ul.products:first li.li-banner1'));
$('.crellyslider-slider-баннер-бол-2').insertAfter($('ul.products:first li.li-banner2'));
$('.crellyslider-slider-баннер-бол-3').insertAfter($('ul.products:first li.li-banner3'));


/*var bannerData = banners.map(function(index, element) {
    return {
        href: $(element).find('a').attr('href'),
        title: $(element).find('img').attr('title')
    };
}).sort(function(a,b) {
    if (a.title > b.title) {
        return 1;
    }
    if (a.title < b.title) {
        return -1;
    }
    return 0;
});

for (var n = 0; n < banners.length; n++) {
    var banner = '<div class="banner"><a href="' + bannerData[n].href + '">' +
        '<img src="' + window.location.origin + '/wp-content/uploads/' + bannerData[n].title + '" class="img">' +
        '</a></div>';

    if (n != 0) {
        $(banner).insertAfter($('ul.products:first li.li-banner' + n));
    }
}*/


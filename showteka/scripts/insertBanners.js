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

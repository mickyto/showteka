/*jQuery(document).ready(function() {
    jQuery(function() {
        jQuery("#_text_field[172]").datepicker({
            "dateFormat" : "dd-mm-yy",
            "duration" : 200
        });
        (function() {
            jQuery.datepicker.regional.ru = {
                closeText: "Закрыть",
                prevText: "&#x3C;Пред",
                nextText: "След&#x3E;",
                currentText: "Сегодня",
                monthNames: [ "Январь","Февраль","Март","Апрель","Май","Июнь",
                    "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь" ],
                monthNamesShort: [ "Янв","Фев","Мар","Апр","Май","Июн",
                    "Июл","Авг","Сен","Окт","Ноя","Дек" ],
                dayNames: [ "воскресенье","понедельник","вторник","среда","четверг","пятница","суббота" ],
                dayNamesShort: [ "вск","пнд","втр","срд","чтв","птн","сбт" ],
                dayNamesMin: [ "Вс","Пн","Вт","Ср","Чт","Пт","Сб" ],
                weekHeader: "Нед",
                dateFormat: "dd.mm.yy",
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ""
            };
            jQuery.datepicker.setDefaults( jQuery.datepicker.regional.ru );
        }());
    });
});*/

!function(e) {
    "use strict";
    e(".my_datepicker").datepicker({
            defaultDate: "",
            dateFormat: "yy-mm-dd",
            numberOfMonths: 1,
            showButtonPanel: !0
    })
}(jQuery);




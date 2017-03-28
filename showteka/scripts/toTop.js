$(document).ready(function () {
    $("#toTop").css("display", "none");
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0) {
            $("#toTop").fadeIn("slow");
        }
        else {
            $("#toTop").fadeOut("slow");
        }
    });

    $("#toTop").click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, "slow");
    });

    $("<p id='close-alert'></p>").appendTo(".woocommerce-error, .woocommerce-message");

    $("#close-alert").click(function () {
        $(".woocommerce-error, .woocommerce-message").css("display", "none");
    });
});

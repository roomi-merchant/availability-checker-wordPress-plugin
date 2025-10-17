jQuery(document).ready(function ($) {
    $('a.close_popup').click(function () {
        $('.popup').fadeOut();
        setTimeout(function () {
            $('.popup').remove();
        }, 1000);
    })
})
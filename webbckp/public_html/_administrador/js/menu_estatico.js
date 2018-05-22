$(function() {

    $('.mn_est_t').mouseover(function() {
        $('.mn_est_n2_int').hide();
        $("#mn_est_" + $(this).attr("id_num")).fadeIn('slow', function() {

            $('.contenedor_menu_predefinido').mouseleave(function() {
                $('.mn_est_n2_int').fadeOut('slow');
            });

        });
    });

});

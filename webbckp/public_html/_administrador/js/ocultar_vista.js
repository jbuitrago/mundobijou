/**
 * Se utiliza como sistema de template, que muestre las acciones de los botones.
 *
 * @category   Funciones_Sitios_Institucionales
 * @package    Sitios
 * @copyright  2015 KIRKE
 * @license    GPL
 * @version    Release: 3.0
 * @link       http://kirke.ws
 * @since      Function available since Release 1.0
 * @deprecated
 */

$(function () {
    $('.ocultos_ocultar').click(function () {
        var id_cp = $(this).attr('id_ocultar_cp');
        $('#ocultar_cp_lin_' + id_cp).fadeOut('slow');
        $('#mostrar_ocultos').fadeIn('slow');
        $('#li_ocultar_' + id_cp).css("display", "block");
        cantidad_cp_ocultos++;
        $.get($("body").attr("url") + '&id_ocultar_cp=' + id_cp);
    });
    $('.ocultos_mostrar').click(function () {
        cantidad_cp_ocultos--;
        var id_cp = $(this).attr('id_mostrar_cp');
        $('#ocultar_cp_lin_' + id_cp).fadeIn('slow');
        $('#li_ocultar_' + id_cp).css("display", "none");
        if (cantidad_cp_ocultos == 0) {
            $('#mostrar_ocultos').fadeOut('slow');
        }
        $.get($("body").attr("url") + '&id_mostrar_cp=' + id_cp);
    });
    $('#mostrar_todos').click(function () {
        $('.componente_contenedor').fadeIn('slow');
        $('.ocultos_mostrar').css("display", "none");
        $('#mostrar_ocultos').fadeOut('slow');
        cantidad_cp_ocultos = 0;
        $.get($("body").attr("url") + '&mostrar_todo=si');
    });
});

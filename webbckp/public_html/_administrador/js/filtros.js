/**
 * Se utiliza como sistema de template, que muestre las acciones de los botones.
 *
 * @category   Funciones_Sitios_Institucionales
 * @package    Sitios
 * @copyright  2010 KIRKE
 * @license    GPL
 * @version    Release: 2.0
 * @link       http://kirke.ws
 * @since      Function available since Release 1.0
 * @deprecated
 */

$(function() {
    $('.filtro_no_seleccionado').click(function() {
        var id = $(this).attr('filtro_tipo_id');
        var atributo = $(this).attr('filtro_tipo');

        $('#parametro_' + id).val(atributo);
        // remuevo atributos del anteriormente seleccionado
        $('#filtro_seleccionado_' + id).removeClass(id + " filtro_seleccionado").addClass("filtro_no_seleccionado");
        $('#filtro_seleccionado_' + id).removeAttr('id');
        // agrego atributos al seleccionado
        $(this).removeClass("filtro_no_seleccionado").addClass(id + " filtro_seleccionado");
        $(this).attr('id', 'filtro_seleccionado_' + id);

        if ((atributo == 'nulo') || (atributo == 'no_nulo') || (atributo == 'activo') || (atributo == 'inactivo')) {
            $('#valor_' + id).hide();
            if ($('#valor_' + id + '_2').length) {
                $('#valor_' + id + '_2').hide();
            }
        } else {
            $('#valor_' + id).show();
            if (((atributo == 'rango') || (atributo == 'fecha_rango')) && $('#valor_' + id + '_2').length) {
                $('#valor_' + id + '_2').show();
            } else if (((atributo != 'rango') || (atributo == 'fecha_rango')) && $('#valor_' + id + '_2').length) {
                $('#valor_' + id + '_2').hide();
            }
        }
    });

    $('.bt_tb_eliminar_filtro').click(function() {
        var id = $(this).attr('filtro_eliminar_id');
        $('#parametro_' + id).val('');
        $('#valor_' + id).val('');
        $('#filtro_seleccionado_' + id).removeClass(id + " filtro_seleccionado").addClass("filtro_no_seleccionado");
        $('#valor_' + id).hide();
        if ($('#valor_' + id + '_2').length) {
            $('#valor_' + id + '_2').val('');
            $('#valor_' + id + '_2').hide();
        }
    });

});


$(function () {
    $('.bt_tb_bajar').click(function () {
        var url_array = $(this).attr("url").split(',');
        if (url_array[7]) {
            var extra = '&extra=' + url_array[7];
        } else {
            var extra = '';
        }
        $(location).attr('href', './index.php?kk_generar=' + url_array[0] + '&accion=' + url_array[2] + '&id_tabla=' + url_array[1] + '&orden_act=' + url_array[3] + '&id_orden_act=' + url_array[4] + '&orden_sig=' + url_array[5] + '&id_orden_sig=' + url_array[6] + extra);
    });
    $('.bt_tb_subir').click(function () {
        var url_array = $(this).attr("url").split(',');
        if (url_array[7]) {
            var extra = '&extra=' + url_array[7];
        } else {
            var extra = '';
        }
        $(location).attr('href', './index.php?kk_generar=' + url_array[0] + '&accion=' + url_array[2] + '&id_tabla=' + url_array[1] + '&orden_act=' + url_array[3] + '&id_orden_act=' + url_array[4] + '&orden_ant=' + url_array[5] + '&id_orden_ant=' + url_array[6] + extra);
    });
    $('.bt_tb_ver').click(function () {
        var url_array = $(this).attr("url").split(',');
        $(location).attr('href', './index.php?kk_generar=' + url_array[0] + '&accion=' + url_array[4] + '&id_tabla=' + url_array[1] + '&' + url_array[2] + '=' + url_array[3]);
    });
    $('.bt_tb_editar').click(function () {
        var url_array = $(this).attr("url").split(',');
        if (url_array[5]) {
            var extra = '&extra=' + url_array[5];
        } else {
            var extra = '';
        }
        $(location).attr('href', './index.php?kk_generar=' + url_array[0] + '&accion=' + url_array[4] + '&id_tabla=' + url_array[1] + '&' + url_array[2] + '=' + url_array[3] + extra);
    });
    $('.bt_tb_eliminar').click(function () {
        var eliminar = confirm($(this).attr("mensaje"));
        if (eliminar == true) {
            var url_array = $(this).attr("url").split(',');
            if (url_array[5]) {
                var extra = '&extra=' + url_array[5];
            } else {
                var extra = '';
            }
            $(location).attr('href', './index.php?kk_generar=' + url_array[0] + '&accion=' + url_array[4] + '&id_tabla=' + url_array[1] + '&' + url_array[2] + '=' + url_array[3] + extra);
        }
    });
    $('.bt_tb_link').click(function () {
        var url_array = $(this).attr("url").split(',');
        $(location).attr('href', './index.php?kk_generar=' + url_array[0] + '&accion=' + url_array[1] + '&valor_sistema=' + url_array[4] + '&' + url_array[2] + '=' + url_array[3]);
    });
    $("#cantidad_por_pagina").change(function () {
        $( "#form_cantidad_por_pagina" ).submit();
    });
});


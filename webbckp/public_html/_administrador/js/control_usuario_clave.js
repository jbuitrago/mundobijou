$(function () {

    $('.mensaje_error').hide();

    $('#form').submit(function () {

        if ($('#opciones_avanzadas').is(':visible')) {
            var error = true;
            if ($('#clave').val() == '') {
                var clave_mensaje_no_nulo = $('#clave').attr('mensaje_no_nulo');
                $('#VC_clave').html(clave_mensaje_no_nulo);
                $('#VC_clave').show('slow');
                error = false;
            } else {
                $('#VC_clave').hide('slow');
                $('#VC_clave').html('');
            }
            if ($('#clave2').val() == '') {
                var clave2_mensaje_no_nulo = $('#clave2').attr('mensaje_no_nulo');
                $('#VC_clave2').html(clave2_mensaje_no_nulo);
                $('#VC_clave2').show('slow');
                error = false;
            } else {
                $('#VC_clave2').hide('slow');
                $('#VC_clave2').html('');
            }
            if (($('#clave').val() != '') && ($('#clave2').val() != '') && ($('#clave').val() != $('#clave2').val())) {
                var claves_mensaje = $('#clave2').attr('mensaje_claves');
                $('#VC_clave2').html(claves_mensaje);
                $('#VC_clave2').show('slow');
                error = false;
            } else if (($('#clave').val() != '') && ($('#clave2').val() != '') && ($('#clave').val() == $('#clave2').val())) {
                $('#VC_clave2').hide('slow');
                $('#VC_clave2').html('');
            }
            return error;
        }

    });

});
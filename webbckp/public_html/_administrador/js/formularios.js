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

    $('.VC_error').hide();

    $('input').keypress(function(e) {
        if ($(this).attr('filtro') && ($(this).attr('filtro') == 'letras')) {
            filtro(e, 'abcdefghijklmnñopqrstuvwzyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ');
        } else if ($(this).attr('filtro') && ($(this).attr('filtro') == 'numeros')) {
            filtro(e, '0123456789-,');
        } else if ($(this).attr('filtro') && ($(this).attr('filtro') == 'mail')) {
            filtro(e, '0123456789abcdefghijklmnñopqrstuvwxyz@0123456789.-_');
        } else if ($(this).attr('filtro')) {
            filtro(e, $(this).attr("filtro"));
        }
    });

    $('input').keyup(function() {
        if ($(this).attr('max')) {
            valor_maximo(this, $(this).attr("max"));
        }
    });

    $('.link_submit').click(function() {
        $('#form').attr('action', $(this).attr('accion'));
    });

    $('textarea[maxlength]').keyup(function() {
        var max = parseInt($(this).attr('maxlength'));
        if ($(this).val().length > max) {
            $(this).val($(this).val().substr(0, $(this).attr('maxlength')));
            $("#VC_" + this.name).html('No puede ingresar mas caracteres');
            $("#VC_" + this.name).show('slow');
        } else {
            $("#VC_" + this.name).hide('slow');
            $("#VC_" + this.name).html('');
        }
    });

    $('#form').submit(function() {

        var enviar = true;
        var fields = $(this).serializeArray();
        jQuery.each(fields, function(i, field) {


            var id_campo = field.name;
            id_campo = id_campo.replace(']', '');
            id_campo = id_campo.replace('[', '_');

            if ($('#' + id_campo).attr('no_nulo')) {
                if (!no_nulo(id_campo, $('#' + id_campo).attr('no_nulo'))) {
                    enviar = false;
                }
            }
            if ($('#' + id_campo).attr('es_mail')) {
                if (!es_mail(id_campo, $('#' + id_campo).attr('es_mail'))) {
                    enviar = false;
                }
            }
            if ($('#' + id_campo).attr('min')) {
                if (parseInt($('#' + id_campo).val()) < $('#' + id_campo).attr('min')) {
                    $('#' + id_campo).val($('#' + id_campo).attr('min'));
                }
            }

        });
        return enviar;
    });

});

/*
 *
 * validaciones generales de formularios
 *
 */

function es_mail(campo_nombre, cadena) {
    if ($('#' + campo_nombre).val().match(/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/)) {
        $('#VC_' + campo_nombre).hide('slow');
        $('#VC_' + campo_nombre).html('');
        return true;
    } else {
        $('#VC_' + campo_nombre).html(cadena);
        $('#VC_' + campo_nombre).addClass('VC_campo_requerido');
        $('#VC_' + campo_nombre).show('slow');
        return false;
    }
}

function no_nulo(campo_nombre, cadena) {
    if ($('#' + campo_nombre).val().length > 0) {
        $('#VC_' + campo_nombre).hide('slow');
        $('#VC_' + campo_nombre).html('');
        return true;
    } else {
        $('#VC_' + campo_nombre).html(cadena);
        $('#VC_' + campo_nombre).addClass('VC_campo_requerido');
        $('#VC_' + campo_nombre).show('slow');
        return false;
    }
}

function filtro(e, cadena) {

    /*
     obtener el objeto de evento: o bien window.event para IE
     o el parámetro e para otros navegadores
     */
    var evt = window.event ? window.event : e;
    /*
     obtener el valor numérico de la tecla pulsada:
     event.keyCode para IE. o e.which para otros navegadores
     */
    var keyCode = evt.keyCode ? evt.keyCode : e.which;

    if (
            e.keyCode == 46 // delete
            || e.keyCode == 8  // backspace
            || e.keyCode == 9  // tab
            || e.keyCode == 35 // end
            || e.keyCode == 36 // home
            || e.keyCode == 37 // left arrow
            || e.keyCode == 39 // right arrow
            ) {
        // no realizar nada es para poder borrar: backspace y delete
    } else {
        if (cadena.indexOf(String.fromCharCode(keyCode)) == -1) {
            e.preventDefault();
        }
    }
}

function valor_maximo(campo_nombre, valor_maximo) {
    if (parseInt($(campo_nombre).val()) > valor_maximo) {
        $(campo_nombre).val($(campo_nombre).val().substr(0, ($(campo_nombre).val().length) - 1));
    }
}

function control_de_valores(campo_nombre) {
    // async hace que primero lea el archivo y después siga con el resto.
    var control = $.ajax({
        type: "GET",
        url: "control.php?prefijo=1&valor=" + $("#" + campo_nombre).val(),
        async: false
    }).responseText;
    if (control == '') {
        alert('OK');
    }
//Listas de keycode: http://www.htmlgoodies.com/beyond/javascript/article.php/3471141
//http://www.cambiaresearch.com/c4/702b8cd1-e5b0-42e6-83ac-25f0306e3e25/Javascript-Char-Codes-Key-Codes.aspx
}

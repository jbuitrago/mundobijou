/**
 * Se utiliza como sistema de template, que muestre las acciones de los botones.
 *
 * @category   Funciones_Sitios_Institucionales
 * @package    Sitios_Institucionales
 * @copyright  2010 KIRKE
 * @license    GPL
 * @version    Release: 2.0
 * @link       http://kirke.ws
 * @since      Function available since Release 1.0
 * @deprecated
 */

function no_nulo(id_formulario, campo_nombre, cadena) {
    if ($("#" + id_formulario + " #" + campo_nombre).val() != '') {
        $("#" + id_formulario + " #VC_" + campo_nombre).hide('slow');
        $("#" + id_formulario + " #VC_" + campo_nombre).html('');
        return true;
    } else {
        $("#" + id_formulario + " #VC_" + campo_nombre).html(cadena);
        $("#" + id_formulario + " #VC_" + campo_nombre).show('slow');
        return false;
    }
    return false;
}

function es_mail(id_formulario, campo_nombre, cadena) {
    if ($("#" + id_formulario + " #" + campo_nombre).val() != '') {
        if ($("#" + id_formulario + " #" + campo_nombre).val().match(/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/)) {
            $("#" + id_formulario + " #VC_" + campo_nombre).hide('slow');
            $("#" + id_formulario + " #VC_" + campo_nombre).html('');
            return true;
        } else {
            $("#" + id_formulario + " #VC_" + campo_nombre).html(cadena);
            $("#" + id_formulario + " #VC_" + campo_nombre).show('slow');
            return false;
        }
    }
    return true;
}

function mensaje_ok(id_formulario, campo_nombre) {
    $("#" + id_formulario + " #VC_" + campo_nombre).hide('slow');
    $("#" + id_formulario + " #VC_" + campo_nombre).html('');
}

function mensaje_error(id_formulario, campo_nombre, cadena) {
    $("#" + id_formulario + " #VC_" + campo_nombre).html(cadena);
    $("#" + id_formulario + " #VC_" + campo_nombre).show('slow');
}

function control_captcha(id_formulario, campo_nombre, cadena, cadena2, cadena3) {
    var respuesta = null;
    var scriptUrl = '/index.php?kk_captcha=captcha&formulario=' + id_formulario + '&codigo=' + $("#" + id_formulario + " #" + campo_nombre).val();
    
    $.ajax({
        url: scriptUrl,
        type: 'get',
        dataType: 'html',
        async: false,
        success: function(datos) {
            respuesta = datos;
        }
    });
    if (respuesta == 'ok') {
        $("#" + id_formulario + " #VC_" + campo_nombre).hide('slow');
        $("#" + id_formulario + " #VC_" + campo_nombre).html('');
        return true;
    } else if (respuesta == '10') {
        $("#" + id_formulario + " #VC_" + campo_nombre).html(cadena2);
        $("#" + id_formulario + " #VC_" + campo_nombre).show('slow');
        return false;
    } else if ($("#" + id_formulario + " #" + campo_nombre).val() != '') {
        $("#" + id_formulario + " #VC_" + campo_nombre).html(cadena);
        $("#" + id_formulario + " #VC_" + campo_nombre).show('slow');
        return false;
    } else {
        $("#" + id_formulario + " #VC_" + campo_nombre).html(cadena3);
        $("#" + id_formulario + " #VC_" + campo_nombre).show('slow');
        return false;
    }
}

function solo_texto_permitido(campo_nombre, cadena) {

    $("#" + campo_nombre).keypress(function(e) {

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
        
        //var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
        //var is_explorer = navigator.userAgent.indexOf('MSIE') > -1;
        var is_firefox = navigator.userAgent.indexOf('Firefox') > -1;
        //var is_safari = navigator.userAgent.indexOf("Safari") > -1;
        //var is_opera = navigator.userAgent.toLowerCase().indexOf("op") > -1;

        if (
                (is_firefox)
                && (
                e.keyCode == 8  // backspace
                || e.keyCode == 9  // tab
                || e.keyCode == 13 // enter
                || e.keyCode == 16 // shift
                || e.keyCode == 17 // ctrl
                || e.keyCode == 18 // alt
                || e.keyCode == 20 // caps lock 
                || e.keyCode == 27 // escape 
                || e.keyCode == 33 // page up  
                || e.keyCode == 34 // page down  
                || e.keyCode == 35 // end
                || e.keyCode == 36 // home
                || e.keyCode == 37 // left arrow
                || e.keyCode == 38 // up arrow 
                || e.keyCode == 39 // right arrow
                || e.keyCode == 40 // down arrow 
                || e.keyCode == 45 // insert
                || e.keyCode == 46 // delete
                )
                ) {
            // no realizar nada es para poder borrar: backspace y delete
            return true;
        } else {
            if (cadena.indexOf(String.fromCharCode(keyCode)) == -1) {
                e.preventDefault();
            }
        }
    });
}

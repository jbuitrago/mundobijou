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

function formulario(idioma) {

    $(".VC_error").hide();

    if ($('textarea[maxlength]')) {
        $('textarea[maxlength]').keyup(function() {
            var max = parseInt($(this).attr('maxlength'));
            if ($(this).val().length > max) {
                $(this).val($(this).val().substr(0, $(this).attr('maxlength')));
                $("#VC_" + this.name).html(maxlength_idioma);
                $("#VC_" + this.name).show('slow');
            } else {
                $("#VC_" + this.name).hide('slow');
                $("#VC_" + this.name).html('');
            }
        });
    }

    $("form").submit(function() {

        var enviar = true;
        var valor = true;
        var errores = '';
        var texto = '';
        var etiqueta = '';
        var id_formulario = $(this).attr('id');

        var fields = $(this).serializeArray();

        jQuery.each(fields, function(i, field) {
            texto = '';
            etiqueta = '';

            if ($('#' + field.name).attr("id") == 'captcha') {
                if (!control_captcha(id_formulario, field.name, captcha_idioma, captcha_10_idioma, obligatorio_idioma)) {
                    etiqueta = $('#' + field.name).attr("etiqueta");
                    errores = errores + captcha_idioma + '<br />';
                    valor = false;
                }
            }
            if ($('#' + field.name).attr("tipo") == 'obligatorio') {
                if (!no_nulo(id_formulario, field.name, obligatorio_idioma)) {
                    etiqueta = $('#' + field.name).attr("etiqueta");
                    errores = errores + obligatorio_gral_idioma + etiqueta + '<br />';
                    valor = false;
                }
            }
            if ($('#' + field.name).attr("valor") == 'mail') {
                if (!es_mail(id_formulario, field.name, mail_idioma)) {
                    etiqueta = $('#' + field.name).attr("etiqueta");
                    errores = errores + mail_gral_idioma + etiqueta + '<br />';
                    valor = false;
                }
            }

            if (field.name == ('kk-file-' + $('#' + field.name).attr("valor"))) {
                var nombre_campo_file = $('#' + field.name).attr("valor");
                if (!no_nulo(id_formulario, nombre_campo_file, obligatorio_idioma)) {
                    etiqueta = $('#' + nombre_campo_file).attr("etiqueta");
                    errores = errores + obligatorio_gral_idioma + etiqueta + '<br />';
                    valor = false;
                }
            }

            if ((valor == false) || (enviar == false)) {
                enviar = false;
            }

        });

        $('.kk_html_checkboxes').each(function() {
            var i_c_control = false;
            for (var i_c = 1; i_c <= $(this).attr('cant'); i_c++) {
                if ($('#' + $(this).attr('name') + '_' + i_c).is(':checked')) {
                    i_c_control = true;
                }
            }
            if (i_c_control == false) {
                mensaje_error(id_formulario, $(this).attr('name'), obligatorio_seleccionado_idioma);
                enviar = false;
            } else {
                mensaje_ok(id_formulario, $(this).attr('name'));
            }
        });

        $('.kk_html_radios').each(function() {
            var i_r_control = false;
            for (var i_r = 1; i_r <= $(this).attr('cant'); i_r++) {
                if ($('#' + $(this).attr('name') + '_' + i_r).is(':checked')) {
                    i_r_control = true;
                }
            }
            if (i_r_control == false) {
                mensaje_error(id_formulario, $(this).attr('name'), obligatorio_seleccionado_idioma);
                enviar = false;
            } else {
                mensaje_ok(id_formulario, $(this).attr('name'));
            }
        });

        $('#' + id_formulario + ' #VF_todos').html(errores);
        $('#' + id_formulario + ' #VF_todos').show('slow');

        return enviar;

    });

    switch (idioma) {
        case "cs":
            var maxlength_idioma = 'No puede ingresar mas caracteres';
            var obligatorio_idioma = 'Ingrese un dato';
            var obligatorio_seleccionado_idioma = 'Seleccione un dato';
            var obligatorio_gral_idioma = 'Debe ingresar un dato en ';
            var mail_idioma = 'Debe ingresar un mail válido';
            var mail_gral_idioma = 'Debe ingresar un mail válido en ';
            var captcha_idioma = 'El codigo ingresado de la imagen es incorrecto';
            var captcha_10_idioma = 'Ha hecho mas de 10 intentos, debe refrescar la imagen.';
            break;
        case "in":
            var maxlength_idioma = 'You can´t enter more characters';
            var obligatorio_idioma = 'Enter a data';
            var obligatorio_seleccionado_idioma = 'Seleccione un dato';
            var obligatorio_gral_idioma = 'You must enter a value in ';
            var mail_idioma = 'You must enter a valid email address ';
            var mail_gral_idioma = 'You must enter a valid email address in ';
            var captcha_idioma = 'The code entered in the image is incorrect';
            var captcha_10_idioma = 'He has made over 10 attempts, refresh the image.';
            break;
        case "pt":
            var maxlength_idioma = 'Você não poder adicionar mais caracteres';
            var obligatorio_idioma = 'Digite os dados';
            var obligatorio_seleccionado_idioma = 'Seleccione un dato';
            var obligatorio_gral_idioma = 'Você deve digitar um valor em ';
            var mail_idioma = 'Você deve digitar um e-mail correto';
            var mail_gral_idioma = 'Você deve digitar um endereço de e-mail válido ';
            var captcha_idioma = 'O código inserido na imagem está errada';
            var captcha_10_idioma = 'Você Ele fez mais de 10 tentativas, você deve atualizar a imagem.';
            break;
        default:
            var maxlength_idioma = 'No puede ingresar mas caracteres';
            var obligatorio_idioma = 'Ingrese un dato';
            var obligatorio_seleccionado_idioma = 'Seleccione un dato';
            var obligatorio_gral_idioma = 'Debe ingresar un dato en ';
            var mail_idioma = 'Debe ingresar un mail válido';
            var mail_gral_idioma = 'Debe ingresar un mail válido en ';
            var captcha_idioma = 'El codigo ingresado de la imagen es incorrecto';
            var captcha_10_idioma = 'Ha hecho mas de 10 intentos, debe refrescar la imagen.';
    }

}

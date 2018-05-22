

(function($) {

    $.fn.CampoTexto = function(options) {

        var settings = $.extend({
            'limite_maximo': 100000,
            'sufijo': '_texto',
            'letras': 'letras',
            'palabras': 'palabras',
            'alerta_clase': 'texto_claro_alerta',
            'letras_falta_alerta': 5
        }, options);

        this.each(function() {

            var id_div = $(this).attr('id') + settings.sufijo;
            var largo_maximo = settings.limite_maximo;

            $(this).focusout(function() {

                var largo_actual = campoControlobtenerLargo($(this));

                if (largo_actual > largo_maximo) {
                    $(this).val($(this).val().substr(0, largo_maximo));
                }

                mostraCantidades($(this), id_div, settings.letras, settings.palabras, largo_maximo, settings.letras_falta_alerta, settings.alerta_clase);

            });

            $(this).keydown(function(e) {

                var largo_actual = campoControlobtenerLargo($(this));

                if (largo_actual == largo_maximo) {

                    var evt = window.event ? window.event : e;
                    var keyCode = evt.keyCode ? evt.keyCode : e.which;

                    if (
                            e.keyCode != 46 // delete
                            && e.keyCode != 8  // backspace
                            && e.keyCode != 9  // tab
                            && e.keyCode != 35 // end
                            && e.keyCode != 36 // home
                            && e.keyCode != 37 // left arrow
                            && e.keyCode != 39 // right arrow
                            && e.keyCode != 40 // Down
                            && e.keyCode != 38 // Up
                            ) {
                        return false;
                    }
                }

            });

            $(this).keyup(function() {

                mostraCantidades($(this), id_div, settings.letras, settings.palabras, largo_maximo, settings.letras_falta_alerta, settings.alerta_clase);

            });

        });

        function campoControlobtenerLargo(campo) {
            return campo.val().length;
        }

        function mostraCantidades(campo, id_div, letras, palabras, largo_maximo, letras_falta_alerta, alerta_clase) {

            var largo_actual = campoControlobtenerLargo(campo);

            if (largo_actual > largo_maximo) {
                campo.val(campo.val().substr(0, largo_maximo));
            } else if (largo_actual > (largo_maximo - letras_falta_alerta)) {
                $('#' + id_div).addClass(alerta_clase);
            } else {
                $('#' + id_div).removeClass(alerta_clase);
            }

            var cantidad_palabras = 0;
            if (largo_actual != 0) {
                cantidad_palabras = campo.val().split(/\b[\s,\.\-:;]*/).length;
            }
            var mensaje = '[';
            mensaje += letras + ': ' + largo_actual;
            mensaje += ' | ' + palabras + ': ' + cantidad_palabras;
            mensaje += ']';
            $('#' + id_div).html(mensaje);
        }

    };

})(jQuery);

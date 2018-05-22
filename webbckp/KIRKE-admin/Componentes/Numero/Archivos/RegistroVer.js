

$(function() {

    $('input').keyup(function() {
        if ($(this).attr('control') == 'CampoNumero') {

            var valor;
            var numero_formato;
            var enteros_formato;
            var decimales_formato;
            var signo;
            var enteros;
            var decimales;
            var i;
            var j;
            var coma = '';

            valor = $(this).val();

            // se obtine a cantidad de enteros y decimales permitidos
            numero_formato = $(this).attr('control_valor').split(',');
            enteros_formato = parseInt(numero_formato[0]);
            decimales_formato = parseInt(numero_formato[1]);

            if (!enteros_formato) {
                enteros_formato = 1;
            }
            if (!decimales_formato) {
                decimales_formato = 0;
            }

            // toma el signo del valor
            signo = valor.substr(0, 1);
            if (signo != '-') {
                signo = '';
            } else {
                valor = valor.substr(1, valor.length);
            }

            // quita los signos que no son numeros para poder hacer los procesos
            valor = valor.replace('-', '');
            valor = valor.replace(',', '');
            valor = valor.replace(/\s/g, '');

            // separa enteros de decimales
            if (decimales_formato == 0) {
                enteros = valor;
                decimales = '';
            } else if (valor.length > decimales_formato) {
                enteros = valor.substr(0, (valor.length - decimales_formato));
                decimales = valor.substr((valor.length - decimales_formato), decimales_formato);
            } else if (valor.length == decimales_formato) {
                enteros = 0;
                decimales = valor;
            } else {
                enteros = 0;
                decimales = valor;
                var decimales_ini = '';
                for (i = 0; i < (decimales_formato - valor.length); i++) {
                    decimales_ini += '0';
                }
                decimales = decimales_ini + valor;
            }

            // limita el largo al permitido, ya que por el signo negativo hay un campo de mas
            if ((enteros_formato - enteros.length) < 0) {
                enteros = enteros.substr((enteros.length - enteros_formato), enteros_formato);
            }

            // quita los ceros del entero de la izquierda
            while ((enteros != '') && (enteros != '0') && (enteros.substr(0, 1) == 0)) {
                enteros = enteros.substr(1, enteros.length);
            }

            // armado de enteros
            for (j = 0; j < Math.floor((enteros.length - (1 + j)) / 3); j++) {
                enteros = enteros.substring(0, enteros.length - (4 * j + 3)) + ' ' + enteros.substring(enteros.length - (4 * j + 3));
            }

            if (decimales_formato != 0) {
                coma = ',';
            }

            // nuevo valor
            $(this).val(signo + enteros + coma + decimales);

        }
    });

});

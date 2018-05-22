

$(function() {

    $('#directorio').click(function() {
        $('#directorio').val(' ');
    });
    
    $('#opciones_avanzadas').hide();

    $('#ver_opciones_avanzadas').click(function() {
        $('#opciones_avanzadas_boton').hide();
        $('#opciones_avanzadas').fadeIn('1200', function() {
        });
    });

    $('#ocultar_opciones_avanzadas').click(function() {
        $('#opciones_avanzadas_boton').show();
        $('#opciones_avanzadas').fadeOut('1200', function() {
        });
    });

});
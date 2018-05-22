

$(function() {

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

    $(".DesplegableTablasVarias_cont").hide();

    $("#origen_cp_id").change(function() {
        eliminar_valores(0);
        DesplegableTablasVarias(0, $(this).val());
    });

    $(".DesplegableTablasVarias").change(function() {
        id = parseInt(this.id.substr(-1, 1)) + 1;	// siguiente id
        eliminar_valores(id);
        if ($(this).val() != '') {
            DesplegableTablasVarias((id), $(this).val());
        }
    });

});

function eliminar_valores(desplegable_n) {
    for (x = (desplegable_n); x < 10; x++) {
        $("#desplegable_" + x).html('');
        $("#desplegable_cont_" + x).hide();
    }
}

function DesplegableTablasVarias(desplegable, id_componente) {

    $.ajax({
        type: "GET",
        url: "index.php?kk_generar=3&componente=DesplegableTablasVarias&archivo=ComponenteVer.php&cp=" + id_componente,
        datatype: "xml",
        success: function(xml) {
            var contenido = '';
            var id = '';
            var texto = '';
            var primero = true;
            $(xml).find("dato").each(function() {
                if (primero) {
                    $("#desplegable_cont_" + desplegable).show();
                    contenido += '<option value="" selected="selected">SELECCIONAR</option>';
                    primero = false;
                }
                id = $(this).find("id").text();
                texto = $(this).find("texto").text();
                contenido += '<option value="' + id + '">' + texto + '</option>';
            });

            $("#desplegable_" + desplegable).html(contenido);
        }
    });
}

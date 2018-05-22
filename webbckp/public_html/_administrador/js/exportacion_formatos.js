
$(function() {
    $('.exportacion_formatos').click(function() {
        var tipo_archivo = $(this).attr("tipo_archivo");
        var id_tabla = $('#cuadro_exportacion_formatos').attr("id_tabla");

	if(!$('#cuadro_exportacion_formatos').attr("id_registro")){
            $(location).attr('href', './index.php?kk_generar=0&accion=64&id_tabla=' + id_tabla + '&valor_sistema=0&tipo=' + tipo_archivo);
	}else{
            var id_registro = $('#cuadro_exportacion_formatos').attr("id_registro");
            $(location).attr('href', './index.php?kk_generar=0&accion=64&id_tabla=' + id_tabla + '&valor_sistema=0&tipo=' + tipo_archivo+'&id_registro=' + id_registro);
	}
    });
});


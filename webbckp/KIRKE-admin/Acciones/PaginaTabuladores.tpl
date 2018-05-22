<script type="text/javascript">

$(function() {
    $("#id_tb_rel").change(function() {
        if($(this).val()!=''){
            $("#VC_id_tb_rel").hide();
        }
    });
    $("#familias").hide();
    $("#id_tb_rel").change(function() {
        DesplegableMultiple($(this).val());
    });

});

function DesplegableMultiple(id_tabla) {
    
    $.ajax({
        type: "GET",
        url: "index.php?kk_generar=0&accion=68&desplegable=si&id_tabla=" + id_tabla,
        datatype: "xml",
        success: function(xml) {
            var contiene_datos = false;
            var id = '';
            var texto = '';
            var contenido = '<select name="id_cp_rel" id="id_cp_rel">';
            contenido += '<option value="" selected="selected">{TR|o_seleccionar}</option>';
            $(xml).find("dato").each(function() {
                    contenido += '<option value="' + $(this).find("id").text() + '">' + $(this).find("texto").text() + '</option>';
                    contiene_datos = true;
            });
            contenido +=  '</select>';
            if(contiene_datos == true){
                $("#desplegable").html(contenido);
                $("#familias").show();
            }else{
                $("#desplegable").html('');
                $("#familias").hide();
            }
        }
    });
}

</script>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_pagina_que_contiene_los_tabuladores}</div>
<div class="contenido_7">{PL|campos_relacionados}</div>
<div class="contenido_separador"></div>
<div id="familias">
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_componente_que_define_las_familias}</div>
    <div class="contenido_1"><div id="desplegable"></div></div>
    <div class="contenido_6"></div>
</div>

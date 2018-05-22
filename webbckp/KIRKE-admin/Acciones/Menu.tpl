<script type="text/javascript">
$(function(){
    $("#tree").dynatree({
        onActivate: function(node) {
            var titulo_completo = node.data.title;
            var titulo = titulo_completo.split(' <span class');
            ver_nodo(node.data.key, titulo[0], node.data.cantidad, node.data.nivel);
        },
        onFocus: function(node) {
            var titulo_completo = node.data.title;
            var titulo = titulo_completo.split(' <span class');
            ver_nodo(node.data.key, titulo[0], node.data.cantidad, node.data.nivel);
        },
    });
    $("#btnCollapseAll").click(function(){
        $("#tree").dynatree("getRoot").visit(function(node){
            node.expand(false);
        });
        return false;
    });
    $("#btnExpandAll").click(function(){
        $("#tree").dynatree("getRoot").visit(function(node){
            node.expand(true);
        });
        return false;
    });
    $("#tree").dynatree("getRoot").visit(function(node){
        node.expand(true);
    });
});
$(document).ready(function () {
    $("#opciones_menu").hide();
    $("#agregar_nivel_inicio").click(function(){
        $("#id_menu").val('0');
        $("#opciones_menu").hide();
        $("#directorio_seleccionado").html('{TR|o_el_nuevo_item_se_agregara_al_inicio_del_menu}');
        $("#nombre_menu").show();
    });
    $("#mismo_nivel").click(function(){
        $("#nombre_menu").show();
    });
    $("#agregar_subnivel").click(function(){
        $("#nombre_menu").show();
    });
    $("#modificar").click(function(){
        $("#nombre_menu").show();
    });
    $("#eliminar").click(function(){
        $("#nombre_menu").hide();
    });
});
function ver_nodo(id, titulo, cantidad, nivel){
    $("#opciones_menu").show();
    var link_id_menu = './index.php?kk_generar=0&id_menu='+id+'&accion=21';
    $("#directorio_seleccionado").html(titulo + ' <a href="'+link_id_menu+'" class="tree_cantidad">['+cantidad+']</a>');
    $("#links_relacionados").html('<a href="'+link_id_menu+'" class="tree_cantidad">{TR|o_ediar_links_relacionados}</a>');
    $("#id_menu").val(id);
    $("#mismo_nivel").attr('checked', true);
    $("#nombre_menu").show();
    $("#agregar_subnivel_div").show();
    if(nivel == '4'){
        $("#agregar_subnivel_div").hide();
    }
}
</script>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo">&nbsp;</div>
<div class="contenido_2_columnas_1_3">&nbsp;</div>
<div class="contenido_2_columnas_2_4">
    <div class="tree_content">
        <div id="tree">
        {PL|menu_arbol}
        </div>
        <div>
            <a href="#" id="btnExpandAll">{TR|o_expandir_todo}</a> -
            <a href="#" id="btnCollapseAll">{TR|o_collapsar_todo}</a> 
        </div>
    </div>
</div>
<div class="contenido_2_columnas_1_3"></div>
<div class="contenido_2_columnas_2_4">
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_directorio_seleccionado}</div>
<div class="contenido_7"><div id="directorio_seleccionado">{TR|o_el_nuevo_item_se_agregara_al_inicio_del_menu}</div></div>
<input type="hidden" value="0" id="id_menu" name="id_menu">
<div class="contenido_separador"></div>
<div id="opciones_menu">
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_accion_a_realizar}</div>
<div class="contenido_7">
    <input name="agregar_nivel" type="radio" value="agregar_nivel_inicio" id="agregar_nivel_inicio"> {TR|o_agregar_menu_al_inicio}<br />
    <input name="agregar_nivel" type="radio" value="mismo_nivel" id="mismo_nivel" checked> {TR|o_agregar_menu_en_el_mismo_nivel}<br />
    <div id="agregar_subnivel_div"><input name="agregar_nivel" type="radio" value="agregar_subnivel" id="agregar_subnivel"> {TR|o_agregar_menu_en_un_subnivel}<br /></div>
    <input name="agregar_nivel" type="radio" value="eliminar" id="eliminar"> {TR|o_eliminar_el_menu_seleccionado}<br />
    <input name="agregar_nivel" type="radio" value="modificar" id="modificar"> {TR|o_modificar_el_nombre_del_menu_seleccionado}<br />
    <br />
    <div id="links_relacionados"></div>
</div>
</div>
<div id="nombre_menu">    
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_etiqueta_menu}</div>
<div class="contenido_7">{PL|menu_etiqueta} </div>
</div>
</div>

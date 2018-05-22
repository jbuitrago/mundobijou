<script type="text/javascript">
    $(function () {
        $("#tree").dynatree({
            onActivate: function (node) {
                var titulo_completo = node.data.title;
                var titulo = titulo_completo.split(' <span class');
                ver_nodo(node.data.key, titulo[0], node.data.cantidad, node.data.nivel);
            },
            onFocus: function (node) {
                var titulo_completo = node.data.title;
                var titulo = titulo_completo.split(' <span class');
                ver_nodo(node.data.key, titulo[0], node.data.cantidad, node.data.nivel);
            },
        });
        $("#btnCollapseAll").click(function () {
            $("#tree").dynatree("getRoot").visit(function (node) {
                node.expand(false);
            });
            return false;
        });
        $("#btnExpandAll").click(function () {
            $("#tree").dynatree("getRoot").visit(function (node) {
                node.expand(true);
            });
            return false;
        });
        $("#tree").dynatree("getRoot").visit(function (node) {
            node.expand(true);
        });
    });
    $(document).ready(function () {
        $("#opciones_menu").hide();
        $("#agregar_nivel_inicio").click(function () {
            $("#id_menu").val('0');
            $("#opciones_menu").hide();
            $("#directorio_seleccionado").html('{TR|o_el_nuevo_item_se_agregara_al_inicio_del_menu}');
            $("#nombre_menu").show();
        });
        $("#mismo_nivel").click(function () {
            $("#nombre_menu").show();
        });
        $("#agregar_subnivel").click(function () {
            $("#nombre_menu").show();
        });
        $("#modificar").click(function () {
            $("#nombre_menu").show();
        });
        $("#eliminar").click(function () {
            $("#nombre_menu").hide();
        });
        if ({PL|niveles_protegidos} > 0) {
            $("#opciones_nodo").hide();
        }
    });
    function ver_nodo(id, titulo, cantidad, nivel) {
        var id_tabla = $('#datos_links').attr('id_tabla');
        var destino_id_cp = $('#datos_links').attr('destino_id_cp');

        $("#opciones_nodo").show();
        $("#opciones_menu").show();
        $("#directorio_seleccionado .tree_cantidad").show();
        $("#links_relacionados").show();
        $("#agregar_nivel_inicio_div").show();
        $("#mismo_nivel_div").show();
        $("#eliminarl_div").show();
        $("#modificar_div").show();
        $("#mismo_nivel").attr('checked', true);

        var link_id_menu = './index.php?kk_generar=0&accion=41&id_tabla=' + id_tabla + '&id_link=' + destino_id_cp + '_' + id + '-';
        if (cantidad != 'n') {
            $("#links_relacionados").html('<a href="' + link_id_menu + '" class="tree_cantidad">{TR|o_ver_registros_relacionados}</a>');
            $("#directorio_seleccionado").html(titulo + ' <a href="' + link_id_menu + '" class="tree_cantidad">[' + cantidad + ']</a>');
        } else {
            $("#links_relacionados").html('');
            $("#directorio_seleccionado").html(titulo);
        }
        $("#id_menu").val(id);
        $("#mismo_nivel").attr('checked', true);
        $("#nombre_menu").show();
        $("#agregar_subnivel_div").show();
        if (nivel == {PL|numero_niveles}) {
            $("#agregar_subnivel_div").hide();
        }else if(nivel < {PL|niveles_protegidos}){
            $("#agregar_subnivel_div").hide();
        }
        if (nivel <= {PL|niveles_protegidos}) {
            $("#directorio_seleccionado .tree_cantidad").hide();
            $("#agregar_nivel_inicio_div").hide();
            $("#mismo_nivel_div").hide();
            $("#eliminarl_div").hide();
            $("#modificar_div").hide();
            $("#agregar_subnivel_div").attr('checked', true);
        }
        if (nivel < {PL|niveles_habilitados}) {
            $("#links_relacionados").hide();
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
<div id="datos_links" id_tabla="{PL|id_tabla}" destino_id_cp="{PL|destino_id_cp}"></div>
<div class="contenido_2_columnas_1_3"></div>
<div class="contenido_2_columnas_2_4" id="opciones_nodo">
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_directorio_seleccionado}</div>
    <div class="contenido_7"><div id="directorio_seleccionado">{TR|o_el_nuevo_item_se_agregara_al_inicio_del_menu}</div></div>
    <input type="hidden" value="0" id="id_menu" name="id_menu">
    <div class="contenido_separador"></div>
    <div id="opciones_menu">
        <div class="contenido_margen"></div>
        <div class="contenido_titulo">{TR|o_accion_a_realizar}</div>
        <div class="contenido_7">
            <div id="links_relacionados"></div>
            <br />
            <div id="agregar_nivel_inicio_div"><input name="agregar_nivel" type="radio" value="agregar_nivel_inicio" id="agregar_nivel_inicio"> {TR|o_agregar_menu_al_inicio}<br /></div>
            <div id="mismo_nivel_div"><input name="agregar_nivel" type="radio" value="mismo_nivel" id="mismo_nivel" checked> {TR|o_agregar_menu_en_el_mismo_nivel}<br /></div>
            <div id="agregar_subnivel_div"><input name="agregar_nivel" type="radio" value="agregar_subnivel" id="agregar_subnivel"> {TR|o_agregar_menu_en_un_subnivel}<br /></div>
            <div id="eliminarl_div"><input name="agregar_nivel" type="radio" value="eliminar" id="eliminar"> {TR|o_eliminar_el_menu_seleccionado}<br /></div>
            <div id="modificar_div"><input name="agregar_nivel" type="radio" value="modificar" id="modificar"> {TR|o_modificar_el_nombre_del_menu_seleccionado}<br /></div>
        </div>
    </div>
    <div id="nombre_menu">    
        <div class="contenido_separador"></div>
        <div class="contenido_margen"></div>
        <div class="contenido_titulo">{TR|o_etiqueta_menu}</div>
        <div class="contenido_7">{PL|menu_etiqueta} </div>
    </div>
</div>

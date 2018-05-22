        <div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo"><span class="titulo_texto">{TR|o_nombre_componente}</span></div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo">{TR|o_descripcion_componente}</div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo"><span class="texto_advertencia">{TR|o_advertencia_componente}</span></div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_etiqueta}</div>
<div class="contenido_7">{PL|etiqueta}</div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_permitir_seleccionar_un_grupo_diferente_al_filtrado_al_dar_de_alta}</div>
<div class="contenido_1"><input name="seleccionar_alta" type="radio" value="s" {PL|sa_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="seleccionar_alta" type="radio" value="n" {PL|sa_n} /> {TR|o_no}</div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_link_a_grupo} ({TR|m_origen_a_destino}):</div>
<div class="contenido_1"><input name="link_a_grupo" type="radio" value="s" {PL|lk_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="link_a_grupo" type="radio" value="n" {PL|lk_n} /> {TR|o_no}</div>
    {PL|campo_relacionado_ver}
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_mostrar_en_listado}</div>
<div class="contenido_1"><input name="listado_mostrar" type="radio" value="s" {PL|lm_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="listado_mostrar" type="radio" value="n" {PL|lm_n} /> {TR|o_no}</div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_filtrar}</div>
<div class="contenido_1"><input name="filtrar" type="radio" value="s" {PL|f_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="filtrar" type="radio" value="n" {PL|f_n} /> {TR|o_no}</div>
<div id="opciones_avanzadas_boton">
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo"><div id="ver_opciones_avanzadas" class="link_jquery">{TR|o_ver_opciones_avanzadas}</div></div>
</div>
<div id="opciones_avanzadas">
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo"><div id="ocultar_opciones_avanzadas" class="link_jquery">{TR|o_ocultar_opciones_avanzadas}</div></div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_sufijo} </div>
    <div class="contenido_7"><input type="text" name="sufijo" id="sufijo" size="30" value="{PL|sufijo}" /> </div>
    <div class="contenido_separador"></div>
    <div class="fondo_titulos_componentes">
        <div class="contenido_margen"></div>
        <div class="contenido_solo_titulo">{TR|o_concatenar_campos}</div>
        <div class="contenido_separador"></div>
    </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_separador_del_campo_1}</div>
    <div class="contenido_7"><input type="text" name="separador_del_campo_1" id="campo_filtro" size="5" value="{PL|separador_del_campo_1}" /> </div>
    <div class="contenido_separador"></div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_nombre_del_campo_1}</div>
    <div class="contenido_7"><input type="text" name="nombre_del_campo_1" id="campo_filtro" value="{PL|nombre_del_campo_1}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" /> </div>
    <div class="contenido_separador"></div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_separador_del_campo_2}</div>
    <div class="contenido_7"><input type="text" name="separador_del_campo_2" id="campo_filtro" size="5" value="{PL|separador_del_campo_2}" /> </div>
    <div class="contenido_separador"></div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_nombre_del_campo_2}</div>
    <div class="contenido_7"><input type="text" name="nombre_del_campo_2" id="campo_filtro" value="{PL|nombre_del_campo_2}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" /> </div>
    <div class="contenido_separador"></div>
    <div class="fondo_titulos_componentes">
        <div class="contenido_margen"></div>
        <div class="contenido_solo_titulo">{TR|o_otras_opciones}</div>
        <div class="contenido_separador"></div>
    </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_ocultar_edicion} </div>
    <div class="contenido_1"><input name="ocultar_edicion" type="radio" value="s" {PL|oe_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="ocultar_edicion" type="radio" value="n" {PL|oe_n}>{TR|o_no} </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_ocultar_vista} </div>
    <div class="contenido_1"><input name="ocultar_vista" type="radio" value="s" {PL|ov_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="ocultar_vista" type="radio" value="n" {PL|ov_n}>{TR|o_no} </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_predefinir_ultimo_valor_cargado} </div>
    <div class="contenido_1"><input name="predefinir_ultimo_val_cargado" type="radio" value="s" {PL|puvc_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="predefinir_ultimo_val_cargado" type="radio" value="n" {PL|puvc_n}>{TR|o_no} </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_permitir_agregar_registro} </div>
    <div class="contenido_1"><input name="agregar_registro" type="radio" value="s" {PL|ar_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="agregar_registro" type="radio" value="n" {PL|ar_n}>{TR|o_no} </div>
</div>
<div class="contenido_separador_color"></div>

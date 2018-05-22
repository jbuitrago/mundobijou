        <div class="contenido_margen"></div>
<div class="contenido_solo_titulo"><span class="titulo_texto">{TR|o_nombre_componente}</span></div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo">{TR|o_descripcion_componente}</div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo"><span class="texto_advertencia">{TR|o_advertencia_componente}</span></div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_nombre_del_campo}</div>
<div class="contenido_7"><input type="text" name="nombre_campo" id="nombre_campo" size="30" value="{PL|tb_campo}" maxlength="30" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" /> <div class="VC_error" id="VC_nombre_campo"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_etiqueta}</div>
<div class="contenido_7">{PL|etiqueta}</div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_obligatorio} </div>
<div class="contenido_1"><input name="obligatorio" type="radio" value="no_nulo" {PL|co_s}>{TR|o_si} </div>
<div class="contenido_6"><input name="obligatorio" type="radio" value="nulo" {PL|co_n}>{TR|o_no} </div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_mostrar_en_listado}</div>
<div class="contenido_1"><input name="listado_mostrar" type="radio" value="s" {PL|lm_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="listado_mostrar" type="radio" value="n" {PL|lm_n} /> {TR|o_no}</div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_filtrar}</div>
<div class="contenido_1"><input name="filtrar" type="radio" value="s" {PL|f_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="filtrar" type="radio" value="n" {PL|f_n} /> {TR|o_no}</div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_color_inicial}</div>
<div class="contenido_7"><input type="text" name="color_inicial" id="color_inicial" size="7" value="{PL|color_inicial}" maxlength="7" filtro="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#" /></div>
<div class="contenido_separador"></div>
<div id="opciones_avanzadas_boton">
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo"><div id="ver_opciones_avanzadas" class="link_jquery">{TR|o_ver_opciones_avanzadas}</div></div>
</div>
<div id="opciones_avanzadas">
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo"><div id="ocultar_opciones_avanzadas" class="link_jquery">{TR|o_ocultar_opciones_avanzadas}</div></div>
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
</div>
<div class="contenido_separador_color"></div>

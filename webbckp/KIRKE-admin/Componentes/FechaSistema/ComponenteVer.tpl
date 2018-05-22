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
<div class="contenido_titulo">{TR|o_tipo}</div>
<div class="contenido_1"><input name="tipo" type="radio" value="alta" {PL|tp_a} /> {TR|o_alta}</div>
<div class="contenido_1"><input name="tipo" type="radio" value="modificacion" {PL|tp_m} /> {TR|o_modificacion}</div>
<div class="contenido_5"><input name="tipo" type="radio" value="alta_modificacion" {PL|tp_am} /> {TR|o_alta_modificacion}</div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_mostrar_en_listado}</div>
<div class="contenido_1"><input name="listado_mostrar" type="radio" value="s" {PL|lm_s} /> {TR|o_si}</div>
<div class="contenido_6"><input name="listado_mostrar" type="radio" value="n" {PL|lm_n} /> {TR|o_no}</div>
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
    <div class="contenido_titulo">{TR|o_formato}</div>
    <div class="contenido_7"><input name="formato_fecha" type="radio" value="ddmmaaaa" {PL|formato_ddmmaaaa_s} />
        {TR|n_d}{TR|n_d}-{TR|n_m}{TR|n_m}-{TR|n_a}{TR|n_a}{TR|n_a}{TR|n_a}<br>
        <input name="formato_fecha" type="radio" value="ddmmaa" {PL|formato_ddmmaa_s} />
        {TR|n_d}{TR|n_d}-{TR|n_m}{TR|n_m}-{TR|n_a}{TR|n_a}<br>
        <input name="formato_fecha" type="radio" value="mmddaaaa" {PL|formato_mmddaaaa_s} />
        {TR|n_m}{TR|n_m}-{TR|n_d}{TR|n_d}-{TR|n_a}{TR|n_a}{TR|n_a}{TR|n_a}<br>
        <input name="formato_fecha" type="radio" value="mmddaa" {PL|formato_mmddaa_s} />
        {TR|n_m}{TR|n_m}-{TR|n_d}{TR|n_d}-{TR|n_a}{TR|n_a}<br>
        <input name="formato_fecha" type="radio" value="aammdd" {PL|formato_aammdd_s} />
        {TR|n_a}{TR|n_a}-{TR|n_m}{TR|n_m}-{TR|n_d}{TR|n_d}</div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_ocultar_vista} </div>
    <div class="contenido_1"><input name="ocultar_vista" type="radio" value="s" {PL|ov_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="ocultar_vista" type="radio" value="n" {PL|ov_n}>{TR|o_no} </div>
</div>
<div class="contenido_separador_color"></div>
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
{PL|crear_directorio}
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_eliminar_las_imagenes_al_quitar_el_componente}</div>
<div class="contenido_1"><input name="eliminar_imagenes" type="radio" value="s" {PL|ei_s}>{TR|o_si}</div>
<div class="contenido_6"><input name="eliminar_imagenes" type="radio" value="n" {PL|ei_n}>{TR|o_no}</div>
<div class="contenido_separador"></div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_recortar}</div>
<div class="contenido_2">{TR|o_ancho_final}</div>
<div class="contenido_5"><input type="text" name="ancho_final" id="ancho_final" size="4" value="{PL|ancho_final}" maxlength="4" filtro="numeros" max="5000" /> {TR|o_valor_maximo_5000} {TR|m_px} <div class="VC_error" id="VC_ancho_final"></div></div>
<div class="contenido_separador"></div>
<div class="contenido_titulo_margen_sin_linea"></div>
<div class="contenido_2">{TR|o_alto_final}</div>
<div class="contenido_5"><input type="text" name="alto_final" id="alto_final" size="4" value="{PL|alto_final}" maxlength="4" filtro="numeros" max="5000" /> {TR|o_valor_maximo_5000} {TR|m_px} <div class="VC_error" id="VC_alto_final"></div></div>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_obligatorio}</div>
<div class="contenido_1"><input name="obligatorio" type="radio" value="no_nulo" {PL|co_s}>{TR|o_si}</div>
<div class="contenido_6"><input name="obligatorio" type="radio" value="nulo" {PL|co_n}>{TR|o_no}</div>
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
    <div class="contenido_titulo">{TR|o_separar_en_directorios_por}:</div>
    <div class="contenido_2">{TR|o_no_separar} <input name="separar_en_directorios" type="radio" value="n" {PL|sd_n_s}></div>
    <div class="contenido_2">{TR|o_anio} <input name="separar_en_directorios" type="radio" value="anio" {PL|sd_a_s}></div>
    <div class="contenido_3">{TR|o_mes} <input name="separar_en_directorios" type="radio" value="mes" {PL|sd_m_s}></div>
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo">{TR|o_otras_copias_de_la_imagen}</div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_1} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_1" id="prefijo_1" size="5" value="{PL|prefijo_1}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_1" id="ancho_1" size="4" value="{PL|ancho_1}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_1" id="alto_1" size="4" value="{PL|alto_1}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_2} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_2" id="prefijo_2" size="5" value="{PL|prefijo_2}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_2" id="ancho_2" size="4" value="{PL|ancho_2}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_2" id="alto_2" size="4" value="{PL|alto_2}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_3} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_3" id="prefijo_3" size="5" value="{PL|prefijo_3}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_3" id="ancho_3" size="4" value="{PL|ancho_3}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_3" id="alto_3" size="4" value="{PL|alto_3}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_4} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_4" id="prefijo_4" size="5" value="{PL|prefijo_4}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_4" id="ancho_4" size="4" value="{PL|ancho_4}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_4" id="alto_4" size="4" value="{PL|alto_4}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_5} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_5" id="prefijo_5" size="5" value="{PL|prefijo_5}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_5" id="ancho_5" size="4" value="{PL|ancho_5}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_5" id="alto_5" size="4" value="{PL|alto_5}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_6} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_6" id="prefijo_6" size="5" value="{PL|prefijo_6}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_6" id="ancho_6" size="4" value="{PL|ancho_6}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_6" id="alto_6" size="4" value="{PL|alto_6}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_7} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_7" id="prefijo_7" size="5" value="{PL|prefijo_7}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_7" id="ancho_7" size="4" value="{PL|ancho_7}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_7" id="alto_7" size="4" value="{PL|alto_7}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_8} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_8" id="prefijo_8" size="5" value="{PL|prefijo_8}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_8" id="ancho_8" size="4" value="{PL|ancho_8}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_8" id="alto_8" size="4" value="{PL|alto_8}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_9} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_9" id="prefijo_9" size="5" value="{PL|prefijo_9}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_9" id="ancho_9" size="4" value="{PL|ancho_9}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_9" id="alto_9" size="4" value="{PL|alto_9}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_copia_10} </div>
    <div class="contenido_2">{TR|o_prefijo} <input type="text" name="prefijo_10" id="prefijo_10" size="5" value="{PL|prefijo_10}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" maxlength="3" /> </div>
    <div class="contenido_2">{TR|o_ancho} <input type="text" name="ancho_10" id="ancho_10" size="4" value="{PL|ancho_10}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_3">{TR|o_alto} <input type="text" name="alto_10" id="alto_10" size="4" value="{PL|alto_10}" maxlength="4" filtro="numeros" max="5000" /> </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_ocultar_edicion} </div>
    <div class="contenido_1"><input name="ocultar_edicion" type="radio" value="s" {PL|oe_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="ocultar_edicion" type="radio" value="n" {PL|oe_n}>{TR|o_no} </div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_ocultar_vista} </div>
    <div class="contenido_1"><input name="ocultar_vista" type="radio" value="s" {PL|ov_s}>{TR|o_si} </div>
    <div class="contenido_6"><input name="ocultar_vista" type="radio" value="n" {PL|ov_n}>{TR|o_no} </div>
</div>
<div class="contenido_separador_color"></div>

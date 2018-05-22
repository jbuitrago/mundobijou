<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_nombre}</div>
<div class="contenido_7"><input type="text" size="50" name="nombre" id="nombre" maxlength="50" no_nulo="{TR|o_debe_ingresar_un_dato}" > <div class="VC_error" id="VC_nombre"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_apellido}</div>
<div class="contenido_7"><input type="text" size="50" name="apellido" id="apellido" maxlength="50" no_nulo="{TR|o_debe_ingresar_un_dato}" > <div class="VC_error" id="VC_apellido"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_mail}</div>
<div class="contenido_7"><input type="text" size="50" name="email" id="email" maxlength="50" es_mail="{TR|o_debe_ingresar_un_dato}" filtro="mail" > <div class="VC_error" id="VC_email"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_telefono}</div>
<div class="contenido_7"><input type="text" name="telefono" id="telefono" maxlength="30" filtro="0123456789-()"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_habilitado}</div>
<div class="contenido_1"><input name="habilitado" type="radio" value="s" checked> {TR|o_si}</div>
<div class="contenido_6"><input name="habilitado" type="radio" value="n" />  {TR|o_no}</div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_usuario}</div>
<div class="contenido_7"><input type="text" size="50" name="usuario" id="usuario" maxlength="50" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_"><div class="mensaje_error" id="VC_usuario"></div></div>

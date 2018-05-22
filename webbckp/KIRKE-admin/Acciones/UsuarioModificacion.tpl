      <div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_nombre}</div>
<div class="contenido_7"><input type="text" size="50" name="nombre" id="nombre" value="{PL|nombre}" no_nulo="{TR|o_debe_ingresar_un_dato}" > <div class="VC_error" id="VC_nombre"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_apellido}</div>
<div class="contenido_7"><input type="text" size="50" name="apellido" id="apellido" value="{PL|apellido}" no_nulo="{TR|o_debe_ingresar_un_dato}" > <div class="VC_error" id="VC_apellido"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_mail}</div>
<div class="contenido_7"><input type="text" size="50" name="email" id="email" value="{PL|mail}"> <div class="VC_error" id="VC_email"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_telefono}</div>
<div class="contenido_7"><input type="text" name="telefono" id="telefono" value="{PL|telefono}" filtro="0123456789-()" ></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_habilitado}</div>
<div class="contenido_1"><input name="habilitado" type="radio" value="s" {PL|habilitado_s} />{TR|o_si}</div>
<div class="contenido_6"><input name="habilitado" type="radio" value="n" {PL|habilitado_n} />  {TR|o_no}</div>
    {PL|listadoRoles}
<div class="contenido_margen"></div>
<div class="contenido_titulo">{TR|o_generar_y_enviar_nueva_clave}</div>
<div class="contenido_7"><input name="nueva_clave" type="checkbox" id="nueva_clave" value="s"></div>
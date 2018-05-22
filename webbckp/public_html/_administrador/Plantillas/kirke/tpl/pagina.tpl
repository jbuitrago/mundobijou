<!DOCTYPE html>
<html>
    <head>
      {PL|cabeceras}{PL|javascript}
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    </head>
    <body {PL|datos_pagina}>
        <div class="cabezal_datos">
            <div class="version">{PL|version}</div>
            <div class="usuario">{TR|o_usuario}: {PL|usuario_nombre}, {PL|usuario_apellido}</div>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="5" class="cabezal_height"></td>
                <td width="18" class="cabezal_height">{IMG|cabezal_izq.png}</td>
                <td width="223" valign="top" class="cabezal cabezal_width"><div class="cabezal_logo_alinacion"><a href="http://www.kirke.ws/" target="_blank" class="logo_img">{IMG|logo.png}</a><div class="boton_celular"></div></div></td>
                <td width="18" class="cabezal_height">{IMG|cabezal_der.png}</td>
                <td width="5">&nbsp;</td>
                <td width="18" class="kk_resp_hidden">{IMG|cabezal_izq.png}</td>
                <td witdh="500" class="cabezal kk_resp_hidden"> <div class="contenedor_menu_predefinido">{PL|menu_predefinido}</div></td>
                <td class="cabezal kk_resp_hidden">{PL|botonera_registro_filtros}</td>
                <td width="18" class="kk_resp_hidden kk_resp_hidden">{IMG|cabezal_der.png}</td>
                <td width="5" class="cabezal_height kk_resp_hidden"></td>
            </tr>
        </table>
        <div class="contenedor_menu">{PL|menu}</div>
        <div class="contenedor_menu_movil">{PL|menu_movil}</div>
        {PL|filtros}
        <div class="titulo">{PL|titulo}{PL|ocultos}</div>
        <div class="titulo_pagina_separador"></div>{PL|filtro_general}{PL|formulario_inicio}
        <div class="cuerpo">{PL|cuerpo}</div>
        <div class="botonera">{PL|botonera}</div>
        <div class="botonera_izq">{PL|botonera_izq}</div>
        <div class="botonera2">{PL|botonera2}</div>
        {PL|formulario_fin}
        <div class="pie_separador"></div>
        <div class="pie">
            <div class="pie_izq">Amenábar 2310 3° 14 - Buenos Aires | Argentina | {TR|o_tel}: (54-11) 4781-8871</div>
            <div class="pie_der">Copyright Kirke 2007 - {TR|o_todos_los_derechos_reservados}</div>
        </div>
        {PL|errores}
    </body>
</html>

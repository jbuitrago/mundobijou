    <div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_usuario_actual}</div>
<div class="contenido_7"><input type="text" size="50" name="usuario_actual" id="usuario_actual" value="" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789" ><div class="VC_error" id="VC_usuario_actual"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_clave_actual}</div>
<div class="contenido_7"><input type="password" name="clave_actual" id="clave_actual" value="" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789" ><div class="VC_error" id="VC_clave_actual"></div></div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo"><span class="titulo_texto">{TR|o_datos_nuevos}</span></div>
<div class="contenido_salto_linea"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_clave_nueva} ({TR|m_debe_contener_al_menos_6_caracteres})</div>
<div class="contenido_3"><input type="password" name="clave_nueva" id="clave_nueva" value="" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789" ><div class="VC_error" id="VC_clave_nueva"></div><div id="clave_control_barra"></div><div id="clave_control_texto"></div></div>
<div class="contenido_4">({TR|o_la_clave_solo_puede_contener_letras_y_numeros_diferenciando_entre_mayusculas_y_minusculas})</div>
<div class="contenido_salto_linea"></div>
<div class="contenido_margen"></div>
<div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_clave_nueva_confirmacion}</div>
<div class="contenido_7"><input type="password" name="clave_nueva_confirmacion" id="clave_nueva_confirmacion" value="" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789" ><div class="VC_error" id="VC_clave_nueva_confirmacion"></div></div>
<script type="text/javascript">
    $("#clave_control_barra").progressbar({
        value: 0
    });

    $(document).ready(function() {
        $("#clave_nueva").keyup(function () {
            var valor_control = 0;
            
            if($("#clave_nueva").val().length >= 6){
                if($("#clave_nueva").val().length == 6){
                    valor_control += 10;
                }else if($("#clave_nueva").val().length <= 10){
                    valor_control += 20;
                }else{
                    valor_control += 30;
                }
                if($("#clave_nueva").attr("value").match(/[0-9]/)){
                    valor_control += 20;
                }
                if($("#clave_nueva").attr("value").match(/[a-z]/)){
                    valor_control += 20;
                }
                if($("#clave_nueva").attr("value").match(/[A-Z]/)){
                    valor_control += 30;
                }
                
                if(valor_control == 100){
                    $("#clave_control_texto").html("fuerte");
                }else if(valor_control >= 60){
                    $("#clave_control_texto").html("media");
                }else if(valor_control >= 50){
                    $("#clave_control_texto").html("debil");
                }else{
                    $("#clave_control_texto").html("muy debil");
                }
                
            }else{
                $("#clave_control_texto").html("no aprobada");
            }
            
            $("#clave_control_barra").progressbar({
                max: 100,
                value: valor_control
            });
        });
    });
</script>
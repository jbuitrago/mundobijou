<div id="opciones_avanzadas_boton">
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo"><div id="ver_opciones_avanzadas" class="link_jquery">{TR|o_ver_opciones_avanzadas}</div></div>
</div>
<div id="opciones_avanzadas">
    <div class="contenido_margen"></div>
    <div class="contenido_solo_titulo"><div id="ocultar_opciones_avanzadas" class="link_jquery">{TR|o_ocultar_opciones_avanzadas}</div></div>    
    <div class="contenido_separador"></div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_clave} ({TR|m_debe_contener_al_menos_6_caracteres})</div>
    <div class="contenido_7"><input type="password" size="50" name="clave" id="clave" maxlength="50" mensaje_no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"> <div class="mensaje_error" id="VC_clave"></div><div id="clave_control_barra"></div><div id="clave_control_texto"></div></div>
    <div class="contenido_margen"></div>
    <div class="contenido_titulo"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_repita_la_clave}</div>
    <div class="contenido_7"><input type="password" size="50" name="clave2" id="clave2" maxlength="50" mensaje_no_nulo="{TR|o_debe_ingresar_un_dato}" mensaje_claves="{TR|o_las_claves_no_coinciden}" filtro="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"> <div class="mensaje_error" id="VC_clave2"></div></div>
</div>
<div class="contenido_separador_color"></div>
<script type="text/javascript">
    $("#clave_control_barra").progressbar({
        value: 0
    });

    $(document).ready(function() {
        $("#clave").keyup(function () {
            var valor_control = 0;
            
            if($("#clave").val().length >= 6){
                if($("#clave").val().length == 6){
                    valor_control += 10;
                }else if($("#clave").val().length <= 10){
                    valor_control += 20;
                }else{
                    valor_control += 30;
                }
                if($("#clave").attr("value").match(/[0-9]/)){
                    valor_control += 20;
                }
                if($("#clave").attr("value").match(/[a-z]/)){
                    valor_control += 20;
                }
                if($("#clave").attr("value").match(/[A-Z]/)){
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
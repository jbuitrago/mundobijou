<script type="text/javascript">
$(function(){
    $('.link_jquery').click(function(){
        var id_registro = $(this).attr('url');
        window.location.href = './index.php?kk_generar=0&accion=41&id_tabla={PL|id_tabla}&id_registro='+id_registro;
    });
});
</script>
<div class="contenido_separador"></div>
<div class="contenido_margen"></div>
<div class="contenido_solo_titulo">&nbsp;</div>
{PL|familias}
{PL|familia_registros}
{PL|id_registros}
<div class="contenido_margen"></div>
{PL|formulario}
</div>

<div id="nombre_menu">    
<div class="contenido_separador"> </div>

<div class="contenido_separador"> </div>
<div class="contenido_margen"> </div>
<div class="contenido_solo_titulo"> </div>

{PL|familias_cierre}

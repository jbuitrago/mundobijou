<?php

class DesarrollosSistema_FiltroGeneral extends Generales_FiltrosOrden {

    static public function armado($tabla) {

        $armado_botonera = new Armado_Botonera('filtros');
        if (isset($_GET['eliminar_filtro_general']) && ($_GET['eliminar_filtro_general'] == 'si') && ($_GET['kk_tabla'] == $tabla)) {
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['filtros']['filtro_general_valor']);
            $parametros = array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0']);
            $armado_botonera->armar('redirigir', $parametros);
        } elseif (isset($_POST['buscador_general']) && ($_POST['buscador_general'] != '') && isset($_POST['control_buscador_general']) && ($_POST['control_buscador_general'] == $tabla)) {
            // elimino el paginado, para que no se caiga en la pagina 2 y quede vacia porque solo haya resultados para la primera pagina
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['FiltrosOrdenPaginado'] = 0;
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['filtros']['filtro_general_valor'] = $_POST['buscador_general'];
            $parametros = array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0']);
            $armado_botonera->armar('redirigir', $parametros);
        } elseif (
                isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['filtros']['filtro_general_valor']) &&
                ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['filtros']['filtro_general_valor'] != '')
        ) {
            $valor = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['filtros']['filtro_general_valor'];
        } else {
            $valor = '';
        }
        $parametros = array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0']);
        $boton = $armado_botonera->armar('filtrar', $parametros, 'buscar', 's', true);

        $parametros = array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0'], 'eliminar_filtro_general' => 'si', 'kk_tabla' => $tabla);
        $boton_borrar = $armado_botonera->armar('eliminar', $parametros, 'borrar_busqueda', 's', true);

        return '
            <div class="contenido_separador"></div>
            <br />
            <form name="form_buscador_general" id="form_buscador_general" target="_self" method="post" action="">            
                <div class="fg_contenido_margen"></div>
                <div class="fg_contenido_solo_titulo">
                    <div style="float:left;margin-top:8px;color:#791E1E;font-size:12px;">
                        <input type="text" id="buscador_general" name="buscador_general" value="' . $valor . '">
                        <input type="hidden" name="control_buscador_general" value="' . $tabla . '">
                    </div>
                    <div class="fg_botonera">
                        ' . $boton . $boton_borrar['contenido'] . '
                    </div>
                </div>
            </form>
            ';
    }

}

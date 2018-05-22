<?php

class Armado_FiltroGeneral extends Generales_FiltrosOrden {

    static public function armado() {

        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general'])) {
            $armado_botonera = new Armado_Botonera('filtros');
            if (isset($_GET['eliminar_filtro_general']) && ($_GET['eliminar_filtro_general'] == 'si')) {
                unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general_valor']);
                $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
                $armado_botonera->armar('redirigir', $parametros);
            } elseif (isset($_POST['buscador_general']) && ($_POST['buscador_general'] != '')) {
                // elimino el paginado, para que no se caiga en la pagina 2 y quede vacia porque solo haya resultados para la primera pagina
                $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPaginado'] = 0;
                $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general_valor'] = $_POST['buscador_general'];
                $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
                $armado_botonera->armar('redirigir', $parametros);
            } elseif (
                    isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general_valor']) &&
                    ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general_valor'] != '')
            ) {
                $valor = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general_valor'];
            } else {
                $valor = '';
            }
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
            $boton = $armado_botonera->armar('filtrar', $parametros, 'buscar', 's', true);

            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'eliminar_filtro_general' => 'si');
            $boton_borrar = $armado_botonera->armar('eliminar', $parametros, 'borrar_busqueda', 's', true);

            return '
            <form name="form_buscador_general" id="form_buscador_general" target="_self" method="post" action="">
                <div class="fg_contenido_margen"></div>
                <div class="fg_contenido_solo_titulo">
                    <div style="float:left;margin-top:8px;color:#791E1E;font-size:12px;">
                        <input type="text" id="buscador_general" name="buscador_general" value="' . $valor . '">
                    </div>
                    <div class="fg_botonera">
                        ' . $boton . $boton_borrar['contenido'] . '
                    </div>
                </div>
            </form>
            ';
        } else {
            return false;
        }
    }

}

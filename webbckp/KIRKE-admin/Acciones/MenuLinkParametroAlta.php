<?php

class Acciones_MenuLinkParametroAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla_formulario', 'si'));

        $armado_botonera = new Armado_Botonera();

        $id_tabla = Consultas_MenuLink::RegistroConsultaIdMenuLink(__FILE__, __LINE__, $_GET['id_menu_link']);

        $id_menu = Consultas_MenuLink::RegistroConsultaIdMenuObtener(__FILE__, __LINE__, $_GET['id_menu_link']);

        if (!isset($_GET['alta'])) {
            $parametros = array('kk_generar' => '0', 'accion' => '23', 'id_menu' => $id_menu[0]['id_menu'], 'id_menu_link' => $_GET['id_menu_link']);
            $armado_botonera->armar('guardar', $parametros);

            $parametros = array('kk_generar' => '0', 'accion' => '21', 'id_menu' => $id_menu[0]['id_menu']);
            $armado_botonera->armar('volver', $parametros);
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '23', 'id_menu' => $id_menu[0]['id_menu'], 'id_menu_link' => $_GET['id_menu_link'], 'alta' => 'si');
            $armado_botonera->armar('guardar', $parametros);
        }

        $elemento = $id_tabla[0]['elemento'];
        $id_tabla = $id_tabla[0]['id_elemento'];

        $menu_link_parametros = Consultas_MenuLinkParametro::RegistroConsulta(__FILE__, __LINE__, $_GET['id_menu_link'], $id_tabla);
        $parametros = Armado_FiltrosMenuPaginas::armado($id_tabla, $menu_link_parametros);

        if (!isset($_GET['alta'])) {
            $menu_nombre_idiomas = Consultas_MenuLinkNombre::RegistroConsultaIdMenuLink(__FILE__, __LINE__, $_GET['id_menu_link']);
            if (is_array($menu_nombre_idiomas)) {
                foreach ($menu_nombre_idiomas as $valores) {
                    $datos_idiomas_cargados[$valores['idioma_codigo']] = $valores['menu_link_nombre'];
                }
            }
            $plantilla['etiqueta_idiomas'] = Armado_EtiquetaIdiomas::armar('etiqueta', $datos_idiomas_cargados);
        } else {
            $nombre_menu_link = Consultas_MenuLinkNombre::RegistroConsultaIdMenu(__FILE__, __LINE__, $_GET['id_menu_link']);
            $plantilla['etiqueta_idiomas'] = $nombre_menu_link[0]['menu_link_nombre'];
        }

        if (isset($parametros['desplegable1']) && ($parametros['desplegable1'] != '')) {
            $plantilla['parametros_orden_1'] = '
            <div class="contenido_margen"></div>
            <div class="contenido_titulo">{TR|o_orden} 1</div>
            <div class="contenido_1"><input name="kk_orden1" type="radio" value="ascendente" ' . $parametros['orden']['asc1'] . ' /> {TR|o_ascendente}</div>
            <div class="contenido_1"><input name="kk_orden1" type="radio" value="descendente" ' . $parametros['orden']['desc1'] . ' /> {TR|o_descendente}</div>
            <div class="contenido_5">' . $parametros['desplegable1'] . '</div>
            <div class="contenido_separador"></div>
            ';
        }

        if (isset($parametros['desplegable2']) && ($parametros['desplegable2'] != '')) {
            $plantilla['parametros_orden_2'] = '
            <div class="contenido_margen"></div>
            <div class="contenido_titulo">{TR|o_orden} 2</div>
            <div class="contenido_1"><input name="kk_orden2" type="radio" value="ascendente" ' . $parametros['orden']['asc2'] . ' /> {TR|o_ascendente}</div>
            <div class="contenido_1"><input name="kk_orden2" type="radio" value="descendente" ' . $parametros['orden']['desc2'] . ' /> {TR|o_descendente}</div>
            <div class="contenido_5">' . $parametros['desplegable2'] . '</div>
            <div class="contenido_separador"></div>
            ';
        }

        if (isset($parametros['desplegable3']) && ($parametros['desplegable3'] != '')) {
            $plantilla['parametros_orden_3'] = '
            <div class="contenido_margen"></div>
            <div class="contenido_titulo">{TR|o_orden} 3</div>
            <div class="contenido_1"><input name="kk_orden3" type="radio" value="ascendente" ' . $parametros['orden']['asc3'] . ' /> {TR|o_ascendente}</div>
            <div class="contenido_1"><input name="kk_orden3" type="radio" value="descendente" ' . $parametros['orden']['desc3'] . ' /> {TR|o_descendente}</div>
            <div class="contenido_5">' . $parametros['desplegable3'] . '</div>
            <div class="contenido_separador"></div>
            ';
        }

        if ($elemento == 'pagina') {
            $plantilla['buscardor_y_ocultar'] = '
            <div class="contenido_margen"></div>
            <div class="contenido_titulo">{TR|o_mostrar_buscador_rapido}</div>
            <div class="contenido_1"><input name="kk_filtro_general" type="radio" value="si" ' . $parametros['filtro_general']['si'] . ' /> {TR|o_si}</div>
            <div class="contenido_6"><input name="kk_filtro_general" type="radio" value="no" ' . $parametros['filtro_general']['no'] . ' /> {TR|o_no}</div>
            <div class="contenido_separador"></div>
            <div class="contenido_margen"></div>
            <div class="contenido_titulo">{TR|o_mostrar_ocultar_campos}</div>
            <div class="contenido_1"><input name="kk_ocultar_campos" type="radio" value="si" ' . $parametros['ocultar_campos']['si'] . ' /> {TR|o_si}</div>
            <div class="contenido_6"><input name="kk_ocultar_campos" type="radio" value="no" ' . $parametros['ocultar_campos']['no'] . ' /> {TR|o_no}</div>
            <div class="contenido_separador"></div>
            ';
        }

        if (isset($parametros['filtros']) && ($parametros['filtros'] != '')) {
            $plantilla['parametros_filtros'] = '
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_filtros}</div>
                <div class="contenido_7"></div>' . $parametros['filtros'] . '
                <div class="contenido_separador_color"></div>
               ';
        }

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}

<?php

class Acciones_MenuLinkListado extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        if (isset($_GET['id_menu'])) {
            $id_menu = $_GET['id_menu'];
        } else {
            $id_menu = Consultas_MenuLink::RegistroConsultaIdMenuObtener(__FILE__, __LINE__, $_GET['id_orden_act']);
            $id_menu = $id_menu[0]['id_menu'];
        }

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '18', 'id_menu' => $id_menu);
        $armado_botonera->armar('nuevo', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '15');
        $armado_botonera->armar('volver', $parametros, '', 's');

        // modificacion de orden de los elementos de la tabla
        if (isset($_GET['orden_act']) && $_GET['orden_act'] != '' && $_GET['id_orden_act'] != ''
        ) {
            if (isset($_GET['orden_ant']) && ($_GET['orden_ant'] != '') && ($_GET['id_orden_ant'] != '')) {
                Consultas_CambiarOrden::armado('kirke_menu_link', $_GET['id_orden_ant'], 'orden', $_GET['orden_act'], 'id_menu_link');
                Consultas_CambiarOrden::armado('kirke_menu_link', $_GET['id_orden_act'], 'orden', $_GET['orden_ant'], 'id_menu_link');
            } elseif (isset($_GET['orden_sig']) && ($_GET['orden_sig'] != '') && ($_GET['id_orden_sig'] != '')) {
                Consultas_CambiarOrden::armado('kirke_menu_link', $_GET['id_orden_sig'], 'orden', $_GET['orden_act'], 'id_menu_link');
                Consultas_CambiarOrden::armado('kirke_menu_link', $_GET['id_orden_act'], 'orden', $_GET['orden_sig'], 'id_menu_link');
            }
            Armado_Menu::reinciarMenu();
            $parametros = array('kk_generar' => '0', 'accion' => '21', 'id_menu' => $id_menu);
            $armado_botonera->armar('redirigir', $parametros);
        }

        // datos necesarios para armar la tabla:
        // columna 0
        $tabla_columnas[0]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[0]['tb_titulo_idioma'] = '{TR|o_nombre_link}';
        $tabla_columnas[0]['tb_columna_ancho'] = '40';
        $tabla_columnas[0]['tb_campo'] = 'menu_link_nombre';
        // columna 1
        $tabla_columnas[1]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[1]['tb_titulo_idioma'] = '{TR|o_tipo}';
        $tabla_columnas[1]['tb_columna_ancho'] = '';
        $tabla_columnas[1]['tb_campo'] = 'tipo';
        // columna 2
        $tabla_columnas[2]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[2]['tb_titulo_idioma'] = '{TR|o_pagina}';
        $tabla_columnas[2]['tb_columna_ancho'] = '';
        $tabla_columnas[2]['tb_campo'] = 'nombre';
        // columna 3
        $tabla_columnas[3]['tb_columna_tipo'] = 'opcion';
        $tabla_columnas[3]['tb_columna_ancho'] = '';
        $tabla_columnas[3]['tb_titulo_idioma'] = '{TR|o_habilitado}';
        $tabla_columnas[3]['tb_campo'] = 'habilitado';
        // columna 4
        $tabla_columnas[4]['tb_columna_tipo'] = 'orden';
        $tabla_columnas[4]['tb_titulo_idioma'] = '{TR|o_orden}';
        $tabla_columnas[4]['tb_campo'] = 'orden';
        $tabla_columnas[4]['tb_campo_id'] = 'id_menu_link';
        $tabla_columnas[4]['accion'] = '21';
        // columna 5
        $tabla_columnas[5]['tb_columna_tipo'] = 'editar';
        $tabla_columnas[5]['tb_titulo_idioma'] = '{TR|o_editar}';
        $tabla_columnas[5]['tb_campo'] = 'id_menu_link';
        $tabla_columnas[5]['variable_link'] = 'id_menu_link';
        $tabla_columnas[5]['accion'] = '22';
        // columna 6
        $tabla_columnas[6]['tb_columna_tipo'] = 'eliminar';
        $tabla_columnas[6]['tb_titulo_idioma'] = '{TR|o_eliminar}';
        $tabla_columnas[6]['tb_campo'] = 'id_menu_link';
        $tabla_columnas[6]['accion'] = '20';

        // query para armar la consulta
        $tablas = Consultas_MatrizMenuLink::armado($id_menu);

        $tabla_detalles = array();
        $cont = 0;
        if (is_array($tablas)) {
            foreach ($tablas as $tabla) {
                $tabla_detalles[$cont]['menu_link_nombre'] = $tabla['menu_link_nombre'];
                if ($tabla['elemento'] == 'pagina') {
                    $tabla_detalles[$cont]['id_menu_link'] = $tabla['id_menu_link'];
                    $tabla_detalles[$cont]['tipo'] = '{TR|o_pagina}';
                    $tabla_detalles[$cont]['nombre'] = $tabla['prefijo'] . '_' . $tabla['tabla_nombre'];
                } elseif ($tabla['elemento'] == 'informe') {
                    $tabla_detalles[$cont]['id_menu_link'] = $tabla['id_menu_link'];
                    $tabla_detalles[$cont]['tipo'] = '{TR|o_informe}';
                    $tabla_detalles[$cont]['nombre'] = Armado_InformeSelect::obtenerNombre($tabla['id_elemento']);
                } else {
                    $tabla_detalles[$cont]['id_menu_link'] = $tabla['id_menu_link'];
                    $tabla_detalles[$cont]['tipo'] = '{TR|o_desarrollo}';
                    $tabla_detalles[$cont]['nombre'] = $tabla['id_elemento'];
                }
                if ($tabla['habilitado'] == 's') {
                    $tabla_detalles[$cont]['habilitado'] = 1;
                } else {
                    $tabla_detalles[$cont]['habilitado'] = 0;
                }
                $tabla_detalles[$cont]['orden'] = $tabla['orden'];
                $cont++;
            }
        }

        // armado de la tabla
        $armar_tabla = new Armado_Tabla();
        $armar_tabla->sinDatosPie();
        return $armar_tabla->armar($tabla_columnas, $tabla_detalles);
    }

}

<?php

class Armado_LinkADestino {

    static private $_idLinkFiltro;
    static private $_idLinkComponente;
    static private $_linkADestino = array();

    static public function armadoActual() {

        $filtrosIdLink = Generales_FiltrosOrden::filtrosIdLink();

        if (isset($filtrosIdLink[0]['valor'])) {
            self::$_idLinkFiltro = $filtrosIdLink[0]['valor'];
        }
        if (isset($filtrosIdLink[0]['id'])) {
            self::$_idLinkComponente = $filtrosIdLink[0]['id'];
        }
    }

    // $id_link_filtro es el id del registro del componente de origen

    static public function armadoIdLinkFiltro() {
        return self::$_idLinkFiltro;
    }

    // $id_link_componente es el componente de destino a filtrar

    static public function armadoIdLinkComponente() {
        return self::$_idLinkComponente;
    }

    static public function armadoSiguiente($id_link_componente, $id_link_filtro, $accion, $cantidad = null, $solo_link = false) {

        if ($id_link_componente != '') {

            if (!isset($link_a_destino[$id_link_componente])) {

                $id_tabla = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $id_link_componente);

                $link_a_destino[$id_link_componente] = $id_tabla[0]['id_tabla'];
                $id_link_tabla = $id_tabla[0]['id_tabla'];
            } else {
                $id_link_tabla = $link_a_destino[$id_link_componente];
            }

            // link a la siguiente tabla
            $link_armado['kk_generar'] = $_GET['kk_generar'];
            $link_armado['accion'] = $accion;
            $link_armado['id_tabla'] = $id_link_tabla;

            // agrego los nuevos valores
            $link_armado['id_link'] = $id_link_componente . '_' . $id_link_filtro;

            // armo boton y link
            $link = './index.php?' . Generales_VariablesGet::armar($link_armado, 's');
            
            if ($solo_link === true) {
                return $link;
            }

            if ($cantidad !== null) {
                $cantidad = '<div class="bt_tb_link_destino_cant">[' . $cantidad . ']</div>';
            } else {
                $cantidad = '';
            }

            return '<a href="' . $link . '" target="_top" title="{TR|o_registros_relacionados}"><div class="bt_tb_link_destino">' . $cantidad . '</div></a>';
        } else {

            return false;
        }
    }

    static public function armadoSiguienteCantidad($tabla_relacionada, $tb_campo) {
        $cantidades = Consultas_RegistroCantidad::Consulta(__FILE__, __LINE__, $tabla_relacionada, $tb_campo);

        $array = false;
        if (is_array($cantidades)) {
            foreach ($cantidades as $linea) {
                $array[$linea['elemento']] = $linea['cantidad'];
            }
        }
        return $array;
    }

    static public function armadoVolver() {

        $volver = Generales_FiltrosOrden::filtrosVolver();

        if ($volver !== false) {

            $id_accion = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$volver]['id_accion'];
            $id_tabla = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$volver]['id_elemento'];
            $id_registro = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$volver]['id_registro'];

            if ($id_registro == 0) {
                return array('kk_generar' => '0', 'accion' => $id_accion, 'id_tabla' => $id_tabla, 'volver' => $volver);
            } else {
                return array('kk_generar' => '0', 'accion' => $id_accion, 'id_tabla' => $id_tabla, 'id_tabla_registro' => $id_registro, 'volver' => $volver);
            }
        } else {
            return false;
        }
    }

}

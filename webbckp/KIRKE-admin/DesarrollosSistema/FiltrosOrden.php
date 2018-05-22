<?php

class DesarrollosSistema_FiltrosOrden {

    static private $_filtros = array();
    static private $_filtroGeneral = array();
    static private $_filtroGeneralRelacionados = array();
    static private $_orden;
    static private $_filtrosPostCabezal;
    static private $_ordenValores = array('1' => 'ascendente', '2' => 'descendente');
    static private $_idAccion;
    static public $_nivelActual = 0;
    static public $mismoCampoOrden = false;
    static public $ordenValoresNum = array('ascendente' => '1', 'descendente' => '2');

    static public function armar($tabla) {
        if (isset($_GET['kk_pagina_n']) && ($_GET['kk_pagina_n'] !== false)) {
            // Numero de pagina actual
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['FiltrosOrdenPaginado'] = $_GET['kk_pagina_n'];
        }
        self::$_orden[$tabla] = array();
        // orden
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosOrden'][$tabla]['filtros']['orden']) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosOrden'][$tabla]['filtros']['orden'])) {
            foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosOrden'][$tabla]['filtros']['orden'] as $identificador => $valor) {
                self::$_orden[$tabla][] = $valor;
            }
        }
        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosNivelActual'] = 0;
        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosOrden'][$tabla]['DesarrolloFiltrosOrdenPostCabezal'] = self::$_filtrosPostCabezal;
    }

    static public function obtenerOrden($tabla) {
        if (isset(self::$_orden[$tabla]) && (count(self::$_orden[$tabla]) > 0)) {
            return self::$_orden[$tabla];
        }else{
            return array();
        }
    }

    static public function insertarOrden($tabla, $identificador, $orden_direccion) {
        $cantidad_ord = 3;
        if ((isset(self::$_orden[$tabla][0]['identificador']) && isset($_GET['kk_orden_listado']) && (self::$_orden[$tabla][0]['identificador'] == $_GET['kk_orden_listado']))) {
            self::$mismoCampoOrden = true;
        }
        foreach (self::$_orden[$tabla] as $i => $valor) {
            if (isset(self::$_orden[$tabla][$i]['identificador']) && (self::$_orden[$tabla][$i]['identificador'] == $identificador)) {
                unset(self::$_orden[$tabla][$i]);
            }
        }
        if (!isset(self::$_orden[$tabla][0]['identificador']) || (self::$_orden[$tabla][0]['identificador'] != $identificador)) {
            $cantidad = count(self::$_orden[$tabla]);
            if ($cantidad == $cantidad_ord) {
                unset(self::$_orden[$tabla][2]);
            }
            array_unshift(self::$_orden[$tabla], array('parametro' => self::$_ordenValores[$orden_direccion], 'identificador' => $identificador));
        } else {
            self::$_orden[$tabla][0]['parametro'] = self::$_ordenValores[$orden_direccion];
        }
        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosOrden'][$tabla]['filtros']['orden'] = self::$_orden[$tabla];
    }

    static public function obtenerPaginado($tabla) {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['FiltrosOrdenPaginado'])) {
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$tabla]['FiltrosOrdenPaginado'];
        } else {
            return 0;
        }
    }

}

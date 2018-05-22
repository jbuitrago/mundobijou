<?php

class Componentes_Separador_ComponenteInsercion {

    private $_nombreComponente;
    private $_colorFondo;
    private $_pv;

    public function set() {
        $this->_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
    }

    public function get() {
        $this->_insertarConfiguracion();
    }

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    private function _insertarConfiguracion() {

        if ($_GET['accion'] == 'ComponenteAltaInsercion') {

            // creo el objeto 'componente_carga' para el alta
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            // crear componente y obtener id de insercion
            $id_componente = $componente_carga->crearComponente($_GET['id_tabla'], $this->_nombreComponente, '');

            // se inserta el nuevo componente
            $componente_carga->consultaParametro($id_componente, 'tipo_dato', '');
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {

            // creo el objeto 'componente_carga' para la modificacion
            $componente_carga = new Acciones_ComponenteModificacionInsercion();
            // se le pasa el id para poder hacer las modificaciones
            $id_componente = $_GET['cp_id'];
        }
        if ($_POST['color_fondo'] != $this->_pv['color_fondo']) {
            $componente_carga->consultaParametro($id_componente, 'color_fondo', Bases_InyeccionSql::consulta($_POST['color_fondo']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'color_fondo');
        }

        // si no existe, no agrega el parametro del componente
        $componente_carga->consultaParametro($id_componente, 'idioma_' . Generales_Idioma::obtener(), '');
    }

}

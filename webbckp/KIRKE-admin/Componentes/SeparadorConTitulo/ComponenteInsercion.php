<?php

class Componentes_SeparadorConTitulo_ComponenteInsercion {

    private $_nombreComponente;
    private $_colorFondo;
    private $_tbTituloIdioma = array();   // textos en idiomas a cargar
    private $_pv;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set() {

        if (is_array(Inicio::confVars('idiomas'))) {
            // obtiencion de etiquetas de idiomas
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                $idioma_s_ext = substr($value, 0, 2);
                $this->_idioma[$contador] = $value;
                $this->_tbTituloIdioma[$contador] = Bases_InyeccionSql::consulta($_POST['etiqueta_' . $idioma_s_ext]);
                $contador++;
            }
        }

        $this->_colorFondo = Bases_InyeccionSql::consulta($_POST['color_fondo']);
        $this->_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
    }

    public function get() {
        $this->_insertarConfiguracion();
    }

    private function _insertarConfiguracion() {

        // obtengo nombre y tipo de tabla actual
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $tabla_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tabla_tipo = $datos_tabla['tipo'];

        if ($_GET['accion'] == 'ComponenteAltaInsercion') {

            // creo el objeto 'componente_carga' para el alta
            $componente_carga = new Acciones_ComponenteAltaInsercion();
            // crear componente y obtener id de insercion
            $id_componente = $componente_carga->crearComponente($_GET['id_tabla'], $this->_nombreComponente, '');
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {

            // creo el objeto 'componente_carga' para la modificacion
            $componente_carga = new Acciones_ComponenteModificacionInsercion();
            // se le pasa el id para poder hacer las modificaciones
            $id_componente = $_GET['cp_id'];
        }

        foreach ($this->_tbTituloIdioma as $key => $value) {
            $componente_carga->consultaParametro($id_componente, 'idioma_' . $this->_idioma[$key], $this->_tbTituloIdioma[$key]);
        }

        if ($_POST['color_fondo'] != $this->_pv['color_fondo']) {
            $componente_carga->consultaParametro($id_componente, 'color_fondo', Bases_InyeccionSql::consulta($_POST['color_fondo']));
        } elseif ($_GET['accion'] == 'ComponenteModificacionInsercion') {
            $componente_carga->consultaParametroEliminar($id_componente, 'color_fondo');
        }
    }

}

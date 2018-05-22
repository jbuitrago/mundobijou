<?php

new DesplegableTablasVarias_Archivos_XmlDesplegables();

class DesplegableTablasVarias_Archivos_XmlDesplegables {

    private $_datos;
    private $_datosComponente;
    private $_idTabla;

    public function __construct() {

        $cp_id = explode('_', $_GET['id_cp']);
        $this->_idComponente = $cp_id[0];

        $this->_datosComponente = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($this->_idComponente);
        $this->_idTabla = $this->_datosComponente['tb_id'];

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $this->_idTabla, 'ver');

        $this->set();
        $this->get();
    }

    private function set() {

        if (isset($_GET['q'])) {
            $q = $_GET['q'];
        } else {
            $q = '';
        }

        $this->_datos = Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'consultaAjax', $q, $this->_datosComponente, $this->_datosComponente['cp_nombre'], $this->_idComponente);
    }

    private function get() {

        echo $this->_datos;
    }

}

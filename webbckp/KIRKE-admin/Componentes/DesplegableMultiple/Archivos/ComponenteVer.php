<?php

new DesplegableMultiple_Archivos_XmlDesplegables();

class DesplegableMultiple_Archivos_XmlDesplegables {

    private $_datos;
    private $_idComponente;
    private $_idTabla;

    public function __construct() {

        $this->_idComponente = $_GET['cp'];

        $tablaComponente = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $this->_idComponente);

        $this->_idTabla = $tablaComponente[0]['id_tabla'];

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $this->_idTabla, 'ver');

        $this->set();
        $this->get();
    }

    private function set() {

        $tablaComponentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos', $this->_idTabla);

        if (is_array($tablaComponentes)) {
            foreach ($tablaComponentes as $datos) {
                if (isset($datos['origen_cp_id'])) {
                    $componente = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($datos['origen_cp_id']);
                    $this->_datos[$datos['origen_cp_id']] = $datos['origen_tb_prefijo'] . '_' . $datos['origen_tb_nombre'] . ' - ' . $datos['origen_tb_campo'];
                }
            }
        }
    }

    private function get() {

        $xml = '';
        $largo = 50;

        if (is_array($this->_datos)) {

            Header("Content-Type:application/xhtml+xml; charset=utf-8");

            $xml .= "<?xml version=\"1.0\" standalone=\"yes\" ?>";
            $xml .= "<datos>";

            foreach ($this->_datos as $id => $valor) {
                $puntos = '';
                if (strlen($valor) > $largo) {
                    $puntos = '... ';
                }

                $xml .= "<dato>";
                $xml .= "<id>" . $id . "</id>";
                $xml .= "<texto>" . substr($valor, 0, $largo) . $puntos . "</texto>";
                $xml .= "</dato>";
            }

            $xml .= "</datos>";
        }

        echo $xml;
    }

}

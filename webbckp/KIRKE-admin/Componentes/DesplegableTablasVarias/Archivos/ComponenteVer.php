<?php

new DesplegableTablasVarias_Archivos_XmlDesplegables();

class DesplegableTablasVarias_Archivos_XmlDesplegables {

    private $_datos;

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

        $matriz_tb_campo = Consultas_Componente::RegistroConsultaTabla(__FILE__, __LINE__, '', true);

        if ($matriz_tb_campo) {
            foreach ($matriz_tb_campo as $linea) {
                $this->_datos[$linea['id_componente']] = $linea['tabla_nombre'] . ' - ' . $linea['tabla_campo'];
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

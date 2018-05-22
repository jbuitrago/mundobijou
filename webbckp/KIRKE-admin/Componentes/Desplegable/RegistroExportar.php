<?php

class Componentes_Desplegable_RegistroExportar{// extends Armado_Plantilla {

    private $_nombreComponente;
    private $valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array(); // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $_tb_nombre;
    private $_tb_campo;
    private $_matriz_links;
    private $_titulo;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {
        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_idRegistro = $datos[4];
        $_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        $this->_dcp = array_merge($_pv, $this->_dcp);
        $this->_titulo = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
    }

    public function get() {
        $this->_valor = $this->_mostrarValor($this->_valor);
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _excel() {
        $valor['titulo'] = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_titulo, '<a>'));
        $valor['valor'] = '<td bgcolor="#eeeeec">' . str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_valor, '<a>')) . '</td>';
        return $valor;
    }

    private function _pdf() {
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = $this->_valor;
        return $valor;
    }

    private function _cvs() {
        $valor['titulo'] = str_replace("\n", ' ', $this->_titulo);
        $valor['valor'] = str_replace(';', ',', str_replace(array("\r\n", "\n", "\r"), ' ', $this->_valor)) . ';';
        return $valor;
    }

    private function _xml() {
        $this->_titulo = strtolower(str_replace(' ', '_', $this->_titulo));
        return '    <' . $this->_titulo . '><![CDATA[' . $this->_valor . ']]></' . $this->_titulo . '>' . "\n";
    }

    private function _html() {
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = '<td>' . $this->_valor . '</td>';
        return $valor;
    }

    private function _sql() {
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = "'" . str_replace("'", '\\\'', $this->_valor) . "', ";
        return $valor;
    }

    private function _sqlEstructura() {
        $titulo[0] = '  `' . $this->_dcp['tb_campo'] . '` longtext,' . "\n";
        $titulo[1] = '`' . $this->_dcp['tb_campo'] . '`, ';
        return $titulo;
    }

    private function _mostrarValor($id_origen) {

        if ($id_origen != '') {

            $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
            $tb_campo = $this->_dcp['origen_tb_campo'];

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($tb_nombre);
            $consulta->campos($tb_nombre, $tb_campo);
            $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre, 'iguales', '', '', $id_origen);
            $valor_campo = $consulta->realizarConsulta();

            $texto = $valor_campo[0][$tb_campo];

            $valor = $this->_valor;

            if (($texto != '') && isset($this->_dcp['motrar_id']) && ($this->_dcp['motrar_id'] == 's')) {
                $texto .= ' (' . $id_origen . ')';
            }

            return $texto;
        } else {

            return '-';
        }
    }

}

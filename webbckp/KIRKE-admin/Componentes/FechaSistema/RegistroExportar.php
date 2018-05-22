<?php

class Componentes_FechaSistema_RegistroExportar extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_valor_original;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_campoAncho = array();
    private $_formatoFecha;
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
        $this->_valor_original = $this->_valor;
        if ($this->_valor) {
            $this->_valor = $this->_formateoFecha($this->_valor);
        }
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
        $valor['valor'] = str_replace("'", '\\\'', $this->_valor_original) . ', ';
        return $valor;
    }

    private function _sqlEstructura() {
        $titulo[0] = '  `' . $this->_dcp['tb_campo'] . '` int(12) ,' . "\n";
        $titulo[1] = '`' . $this->_dcp['tb_campo'] . '`, ';
        return $titulo;
    }

    private function _formateoFecha($fecha_sin_formato) {
        if ($fecha_sin_formato) {
            switch ($this->_dcp['formato_fecha']) {
                case 'ddmmaaaa':
                    $fecha = @date('d-m-Y H:i:s', $fecha_sin_formato);
                    break;
                case 'ddmmaa':
                    $fecha = @date('d-m-y H:i:s', $fecha_sin_formato);
                    break;
                case 'mmddaaaa':
                    $fecha = @date('m-d-Y H:i:s', $fecha_sin_formato);
                    break;
                case 'mmddaa':
                    $fecha = @date('m_d-y H:i:s', $fecha_sin_formato);
                    break;
                case 'aammdd':
                    $fecha = @date('y-m-d H:i:s', $fecha_sin_formato);
                    break;
            }
        }
        return $fecha;
    }

}

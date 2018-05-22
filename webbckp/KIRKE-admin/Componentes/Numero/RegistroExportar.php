<?php

class Componentes_Numero_RegistroExportar extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;                    // valor del compenente en tabla
    private $_valor_original;
    private $_metodo;                   // metodo a llamar
    private $_dcp = array();            // parametros del componente
    private $_idComponente;            // id del componente
    private $_idRegistro;              // id del registro
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
        if ($this->_valor != '') {
            $this->_valor = number_format($this->_valor, $this->_dcp['decimales'], ',', ' ');
        }
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _excel() {
        $valor['titulo'] = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_titulo, '<a>'));
        $valor['valor'] = '<td bgcolor="#eeeeec">' . str_replace(' ', '', strip_tags($this->_valor, '<a>')) . '</td>';
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
        if ($this->_dcp['obligatorio'] == 'nulo') {
            $obligatorio = 'DEFAULT NULL';
        } else {
            $obligatorio = 'NOT NULL';
        }
        if ($this->_dcp['decimales'] == 0) {
            $tipo_campo = 'int';
            $valor_campo = $this->_dcp['largo'];
        } else {
            $tipo_campo = 'decimal';
            $valor_campo = $this->_dcp['largo'] . ',' . $this->_dcp['decimales'];
        }
        $titulo[0] = '  `' . $this->_dcp['tb_campo'] . '` '.$tipo_campo.'(' . $valor_campo . ') ' . $obligatorio . ',' . "\n";
        $titulo[1] = '`' . $this->_dcp['tb_campo'] . '`, ';
        return $titulo;
    }

}

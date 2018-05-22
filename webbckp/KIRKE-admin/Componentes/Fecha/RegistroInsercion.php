<?php

class Componentes_Fecha_RegistroInsercion {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    
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
        if (is_array($this->_dcp)) {
            $this->_dcp = array_merge($_pv, $this->_dcp);
        } else {
            $this->_dcp = $_pv;
        }
    }

    public function get() {

        if (is_numeric($this->_valor)) {
            $valor = $this->_valor;
        } else {
            $valor = $this->_formateoFechaValorSeparar($this->_valor);
        }

        if ($this->_dcp['predefinir_ultimo_val_cargado'] == 's') {
            setcookie(hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente), Bases_InyeccionSql::consulta($valor, 'numero'), time() + 2592000);
        }
        
        return Bases_InyeccionSql::consulta($valor, 'numero');
    }

    private function _formateoFechaValorSeparar($valor) {

        if ($valor == '') {
            return '';
        }

        if (!isset($this->_dcp['formato_fecha']))
            $this->_dcp['formato_fecha'] = 'ddmmaaaa';

        switch ($this->_dcp['formato_fecha']) {
            case 'ddmmaaaa':
                $fecha['d'] = substr($valor, 0, 2);
                $fecha['m'] = substr($valor, 3, 2);
                $fecha['a'] = substr($valor, 6, 4);
                break;
            case 'ddmmaa':
                $fecha['d'] = substr($valor, 0, 2);
                $fecha['m'] = substr($valor, 3, 2);
                $fecha['a'] = substr($valor, 6, 2);
                break;
            case 'mmddaaaa':
                $fecha['d'] = substr($valor, 3, 2);
                $fecha['m'] = substr($valor, 0, 2);
                $fecha['a'] = substr($valor, 6, 4);
                break;
            case 'mmddaa':
                $fecha['d'] = substr($valor, 3, 2);
                $fecha['m'] = substr($valor, 0, 2);
                $fecha['a'] = substr($valor, 6, 2);
                break;
            case 'aammdd':
                $fecha['d'] = substr($valor, 6, 2);
                $fecha['m'] = substr($valor, 3, 2);
                $fecha['a'] = substr($valor, 0, 2);
                break;
            case 'aaaammdd':
                $fecha['d'] = substr($valor, 8, 2);
                $fecha['m'] = substr($valor, 5, 2);
                $fecha['a'] = substr($valor, 0, 4);
                break;
        }

        if (isset($this->_dcp['mostrar_hora']) && ($this->_dcp['mostrar_hora'] == 's')) {
            $horario = explode(' ', $valor);
            $horario = explode(':', $horario[1]);
            $hora = $horario[0];
            $minutos = $horario[1];
        } else {
            $hora = 0;
            $minutos = 0;
        }

        if (mktime($hora, $minutos, 0, $fecha['m'], $fecha['d'], $fecha['a'])) {
            $fecha_post = mktime($hora, $minutos, 0, $fecha['m'], $fecha['d'], $fecha['a']);
        } else {
            $fecha_post = 0;
        }

        return $fecha_post;
    }

}


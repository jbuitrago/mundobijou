<?php

class Componentes_OpcionMultipleCheck_RegistroExportar extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array();         // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
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
        $valores = $this->_mostrarValor('valores');
        if (is_array($valores)) {
            foreach ($valores as $valor) {
                $this->_valor[] = $valor[$this->_dcp['origen_tb_campo']];
            }
        } else {
            $this->_valor = '-';
        }
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _excel() {
        if (is_array($this->_valor)) {
            $this->_valor = implode('; ', $this->_valor);
        }
        $valor['titulo'] =  str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_titulo, '<a>'));
        $valor['valor'] = '<td bgcolor="#eeeeec">' . str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_valor, '<a>')) . '</td>';
        return $valor; 
    }

    private function _pdf() {
        if (is_array($this->_valor)) {
            $this->_valor = implode('; ', $this->_valor);
        }
        $valor['titulo'] =  $this->_titulo;
        $valor['valor'] = $this->_valor;
        return $valor; 
    }

    private function _cvs() {
        if (is_array($this->_valor)) {
            $this->_valor = implode(', ', str_replace(';', ',', $this->_valor));
        }
        $valor['titulo'] = str_replace("\n", ' ', $this->_titulo);
        $valor['valor'] = str_replace(';', ',', str_replace(array("\r\n", "\n", "\r"), ' ', $this->_valor)) . ';';
        return $valor;
    }
    
    private function _xml() {
        if (is_array($this->_valor)) {
            $dato = '';
            foreach ($this->_valor as $valor) {
                $dato .= '      <dato><![CDATA[' . $valor . ']]></dato>' . "\n";
            }
            $this->_valor = $dato;
        }
        $this->_titulo = strtolower(str_replace(' ', '_', $this->_titulo));
        return '    <'.$this->_titulo.'><![CDATA[' . $this->_valor . ']]></'.$this->_titulo.'>'."\n";
    }
    
    private function _html() {
        if (is_array($this->_valor)) {
            $this->_valor = implode('; ', $this->_valor);
        }
        $valor['titulo'] =  $this->_titulo;
        $valor['valor'] = '<td>' . $this->_valor . '</td>';
        return $valor; 
    }    

    private function _sql() {
        if (is_array($this->_valor)) {
            $this->_valor = implode(' - ', $this->_valor);
        }
        $valor['titulo'] =  $this->_titulo;
        $valor['valor'] = "'" . str_replace("'", '\\\'', $this->_valor) . "', ";
        return $valor; 
    }
    
    private function _sqlEstructura() {
        if ($this->_dcp['obligatorio'] == 'nulo') {
            $obligatorio = 'DEFAULT NULL';
        } else {
            $obligatorio = 'NOT NULL';
        }
        $intermedia_tb_id = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
        $titulo[0] = '  `id_' . $intermedia_tb_id['prefijo'] . '_' . $intermedia_tb_id['nombre'] . '` longtext,' . "\n";
        $titulo[1] = '`id_' . $intermedia_tb_id['prefijo'] . '_' . $intermedia_tb_id['nombre'] . '`, ';
        return $titulo;
    }
    
    private function _mostrarValor($elemento) {

        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
        $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre']);
        $consulta->tablas($intermedia_tb);
        $consulta->tablas($this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']);

        if ($elemento == 'id') {
            $consulta->campos($this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'], "id_" . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']);
        } elseif ($elemento == 'valores') {
            $consulta->campos($this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'], $this->_dcp['origen_tb_campo']);
        }

        $consulta->condiciones('', $intermedia_tb, 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre']);
        $consulta->condiciones('y', $intermedia_tb, 'id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'], 'iguales', $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'], 'id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']);
        $consulta->condiciones('y', $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);

        $valor_origen = $consulta->realizarConsulta();

        return $valor_origen;
    }

}

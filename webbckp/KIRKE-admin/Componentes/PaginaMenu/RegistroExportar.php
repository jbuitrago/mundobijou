<?php

class Componentes_PaginaMenu_RegistroExportar extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array();         // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $_titulo;
    private $_tabla_menu;
    private static $_parametros_tabla_menu = array();

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
        $this->_tabla_menu = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        if (is_array($parametros_tabla)) {
            foreach ($parametros_tabla as $valor) {
                self::$_parametros_tabla_menu[$valor['parametro']] = $valor['valor'];
            }
        }        
        $this->_titulo = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
    }

    public function get() {
        $valores = $this->_mostrarValor($this->_idRegistro);
        if (is_array($valores)) {
            foreach ($valores as $valor) {
                $this->_valor[] = $valor;
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
        $valor['titulo'] = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_titulo, '<a>'));
        $valor['valor'] = '<td bgcolor="#eeeeec">' . str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_valor, '<a>')) . '</td>';
        return $valor;
    }

    private function _pdf() {
        if (is_array($this->_valor)) {
            $this->_valor = implode('; ', $this->_valor);
        }
        $valor['titulo'] = $this->_titulo;
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
        return '    <' . $this->_titulo . '>' . $this->_valor . '</' . $this->_titulo . '>' . "\n";
    }

    private function _html() {
        if (is_array($this->_valor)) {
            $this->_valor = implode('; ', $this->_valor);
        }
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = '<td>' . $this->_valor . '</td>';
        return $valor;
    }

    private function _sql() {
        if (is_array($this->_valor)) {
            $this->_valor = implode(' - ', $this->_valor);
        }
        $valor['titulo'] = $this->_titulo;
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
    
    private function _mostrarValor($id_registro) {
        
        $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu, $id_registro);

        $texto = array();
        if (is_array($matriz_menu)) {
            foreach ($matriz_menu as $linea) {
                $texto[] = $this->_obtenerNombreMenu($linea["nivel1_orden"], $linea["nivel2_orden"], $linea["nivel3_orden"], $linea["nivel4_orden"], $linea["nivel5_orden"], $linea["nivel6_orden"], $linea["nivel7_orden"], $linea["nivel8_orden"], $linea["nivel9_orden"], $linea["nivel10_orden"]) . '<br />';
            }
        }
        return $texto;

    }

    private function _obtenerMatrizMenu($tabla_menu, $id_registro = '', $id_menu = '') {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tabla_menu);
        $consulta->tablas($tabla_menu . '_trd');
        $consulta->campos($tabla_menu, 'id_' . $tabla_menu, 'id_menu');
        $consulta->campos($tabla_menu, 'nivel1_orden');
        $consulta->campos($tabla_menu, 'nivel2_orden');
        $consulta->campos($tabla_menu, 'nivel3_orden');
        $consulta->campos($tabla_menu, 'nivel4_orden');
        $consulta->campos($tabla_menu, 'nivel5_orden');
        $consulta->campos($tabla_menu, 'nivel6_orden');
        $consulta->campos($tabla_menu, 'nivel7_orden');
        $consulta->campos($tabla_menu, 'nivel8_orden');
        $consulta->campos($tabla_menu, 'nivel9_orden');
        $consulta->campos($tabla_menu, 'nivel10_orden');
        $consulta->campos($tabla_menu . '_trd', 'menu_nombre');
        $consulta->campos($tabla_menu . '_rel', 'id_' . $tabla_menu . '_rel', 'cantidad', 'contador');
        $consulta->condiciones('', $tabla_menu, 'id_' . $tabla_menu, 'iguales', $tabla_menu . '_trd', 'id_' . $tabla_menu);
        $consulta->condiciones('y', $tabla_menu . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
        if ($id_registro != '') {
            $consulta->condiciones('y', $tabla_menu . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $id_registro);
        }
        if ($id_menu != '') {
            $consulta->condiciones('y', $tabla_menu . '_rel', 'id_' . $tabla_menu, 'iguales', '', '', $id_menu);
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 10) {
            $consulta->condiciones('y', $tabla_menu, 'nivel10_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 9) {
            $consulta->condiciones('y', $tabla_menu, 'nivel9_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 8) {
            $consulta->condiciones('y', $tabla_menu, 'nivel8_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 7) {
            $consulta->condiciones('y', $tabla_menu, 'nivel7_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 6) {
            $consulta->condiciones('y', $tabla_menu, 'nivel6_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 5) {
            $consulta->condiciones('y', $tabla_menu, 'nivel5_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 4) {
            $consulta->condiciones('y', $tabla_menu, 'nivel4_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 3) {
            $consulta->condiciones('y', $tabla_menu, 'nivel3_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 2) {
            $consulta->condiciones('y', $tabla_menu, 'nivel2_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['niveles_habilitados'] <= self::$_parametros_tabla_menu['numero_niveles']) {
            $consulta->condiciones('y', $tabla_menu, 'nivel' . self::$_parametros_tabla_menu['numero_niveles'] . '_orden', 'no_nulo');
        }
        $consulta->unionIzquierdaTablas('relacion', $tabla_menu, '', $tabla_menu . '_rel');
        $consulta->unionIzquierdaCondiciones('relacion', '', $tabla_menu, 'id_' . $tabla_menu, 'iguales', $tabla_menu . '_rel', 'id_' . $tabla_menu);
        $consulta->grupo($tabla_menu, 'id_' . $tabla_menu);
        $consulta->orden($tabla_menu, 'nivel1_orden');
        $consulta->orden($tabla_menu, 'nivel2_orden');
        $consulta->orden($tabla_menu, 'nivel3_orden');
        $consulta->orden($tabla_menu, 'nivel4_orden');
        $consulta->orden($tabla_menu, 'nivel5_orden');
        $consulta->orden($tabla_menu, 'nivel6_orden');
        $consulta->orden($tabla_menu, 'nivel7_orden');
        $consulta->orden($tabla_menu, 'nivel8_orden');
        $consulta->orden($tabla_menu, 'nivel9_orden');
        $consulta->orden($tabla_menu, 'nivel10_orden');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    private function _obtenerNombreMenu($nivel1_orden, $nivel2_orden, $nivel3_orden, $nivel4_orden, $nivel5_orden, $nivel6_orden, $nivel7_orden, $nivel8_orden, $nivel9_orden, $nivel10_orden) {

        $texto = '';
        $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu);
        foreach ($matriz_menu as $linea) {
            if (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($nivel1_orden != '') &&
                    ($linea['nivel2_orden'] == '')
            ) {
                $texto .= $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($nivel2_orden != '') &&
                    ($linea['nivel3_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($nivel3_orden != '') &&
                    ($linea['nivel4_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($nivel4_orden != '') &&
                    ($linea['nivel5_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($nivel5_orden != '') &&
                    ($linea['nivel6_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($nivel6_orden != '') &&
                    ($linea['nivel7_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($nivel7_orden != '') &&
                    ($linea['nivel8_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($linea['nivel8_orden'] == $nivel8_orden) &&
                    ($nivel8_orden != '') &&
                    ($linea['nivel9_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($linea['nivel8_orden'] == $nivel8_orden) &&
                    ($linea['nivel9_orden'] == $nivel9_orden) &&
                    ($nivel9_orden != '') &&
                    ($linea['nivel10_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($linea['nivel8_orden'] == $nivel8_orden) &&
                    ($linea['nivel9_orden'] == $nivel9_orden) &&
                    ($linea['nivel10_orden'] == $nivel10_orden) &&
                    ($nivel10_orden != '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            }
        }
        return $texto;
    }

}

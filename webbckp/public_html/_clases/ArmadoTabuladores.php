<?php

class ArmadoTabuladores {

    private $_tabla_nombre;
    private $_id_tabla_registro;
    private $_tabla_tabuladores_nombre;
    private $_id_tabla_tabuladores;
    private $_idioma = 'es';
    private $_grupo_tabuladores;
    private $_buscar_nombre = array();
    private $_buscar_valor = array();
    private $_buscar_valor_predefinido = array();

    public function obtenerTabuladores() {

        $clase_de_bd = 'ArmadoTabuladores' . ucfirst(VariableGet::sistema('tipo_base'));
        eval('$parametros_tabla = ' . $clase_de_bd . '::obtenerParametros($this->_id_tabla_tabuladores);');

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        if (isset($parametros['id_cp_rel'])) {
            eval('$tabla_relacionada = ' . $clase_de_bd . '::obtenerTablaNombreComponente(' . $parametros['id_cp_rel'] . ');');
        } else {
            $tabla_relacionada = false;
        }

        if (is_array($tabla_relacionada)) {
            $grupo_tab = ', "' . $tabla_relacionada[0]['prefijo'] . '_' . $tabla_relacionada[0]['tabla_nombre'] . '"';
            if ($this->_grupo_tabuladores !== null) {
                $id_grupo_tab = ', $this->_grupo_tabuladores';
            } else {
                $id_grupo_tab = ', null';
            }
            $grupo_tab_campo = ', "' . $tabla_relacionada[0]['tabla_campo'] . '"';
        } else {
            $grupo_tab = ', null';
            $id_grupo_tab = ', null';
            $grupo_tab_campo = ', null';
        }

        eval('$tabuladores = ' . $clase_de_bd . '::obtenerElementos($this->_tabla_nombre, $this->_id_tabla_registro, $this->_tabla_tabuladores_nombre, $this->_idioma' . $grupo_tab . $id_grupo_tab . $grupo_tab_campo . ');');
        
        if (is_array($tabuladores)) {
            foreach ($tabuladores as $id => $tabulador_dato) {
                if ($tabulador_dato['id_predefinido'] != '0') {
                    eval('$valor_pred = ' . $clase_de_bd . '::obtenerValorPredefinido($this->_tabla_tabuladores_nombre, $this->_idioma, $tabulador_dato[\'id_tabulador\']);');
                    if (isset($tabulador_dato['id_grupo_tabuladores'])) {
                        $tabuladores[$id]['id_grupo_tabuladores'] = $tabulador_dato['id_grupo_tabuladores'];
                    }
                    if (is_array($valor_pred)) {
                        $valores_predefinidos = array();
                        foreach ($valor_pred AS $id_predefinido => $datos) {
                            if ($valor_pred[$id_predefinido]['id'] == $tabulador_dato['id_predefinido']) {
                                $tabuladores[$id]['valor'] = $valor_pred[$id_predefinido]['nombre'];
                            }
                            $valores_predefinidos[$datos['id']] = $datos['nombre'];
                        }
                        $tabuladores[$id]['valores_predefinidos'] = $valores_predefinidos;
                    }
                } else {
                    unset($tabuladores[$id]['id_predefinido']);
                }
            }
        }

        return $tabuladores;
    }

    public function insertarTabulador($id_registro, $id_tabulador, $valor_no_predefinido = '', $id_valor_predefinido = '') {

        $clase_de_bd = 'ArmadoTabuladores' . ucfirst(VariableGet::sistema('tipo_base'));

        eval('$tabuladores = ' . $clase_de_bd . '::insertarTabulador($this->_tabla_tabuladores_nombre, $this->_tabla_nombre, $this->_idioma, $id_tabulador, $valor_no_predefinido, $id_valor_predefinido, $id_registro);');

        return $tabuladores;
    }

    public function actualizarTabulador($id_registro, $id_tabulador, $valor_no_predefinido = '', $id_valor_predefinido = '') {

        $clase_de_bd = 'ArmadoTabuladores' . ucfirst(VariableGet::sistema('tipo_base'));

        eval('$tabuladores = ' . $clase_de_bd . '::actualizarTabulador($this->_tabla_tabuladores_nombre, $this->_tabla_nombre, $this->_idioma, $id_tabulador, $valor_no_predefinido, $id_valor_predefinido, $id_registro);');

        return $tabuladores;
    }

    public function tablaNombre($tabla_nombre) {
        $this->_tabla_nombre = $tabla_nombre;
    }

    public function idRegistroTabla($id_tabla_registro) {
        $this->_id_tabla_registro = $id_tabla_registro;
    }

    public function tablaTabuladoresNombre($tabla_tabuladores_nombre) {
        $this->_tabla_tabuladores_nombre = $tabla_tabuladores_nombre;

        $tabla_nombre_elementos = explode('_', $tabla_tabuladores_nombre);
        $c_tabla_prefijo = array_shift($tabla_nombre_elementos);
        $c_tabla_nombre = implode('_', $tabla_nombre_elementos);
        $clase_de_bd = 'ArmadoTabuladores' . ucfirst(VariableGet::sistema('tipo_base'));
        eval('$c_id_tabla = ' . $clase_de_bd . '::obtenerIdTabla($c_tabla_prefijo, $c_tabla_nombre);');

        $this->_id_tabla_tabuladores = $c_id_tabla[0]['id_tabla'];
    }

    public function idioma($idioma) {
        $this->_idioma = $idioma;
    }

    public function idGrupoTabuladores($grupo_tabuladores) {
        $this->_grupo_tabuladores = $grupo_tabuladores;
    }

    public function buscar() {
        $clase_de_bd = 'ArmadoTabuladores' . ucfirst(VariableGet::sistema('tipo_base'));

        eval('$tabuladores = ' . $clase_de_bd . '::buscar($this->_tabla_nombre, $this->_tabla_tabuladores_nombre, $this->_idioma, $this->_tabla_tabuladores_nombre, $this->_grupo_tabuladores, $this->_buscar_nombre, $this->_buscar_valor_predefinido, $this->_buscar_valor);');

        if(is_array($tabuladores)){
            $id_tabuladores;
            foreach ($tabuladores as $id_tabulador) {
                $id_tabuladores[] = $id_tabulador['id'];
            }
            return $id_tabuladores;
        }else{
            return false;
        }
    }

    public function buscarNombre($dato) {
        $this->_buscar_nombre[] = $dato;
    }

    public function buscarValorPredefinido($dato) {
        $this->_buscar_valor_predefinido[] = $dato;
    }

    public function buscarValor($dato) {
        $this->_buscar_valor[] = $dato;
    }

}

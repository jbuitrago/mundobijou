<?php

class Componentes_OpcionMultiple_RegistroInsercion {

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

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
            $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

            if (isset($_POST['cp_' . $this->_dcp['cp_id']]) && is_array($_POST['cp_' . $this->_dcp['cp_id']])) {
                
                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($intermedia_tb);
                $consulta->campos($intermedia_tb, 'id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'], 'id_campo');
                $consulta->condiciones('', $intermedia_tb, 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
                //$consulta->verConsulta();
                $id_registros_origen_array = $consulta->realizarConsulta();

                if (is_array($id_registros_origen_array)) {
                    $id_registros_origen = array();
                    foreach ($id_registros_origen_array AS $id) {
                        $id_registros_origen[] = $id['id_campo'];
                    }
                }

                if (isset($id_registros_origen)) {
                    $elementos_agregar = array_diff($_POST['cp_' . $this->_dcp['cp_id']], $id_registros_origen);

                    $elementos_eliminar = array_diff($id_registros_origen, $_POST['cp_' . $this->_dcp['cp_id']]);
                    // elimino los valores en la tabla de relacion
                    foreach ($elementos_eliminar as $valor) {
                        $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
                        $consulta->tabla($intermedia_tb);
                        $consulta->condiciones('', $intermedia_tb, 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
                        $consulta->condiciones('y', $intermedia_tb, 'id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'], 'iguales', '', '', $valor);
                        $consulta->realizarConsulta();
                    }
                } else {
                    $elementos_agregar = $_POST['cp_' . $this->_dcp['cp_id']];
                }
                if ($this->_dcp['predefinir_ultimo_val_cargado'] == 's') {
                    setcookie(hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente), implode(",", $elementos_agregar), time() + 2592000);
                }
                // inserto los valores en la tabla de relacion
                $orden_maximo = Consultas_ObtenerRegistroMaximo::armado($intermedia_tb, 'orden');
                $orden_maximo = $orden_maximo[0]['orden'] + 1;
                foreach ($elementos_agregar as $valor) {
                    $campos_registro_crear = array(
                        'orden' => $orden_maximo,
                        'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'] => $this->_idRegistro,
                        'id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'] => $valor,
                    );
                    $id_insertado = Consultas_RegistroCrear::armado(__FILE__, __LINE__, $intermedia_tb, $campos_registro_crear);
                    $orden_maximo++;
                }
            } else {

                $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
                $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

                // elimino el campo de la tabla
                Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $intermedia_tb, 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], $this->_idRegistro);
                
                unset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)]);
            }
        }

        return false;
    }

}

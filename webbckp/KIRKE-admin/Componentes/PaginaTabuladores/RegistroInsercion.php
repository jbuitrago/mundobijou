<?php

class Componentes_PaginaTabuladores_RegistroInsercion {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array();         // parametros del componente
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $_tabla_tabuladores;
    private $_tabla_tabuladores_nombre_idioma;
    private static $_parametros_tabla_tabuladores = array();
    private $_tb_origen;
    private $_id_cp_rel_destino;

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
        $this->_tabla_tabuladores = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        $tabla_tabuladores_nombre_idioma = Consultas_TablaNombreIdioma::RegistroConsulta(__FILE__, __LINE__, Generales_Idioma::obtener(), $this->_dcp['id_tabla']);
        $this->_tabla_tabuladores_nombre_idioma = $tabla_tabuladores_nombre_idioma[0]['tabla_nombre_idioma'];
    }

    public function get() {

        $id_cp_rel_destino = '';
        if (isset($this->_dcp['id_cp_rel'])) {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_componente_parametro');
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_componente_parametro', 'id_componente');
            $consulta->condiciones('', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', 'origen_cp_id');
            $consulta->condiciones('y', 'kirke_componente_parametro', 'valor', 'iguales', '', '', $this->_dcp['id_cp_rel']);
            $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', 'kirke_componente', 'id_componente');
            $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', '', '', $this->_dcp['tb_id']);
            //$consulta->verConsulta();
            $id_cp_rel_destino = $consulta->realizarConsulta();
            $id_cp_rel_destino = $id_cp_rel_destino[0]['id_componente'];
        }

        if (!isset($this->_dcp['id_cp_rel']) || (isset($this->_dcp['id_cp_rel']) && ($_POST['cp_' . $id_cp_rel_destino] != ''))) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos();
            $matriz_tabuladores_valores = $this->_obtenerMatrizTabuladoresValores();

            $mostrar = '';
            if (is_array($matriz_tabuladores)) {

                foreach ($matriz_tabuladores as $datos) {

                    if (isset($datos['tab_val_pred'])) {

                        if (
                                !isset($matriz_tabuladores_valores[$datos['tab_id']]['id_tab_prd']) &&
                                ($_POST[$id_campo . '_' . $datos['tab_id']] != '')
                        ) {
                            $orden = Consultas_ObtenerRegistroMaximo::armado($this->_tabla_tabuladores . '_rel', 'orden');
                            $orden = $orden[0]['orden'] + 1;
                            $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
                            $consulta->tabla($this->_tabla_tabuladores . '_rel');
                            $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'orden', $orden);
                            $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, $datos['tab_id']);
                            $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], $this->_idRegistro);
                            $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'id_tab_prd', $_POST[$id_campo . '_' . $datos['tab_id']]);
                            //$consulta->verConsulta();
                            $consulta->realizarConsulta();
                        } elseif (
                                (!isset($_POST[$id_campo . '_' . $datos['tab_id']]) ||
                                ($_POST[$id_campo . '_' . $datos['tab_id']] == '') ) &&
                                isset($matriz_tabuladores_valores[$datos['tab_id']]['id_tab_prd'])
                        ) {
                            $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
                            $consulta->tabla($this->_tabla_tabuladores . '_rel');
                            $consulta->condiciones('', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, 'iguales', '', '', $datos['tab_id']);
                            $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
                            //$consulta->verConsulta();
                            $consulta->realizarConsulta();
                        } elseif (
                                ($_POST[$id_campo . '_' . $datos['tab_id']] != '') &&
                                isset($matriz_tabuladores_valores[$datos['tab_id']]['id_tab_prd']) &&
                                ($_POST[$id_campo . '_' . $datos['tab_id']] != $matriz_tabuladores_valores[$datos['tab_id']]['id_tab_prd'])
                        ) {
                            $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
                            $consulta->tabla($this->_tabla_tabuladores . '_rel');
                            $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'id_tab_prd', $_POST[$id_campo . '_' . $datos['tab_id']]);
                            $consulta->condiciones('', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, 'iguales', '', '', $datos['tab_id']);
                            $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
                            //$consulta->verConsulta();
                            $consulta->realizarConsulta();
                        }

                    } else {

                        foreach (Inicio::confVars('idiomas') as $idioma_v) {

                            if (
                                    !isset($matriz_tabuladores_valores[$datos['tab_id']][$idioma_v]) &&
                                    ($_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v] != '')
                            ) {
                                $orden = Consultas_ObtenerRegistroMaximo::armado($this->_tabla_tabuladores . '_rel', 'orden');
                                $orden = $orden[0]['orden'] + 1;
                                $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
                                $consulta->tabla($this->_tabla_tabuladores . '_rel');
                                $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'orden', $orden);
                                $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, $datos['tab_id']);
                                $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], $this->_idRegistro);
                                $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'idioma', $idioma_v);
                                $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'tabulador', $_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v]);
                                //$consulta->verConsulta();
                                $consulta->realizarConsulta();
                            } elseif (
                                    (!isset($_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v]) ||
                                    ($_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v] == '') ) &&
                                    isset($matriz_tabuladores_valores[$datos['tab_id']][$idioma_v])
                            ) {
                                $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
                                $consulta->tabla($this->_tabla_tabuladores . '_rel');
                                $consulta->condiciones('', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, 'iguales', '', '', $datos['tab_id']);
                                $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
                                $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'idioma', 'iguales', '', '', $idioma_v);
                                //$consulta->verConsulta();
                                $consulta->realizarConsulta();
                            } elseif (
                                    ($_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v] != '') &&
                                    isset($matriz_tabuladores_valores[$datos['tab_id']][$idioma_v]) &&
                                    ($_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v] != $matriz_tabuladores_valores[$datos['tab_id']][$idioma_v])
                            ) {
                                $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
                                $consulta->tabla($this->_tabla_tabuladores . '_rel');
                                $consulta->campoValor($this->_tabla_tabuladores . '_rel', 'tabulador', $_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $idioma_v]);
                                $consulta->condiciones('', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, 'iguales', '', '', $datos['tab_id']);
                                $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
                                $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'idioma', 'iguales', '', '', $idioma_v);
                                //$consulta->verConsulta();
                                $consulta->realizarConsulta();
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

    private function _obtenerMatrizTabuladoresTitulos() {

        if (isset($this->_dcp['id_cp_rel'])) {
            $this->_obtenerTablaOrigen();
        }

        $id_cp_rel_destino = '';
        if (isset($this->_dcp['id_cp_rel'])) {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_componente_parametro');
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_componente_parametro', 'id_componente');
            $consulta->condiciones('', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', 'origen_cp_id');
            $consulta->condiciones('y', 'kirke_componente_parametro', 'valor', 'iguales', '', '', $this->_dcp['id_cp_rel']);
            $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', 'kirke_componente', 'id_componente');
            $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', '', '', $this->_dcp['tb_id']);
            //$consulta->verConsulta();
            $id_cp_rel_destino = $consulta->realizarConsulta();
            $id_cp_rel_destino = $id_cp_rel_destino[0]['id_componente'];
        }

        if (!isset($this->_dcp['id_cp_rel']) || (isset($this->_dcp['id_cp_rel']) && ($_POST['cp_' . $id_cp_rel_destino] != ''))) {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->_tabla_tabuladores);
            $consulta->tablas($this->_tabla_tabuladores . '_trd');
            $consulta->campos($this->_tabla_tabuladores . '_trd', 'id_' . $this->_tabla_tabuladores, 'tab_id');
            $consulta->campos($this->_tabla_tabuladores . '_trd', 'tabulador_nombre', 'tab_nombre');
            $consulta->condiciones('', $this->_tabla_tabuladores, 'id_' . $this->_tabla_tabuladores, 'iguales', $this->_tabla_tabuladores . '_trd', 'id_' . $this->_tabla_tabuladores);
            $consulta->condiciones('y', $this->_tabla_tabuladores . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
            if (isset($this->_dcp['id_cp_rel'])) {
                // controlo si recibo el valor post del campo relacionado con el origen.
                $consulta->condiciones('y', $this->_tabla_tabuladores, 'id_' . $this->_tb_origen, 'iguales', '', '', $_POST['cp_' . $id_cp_rel_destino]);
            }
            $consulta->orden($this->_tabla_tabuladores, 'orden');
            $consulta->orden($this->_tabla_tabuladores . '_trd', 'idioma');
            //$consulta->verConsulta();
            $matriz = $consulta->realizarConsulta();

            // consulto los valores predefinidos
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->_tabla_tabuladores);
            $consulta->tablas($this->_tabla_tabuladores . '_prd');
            $consulta->campos($this->_tabla_tabuladores . '_prd', 'id_' . $this->_tabla_tabuladores, 'tab_id');
            $consulta->campos($this->_tabla_tabuladores . '_prd', 'id_tab_prd');
            $consulta->campos($this->_tabla_tabuladores . '_prd', 'valor');
            $consulta->condicionesAgrupacionInicio();
            $consulta->condiciones('', $this->_tabla_tabuladores . '_prd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
            $consulta->condiciones('o', $this->_tabla_tabuladores . '_prd', 'idioma', 'iguales', '', '', 'multi');
            $consulta->condicionesAgrupacionFin();
            $consulta->condiciones('y', $this->_tabla_tabuladores, 'id_' . $this->_tabla_tabuladores, 'iguales', $this->_tabla_tabuladores . '_prd', 'id_' . $this->_tabla_tabuladores);
            if (isset($this->_dcp['id_cp_rel'])) {
                // controlo si recibo el valor post del campo relacionado con el origen.
                $consulta->condiciones('y', $this->_tabla_tabuladores, 'id_' . $this->_tb_origen, 'iguales', '', '', $_POST['cp_' . $id_cp_rel_destino]);
            }
            // no hacer falta 'orden', porque se ordena mas abajo en '$matriz_tabuladores'
            $consulta->orden($this->_tabla_tabuladores, 'orden');
            $consulta->orden($this->_tabla_tabuladores . '_prd', 'idioma');
            $consulta->orden($this->_tabla_tabuladores . '_prd', 'id_tab_prd');
            //$consulta->verConsulta();
            $matriz_val_pred = $consulta->realizarConsulta();

            $matriz_tabuladores = array();

            if (is_array($matriz)) {
                $orden = 0;
                foreach ($matriz as $datos) {
                    $matriz_tabuladores[$orden]['tab_id'] = $datos['tab_id'];
                    $matriz_tabuladores[$orden]['tab_nombre'] = $datos['tab_nombre'];
                    if (is_array($matriz_val_pred)) {
                        foreach ($matriz_val_pred as $val_pred_id => $val_pred) {
                            // agrego los valores predefinidos
                            if ($val_pred['tab_id'] == $datos['tab_id']) {
                                $matriz_tabuladores[$orden]['tab_val_pred'][$val_pred['id_tab_prd']] = $val_pred['valor'];
                                unset($matriz_val_pred[$val_pred_id]);
                            }
                        }
                    }
                    $orden++;
                }
            }
            return $matriz_tabuladores;
        }
    }

    private function _obtenerMatrizTabuladoresValores() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tabla_tabuladores);
        $consulta->tablas($this->_tabla_tabuladores . '_rel');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores, 'tab_id');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'tabulador', 'tab_valor');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'idioma', 'tab_idioma');
        $consulta->campos($this->_tabla_tabuladores . '_rel', 'id_tab_prd');
        $consulta->condiciones('', $this->_tabla_tabuladores, 'id_' . $this->_tabla_tabuladores, 'iguales', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_tabla_tabuladores);
        $consulta->condiciones('y', $this->_tabla_tabuladores . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        $matriz_tabuladores = array();

        if (is_array($matriz)) {
            foreach ($matriz as $datos) {
                if ($datos['id_tab_prd'] == '0') {
                    $matriz_tabuladores[$datos['tab_id']][$datos['tab_idioma']] = $datos['tab_valor'];
                } else {
                    $matriz_tabuladores[$datos['tab_id']]['id_tab_prd'] = $datos['id_tab_prd'];
                }
            }
        }

        return $matriz_tabuladores;
    }

    private function _obtenerTablaOrigen() {
        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->tablas('kirke_componente');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_componente', 'id_tabla');
        $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'iguales', '', '', $this->_dcp['id_cp_rel']);
        $id_tabla_cp_rel = $consulta->realizarConsulta();
        $this->_tb_origen = $id_tabla_cp_rel[0]['prefijo'] . '_' . $id_tabla_cp_rel[0]['tabla_nombre'];
    }

}

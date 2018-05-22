<?php

class Componentes_PaginaTabuladores_RegistroVer extends Armado_Plantilla {

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
        $this->_dcp = array_merge($_pv, $this->_dcp);
        $this->_tabla_tabuladores = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        $tabla_tabuladores_nombre_idioma = Consultas_TablaNombreIdioma::RegistroConsulta(__FILE__, __LINE__, Generales_Idioma::obtener(), $this->_dcp['id_tabla']);
        $this->_tabla_tabuladores_nombre_idioma = $tabla_tabuladores_nombre_idioma[0]['tabla_nombre_idioma'];
        $this->_id_cp_rel_destino = $this->_idCpRelDestino();
    }

    public function get() {
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _registroValor() {
        return false;
    }

    private function _registroListadoCabezal() {
        return false;
    }

    private function _registroListadoCuerpo() {
        return false;
    }

    private function _registroListadoPie() {
        return false;
    }

    private function _registroVer() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($this->_idGrupoTabuladoresBD());

        $matriz_tabuladores_valores = $this->_obtenerMatrizTabuladoresValores();

        $mostrar = '';
        if (is_array($matriz_tabuladores)) {
            foreach ($matriz_tabuladores as $datos) {

                $etiqueta = '';
                foreach (Inicio::confVars('idiomas') as $value) {
                    if (isset($matriz_tabuladores_valores[$datos['tab_id']][$value])) {
                        $etiqueta .= '<div class="tabuladores_col_izq">' . $matriz_tabuladores_valores[$datos['tab_id']][$value] . '</div><div class="tabuladores_col_der">{TR|o_traduccion_' . $value . '}</div>';
                    } else {
                        $etiqueta .= '<div class="tabuladores_col_izq"><span class="texto_claro">&lt; {TR|m_sin_valor} &gt;</span></div><div class="tabuladores_col_der">{TR|o_traduccion_' . $value . '}</div>';
                    }
                }

                $mostrar .= '<div class="contenido_titulo">' . $datos['tab_nombre'] . ':</div> <div class="contenido_7">' . $etiqueta . '</div>';
            }
        }
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroAlta() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $mostrar_js = '';
        if (isset($this->_dcp['id_cp_rel'])) {

            if (!isset($_GET['id_grupo_tabuladores'])) {

                $mostrar_js = '
                <script type="text/javascript">
                  $(document).ready(function() {
                      $("#cp_' . $this->_id_cp_rel_destino . '").change(function() {
                          var var_url = "index.php?kk_generar=0&accion=37&id_tabla=' . $_GET['id_tabla'] . '&id_grupo_tabuladores="+$(this).val();
                          $.ajax({
                              url: var_url,
                              success: function(data) {
                                  $("#PaginaTabuladores_' . $this->_idComponente . '").html(data);
                              }
                          });
                      });
                  });
                </script>
                ';

                if (isset($_POST['cp_' . $this->_id_cp_rel_destino])) {
                    $imprimir_datos = true;
                } else {
                    return $this->_tituloYComponente('<div id="PaginaTabuladores_' . $this->_idComponente . '"><span class="texto_claro">&lt; {TR|m_sin_valor} &gt;</span></div>' . $mostrar_js);
                }
            } else {
                // muestra del contenido para javascript
                $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($_GET['id_grupo_tabuladores']);

                $mostrar = '';
                if (is_array($matriz_tabuladores)) {
                    foreach ($matriz_tabuladores as $datos) {
                        if (isset($datos['tab_val_pred'])) {
                            if (isset($_POST[$id_campo . '_' . $datos['tab_id']])) {
                                $valor_seleccionado = $_POST[$id_campo . '_' . $datos['tab_id']];
                            } else {
                                $valor_seleccionado = '';
                            }
                            $etiqueta = $this->_selectValores($id_campo . '_' . $datos['tab_id'], $datos['tab_val_pred'], $valor_seleccionado);
                        } else {
                            if (isset($_POST[$id_campo])) {
                                foreach (Inicio::confVars('idiomas') as $value) {
                                    $datos_idiomas_cargados[$value] = $_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $value];
                                }
                                $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id'], $datos_idiomas_cargados);
                            } else {
                                $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id']);
                            }
                        }

                        $mostrar .= '<div class="contenido_titulo">' . $datos['tab_nombre'] . '</div><div class="contenido_7">' . $etiqueta . '</div>';
                    }
                }

                // traduccion del componente
                if (file_exists(Inicio::path() . '/Componentes/' . $this->_nombreComponente . '/' . Generales_Idioma::obtener() . '.php')) {
                    $archivo_de_idioma = Generales_Idioma::obtener();
                } else {
                    $idiomas = Inicio::confVars('idiomas');
                    $archivo_de_idioma = $idiomas[0];
                }

                $componente_traducido = self::traduccion($mostrar, Inicio::path() . '/Componentes/' . $this->_nombreComponente . '/', $archivo_de_idioma, '/\{TR\|([a-z])_(.*?)\}/');

                die($componente_traducido);
            }
        }

        if (!isset($this->_dcp['id_cp_rel']) || $imprimir_datos) {

            if (!isset($_POST['cp_' . $this->_id_cp_rel_destino])) {
                $id_grupo_tabuladores = false;
            } else {
                $id_grupo_tabuladores = $_POST['cp_' . $this->_id_cp_rel_destino];
            }

            $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($id_grupo_tabuladores);

            $mostrar = '<div id="PaginaTabuladores_' . $this->_idComponente . '">';
            if (is_array($matriz_tabuladores)) {
                foreach ($matriz_tabuladores as $datos) {
                    if (isset($datos['tab_val_pred'])) {
                        if (isset($_POST[$id_campo . '_' . $datos['tab_id']])) {
                            $valor_seleccionado = $_POST[$id_campo . '_' . $datos['tab_id']];
                        } else {
                            $valor_seleccionado = '';
                        }
                        $etiqueta = $this->_selectValores($id_campo . '_' . $datos['tab_id'], $datos['tab_val_pred'], $valor_seleccionado);
                    } else {
                        if (isset($_POST)) {
                            foreach (Inicio::confVars('idiomas') as $value) {
                                if (isset($_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $value])) {
                                    $datos_idiomas_cargados[$value] = $_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $value];
                                } else {
                                    $datos_idiomas_cargados[$value] = '';
                                }
                            }
                            $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id'], $datos_idiomas_cargados);
                        } else {
                            $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id']);
                        }
                    }

                    $mostrar .= '<div class="contenido_titulo">' . $datos['tab_nombre'] . '</div><div class="contenido_7">' . $etiqueta . '</div>';
                }
            }
            $mostrar .= '</div>';
            return $this->_tituloYComponente($mostrar_js . $mostrar);
        }
    }

    private function _registroAltaPrevia() {
        return $this->_vistaPrevia();
    }

    private function _registroModificacion() {
        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $mostrar_js = '';
        if (isset($this->_dcp['id_cp_rel'])) {

            if (!isset($_GET['id_grupo_tabuladores'])) {

                $mostrar_js = '
                <script type="text/javascript">
                  $(document).ready(function() {
                      $("#cp_' . $this->_id_cp_rel_destino . '").change(function() {
                          var var_url = "index.php?kk_generar=0&accion=37&id_tabla=' . $_GET['id_tabla'] . '&id_grupo_tabuladores="+$(this).val();
                          $.ajax({
                              url: var_url,
                              success: function(data) {
                                  $("#PaginaTabuladores_' . $this->_idComponente . '").html(data);
                              }
                          });
                      });
                  });
                </script>
                ';

                if (isset($_POST['cp_' . $this->_id_cp_rel_destino]) || ($this->_idGrupoTabuladoresBD() !== false)) {
                    $imprimir_datos = true;
                } else {
                    return $this->_tituloYComponente('<div id="PaginaTabuladores_' . $this->_idComponente . '"><span class="texto_claro">&lt; {TR|m_sin_valor} &gt;</span></div>' . $mostrar_js);
                }
            } else {
                // muestra del contenido para javascript

                $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($_GET['id_grupo_tabuladores']);

                $mostrar = '';
                if (is_array($matriz_tabuladores)) {
                    foreach ($matriz_tabuladores as $datos) {
                        if (isset($datos['tab_val_pred'])) {
                            if (isset($_POST[$id_campo . '_' . $datos['tab_id']])) {
                                $valor_seleccionado = $_POST[$id_campo . '_' . $datos['tab_id']];
                            } else {
                                $valor_seleccionado = '';
                            }
                            $etiqueta = $this->_selectValores($id_campo . '_' . $datos['tab_id'], $datos['tab_val_pred'], $valor_seleccionado);
                        } else {
                            if (isset($_POST[$id_campo])) {
                                foreach (Inicio::confVars('idiomas') as $value) {
                                    $datos_idiomas_cargados[$value] = $_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $value];
                                }
                                $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id'], $datos_idiomas_cargados);
                            } else {
                                $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id']);
                            }
                        }
                        $mostrar .= '<div class="contenido_titulo">' . $datos['tab_nombre'] . '</div><div class="contenido_7">' . $etiqueta . '</div>';
                    }
                }

                // traduccion del componente
                if (file_exists(Inicio::path() . '/Componentes/' . $this->_nombreComponente . '/' . Generales_Idioma::obtener() . '.php')) {
                    $archivo_de_idioma = Generales_Idioma::obtener();
                } else {
                    $idiomas = Inicio::confVars('idiomas');
                    $archivo_de_idioma = $idiomas[0];
                }

                $componente_traducido = self::traduccion($mostrar, Inicio::path() . '/Componentes/' . $this->_nombreComponente . '/', $archivo_de_idioma, '/\{TR\|([a-z])_(.*?)\}/');

                die($componente_traducido);
            }
        }

        if (!isset($this->_dcp['id_cp_rel']) || $imprimir_datos) {

            if ($this->_idGrupoTabuladoresBD() !== false) {
                $id_grupo_tabuladores = $this->_idGrupoTabuladoresBD();
            } elseif (!isset($_POST['cp_' . $this->_id_cp_rel_destino])) {
                $id_grupo_tabuladores = false;
            } else {
                $id_grupo_tabuladores = $_POST['cp_' . $this->_id_cp_rel_destino];
            }

            $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($id_grupo_tabuladores);

            $matriz_tabuladores_valores = $this->_obtenerMatrizTabuladoresValores();

            $mostrar = '<div id="PaginaTabuladores_' . $this->_idComponente . '">';
            if (is_array($matriz_tabuladores)) {
                foreach ($matriz_tabuladores as $datos) {
                    if (isset($datos['tab_val_pred'])) {
                        if (isset($_POST[$id_campo . '_' . $datos['tab_id']])) {
                            $valor_seleccionado = $_POST[$id_campo . '_' . $datos['tab_id']];
                        } else {
                            if (isset($matriz_tabuladores_valores[$datos['tab_id']]['id_tab_prd'])) {
                                $valor_seleccionado = $matriz_tabuladores_valores[$datos['tab_id']]['id_tab_prd'];
                            } else {
                                $valor_seleccionado = '';
                            }
                        }
                        $etiqueta = $this->_selectValores($id_campo . '_' . $datos['tab_id'], $datos['tab_val_pred'], $valor_seleccionado);
                    } else {
                        if (isset($_POST) && (count($_POST) > 0)) {
                            foreach (Inicio::confVars('idiomas') as $value) {
                                $datos_idiomas_cargados[$value] = $_POST[$id_campo . '_' . $datos['tab_id'] . '_' . $value];
                            }
                        } else {
                            foreach (Inicio::confVars('idiomas') as $value) {
                                if (isset($matriz_tabuladores_valores[$datos['tab_id']][$value])) {
                                    $datos_idiomas_cargados[$value] = $matriz_tabuladores_valores[$datos['tab_id']][$value];
                                } else {
                                    $datos_idiomas_cargados[$value] = '';
                                }
                            }
                        }
                        $etiqueta = Armado_EtiquetaIdiomas::armar($id_campo . '_' . $datos['tab_id'], $datos_idiomas_cargados);
                    }
                    $mostrar .= '<div class="contenido_titulo">' . $datos['tab_nombre'] . '</div><div class="contenido_7">' . $etiqueta . '</div>';
                }
            }
            $mostrar .= '</div>';
            return $this->_tituloYComponente($mostrar_js . $mostrar);
        }
    }

    private function _registroModificacionPrevia() {
        return $this->_vistaPrevia();
    }

    private function _registroFiltroCampo() {

        return false;
    }

    private function _obtenerMatrizTabuladoresTitulos($id_grupo_tabuladores = false) {

        if (isset($this->_dcp['id_cp_rel'])) {
            $this->_obtenerTablaOrigen();
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tabla_tabuladores);
        $consulta->tablas($this->_tabla_tabuladores . '_trd');
        $consulta->campos($this->_tabla_tabuladores . '_trd', 'id_' . $this->_tabla_tabuladores, 'tab_id');
        $consulta->campos($this->_tabla_tabuladores . '_trd', 'tabulador_nombre', 'tab_nombre');
        $consulta->condiciones('', $this->_tabla_tabuladores, 'id_' . $this->_tabla_tabuladores, 'iguales', $this->_tabla_tabuladores . '_trd', 'id_' . $this->_tabla_tabuladores);
        $consulta->condiciones('y', $this->_tabla_tabuladores . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
        if ($id_grupo_tabuladores !== false) {
            // controlo si recibo el valor post del campo relacionado con el origen.
            $consulta->condiciones('y', $this->_tabla_tabuladores, 'id_' . $this->_tb_origen, 'iguales', '', '', $id_grupo_tabuladores);
        }
        // no hacer falta 'orden', porque se ordena mas abajo en '$matriz_tabuladores'
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
        if ($id_grupo_tabuladores !== false) {
            // controlo si recibo el valor post del campo relacionado con el origen.
            $consulta->condiciones('y', $this->_tabla_tabuladores, 'id_' . $this->_tb_origen, 'iguales', '', '', $id_grupo_tabuladores);
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

    private function _idCpRelDestino() {

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
            return $id_cp_rel_destino[0]['id_componente'];
        } else {
            return '';
        }
    }

    // metodos especiales
    private function _tituloYComponente($mostrar) {

        $plantilla['mostrar'] = $mostrar;

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['id_tabla'], 'sin_idioma');
            $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $intermedia_tb . '.id_' . $this->_tabla_tabuladores . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }
        
        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _vistaPrevia() {

        $mostrar = '';
        if (!isset($this->_dcp['id_cp_rel']) || (isset($this->_dcp['id_cp_rel']) && ($_POST['cp_' . $this->_id_cp_rel_destino] != ''))) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            if (isset($this->_dcp['id_cp_rel']) && ($_POST['cp_' . $this->_id_cp_rel_destino] != '')) {
                $id_grupo_tabuladores = $_POST['cp_' . $this->_id_cp_rel_destino];
            } else {
                $id_grupo_tabuladores = false;
            }

            $matriz_tabuladores = $this->_obtenerMatrizTabuladoresTitulos($id_grupo_tabuladores);
            if (is_array($matriz_tabuladores)) {
                foreach ($matriz_tabuladores as $datos) {
                    if (isset($datos['tab_val_pred'])) {
                        $campo = $id_campo . '_' . $datos['tab_id'];
                        if (isset($_POST[$campo]) && ($_POST[$campo] != '')) {
                            $valor = Generales_Post::obtener($_POST[$campo], 'h');
                            $texto = $datos['tab_val_pred'][Generales_Post::obtener($_POST[$campo], 'h')];
                        } else {
                            $valor = '';
                            $texto = '<span class="texto_claro">&lt; {TR|m_sin_valor} &gt;</span>';
                        }
                        $etiqueta = $texto . '<input type="hidden" name="' . $campo . '" id="' . $campo . '" value="' . $valor . '" /><br />';
                    } else {
                        foreach (Inicio::confVars('idiomas') as $value) {
                            $campo = $id_campo . '_' . $datos['tab_id'] . '_' . $value;
                            if (isset($_POST[$campo]) && ($_POST[$campo] != '')) {
                                $valor = Generales_Post::obtener($_POST[$campo], 'h');
                                $texto = $valor;
                            } else {
                                $valor = '';
                                $texto = '<span class="texto_claro">&lt; {TR|m_sin_valor} &gt;</span>';
                            }
                            $etiqueta = $texto . '<input type="hidden" name="' . $campo . '" id="' . $campo . '" value="' . $valor . '" /><br />';
                        }
                    }
                    $mostrar .= '<div class="contenido_titulo">' . $datos['tab_nombre'] . '</div><div class="contenido_7">' . $etiqueta . '</div>';
                }
            }
        } elseif (isset($this->_dcp['id_cp_rel']) && ($_POST['cp_' . $this->_id_cp_rel_destino] == '')) {

            $compontente_nombre = Consultas_ComponenteParametro::RegistroConsulta(__FILE__, __LINE__, $this->_id_cp_rel_destino, 'idioma_' . Generales_Idioma::obtener());
            $mostrar = '{TR|o_para_agregar_valores_en_tabuladores_debe_seleccionar_el_siguiente_campo}: "' . $compontente_nombre[0]['valor'] . '"';
        }
        return $this->_tituloYComponente($mostrar);
    }

    private function _idGrupoTabuladoresBD() {

        $cp_rel_destino = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $this->_id_cp_rel_destino);

        if (!isset($cp_rel_destino[0]['tabla_campo'])) {
            return false;
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre']);
        $consulta->campos($this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], $cp_rel_destino[0]['tabla_campo'], 'id_cp_rel_destino');
        $consulta->condiciones('', $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);
        //$consulta->verConsulta();
        $id_cp_rel_destino = $consulta->realizarConsulta();

        if (isset($id_cp_rel_destino[0]['id_cp_rel_destino'])) {
            return $id_cp_rel_destino[0]['id_cp_rel_destino'];
        } else {
            return false;
        }
    }

    private function _selectValores($nombre, $datos, $seleccionado) {

        $ver = '<select name="' . $nombre . '">';
        if (is_array($datos)) {
            foreach ($datos as $id => $valor) {
                if ($id != $seleccionado) {
                    $selected = '';
                } else {
                    $selected = 'selected';
                }
                $ver .= '<option value="' . $id . '" ' . $selected . '>' . $valor . '</option>';
            }
            $ver .= '</select>';
        }

        return $ver;
    }

}

<?php

class Acciones_RegistroListado extends Armado_Plantilla {

    private $_tablaNombre;
    private $_tablaTipo;
    private $_matrizComponentes = array();
    private $_validacion;
    private $_destinoIdCp = array(); // link a componente de destino
    private $_tablas_origen = array();
    private $_tablas_origen_cond = array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $this->_validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'ver');

        if (!isset($_GET['id_link'])) {
            Generales_FiltrosOrden::actualizarIdAccion();
        }

        Generales_FiltrosOrden::armar();
        Armado_Titulo::forzarTitulo(Generales_FiltrosOrden::obtenerMenuLinkTitulo());

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        if (isset($_GET['filtro'])) {
            $mostar_filtros = 'si';
        } else {
            $mostar_filtros = 'no';
        }

        // se obtiene el nombre de la pagina
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();

        $this->_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $this->_tablaTipo = $datos_tabla['tipo'];

        if ($this->_tablaTipo == 'registros') {
            // modificacion de orden de los elementos de la tabla
            if (isset($_GET['orden_act']) && ($_GET['orden_act'] != '') && ($_GET['id_orden_act'] != '')
            ) {
                if (
                        isset($_GET['orden_ant']) && ($_GET['orden_ant'] != '') && ($_GET['id_orden_ant'] != '')
                ) {
                    Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_ant'], 'orden', $_GET['orden_act']);
                    Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_act'], 'orden', $_GET['orden_ant']);
                } elseif (
                        isset($_GET['orden_sig']) && ($_GET['orden_sig'] != '') && ($_GET['id_orden_sig'] != '')
                ) {
                    Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_sig'], 'orden', $_GET['orden_act']);
                    Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_act'], 'orden', $_GET['orden_sig']);
                }
            }
        }

        if (($this->_tablaTipo == 'registros') || ($this->_tablaTipo == 'variables')) {
            $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla', $mostar_filtros));

            // listado de componentes
            $this->_matrizComponentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

            if (!is_array($this->_matrizComponentes)) {
                return '<div class="texto_advertencia_pagina" align="center">{TR|o_la_pagina_no_tiene_componentes_asignados}</div>';
            } else {

                // llamo al metodo del tipo de tabla correspondiente
                $agregara_registro = '_verRegistros' . ucwords($this->_tablaTipo);
                return $this->$agregara_registro();
            }
        } elseif ($this->_tablaTipo == 'menu') {

            $armado_botonera = new Armado_Botonera();

            $parametros = array('kk_generar' => '0', 'accion' => '67', 'id_tabla' => $_GET['id_tabla']);
            $armado_botonera->armar('guardar', $parametros);

            $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));
            return $this->_verRegistrosMenu();
        } elseif ($this->_tablaTipo == 'tabuladores') {

            $armado_botonera = new Armado_Botonera();

            if (isset($_GET['id_registro'])) {
                $id_registro = $_GET['id_registro'];
            } elseif (isset($_GET['extra'])) {
                $id_registro = $_GET['extra'];
            } else {
                $id_registro = '';
            }

            if (isset($_GET['id_tabulador'])) {
                $id_tabulador = $_GET['id_tabulador'];
            } else {
                $id_tabulador = '';
            }

            $parametros = array('kk_generar' => '0', 'accion' => '70', 'id_tabla' => $_GET['id_tabla'], 'id_registro' => $id_registro, 'id_tabulador' => $id_tabulador);
            $armado_botonera->armar('guardar', $parametros);

            if ($id_tabulador != '') {
                $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'id_registro' => $id_registro);
                $armado_botonera->armar('nuevo', $parametros);
            }

            $parametros = array('kk_generar' => '0', 'accion' => '30');
            $armado_botonera->armar('volver_punteado', $parametros, 'listado_de_paginas', 's');

            $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla_formulario'));
            return $this->_verRegistrosTabuladores();
        }
    }

    // tipo de tabla registros

    private function _verRegistrosRegistros() {

        // muestro los filtros de datos
        // elimino los filtros $_POST anteriores
        if (isset($_GET['eliminar_filtros']) && ($_GET['eliminar_filtros'] == 'si')) {
            Generales_FiltrosOrden::filtrosPostEliminar();
        }

        // armado de filtros de datos y envio a la plantilla
        if (isset($_GET['filtro']) && ($_GET['filtro'] == 'si')) {
            $this->_armadoPlantillaSet('filtros', Armado_FiltrosPaginas::armado());
        }

        $this->_armadoPlantillaSet('filtro_general', Armado_FiltroGeneral::armado());

        if (!isset($_GET['vista'])) {

            $armado_botonera = new Armado_Botonera();

            if (($this->_validacion == 'datos') || ($this->_validacion == 'insercion')) {
                $parametros = array('kk_generar' => '0', 'accion' => '37', 'id_tabla' => $_GET['id_tabla']);
                $armado_botonera->armar('nuevo', $parametros);
            }

            // armado de links de relaciones entre paginas para volver a la
            // pagina que la linkeo
            if (Armado_LinkADestino::armadoVolver() !== false) {
                $parametros = Armado_LinkADestino::armadoVolver();
                $armado_botonera->armar('volver_a_registros', $parametros, '', 's');
            }

            if (Inicio::usuario('tipo') == 'administrador general') {
                $parametros = array('kk_generar' => '0', 'accion' => '30');
                $armado_botonera->armar('volver_punteado', $parametros, 'listado_de_paginas', 's');
            }
        }

        $id_nuevo = 0;
        if (is_array($this->_matrizComponentes)) {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->_tablaNombre);

            $mas_una_columna = false;
            $agrupar_por_id = false;

            // Armado de condiciones del "filtro_general".
            $kk_tb_intermedia = 1;
            $kk_tb_intermedia_campos = array();

            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor'])) {
                $consulta->condicionesAgrupacionInicio('');
            }

            $condicion = '';
            $id_columna_izq = 0;
            $id_columna_izq_sub = 0;

            if (file_exists(Inicio::path() . '/Configuraciones/anular_componentes.php')) {
                require( Inicio::path() . '/Configuraciones/anular_componentes.php' );
                $componente_ocultar = $id_cp;
            }

            foreach ($this->_matrizComponentes as $id => $value) {

                if (!isset($componente_ocultar) || (!in_array($value['cp_id'], $componente_ocultar))) {

                    if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor'])) {
                        $agregado_condicion = false;
                        if (($value['tb_campo'] != '') && !isset($value['origen_cp_id'])) {
                            $consulta->condiciones($condicion, $this->_tablaNombre, $value['tb_campo'], 'coincide', '', '', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor']);
                            $condicion = 'o';
                            $agregado_condicion = true;
                        } elseif (isset($value['origen_cp_id']) && !isset($value['intermedia_tb_id'])) {
                            $tabla_origen = $value['origen_tb_prefijo'] . '_' . $value['origen_tb_nombre'];
                            if (!isset($this->_tablas_origen_cond[$tabla_origen])) {
                                $tabla_origen_cond = $tabla_origen;
                                $this->_tablas_origen_cond[$tabla_origen] = true;
                            } else {
                                $tabla_origen_cond = $tabla_origen . '_' . $id_columna_izq;
                            }
                            $consulta->condiciones($condicion, $tabla_origen_cond, $value['origen_tb_campo'], 'coincide', '', '', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor']);
                            $condicion = 'o';
                            $agregado_condicion = true;
                        } elseif (isset($value['origen_cp_id']) && isset($value['intermedia_tb_id'])) {
                            $consulta->condiciones($condicion, 'kk_tb_intermedia_' . $kk_tb_intermedia, $value['origen_tb_campo'], 'coincide', '', '', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor']);
                            $condicion = 'o';
                            $kk_tb_intermedia_campos[$value['cp_id']] = 'kk_tb_intermedia_' . $kk_tb_intermedia;
                            $kk_tb_intermedia++;
                            $agregado_condicion = true;
                        }
                    }

                    $obtener_filtros = Generales_FiltrosOrden::obtenerFiltros();
                    $consulta_intermedia_tb = false;
                    if (is_array($obtener_filtros) && isset($value['intermedia_tb_id'])) {
                        foreach ($obtener_filtros as $id_filtro => $value_filtro) {
                            if (($value_filtro['parametro'] == 'iguales') && ($value_filtro['id'] == $value['cp_id'])) {
                                $consulta_intermedia_tb = true;
                            }
                        }
                    }
                    // Armado de relaciones entre tablas para las condiciones, para todos los tipos de filtros.
                    if (
                            ((
                            isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor']) ||
                            isset($value['listado_mostrar']) && ($value['listado_mostrar'] == 's')
                            ) &&
                            isset($value['origen_cp_id'])
                            ) || $consulta_intermedia_tb
                    ) {
                        // se necesita siempre para poder realizar filtros con los campos relacionados
                        if (!isset($value['intermedia_tb_id'])) {
                            $tabla_origen = $value['origen_tb_prefijo'] . '_' . $value['origen_tb_nombre'];
                            if (!isset($this->_tablas_origen[$tabla_origen])) {
                                $intermedia_tb = $tabla_origen;
                                $as_intermedia_tb_id = '';
                                $this->_tablas_origen[$tabla_origen] = true;
                                $tabla_origen_campo = $tabla_origen;
                            } else {
                                $intermedia_tb = $tabla_origen . '_' . $id_columna_izq;
                                $as_intermedia_tb_id = $tabla_origen . '_' . $id_columna_izq;
                                $tabla_origen_campo = $as_intermedia_tb_id;
                            }
                            $consulta->campos($tabla_origen_campo, $value['origen_tb_campo'], 'kk_' . $value['tb_campo']);
                            $consulta->unionIzquierdaTablas($id_columna_izq, $this->_tablaNombre, '', $tabla_origen, '', $as_intermedia_tb_id);
                            $consulta->unionIzquierdaCondiciones($id_columna_izq, '', $this->_tablaNombre, $value['tb_campo'], 'iguales', $intermedia_tb, 'id_' . $tabla_origen);
                        } else {
                            $tb_intermedia = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $value['intermedia_tb_id']);

                            if (isset($value['origen_tb_prefijo']) && isset($value['origen_tb_prefijo'])) {
                                $tb_origen = $value['origen_tb_prefijo'] . '_' . $value['origen_tb_nombre'];
                            } elseif (isset($value['id_tabla'])) {
                                $tb_origen = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $value['id_tabla']);
                            }

                            $consulta->unionIzquierdaTablas($id_columna_izq, $this->_tablaNombre, '', $tb_intermedia, '');
                            $consulta->unionIzquierdaCondiciones($id_columna_izq, '', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $tb_intermedia, 'id_' . $this->_tablaNombre);

                            if (isset($kk_tb_intermedia_campos[$value['cp_id']])) {
                                // la siguiente línea es importante para que funcione "opcion_multiple" y "opcion_multiple_check"
                                $consulta->unionIzquierdaSubTablas($id_columna_izq, $id_columna_izq_sub, $tb_intermedia, '', $tb_origen, '', $kk_tb_intermedia_campos[$value['cp_id']]);
                                $consulta->unionIzquierdaSubTablasDiferentes($id_columna_izq, $id_columna_izq_sub, $tb_intermedia, '', $tb_origen, '', $kk_tb_intermedia_campos[$value['cp_id']]);
                                $consulta->unionIzquierdaSubCondiciones($id_columna_izq, $id_columna_izq_sub, 'y', $tb_intermedia, 'id_' . $tb_origen, 'iguales', $kk_tb_intermedia_campos[$value['cp_id']], 'id_' . $tb_origen);
                                $id_columna_izq_sub++;
                            }
                        }
                        // ya que al relacionar multiples tablas, responde registros duplicados
                        $agrupar_por_id = true;
                    }

                    // Armado de relaciones de condiciones de filtros, incluyendo las relaciones con tablas intermedias.
                    if (is_array($obtener_filtros)) {
                        foreach ($obtener_filtros as $id_filtro => $value_filtro) {
                            if (
                                    isset($value_filtro['parametro']) &&
                                    ($value_filtro['id'] == $value['cp_id']) &&
                                    ($value_filtro['parametro'] != 'no_consulta_bd') &&
                                    ($value_filtro['parametro'] != 'no_filtrar') &&
                                    !isset($value['intermedia_tb_id'])
                            ) {
                                // armado de condiciones para consulta segun el filtrado por formulario
                                $consulta->condiciones('y', $this->_tablaNombre, $value['tb_campo'], $value_filtro['parametro'], '', '', $value_filtro['valor']);
                            } elseif (
                                    isset($value_filtro['parametro']) &&
                                    ($value_filtro['id'] == $value['cp_id']) &&
                                    ($value_filtro['parametro'] != 'no_consulta_bd') &&
                                    ($value_filtro['parametro'] != 'no_filtrar') &&
                                    isset($value['intermedia_tb_id'])
                            ) {

                                if (isset($value['origen_tb_prefijo']) && isset($value['origen_tb_prefijo'])) {
                                    $tb_origen = $value['origen_tb_prefijo'] . '_' . $value['origen_tb_nombre'];
                                } elseif (isset($value['id_tabla'])) {
                                    $tb_origen = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $value['id_tabla']);
                                }

                                $tb_intermedia = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $value['intermedia_tb_id']);

                                if (!is_array($value_filtro['valor']) && (strlen(trim($value_filtro['valor'])) > 0)) {
                                    $valor_array = explode(';', $value_filtro['valor']);
                                } else {
                                    $valor_array = $value_filtro['valor'];
                                }

                                // el "no_nulo" funciona directamente al no cumplirse ninguna de las condiciones que sigue
                                if ($value_filtro['parametro'] == 'iguales') {
                                    $consulta->condiciones('y', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $tb_intermedia, 'id_' . $this->_tablaNombre);
                                    $consulta->condicionesArrayEn('y', $tb_intermedia, 'id_' . $tb_origen, $valor_array);
                                    //$consulta->tablas($tb_intermedia);
                                } elseif ($value_filtro['parametro'] == 'distintos') {
                                    $consulta->condiciones('y', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $tb_intermedia, 'id_' . $this->_tablaNombre);
                                    $consulta->condicionesArrayNoEn('y', $tb_intermedia, 'id_' . $tb_origen, $valor_array);
                                    //$consulta->tablas($tb_intermedia);
                                } elseif ($value_filtro['parametro'] == 'nulo') {
                                    $consulta->condiciones('y', $tb_intermedia, 'id_' . $tb_origen, 'nulo');
                                }
                            }
                        }
                    }

                    // Armado del orden de los filtros al hacer clic sobre el encabezado de la tabla.
                    if (isset($value['listado_mostrar']) && ($value['listado_mostrar'] == 's')) {
                        if (($value['tb_campo'] != '') && ($value['cp_nombre'] != 'IdRegistro')) {
                            $consulta->campos($this->_tablaNombre, $value['tb_campo']);
                        }
                        $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'componente';
                        $tabla_texto[$id_nuevo]['parametros'] = $value;
                        $tabla_texto[$id_nuevo]['tb_campo_id'] = 'id_' . $this->_tablaNombre;
                        $id_nuevo++;
                        $mas_una_columna = true;
                    }
                    $id_columna_izq++;

                    // orden de registros
                    self::_cargarOrden($value['cp_id'], $value['tb_campo']);
                }
            }

            if (!isset($tabla_texto)) {
                $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'componente';
                $tabla_texto[$id_nuevo]['parametros']['cp_id'] = 0;
                $tabla_texto[$id_nuevo]['parametros']['cp_nombre'] = 'IdRegistro';
                $tabla_texto[$id_nuevo]['parametros']['cp_orden'] = 0;
                $tabla_texto[$id_nuevo]['parametros']['tb_tipo'] = 'registros';
                $tabla_texto[$id_nuevo]['parametros']['tb_campo'] = 'id_' . $this->_tablaNombre;
                $tabla_texto[$id_nuevo]['parametros']['idioma_es'] = '{TR|o_id}';
                $tabla_texto[$id_nuevo]['parametros']['listado_mostrar'] = 's';
                $tabla_texto[$id_nuevo]['parametros']['filtrar'] = 's';
                $tabla_texto[$id_nuevo]['tb_campo_id'] = 'id_' . $this->_tablaNombre;
                $id_nuevo++;

                // orden de registros
                self::_cargarOrden(0, 'id_' . $this->_tablaNombre);
            }

            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][Generales_FiltrosOrden::$_nivelActual]['filtros']['filtro_general_valor'])) {
                $consulta->condicionesAgrupacionFin();
            }

            if (isset($_GET['orden_listado']) && ($_GET['orden_listado'] == 'orden')) {
                if ($_GET['orden_listado_dir']) {
                    $orden_direccion = $_GET['orden_listado_dir'];
                } else {
                    $orden_direccion = '1';
                }
                Generales_FiltrosOrden::insertarOrden('orden', 'orden', $orden_direccion);
            }

            if ($this->_registrosDestinoIdCp()) {
                foreach ($this->_destinoIdCp as $value) {
                    $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'linkDestinoIdCp';
                    $tabla_texto[$id_nuevo]['tb_titulo_idioma'] = $value['tabla_nombre'];
                    $tabla_texto[$id_nuevo]['tb_campo'] = 'id_' . $this->_tablaNombre;
                    $tabla_texto[$id_nuevo]['variable_link'] = 'id_tabla_registro';
                    $tabla_texto[$id_nuevo]['accion'] = '41';
                    $tabla_texto[$id_nuevo]['id_link_componente'] = $value['destino_id_cp'];
                    $tabla_texto[$id_nuevo]['tabla_relacionada'] = $value['tabla'];
                    if (isset($value['link_a_grupo_cantidad'])) {
                        $tabla_texto[$id_nuevo]['link_a_grupo_cantidad'] = $value['link_a_grupo_cantidad'];
                    }
                    if (isset($value['sufijo'])) {
                        $tabla_texto[$id_nuevo]['sufijo'] = $value['sufijo'];
                    }
                    $id_nuevo++;
                }
            }

            if ($this->_validacion == 'datos') {
                $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'orden';
                $tabla_texto[$id_nuevo]['tb_titulo_idioma'] = '{TR|o_orden}';
                $tabla_texto[$id_nuevo]['tb_campo'] = 'orden';
                $tabla_texto[$id_nuevo]['tb_campo_id'] = 'id_' . $this->_tablaNombre;
                $tabla_texto[$id_nuevo]['accion'] = '41';
                $consulta->campos($this->_tablaNombre, 'orden');
                $id_nuevo++;
            }

            $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'ver';
            $tabla_texto[$id_nuevo]['tb_titulo_idioma'] = '{TR|o_ver}';
            $tabla_texto[$id_nuevo]['tb_campo'] = 'id_' . $this->_tablaNombre;
            $tabla_texto[$id_nuevo]['variable_link'] = 'id_tabla_registro';
            $tabla_texto[$id_nuevo]['accion'] = '45';
            $id_nuevo++;

            if ($this->_validacion == 'datos') {
                $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'editar';
                $tabla_texto[$id_nuevo]['tb_titulo_idioma'] = '{TR|o_editar}';
                $tabla_texto[$id_nuevo]['tb_campo'] = 'id_' . $this->_tablaNombre;
                $tabla_texto[$id_nuevo]['variable_link'] = 'id_tabla_registro';
                $tabla_texto[$id_nuevo]['accion'] = '42';
                $id_nuevo++;

                $tabla_texto[$id_nuevo]['tb_columna_tipo'] = 'eliminar';
                $tabla_texto[$id_nuevo]['tb_titulo_idioma'] = '{TR|o_eliminar}';
                $tabla_texto[$id_nuevo]['tb_campo'] = 'id_' . $this->_tablaNombre;
                $tabla_texto[$id_nuevo]['variable_link'] = 'id_tabla_registro';
                $tabla_texto[$id_nuevo]['accion'] = '40';
                $id_nuevo++;
            }

            // agrego el id de la tabla en la culumna para 'eliminar' y 'editar'
            $consulta->campos($this->_tablaNombre, 'id_' . $this->_tablaNombre);

            $obtener_orden = Generales_FiltrosOrden::obtenerOrden();

            if (is_array($obtener_orden)) {
                foreach ($obtener_orden as $value_orden) {
                    if (!isset($value_orden['origen_tb']) || !isset($value_orden['origen_cp'])) {
                        $consulta->orden($this->_tablaNombre, $value_orden['valor'], $value_orden['parametro']);
                    } else {
                        $consulta->orden($value_orden['origen_tb'], $value_orden['origen_cp'], $value_orden['parametro']);
                    }
                }
            } else {
                $consulta->orden($this->_tablaNombre, 'orden', 'ascendente');
            }

            if ($agrupar_por_id === true) {
                $consulta->grupo($this->_tablaNombre, 'id_' . $this->_tablaNombre);
            }

            //==== armado de paginado ==========

            $cantidad_por_pagina = Armado_CantidadPorPagina::cantidad();

            $consulta_cont = new Bases_RegistroConsulta(__FILE__, __LINE__);
            //$consulta_cont->verConsulta();
            $contador = $consulta_cont->contadorTotal($consulta->obtenerConsulta(), 'contador');
            Generales_FiltrosOrden::insertarConsulta($consulta->obtenerConsulta());

            $armar_paginado = new Armado_Paginado();
            $armar_paginado->variableGet('pagina_n');
            $armar_paginado->cantidadTotal($contador[0]['contador']);
            $armar_paginado->cantidadPorPagina($cantidad_por_pagina);
            $armar_paginado->paginaActual(Generales_FiltrosOrden::obtenerPagindo());

            // obtengo el limite inicial para la consulta de los registros
            $limite_inicial_consulta = $armar_paginado->limiteInicialConsulta();

            // un registro mas al inicio y al final para el orden del primer y ultimo elemento de la tabla
            if ($limite_inicial_consulta == 0) {
                $cantidad_por_pagina_consulta = $cantidad_por_pagina;
                $elemento_inicio = 0;
            } else {
                $limite_inicial_consulta -= 1;
                $cantidad_por_pagina_consulta = $cantidad_por_pagina + 1;
                $elemento_inicio = 1;
            }
            // para agregar un elemento al final para modificar elemento con el primero de la pagina siguiente
            $cantidad_por_pagina_consulta += 1;
            // -----

            $this->_armadoPlantillaSet('botonera2', $armar_paginado->paginadoObtener());

            //==== realizacion de la consulta ==========

            $cantidad_registros = '<div class="cantidad_registros">{TR|o_registros}: ' . $contador[0]['contador'] . '</div>';
            $this->_armadoPlantillaSet('botonera_izq', $cantidad_registros);

            $consulta->limite($limite_inicial_consulta, $cantidad_por_pagina_consulta);
            //$consulta->verConsulta();
            $matriz = $consulta->realizarConsulta();

            //==== llamo al boton superior de busqueda =

            $this->_menuFiltrosMostrar();

            //==== armo la tabla =======================
            if ($matriz !== false) {

                if ($contador[0]['contador'] > 0) {
                    Armado_BotoneraExportacion::armado();
                    Armado_CantidadPorPagina::armado();
                }

                $armar_tabla = new Armado_Tabla();
                return $armar_tabla->armar($tabla_texto, $matriz, $elemento_inicio, $cantidad_por_pagina);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // tipo de tabla variables

    private function _verRegistrosVariables() {

        $armado_botonera = new Armado_Botonera();

        if (isset($_GET['id_tabla_registro'])) {
            $id_tabla_registro = $_GET['id_tabla_registro'];
        } else {
            $id_tabla_registro = '';
        }

        $parametros = array('kk_generar' => '0', 'accion' => '42', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $id_tabla_registro);
        $armado_botonera->armar('editar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '30');
        $armado_botonera->armar('volver', $parametros);

        // creo una matriz con los campos de los componentes de la pagina
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        // obtencion de valores
        $matriz_valores = Generales_ObtenerValoresTbVariables::armado($this->_tablaNombre, $matriz_componentes);

        Armado_BotoneraExportacion::armado();

        if (is_array($matriz_componentes)) {
            $componente_armado = '';
            foreach ($matriz_componentes as $id => $value) {

                if (isset($matriz_valores[$value['tb_campo']])) {
                    $tb_campo = $matriz_valores[$value['tb_campo']];
                } else {
                    $tb_campo = '';
                }

                $componente_armado .= Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroVer', $tb_campo, $value, $value['cp_nombre'], $value['cp_id']);
            }
            return $componente_armado;
        } else {
            return false;
        }
    }

    // tipo de tabla menu

    private function _verRegistrosMenu() {

        if (isset($_GET['menu_bajar'])) {
            Generales_MenuModificarNivel::nivel_bajar($this->_tablaNombre, $_GET['menu_bajar']);
            $armado_botonera = new Armado_Botonera();
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => '0');
            $armado_botonera->armar('redirigir', $parametros);
        } elseif (isset($_GET['menu_subir'])) {
            Generales_MenuModificarNivel::nivel_subir($this->_tablaNombre, $_GET['menu_subir']);
            $armado_botonera = new Armado_Botonera();
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => '0');
            $armado_botonera->armar('redirigir', $parametros);
        }

        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $_GET['id_tabla']);

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        Armado_Cabeceras::dynatree();

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tablaNombre);
        $consulta->tablas($this->_tablaNombre . '_trd');
        $consulta->campos($this->_tablaNombre, 'id_' . $this->_tablaNombre, 'id_menu');
        $consulta->campos($this->_tablaNombre, 'nivel1_orden');
        $consulta->campos($this->_tablaNombre, 'nivel2_orden');
        $consulta->campos($this->_tablaNombre, 'nivel3_orden');
        $consulta->campos($this->_tablaNombre, 'nivel4_orden');
        $consulta->campos($this->_tablaNombre, 'nivel5_orden');
        $consulta->campos($this->_tablaNombre, 'nivel6_orden');
        $consulta->campos($this->_tablaNombre, 'nivel7_orden');
        $consulta->campos($this->_tablaNombre, 'nivel8_orden');
        $consulta->campos($this->_tablaNombre, 'nivel9_orden');
        $consulta->campos($this->_tablaNombre, 'nivel10_orden');
        $consulta->campos($this->_tablaNombre . '_trd', 'menu_nombre');
        $consulta->campos($this->_tablaNombre . '_rel', 'id_' . $this->_tablaNombre . '_rel', 'cantidad', 'contador');
        $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre);
        $consulta->condiciones('y', $this->_tablaNombre . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->unionIzquierdaTablas('relacion', $this->_tablaNombre, '', $this->_tablaNombre . '_rel');
        $consulta->unionIzquierdaCondiciones('relacion', '', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $this->_tablaNombre . '_rel', 'id_' . $this->_tablaNombre);
        $consulta->grupo($this->_tablaNombre, 'id_' . $this->_tablaNombre);
        $consulta->orden($this->_tablaNombre, 'nivel1_orden');
        $consulta->orden($this->_tablaNombre, 'nivel2_orden');
        $consulta->orden($this->_tablaNombre, 'nivel3_orden');
        $consulta->orden($this->_tablaNombre, 'nivel4_orden');
        $consulta->orden($this->_tablaNombre, 'nivel5_orden');
        $consulta->orden($this->_tablaNombre, 'nivel6_orden');
        $consulta->orden($this->_tablaNombre, 'nivel7_orden');
        $consulta->orden($this->_tablaNombre, 'nivel8_orden');
        $consulta->orden($this->_tablaNombre, 'nivel9_orden');
        $consulta->orden($this->_tablaNombre, 'nivel10_orden');
        //$consulta->verConsulta();
        $menus = $consulta->realizarConsulta();

        $plantilla['menu_arbol'] = Armado_PaginaArbolMenu::_armarMenu($menus, $parametros['numero_niveles'], $parametros['niveles_habilitados']);
        $plantilla['menu_etiqueta'] = Armado_EtiquetaIdiomas::armar('etiqueta');
        $plantilla['id_tabla'] = $parametros['tabla_relacionada'];
        $plantilla['destino_id_cp'] = $parametros['destino_id_cp'];
        $plantilla['numero_niveles'] = $parametros['numero_niveles'];
        $plantilla['niveles_protegidos'] = $parametros['niveles_protegidos'];
        $plantilla['niveles_habilitados'] = $parametros['niveles_habilitados'];

        return Armado_PlantillasInternas::acciones(basename('PaginaListadoMenu'), $plantilla);
    }

    private function _registrosDestinoIdCp() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla_parametro');
        $consulta->campos('kirke_tabla_parametro', 'valor');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'tipo', 'iguales', '', '', 'link');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'destino_id_cp');
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        if (is_array($matriz)) {
            $cont = 0;
            foreach ($matriz as $value) {

                $dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($value['valor']);

                if (isset($dcp['link_a_grupo']) && ($dcp['link_a_grupo'] == 's')) {
                    $this->_destinoIdCp[$cont]['destino_id_cp'] = $value['valor'];
                    $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($dcp['tb_id']);
                    $this->_destinoIdCp[$cont]['tabla_nombre'] = $datos_tabla['nombre_idioma'];
                    $this->_destinoIdCp[$cont]['tabla'] = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
                    if (isset($dcp['sufijo'])) {
                        $this->_destinoIdCp[$cont]['sufijo'] = $dcp['sufijo'];
                    }
                    if (isset($dcp['link_a_grupo_cantidad']) && ($dcp['link_a_grupo_cantidad'] == 'n')) {
                        $this->_destinoIdCp[$cont]['link_a_grupo_cantidad'] = 'n';
                    }
                    $cont++;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    private function _menuFiltrosMostrar() {

        $armado_botonera_filtro = new Armado_Botonera('registro_filtros');

        if (isset($_GET['filtro']) && ($_GET['filtro'] == 'si')) {
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
            $boton_filtro = $armado_botonera_filtro->armar('ocultar', $parametros, 'ocultar_filtros', 's', true);
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'filtro' => 'si');
            $boton_filtro = $armado_botonera_filtro->armar('mostrar', $parametros, 'mostrar_filtros', 's', true);
        }

        $armado_filtros = '<div class="cabezal_especiales_filtro">';

        $filtrosPostCabezal = Generales_FiltrosOrden::filtrosPostCabezal($_GET['kk_generar'], 'pagina', $_GET['id_tabla']);

        if (is_array($filtrosPostCabezal)) {
            if (count($filtrosPostCabezal) > 0) {
                $armado_filtros .= '<div class="cabezal_especiales_filtro_titulo">{TR|o_filtrado_por}</div>';
                $armado_filtros .= '<div class="cabezal_especiales_filtro_titulo_linea"></div>';
            }
            if (count($filtrosPostCabezal) > 5) {
                $mas_filtros = count($filtrosPostCabezal) - 5;
            }
            $cont = 0;
            $mostrar_mas_filtros = '';
            foreach ($filtrosPostCabezal as $elemento_filtro) {
                if (($cont == 4) && isset($mas_filtros)) {
                    $mostrar_mas_filtros = ' &nbsp;&nbsp; [+' . $mas_filtros . ']';
                } elseif ($cont == 5) {
                    break;
                }
                $armado_filtros .= '<div class="cabezal_especiales_filtro_texto">' . $elemento_filtro . $mostrar_mas_filtros . '</div>';
                $cont++;
            }
        }

        $armado_filtros .= '</div>';
        $armado_filtros .= $boton_filtro['contenido'];

        $this->_armadoPlantillaSet('botonera_registro_filtros', $armado_filtros, 'unico');
    }

    private function _verRegistrosTabuladores() {

        // modificacion de orden de los elementos de la tabla
        if (isset($_GET['orden_act']) && ($_GET['orden_act'] != '') && ($_GET['id_orden_act'] != '')
        ) {
            if (
                    isset($_GET['orden_ant']) && ($_GET['orden_ant'] != '') && ($_GET['id_orden_ant'] != '')
            ) {
                Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_ant'], 'orden', $_GET['orden_act']);
                Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_act'], 'orden', $_GET['orden_ant']);
            } elseif (
                    isset($_GET['orden_sig']) && ($_GET['orden_sig'] != '') && ($_GET['id_orden_sig'] != '')
            ) {
                Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_sig'], 'orden', $_GET['orden_act']);
                Consultas_CambiarOrden::armado($this->_tablaNombre, $_GET['id_orden_act'], 'orden', $_GET['orden_sig']);
            }
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla_parametro');
        $consulta->campos('kirke_tabla_parametro', 'parametro');
        $consulta->campos('kirke_tabla_parametro', 'valor');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        if (is_array($matriz)) {
            foreach ($matriz as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $parametros['tabla_relacionada']);
        //$consulta->verConsulta();
        $tabla_destino_datos = $consulta->realizarConsulta();
        $tabla_destino = $tabla_destino_datos[0]['prefijo'] . '_' . $tabla_destino_datos[0]['tabla_nombre'];

        $familias_tab_tb = false;
        if (isset($parametros['id_cp_rel'])) {
            $id_familias_tab_cp = $parametros['id_cp_rel'];

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_componente_parametro');
            $consulta->tablas('kirke_componente');
            $consulta->tablas('kirke_tabla');
            $consulta->tablas('kirke_tabla_prefijo');
            $consulta->campos('kirke_componente_parametro', 'valor');
            $consulta->campos('kirke_componente', 'tabla_campo');
            $consulta->campos('kirke_tabla', 'tabla_nombre');
            $consulta->campos('kirke_tabla_prefijo', 'prefijo');
            $consulta->condiciones('', 'kirke_componente', 'id_componente', 'iguales', 'kirke_componente_parametro', 'id_componente');
            $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', 'kirke_tabla', 'id_tabla');
            $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
            $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $id_familias_tab_cp);
            $consulta->condiciones('y', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', 'idioma_' . Generales_Idioma::obtener());
            //$consulta->verConsulta();
            $familias_tab_parametros = $consulta->realizarConsulta();

            $familias_tab_tb = $familias_tab_parametros[0]['prefijo'] . '_' . $familias_tab_parametros[0]['tabla_nombre'];
            $id_familias_tab_campo = $familias_tab_parametros[0]['valor'];
            $familias_tab_campo = $familias_tab_parametros[0]['tabla_campo'];

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($familias_tab_tb);
            $consulta->campos($familias_tab_tb, 'id_' . $familias_tab_tb, 'id_registro');
            $consulta->campos($familias_tab_tb, $familias_tab_campo, 'registro');
            //$consulta->verConsulta();
            $familias_tab = $consulta->realizarConsulta();
            $familias = '';
            if (is_array($familias_tab)) {
                $familias .= '<div class="contenido_2_columnas_1_3">&nbsp;</div><div class="contenido_columnas_izq">';
                $familias .= '{TR|o_familias_de_tabuladores}:<br /><br />';
                $id_registro = false;
                foreach ($familias_tab as $familia_tab) {
                    $familias .= '<div class="link_jquery" url="' . $familia_tab['id_registro'] . '">' . $familia_tab['registro'] . '</div>';
                    if (isset($_GET['id_registro'])) {
                        $id_registro = $_GET['id_registro'];
                    } elseif (isset($_GET['extra'])) {
                        $id_registro = $_GET['extra'];
                    }
                    if (($id_registro !== false) && ($familia_tab['id_registro'] == $id_registro)) {
                        $familia_registros = $familia_tab['registro'];
                    }
                }
                $familias .= '</div><div class="contenido_2_columnas_1_3"></div><div class="contenido_columnas_der">';
                $familias_faltantes = false;
            } else {
                $familias_faltantes = true;
            }
            $plantilla['familias'] = $familias;
            $plantilla['familias_cierre'] = '</div>';
        } else {
            $id_familias_tab_cp = false;
            $plantilla['familias'] = '';
            $plantilla['familias_cierre'] = '';
        }

        if (($id_familias_tab_cp === false) || isset($_GET['id_registro']) || isset($_GET['extra'])) {
            if (isset($parametros['id_cp_rel'])) {
                $plantilla['familia_registros'] = '{TR|o_tabuladores}: ' . $familia_registros . '<br /><br />';
            } else {
                $plantilla['familia_registros'] = '';
            }
            $plantilla['id_registros'] = $this->_verRegistrosTabuladoresTabla($tabla_destino, $familias_tab_tb);
        } else {
            $plantilla['familia_registros'] = '';
            $plantilla['id_registros'] = '';
        }

        $plantilla['id_tabla'] = $_GET['id_tabla']; // es necesario para los links de las familias.

        if ((isset($familias_faltantes) && ($familias_faltantes === false) && (isset($_GET['id_registro']) || (isset($_GET['id_tabulador'])))) || !isset($parametros['id_cp_rel'])) {
            if (isset($_GET['id_tabulador'])) {
                $texto = 'editar_tabulador';
                $tabuladores_valores = $this->_verRegistrosTabuladoresFormularioValoresObtener();
            } else {
                $texto = 'agregar_tabulador';
                $tabuladores_valores = '';
            }

            if (count(Inicio::confVars('idiomas')) > 1) {
                $multilingue = '<br /><br /><input name="valores_pred_multi[]" id="valores_pred_multi" type="text" size="30" /> {TR|o_multilingue}';
            } else {
                $multilingue = '';
            }

            $plantilla['formulario'] = '
            <div class="contenido_titulo">{TR|o_' . $texto . '}</div><div class="contenido_7">' . $this->_verRegistrosTabuladoresFormulario() . '</div>

            <div class="contenido_separador"> </div>                  
            <div class="contenido_margen"> </div>
            <div class="contenido_titulo" id="tab_agregar_valor_predefinido">[+] Agregar valor predefinido</div>
            <div class="contenido_7"> </div>

            <script type="text/javascript">
                $(document).ready(function() {
                    $("#tab_agregar_valor_predefinido").click(function() {
                        $("#agregar_valor_predefinido_ele").append(\'<div><div class="contenido_separador"> </div><div class="contenido_margen"> </div><div class="contenido_titulo tab_quitar_valor_predefinido">[-] Quitar</div><div class="contenido_7">' . $this->_verRegistrosTabuladoresFormularioValores() . $multilingue . '</div></div>\');
                    });
                    $(".tab_quitar_valor_predefinido").live("click", function(){ 
                        $(this).parent().remove();
                    });
                });
            </script>

            <div id="agregar_valor_predefinido_ele">' . $tabuladores_valores . '</div>
            ';
        } elseif (isset($parametros['id_cp_rel']) && isset($_GET['id_registro'])) {
            $plantilla['formulario'] = '<div class="contenido_titulo">{TR|o_debe_agregar_familias_en}: "' . $familias_tab_tb . '"</div>';
        }

        return Armado_PlantillasInternas::acciones(basename('PaginaListadoTabuladores'), $plantilla);
    }

    private function _verRegistrosTabuladoresTabla($tabla_destino, $familias_tab_tb) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tablaNombre);
        $consulta->tablas($this->_tablaNombre . '_trd');
        $consulta->campos($this->_tablaNombre, 'id_' . $this->_tablaNombre, 'id_tabulador');
        $consulta->campos($this->_tablaNombre, 'orden');
        $consulta->campos($this->_tablaNombre . '_trd', 'tabulador_nombre');
        if ($familias_tab_tb !== false) {
            $consulta->campos($this->_tablaNombre, 'id_' . $familias_tab_tb, 'id_registro');
        }
        $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre);
        $consulta->condiciones('y', $this->_tablaNombre . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
        if ($familias_tab_tb !== false) {
            if (isset($_GET['id_registro'])) {
                $id_registro = $_GET['id_registro'];
            } else {
                $id_registro = $_GET['extra'];
            }
            $consulta->condiciones('y', $this->_tablaNombre, 'id_' . $familias_tab_tb, 'iguales', '', '', $id_registro);
        }
        $consulta->orden($this->_tablaNombre, 'orden');
        //$consulta->verConsulta();
        $tabuladores = $consulta->realizarConsulta();

        $id_columna = 0;

        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_tabuladores}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'tabulador_nombre';
        $id_columna++;

        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'orden';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_orden}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'orden';
        $tabla_columnas[$id_columna]['tb_campo_id'] = 'id_tabulador';
        $tabla_columnas[$id_columna]['accion'] = '41';
        if ($familias_tab_tb !== false) {
            $tabla_columnas[$id_columna]['extra'] = 'id_registro';
        }
        $id_columna++;

        // columna editar
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'editar';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_editar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'id_tabulador';
        $tabla_columnas[$id_columna]['accion'] = '41';
        if ($familias_tab_tb !== false) {
            $tabla_columnas[$id_columna]['extra'] = 'id_registro';
        }
        $id_columna++;
        // columna eliminar
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'eliminar';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_eliminar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'id_tabulador';
        $tabla_columnas[$id_columna]['accion'] = '71';
        if ($familias_tab_tb !== false) {
            $tabla_columnas[$id_columna]['extra'] = 'id_registro';
        }
        $id_columna++;

        // armado de la tabla
        $armar_tabla = new Armado_Tabla();
        $armar_tabla->sinDatosPie();

        $tabla_armado = $armar_tabla->armar($tabla_columnas, $tabuladores, false, false, false);

        return $tabla_armado;
    }

    private function _verRegistrosTabuladoresFormulario() {

        if (isset($_GET['id_tabulador'])) {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->_tablaNombre . '_trd');
            $consulta->campos($this->_tablaNombre . '_trd', 'idioma');
            $consulta->campos($this->_tablaNombre . '_trd', 'tabulador_nombre');
            $consulta->condiciones('', $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre, 'iguales', '', '', $_GET['id_tabulador']);
            //$consulta->verConsulta();
            $tabulador_nombres = $consulta->realizarConsulta();

            foreach ($tabulador_nombres as $tabulador_nombres) {
                $datos_idiomas_cargados[$tabulador_nombres['idioma']] = $tabulador_nombres['tabulador_nombre'];
            }

            $etiqueta = Armado_EtiquetaIdiomas::armar('etiqueta', $datos_idiomas_cargados, 'no_nulo');
        } else {
            $etiqueta = Armado_EtiquetaIdiomas::armar('etiqueta', null, 'no_nulo');
        }

        return $etiqueta;
    }

    private function _verRegistrosTabuladoresFormularioValores() {

        if (isset($_GET['id_tabulador'])) {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->_tablaNombre . '_trd');
            $consulta->campos($this->_tablaNombre . '_trd', 'idioma');
            $consulta->campos($this->_tablaNombre . '_trd', 'tabulador_nombre');
            $consulta->condiciones('', $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre, 'iguales', '', '', $_GET['id_tabulador']);
            //$consulta->verConsulta();
            $tabulador_nombres = $consulta->realizarConsulta();

            foreach ($tabulador_nombres as $tabulador_nombres) {
                $datos_idiomas_cargados[$tabulador_nombres['idioma']] = $tabulador_nombres['tabulador_nombre'];
            }

            $etiqueta = Armado_EtiquetaIdiomas::armar('valores_pred', $datos_idiomas_cargados, 'no_nulo', true);
        } else {
            $etiqueta = Armado_EtiquetaIdiomas::armar('valores_pred', null, 'no_nulo', true);
        }

        return $etiqueta;
    }

    private function _verRegistrosTabuladoresFormularioValoresObtener() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tablaNombre . '_prd');
        $consulta->campos($this->_tablaNombre . '_prd', 'idioma');
        $consulta->campos($this->_tablaNombre . '_prd', 'valor');
        $consulta->campos($this->_tablaNombre . '_prd', 'id_tab_prd');
        $consulta->condiciones('', $this->_tablaNombre . '_prd', 'id_' . $this->_tablaNombre, 'iguales', '', '', $_GET['id_tabulador']);
        $consulta->orden($this->_tablaNombre . '_prd', 'orden');
        //$consulta->verConsulta();
        $tabulador_valores_consulta = $consulta->realizarConsulta();

        if (is_array($tabulador_valores_consulta)) {

            $idioma = '';
            foreach ($tabulador_valores_consulta as $tab_valor) {
                if ($tab_valor['idioma'] != $idioma) {
                    $tabulador_valores[$tab_valor['id_tab_prd']][$tab_valor['idioma']] = $tab_valor['valor'];
                }
            }

            $valores_existentes = '';

            if (count(Inicio::confVars('idiomas')) > 1) {
                $multilingue = '<br /><br /><input name="valores_pred_multi[]" id="valores_pred_multi" type="text" size="30" /> {TR|o_multilingue}';
            } else {
                $multilingue = '';
            }

            foreach ($tabulador_valores as $valores) {
                $valores_existentes .= '<div><div class="contenido_separador"> </div><div class="contenido_margen"> </div><div class="contenido_titulo tab_quitar_valor_predefinido">[-] Quitar</div><div class="contenido_7">' . Armado_EtiquetaIdiomas::armar('valores_pred', $valores, 'nulo', true) . $multilingue . '</div></div>';
            }

            return $valores_existentes;
        } else {
            return false;
        }
    }

    static private function _cargarOrden($cp_id, $orden_campo) {

        if (isset($_GET['orden_listado']) && ($cp_id == $_GET['orden_listado'])) {
            if ($_GET['orden_listado_dir']) {
                $orden_direccion = $_GET['orden_listado_dir'];
            } else {
                $orden_direccion = '1';
            }
            Generales_FiltrosOrden::insertarOrden($orden_campo, $cp_id, $orden_direccion);
        }
    }

}

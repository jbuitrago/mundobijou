<?php

class DesarrollosSistema_Tabla {

    private $_campos = array();
    private $_campos_busquedas = array();
    private $_campos_busquedas_cont = 0;
    private $_pies = array();
    private $_matriz = array();
    private $_lineas;
    private $_contador_modificadores;
    private $_tabla;
    private $_paginado = '';
    private $_exportar = false;
    private $_buscador = false;
    public static $armar_buscador = array();
    public static $armar_orden = array();
    public static $armar_orden_array = array();
    public static $armar_limites = array();
    private static $_tabla_cont = 1;

    public function __construct($tabla = null) {
        $this->_tabla = $_GET['kk_desarrollo'] . '|' . $_GET['0'] . '|';
        if ($tabla != null) {
            $this->_tabla .= str_replace('|', '_', $tabla);
        } else {
            $this->_tabla .= 'tabla' . self::$_tabla_cont;
            self::$_tabla_cont++;
        }

        if (isset($_GET['id_menu_link'])) {
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['DesarrolloFiltrosOrden'][$this->_tabla]);
        }

        DesarrollosSistema_FiltrosOrden::armar($this->_tabla);

        if (isset($_GET['kk_tabla']) && isset($_GET['kk_orden_listado']) && ($_GET['kk_tabla'] == $this->_tabla)) {
            if (isset($_GET['kk_orden_listado_dir'])) {
                $orden_direccion = $_GET['kk_orden_listado_dir'];
            } else {
                $orden_direccion = '1';
            }
            DesarrollosSistema_FiltrosOrden::insertarOrden($this->_tabla, $_GET['kk_orden_listado'], $orden_direccion);
        }

        if (!isset($_GET['kk_exportar_formato'])) {
            echo '<link type="text/css" rel="stylesheet" href="./Plantillas/kirke/css/tablas.css">';
        }
    }

    public function nombreTabla() {
        return $this->_tabla;
    }

    public function armar($resultado_matriz) {
        if (isset($_GET['kk_exportar_formato']) && ($_GET['kk_tabla'] == $this->_tabla)) {
            $exportacion = new DesarrollosSistema_RegistroExportacion();
            $exportacion->armado($this->_campos, $resultado_matriz, $this->_exportar);
            exit;
        }

        $armar_tabla = '';

        $this->_matriz = $resultado_matriz;
        $this->_lineas = count($resultado_matriz);

        if ($this->_buscador === true) {
            $armar_tabla .= DesarrollosSistema_FiltroGeneral::armado($this->_tabla);
        }

        $armar_tabla .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n\n";

        $armar_tabla .= $this->_titulos();

        if ($this->_lineas > 0) {
            $armar_tabla .= $this->_lineas();
        } else {
            $armar_tabla .= $this->_sinResultados();
        }

        $armar_tabla .= $this->_pie();

        $armar_tabla .= '</table>' . "\n";

        $armar_tabla .= '<div class="contenido_separador"></div>' . "\n";

        $armar_tabla .= $this->_paginado;

        if (($this->_exportar !== false) && ($this->_lineas > 0)) {
            $armar_tabla .= DesarrollosSistema_BotoneraExportacion::armado($this->_tabla);
            $armar_tabla .= '<div class="contenido_separador"></div>' . "\n";
        }

        return $armar_tabla;
    }

    public function tituloOrden($identificador, $orden_direccion) {
        $this->mostrarTitulosOrden();
        if (isset($_GET['id_menu_link'])) {
            DesarrollosSistema_FiltrosOrden::insertarOrden($this->_tabla, $identificador, $orden_direccion);
        }
        DesarrollosSistema_TablaTitulos::$titulosOrden = true;
    }

    public function mostrarTitulosOrden($mostrar_order_by = true) {

        $array_orden = DesarrollosSistema_FiltrosOrden::obtenerOrden($this->_tabla);
        $orden = array('ascendente' => 'ASC', 'descendente' => 'DESC');
        $consulta = '';
        $orden_array = array();
        $orden_array_id = 0;
        if (count($array_orden) > 0) {
            $coma = '';
            if ($mostrar_order_by === true) {
                $consulta .= '  ORDER BY ';
            }
            foreach ($array_orden as $valores) {
                if ($this->_campos[$valores['identificador'] - 1]['tb'] != '') {
                    $consulta .= $coma . $this->_campos[$valores['identificador'] - 1]['tb'] . '.' . $this->_campos[$valores['identificador'] - 1]['tb_campo'] . ' ' . $orden[$valores['parametro']];
                } else {
                    $consulta .= $coma . $this->_campos[$valores['identificador'] - 1]['tb_campo'] . ' ' . $orden[$valores['parametro']];
                }
                $coma = ', ';
                $orden_array[$orden_array_id]['id'] = $valores['identificador'];
                $orden_array[$orden_array_id]['campo'] = $this->_campos[$valores['identificador'] - 1]['tb_campo'];
                $orden_array[$orden_array_id]['orden'] = $valores['parametro'];
                $orden_array_id++;
            }
            self::$armar_orden[$this->_tabla] = $consulta;
            self::$armar_orden_array = $orden_array;
            return $this->_tabla;
        } else {
            return false;
        }
    }

    static public function armarOrden($tabla) {
        if (isset(self::$armar_orden[$tabla])) {
            return self::$armar_orden[$tabla];
        } else {
            return false;
        }
    }

    static public function obtenerOrdenValor($tabla) {
        return self::$armar_orden_array;
    }

    public function exportar($nombre_archivo) {
        $this->_exportar = $nombre_archivo;
    }

    public function buscador($mostrar_where = true, $columnas_deshabilitadas = array()) {
        $this->_buscador = true;
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor']) && ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'] != '')) {
            $consulta = '';
            if ($mostrar_where === true) {
                $consulta .= 'WHERE ';
            } elseif ($mostrar_where == 'y') {
                $consulta .= 'AND ';
            }
            $operador = '';
            $consulta .= '           (' . "\n" . '           ';
            foreach ($this->_campos as $id => $valores) {
                if (!(in_array(($id + 1), $columnas_deshabilitadas))) {
                    if (($valores['tipo'] == 'texto') || ($valores['tipo'] == 'link')) {
                        if ($valores['tb'] != '') {
                            $consulta .= $operador . $valores['tb'] . '.' . $valores['tb_campo'] . " LIKE '%" . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'] . "%' \n";
                        } else {
                            $consulta .= $operador . $valores['tb_campo'] . " LIKE '%" . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'] . "%' \n";
                        }
                        $operador = '           OR ';
                    }
                }
            }
            foreach ($this->_campos_busquedas as $valores) {
                if ($valores['tb'] !== false) {
                    $consulta .= $operador . $valores['tb'] . '.' . $valores['tb_campo'] . " LIKE '%" . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'] . "%' \n";
                } else {
                    $consulta .= $operador . $valores['tb_campo'] . " LIKE '%" . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'] . "%' \n";
                }
                $operador = '           OR ';
            }

            $consulta .= '           )' . "\n";
            self::$armar_buscador[$this->_tabla] = $consulta;
            return $this->_tabla;
        } else {
            return false;
        }
    }

    static public function armarBuscador($tabla) {
        if (isset(self::$armar_buscador[$tabla])) {
            return self::$armar_buscador[$tabla];
        } else {
            return false;
        }
    }

    public function obtenerBuscadorValor() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'])) {
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$this->_tabla]['filtros']['filtro_general_valor'];
        } else {
            return false;
        }
    }

    public function modificadores($identificador, $modificador, $param_1 = null, $param_2 = null, $param_3 = null, $param_4 = null, $param_5 = null) {
        $modificadores = array($modificador, $param_1, $param_2, $param_3, $param_4, $param_5);
        if (!isset($this->_contador_modificadores[$identificador])) {
            $this->_contador_modificadores[$identificador] = 1;
        } else {
            ++$this->_contador_modificadores[$identificador];
        }
        $this->_campos[$identificador - 1]['modificadores'][$this->_contador_modificadores[$identificador]] = $modificadores;
    }

    public function columna($identificador, $tipo, $titulo = null, $tabla_campo = null, $tabla = null, $alinear = null) {

        if ($titulo == null) {
            switch ($tipo) {
                case 'texto':
                    $titulo = "campo: " . $identificador;
                    break;
                case 'id_registro':
                    $titulo = "campo: " . $identificador;
                    break;
                default:
                    return false;
            }
        }
        if ($alinear == 'derecha') {
            $alinear = 'align="right"';
        } else {
            $alinear = '';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => $tipo, // texto, id_registro, link
            'titulo' => $titulo,
            'tb_campo' => $tabla_campo,
            'tb' => $tabla, // se usa para que arme directamente la parte de ORDER BY de la consulta
            'alinear' => $alinear,
        );
    }

    public function columnaOculta($tabla_campo, $tabla = false) {

        $this->_campos_busquedas[$this->_campos_busquedas_cont] = array(
            'tb_campo' => $tabla_campo,
            'tb' => $tabla,
        );

        $this->_campos_busquedas_cont++;
    }

    public function columnaLink($identificador, $valores, $titulo = null, $tabla = null, $tabla_campo = null, $alinear = null) {

        if ($titulo == null) {
            $titulo = "link: " . $identificador;
        }

        if ($alinear == 'derecha') {
            $alinear = 'align="right"';
        } else {
            $alinear = '';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'link', // texto, id_registro, link
            'titulo' => $titulo,
            'tb_campo' => $tabla_campo,
            'tb' => $tabla, // se usa para que arme directamente la parte de ORDER BY de la consulta
            'alinear' => $alinear,
            'valores' => $valores,
        );
    }

    public function columnaOrden($identificador, $tabla = null, $tabla_campo = null, $titulo = null) {

        if ($tabla == null) {
            $tabla = explode('|', $this->_tabla);
            if (is_array($tabla)) {
                $tabla = $tabla[2];
            } else {
                $tabla = '';
            }
        }

        if ($titulo == null) {
            $titulo = 'Orden';
        }

        if ($tabla_campo == null) {
            $tabla_campo = 'orden';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'orden',
            'titulo' => $titulo,
            'tb_campo' => $tabla_campo,
            'tb' => $tabla, // se usa para que arme directamente la parte de ORDER BY de la consulta
        );

        if (isset($_GET['kk_tabla']) && ($_GET['kk_tabla'] == $this->_tabla) && isset($_GET['kk_orden_act']) && ($_GET['kk_orden_act'] != '') && ($_GET['kk_id_orden_act'] != '')
        ) {
            if (
                    isset($_GET['kk_orden_ant']) && ($_GET['kk_orden_ant'] != '') && ($_GET['kk_id_orden_ant'] != '')
            ) {
                Consultas_CambiarOrden::armado($tabla, $_GET['kk_id_orden_ant'], 'orden', $_GET['kk_orden_act']);
                Consultas_CambiarOrden::armado($tabla, $_GET['kk_id_orden_act'], 'orden', $_GET['kk_orden_ant']);
            } elseif (
                    isset($_GET['kk_orden_sig']) && ($_GET['kk_orden_sig'] != '') && ($_GET['kk_id_orden_sig'] != '')
            ) {
                Consultas_CambiarOrden::armado($tabla, $_GET['kk_id_orden_sig'], 'orden', $_GET['kk_orden_act']);
                Consultas_CambiarOrden::armado($tabla, $_GET['kk_id_orden_act'], 'orden', $_GET['kk_orden_sig']);
            }
        }
    }

    public function columnaExportar($identificador, $titulo = null, $tabla = null, $tabla_campo = null, $alinear = null) {
        $this->_campos[$identificador - 1] = array(
            'tipo' => 'exportar', // texto, id_registro, link
            'titulo' => $titulo,
            'tb_campo' => $tabla_campo,
            'tb' => $tabla, // se usa para que arme directamente la parte de ORDER BY de la consulta
            'alinear' => $alinear,
        );
    }

    public function columnaEditar($identificador, $pagina, $titulo = null) {

        if ($titulo == null) {
            $titulo = 'Editar';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'editar',
            'titulo' => $titulo,
            'pagina' => $pagina
        );
    }

    public function columnaSiguiente($identificador, $pagina, $titulo = null) {

        if ($titulo == null) {
            $titulo = 'Siguiente';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'siguiente',
            'titulo' => $titulo,
            'pagina' => $pagina
        );
    }

    public function columnaNuevo($identificador, $pagina, $titulo = null) {

        if ($titulo == null) {
            $titulo = 'Nuevo';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'nuevo',
            'titulo' => $titulo,
            'pagina' => $pagina
        );
    }

    public function columnaVer($identificador, $pagina, $titulo = null) {

        if ($titulo == null) {
            $titulo = 'Ver';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'ver',
            'titulo' => $titulo,
            'pagina' => $pagina
        );
    }

    public function columnaEliminar($identificador, $titulo = null, $tabla_campo = null, $tabla = null, $mensaje = null) {

        if (isset($_GET['kk_accion']) && ($_GET['kk_accion'] == 'eliminar') && ($_GET['kk_tabla'] == $this->_tabla)) {
            Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $tabla, $tabla_campo, $_GET['kk_id']);
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0']), 's');
            header('Location: ' . $link);
            exit;
        }

        if ($titulo == null) {
            $titulo = 'Eliminar';
        }

        if ($mensaje == null) {
            $mensaje = '¿ confirma la eliminacion ?';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'eliminar',
            'titulo' => $titulo,
            'mensaje' => $mensaje
        );
    }
    
    public function columnaEliminarPersonalizado($identificador, $pagina, $titulo = null, $mensaje = null) {

        if ($titulo == null) {
            $titulo = 'Eliminar';
        }

        if ($mensaje == null) {
            $mensaje = '¿ confirma la eliminacion ?';
        }

        $this->_campos[$identificador - 1] = array(
            'tipo' => 'eliminar_personalizado',
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'pagina' => $pagina
        );
    }

    public function pie($identificador, $pie) {
        $this->_pies[$identificador - 1] = array(
            'pie' => $pie
        );
    }

    public function paginado($total_registros, $por_pagina = null) {

        if (is_null($por_pagina)) {
            $por_pagina = 50;
        }

        $paginado = new DesarrollosSistema_Paginado($this->_tabla);
        $paginado->cantidadTotal($total_registros);

        $paginado->cantidadPorPagina($por_pagina);
        $paginado->linksPorLado(5);

        $limite_inicial = $paginado->limiteInicialConsulta();

        $this->_paginado = $paginado->paginadoObtener();

        self::$armar_limites[$this->_tabla] = '         LIMIT ' . ($limite_inicial * $por_pagina) . ', ' . $por_pagina;
        return $this->_tabla;
    }

    static public function armarPaginado($tabla) {
        if (isset(self::$armar_limites[$tabla])) {
            return self::$armar_limites[$tabla];
        } else {
            return false;
        }
    }

    private function _titulos() {

        $armar_titulo = "\n";

        foreach ($this->_campos as $i => $campos) {

            if ($campos['tipo'] != 'exportar') {

                $armar_titulos = '';

                $metodo = $campos['tipo'];
                $armado_tabla_cabezales = new DesarrollosSistema_TablaTitulos();

                if ($metodo == 'eliminar') {
                    $url = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0'], 'kk_accion' => 'eliminar', 'kk_tabla' => $this->_tabla), 's');
                    $this->_eliminarJs($url, $campos['mensaje']);
                }if ($metodo == 'eliminar_personalizado') {
                    $url = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $campos['pagina']), 's');
                    $this->_eliminarJs($url, $campos['mensaje']);
                }

                if (isset($campos['alinear'])) {
                    $alinear = $campos['alinear'];
                } else {
                    $alinear = '';
                }

                $armar_titulos = $armado_tabla_cabezales->$metodo($this->_tabla, $campos['titulo'], ($i + 1), $alinear);

                $armar_titulo .= $armar_titulos . "\n";
            }
        }

        return '<tr class="linea_titulo"><td class="linea_titulo_izq"></td>' . $armar_titulo . '<td class="linea_titulo_der"></td></tr>' . "\n\n";
    }

    private function _lineas() {
        $armar_lineas_predefinida = '';

        $armar_lineas = '';
        if (is_array($this->_matriz)) {

            $color_linea = 1;
            $armado_tabla_columna = new DesarrollosSistema_TablaColumnas();

            foreach ($this->_matriz as $i => $linea) {

                foreach ($this->_campos as $j => $campos) {

                    $valor_campo = array();

                    $otro_valor = '';

                    if (isset($campos['tb_campo']) && isset($linea[$campos['tb_campo']])) {
                        $valor = $linea[$campos['tb_campo']];
                        if (isset($campos['modificadores'])) {
                            $modificadores = new DesarrollosSistema_Modificadores();
                            $valor = $modificadores->modificadores($valor, $campos['modificadores']);
                        }
                    } else {
                        $valor = '';
                    }

                    switch ($campos['tipo']) {
                        case 'orden':
                            $orden_act = $this->_matriz[$i][$campos['tb_campo']];
                            $id_orden_act = $this->_matriz[$i]['id'];
                            $orden_sig = '';
                            $id_orden_sig = '';
                            $orden_ant = '';
                            $id_orden_ant = '';

                            if (isset($this->_matriz[$i + 1])) {
                                $orden_sig = $this->_matriz[$i + 1][$campos['tb_campo']];
                                $id_orden_sig = $this->_matriz[$i + 1]['id'];
                            }
                            if (isset($this->_matriz[$i - 1])) {
                                $orden_ant = $this->_matriz[$i - 1][$campos['tb_campo']];
                                $id_orden_ant = $this->_matriz[$i - 1]['id'];
                            }
                            $armar_lineas_predefinida .= $armado_tabla_columna->orden($this->_tabla, $orden_act, ($j + 1), $id_orden_act, $orden_sig, $id_orden_sig, $orden_ant, $id_orden_ant);
                            break;
                        case 'editar':
                            $armar_lineas_predefinida .= $armado_tabla_columna->editar($this->_tabla, $this->_matriz[$i]['id'], $campos['pagina']);
                            break;
                        case 'nuevo':
                            $armar_lineas_predefinida .= $armado_tabla_columna->nuevo($this->_tabla, $this->_matriz[$i]['id'], $campos['pagina']);
                            break;
                        case 'ver':
                            $armar_lineas_predefinida .= $armado_tabla_columna->ver($this->_tabla, $this->_matriz[$i]['id'], $campos['pagina']);
                            break;
                        case 'siguiente':
                            $armar_lineas_predefinida .= $armado_tabla_columna->siguiente($this->_tabla, $this->_matriz[$i]['id'], $campos['pagina']);
                            break;
                        case 'eliminar':
                            $armar_lineas_predefinida .= $armado_tabla_columna->eliminar($this->_tabla, $this->_matriz[$i]['id']);
                            break;
                        case 'eliminar_personalizado':
                            $armar_lineas_predefinida .= $armado_tabla_columna->eliminar_personalizado($this->_tabla, $this->_matriz[$i]['id'], $campos['pagina']);
                            break;
                        case 'link':
                            $armar_lineas_predefinida .= $armado_tabla_columna->link($this->_tabla, $valor, $this->_matriz[$i]['id'], $campos['alinear'], $campos['tb_campo'], $campos['valores']);
                            break;
                        case 'exportar':
                            break;
                        default:
                            $tb_columna_tipo = $campos['tipo'];
                            $armar_lineas_predefinida .= $armado_tabla_columna->$tb_columna_tipo($this->_tabla, $valor, $j, $campos['alinear'], $campos['tb_campo'], $otro_valor);
                    }
                }

                $armar_lineas .= '<tr class="tbla_linea_' . (($color_linea % 2) + 1) . '"><td class="tbla_linea_izq"></td>' . $armar_lineas_predefinida . '<td></td></tr>' . "\n";

                $armar_lineas_predefinida = '';

                $color_linea++;
            }
        }

        return $armar_lineas . "\n";
    }

    private function _sinResultados() {
        return '<div id="tabla_sin_resultados">{TR|s_sin_elementos_cargados}</div>' . "\n";
    }

    private function _pie() {

        $armar_pie = '<tr class="linea_pie"><td class="linea_pie_izq"></td>' . "\n";

        $armado_tabla_pie = new DesarrollosSistema_TablaPie();

        foreach ($this->_campos as $i => $campos) {

            if ($campos['tipo'] != 'exportar') {

                if (isset($this->_pies[$i]['pie']) && ($this->_pies[$i]['pie'] != '')) {
                    $pie = $this->_pies[$i]['pie'];
                } else {
                    $pie = '';
                }

                $metodo = $campos['tipo'];

                if (isset($campos['alinear'])) {
                    $alinear = $campos['alinear'];
                } else {
                    $alinear = '';
                }


                $armar_pie .= $armado_tabla_pie->$metodo($pie, $alinear);
            }
        }

        $armar_pie .= '<td class="linea_pie_der"></td></tr>' . "\n";

        return $armar_pie;
    }

    private function _eliminarJs($url, $mensaje) {
        echo '
            <script type="text/javascript" language="javascript">
            $(function () {
                $(".bt_tb_eliminar").click(function () {
                    var eliminar = confirm("' . $mensaje . '");
                    if (eliminar == true) {
                        var url_id = $(this).attr("url_id");
                        var url_tabla = $(this).attr("url_tabla");
                        $(location).attr("href", "' . $url . '&kk_id="+url_id);
                    }
                });
            });
            </script>
        ';
    }

}

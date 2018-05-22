<?php

class Generales_FiltrosOrden {

    static private $_filtros = array();
    static private $_filtroGeneral = array();
    static private $_filtroGeneralRelacionados = array();
    static private $_orden = array();
    static private $_filtrosPostCabezal;
    static private $_ordenValores = array('1' => 'ascendente', '2' => 'descendente');
    static private $_idAccion;
    static public $_nivelActual;
    static public $mismoCampoOrden = false;
    static public $ordenValoresNum = array('ascendente' => '1', 'descendente' => '2');

    static public function armar() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
        } else {
            self::$_nivelActual = 0;
        }

        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPostCabezal'])) {
            self::$_filtrosPostCabezal = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPostCabezal'];
        }

        if (isset($_GET['id_menu_link'])) {
            // cuando se accede desde el link de un menu
            // filtros enviados por el menu
            // si la pagina viene desde un menu, se deben eliminar lo links anteriores
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['ocultar_campos']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general_valor']);
            self::$_filtrosPostCabezal = '';

            self::$_nivelActual = 0;
            $datos[self::$_nivelActual]['kk_generar'] = $_GET['kk_generar'];
            $datos[self::$_nivelActual]['id_elemento'] = $_GET['id_tabla'];
            $datos[self::$_nivelActual]['id_accion'] = self::$_idAccion;
            $datos[self::$_nivelActual]['id_registro'] = 0;

            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'] = $datos;

            // obtengo los valores del menu
            $valores = Consultas_MenuLinkParametro::RegistroConsulta(__FILE__, __LINE__, $_GET['id_menu_link']);

            $filtros = '';
            $id_filtros = 0;
            if (is_array($valores)) {
                foreach ($valores as $valor) {
                    if ($valor['tipo'] == 'filtro') {
                        $filtros[$id_filtros]['parametro'] = $valor['parametro'];
                        $filtros[$id_filtros]['valor'] = $valor['valor'];
                        $filtros[$id_filtros]['id'] = $valor['id'];
                        $id_filtros++;
                    } elseif ($valor['tipo'] == 'orden') {
                        $orden_dir = array('ascendente' => '1', 'descendente' => '2');
                        self::insertarOrden($valor['valor'], $valor['id'], $orden_dir[$valor['parametro']], true);
                    } elseif (($valor['tipo'] == 'filtro_general') && ($valor['parametro'] == 'si')) {
                        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general'] = true;
                    } elseif (($valor['tipo'] == 'ocultar_campos') && ($valor['parametro'] == 'si')) {
                        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['ocultar_campos'] = true;
                    }
                }
            }

            // filtros
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_filtros'] = $filtros;

            $nombre_menu_link = Consultas_MenuLinkNombre::RegistroConsultaIdMenu(__FILE__, __LINE__, $_GET['id_menu_link']);

            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_nombre_titulo'] = $nombre_menu_link[0]['menu_link_nombre'];
        } elseif (isset($_GET['id_link']) && ($_GET['id_link'] !== false)) {
            // filtros enviados por un registro de otra tabla
            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][(self::$_nivelActual)])) {
                // controlo que no sea un reenvio de la pagina actual, asi no se crean subniveles que no correspondan
                if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][(self::$_nivelActual)]['id_elemento'] != $_GET['id_tabla']) {
                    $filtros = '';
                    self::$_nivelActual++;
                    $id_link = explode("_", $_GET['id_link']);
                    $filtros[0]['parametro'] = 'iguales';
                    if (substr($id_link[1], -1) != '-') {
                        $filtros[0]['valor'] = $id_link[1]; // valor para filtro
                    } else {
                        $valores = explode('-', $id_link[1]);
                        foreach ($valores as $valores_id => $valores_valor) {
                            if ($valores_valor != '') {
                                $filtros[0]['valor'][$valores_id] = $valores_valor; // valor para filtro
                            }
                        }
                    }
                    $filtros[0]['id'] = $id_link[0]; // id_componente del campo a filtrar
                    $datos['kk_generar'] = $_GET['kk_generar'];
                    $datos['id_elemento'] = $_GET['id_tabla'];
                    $datos['id_accion'] = self::$_idAccion;
                    $datos['id_registro'] = 0;
                    $datos['filtros']['id_link'] = $filtros;
                    array_push($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'], $datos);
                }
            }
        } elseif (isset($_GET['volver']) && ($_GET['volver'] !== false)) {
            // filtros enviados por un registro de otra tabla, elimino los datos de niveles superiores
            // borro los elementos superiores, ya que al volver ya no son necesarios
            self::$_nivelActual = $_GET['volver'];
            array_splice($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'], ($_GET['volver'] + 1));
        } elseif (isset($_POST['kk_control_filtros']) && ($_GET['accion'] == 'RegistroListado')) {
            // elimino el paginado, para que no se caiga en la pagina 2 y quede vacia porque solo haya resultados para la primera pagina
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPaginado'] = 0;

            // filtros enviados por variables pop del encabezado
            // elimino los filtros existentes, ya que van a ser reemplazados
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPostCabezal']);

            // llamo a los componentes para buscar los filtros activos
            $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos', $_GET['id_tabla']);

            // blanqueo la variable para armar nuevamente los filtros a mostrar
            self::$_filtrosPostCabezal = '';
            foreach ($_POST as $id_post => $valor_post) {
                $identificador = explode("_", $id_post);
                if ($identificador[0] == 'parametro') {
                    $pre_filtros[$identificador[1]]['parametro'] = $valor_post;
                    $pre_filtros[$identificador[1]]['id'] = $identificador[1];
                } elseif ($identificador[0] == 'valor') {
                    $pre_filtros[$identificador[1]]['id'] = $identificador[1];
                    if (!isset($identificador[2])) {
                        $pre_filtros[$identificador[1]]['valor'] = $valor_post;
                    } elseif (isset($identificador[2]) && ($identificador[2] == '2') && (($_POST['parametro_' . $identificador[1]] == 'rango') || ($_POST['parametro_' . $identificador[1]] == 'fecha_rango'))) {
                        $pre_filtros[$identificador[1]]['valor'] .= ';' . $valor_post;
                    }
                }
            }

            $id_carga = 0;
            foreach ($pre_filtros as $valor) {
                if (!isset($valor['valor'])) {
                    $valor['valor'] = '';
                }

                $val_filtro = self::ValidacionFiltro($valor['parametro'], $valor['valor'], $valor['id'], $id_carga);
                if ($val_filtro !== false) {
                    foreach ($val_filtro['filtros'] as $key => $val_filtro_valor) {
                        $filtros[$key]['parametro'] = $val_filtro_valor['parametro'];
                        $filtros[$key]['valor'] = $val_filtro_valor['valor'];
                        $filtros[$key]['id'] = $val_filtro_valor['id'];
                    }
                    $filtro_valido = $val_filtro['filtro_valido'];
                    $id_carga = $val_filtro['id_carga'];

                    if (($filtro_valido === true)) {
                        // armo el listado para mostrar en el encabezado
                        foreach ($matriz_componentes as $dcp) {
                            if ($dcp['cp_id'] == $valor['id']) {
                                self::$_filtrosPostCabezal[] = $dcp['idioma_' . Generales_Idioma::obtener()];
                            }
                        }
                        $id_carga++;
                    }
                }
            }

            if (isset($filtros)) {
                // filtros
                $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'] = $filtros;
            }
        } elseif (isset($_GET['pagina_n']) && ($_GET['pagina_n'] !== false)) {
            // Numero de pagina actual
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPaginado'] = $_GET['pagina_n'];
        } elseif (isset($_GET['valor_sistema']) && ($_GET['valor_sistema'] == '0')) {
            // Cuando no se recibe ninguno de los valores anteriores, por lo que es un acceso desde "Paginas/Paginas"
            // si la pagina viene desde un menu, se deben eliminar lo links anteriores
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual']);
            self::$_filtrosPostCabezal = '';

            self::$_nivelActual = 0;
            $datos[self::$_nivelActual]['kk_generar'] = $_GET['kk_generar'];
            $datos[self::$_nivelActual]['id_elemento'] = $_GET['id_tabla'];
            $datos[self::$_nivelActual]['id_accion'] = self::$_idAccion;
            $datos[self::$_nivelActual]['id_registro'] = 0;

            $datos[self::$_nivelActual]['filtros']['orden'][0]['parametro'] = 'ascendente';
            $datos[self::$_nivelActual]['filtros']['orden'][0]['valor'] = 'orden';

            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'] = $datos;

            // para que se muestre el filtro general en las paginas no accedidas desde el menu
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtro_general'] = true;
        }

        self::$_filtros = '';
        // id_menu_link_filtros
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_filtros']) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_filtros'])) {
            foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_filtros'] as $valor) {
                self::$_filtros[] = $valor;
            }
        }
        // id_link
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link']) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link'])) {
            foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link'] as $valor) {
                self::$_filtros[] = $valor;
            }
        }
        // filtros_post
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post']) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'])) {
            foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'] as $valor) {
                self::$_filtros[] = $valor;
            }
        }

        self::$_orden = '';
        // orden
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['orden']) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['orden'])) {
            foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['orden'] as $valor) {
                self::$_orden[] = $valor;
            }
        }

        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'] = self::$_nivelActual;
        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPostCabezal'] = self::$_filtrosPostCabezal;
    }

    static public function obtenerOrden() {
        if (isset(self::$_orden) && (self::$_orden != '')) {
            return self::$_orden;
        }
    }

    static public function obtenerFiltros() {
        if (isset(self::$_filtros) && (self::$_filtros != '')) {
            return self::$_filtros;
        } else {
            return false;
        }
    }

    static public function obtenerFiltroGeneralRelacionados() {
        if (isset(self::$_filtroGeneralRelacionados) && (self::$_filtroGeneralRelacionados != '')) {
            return self::$_filtroGeneralRelacionados;
        } else {
            return false;
        }
    }

    static public function insertarOrden($orden_campo, $cp_id, $orden_direccion, $id_menu_link = false) {
        // para que no genere el orden cuando es un desarrollo interno, ya que el mismo genera su propio orden
        if ($_GET['kk_generar'] == 6) {
            return false;
        }

        $_dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($cp_id);
        if (isset($_dcp['origen_cp_id'])) {
            $tb_origen = $_dcp['origen_tb_prefijo'] . '_' . $_dcp['origen_tb_nombre'];
            $cp_origen = $_dcp['origen_tb_campo'];
        }
        $cantidad_ord = 3;
        if ($id_menu_link === false) {
            if ((isset(self::$_orden[0]['cp_id']) && (self::$_orden[0]['cp_id'] == $_GET['orden_listado']) ) || (isset(self::$_orden[0]['valor']) && ($_GET['orden_listado'] == 'orden') )) {
                self::$mismoCampoOrden = true;
            }
            for ($i = 1; $i < $cantidad_ord; $i++) {
                if (isset(self::$_orden[$i]['valor']) && (self::$_orden[$i]['valor'] == $orden_campo)) {
                    unset(self::$_orden[$i]);
                }
            }
        }
        if (!isset(self::$_orden[0]['valor']) || (self::$_orden[0]['valor'] != $orden_campo)) {
            $cantidad = count(self::$_orden);
            if ($cantidad == $cantidad_ord) {
                unset(self::$_orden[2]);
            }
            if (!isset($_dcp['origen_cp_id'])) {
                array_unshift(self::$_orden, array('parametro' => self::$_ordenValores[$orden_direccion], 'cp_id' => $cp_id, 'valor' => $orden_campo));
            } else {
                array_unshift(self::$_orden, array('parametro' => self::$_ordenValores[$orden_direccion], 'cp_id' => $cp_id, 'valor' => $orden_campo, 'origen_tb' => $tb_origen, 'origen_cp' => $cp_origen));
            }
        } else {
            self::$_orden[0]['parametro'] = self::$_ordenValores[$orden_direccion];
        }
        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['orden'] = self::$_orden;
    }

    static public function insertarConsulta($consulta) {
        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['ConsultaRegistros'] = $consulta;
    }

    static public function obtenerPagindo() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPaginado'])) {
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPaginado'];
        } else {
            return 0;
        }
    }

    static public function obtenerConsulta() {
        self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['ConsultaRegistros'])) {
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['ConsultaRegistros'];
        } else {
            return false;
        }
    }

    static public function insertarIdAccion($id_accion) {
        self::$_idAccion = $id_accion;
    }

    static public function actualizarIdAccion($id_registro = 0) {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['id_accion'] = self::$_idAccion;
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['id_registro'] = $id_registro;
        }
    }

    static public function obtenerMenuLinkTitulo() {

        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_nombre_titulo'])) {
                return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_menu_link_nombre_titulo'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static public function filtrosPostCabezal() {
        if (count(self::$_filtrosPostCabezal) > 0) {
            return self::$_filtrosPostCabezal;
        } else {
            return false;
        }
    }

    static public function filtrosEliminarTodos() {
        unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden']);
        unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual']);
    }

    static public function filtrosPostEliminar() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPostCabezal']);
            unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['FiltrosOrdenPaginado']);
            self::$_filtrosPostCabezal = '';
        }
    }

    static public function filtrosVolver() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
        }
        if (self::$_nivelActual > 0) {
            return (self::$_nivelActual - 1);
        } else {
            return false;
        }
    }

    static public function filtrosIdDesplegable($elemento) {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            self::$_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
        }
        if (self::$_nivelActual > 0) {

            switch ($elemento) {
                case 'id':
                    return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link'][0]['valor'];
                case 'cp':
                    return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link'][0]['id'];
            }
        } else {
            return false;
        }
    }

    static public function filtrosIdLink() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link'])) {
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['id_link'];
        } else {
            $filtros = array();
            $filtros[0]['valor'] = ''; // valor para filtro
            $filtros[0]['id'] = ''; // id_componente del campo a filtrar
            return $filtros;
        }
    }

    static private function _fechaComponente($id, $valor) {
        $mostrar_hora = Consultas_ComponenteParametro::RegistroConsulta(__FILE__, __LINE__, $id, 'mostrar_hora');
        if (is_array($mostrar_hora)) {
            $mostrar_hora_valor = $mostrar_hora[0]['valor'];
        } else {
            $mostrar_hora_valor = '';
        }
        $formato_fecha = Consultas_ComponenteParametro::RegistroConsulta(__FILE__, __LINE__, $id, 'formato_fecha');
        if (is_array($formato_fecha)) {
            $formato_fecha_valor = $formato_fecha[0]['valor'];
        } else {
            $formato_fecha_valor = '';
        }
        $fecha_unix = Generales_FechaFormatoAUnix::armado($valor, $mostrar_hora_valor, $formato_fecha_valor);
        if (!is_array($mostrar_hora)) {
            $hora_inicio = 0;
            $hora_fin = 23;
        } else {
            $hora_inicio = date("H", $fecha_unix);
            $hora_fin = $hora_inicio + 1;
        }
        $horas['fecha_unix'] = $fecha_unix;
        $horas['hora_inicio'] = $hora_inicio;
        $horas['hora_fin'] = $hora_fin;
        return $horas;
    }

    static public function ValidacionFiltro($parametro, $valor, $id, $id_carga) {
        $filtro_valido = false;
        $filtros = '';

        if (
                isset($valor) &&
                ((!is_array($valor) && (trim($valor) != '')) || is_array($valor)) && (
                $parametro == 'iguales' || // =
                $parametro == 'distintos' || // !=
                $parametro == 'mayor' || // >
                $parametro == 'menor' || // <
                $parametro == 'mayor_igual' || // >=
                $parametro == 'menor_igual' || // <=
                $parametro == 'semejante' || // LIKE
                $parametro == 'no_semejante' || // NOT LIKE
                $parametro == 'coincide' || // LIKE % x %
                $parametro == 'coincide_izq' || // LIKE x %
                $parametro == 'coincide_der' || // LIKE % x
                $parametro == 'no_coincide' || // NOT LIKE % x %
                $parametro == 'no_coincide_izq' || // NOT LIKE % x
                $parametro == 'no_coincide_der'        // NOT LIKE x %
                )
        ) {
            $filtros[$id_carga]['parametro'] = $parametro;
            $filtros[$id_carga]['valor'] = $valor;
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif ($parametro == 'nulo') {
            $filtros[$id_carga]['parametro'] = 'nulo';
            $filtros[$id_carga]['valor'] = '';
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif ($parametro == 'no_nulo') {
            $filtros[$id_carga]['parametro'] = 'no_nulo';
            $filtros[$id_carga]['valor'] = '';
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif ($parametro == 'activo') {
            $filtros[$id_carga]['parametro'] = 'iguales';
            $filtros[$id_carga]['valor'] = '1';
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif ($parametro == 'inactivo') {
            $filtros[$id_carga]['parametro'] = 'iguales';
            $filtros[$id_carga]['valor'] = '0';
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif (($parametro == 'fecha_iguales') && (trim($valor) != '')) {
            $horas = self::_fechaComponente($id, $valor);
            $filtros[$id_carga]['parametro'] = 'mayor';
            $filtros[$id_carga]['valor'] = mktime($horas['hora_inicio'], 0, 0, date("m", $horas['fecha_unix']), date("d", $horas['fecha_unix']), date("Y", $horas['fecha_unix']));
            $filtros[$id_carga]['id'] = $id;
            $id_carga++;
            $filtros[$id_carga]['parametro'] = 'menor';
            $filtros[$id_carga]['valor'] = mktime($horas['hora_fin'], 59, 59, date("m", $horas['fecha_unix']), date("d", $horas['fecha_unix']), date("Y", $horas['fecha_unix']));
            $filtros[$id_carga]['id'] = $id;
            $id_carga++;
            $filtros[$id_carga]['parametro'] = 'no_consulta_bd';
            $filtros[$id_carga]['valor'] = $valor;
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif (($parametro == 'fecha_mayor') && (trim($valor) != '')) {
            $horas = self::_fechaComponente($id, $valor);
            $filtros[$id_carga]['parametro'] = 'mayor';
            $filtros[$id_carga]['valor'] = mktime($horas['hora_fin'], 59, 59, date("m", $horas['fecha_unix']), date("d", $horas['fecha_unix']), date("Y", $horas['fecha_unix']));
            $filtros[$id_carga]['id'] = $id;
            $id_carga++;
            $filtros[$id_carga]['parametro'] = 'no_consulta_bd';
            $filtros[$id_carga]['valor'] = $valor;
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif (($parametro == 'fecha_menor') && (trim($valor) != '')) {
            $horas = self::_fechaComponente($id, $valor);
            $filtros[$id_carga]['parametro'] = 'menor';
            $filtros[$id_carga]['valor'] = mktime($horas['hora_inicio'], 0, 0, date("m", $horas['fecha_unix']), date("d", $horas['fecha_unix']), date("Y", $horas['fecha_unix']));
            $filtros[$id_carga]['id'] = $id;
            $id_carga++;
            $filtros[$id_carga]['parametro'] = 'no_consulta_bd';
            $filtros[$id_carga]['valor'] = $valor;
            $filtros[$id_carga]['id'] = $id;
            $filtro_valido = true;
        } elseif (($parametro == 'fecha_rango') && (trim($valor) != '')) {
            $valor_2 = explode(';', trim($valor));
            $horas1 = self::_fechaComponente($id, $valor_2[0]);
            $horas2 = self::_fechaComponente($id, $valor_2[1]);
            if (isset($horas1) && isset($horas2) && ($horas1 < $horas2)) {
                $filtros[$id_carga]['parametro'] = 'mayor_igual';
                $filtros[$id_carga]['valor'] = mktime(date("G", $horas1['fecha_unix']), date("i", $horas1['fecha_unix']), date("s", $horas1['fecha_unix']), date("m", $horas1['fecha_unix']), date("d", $horas1['fecha_unix']), date("Y", $horas1['fecha_unix']));
                $filtros[$id_carga]['id'] = $id;
                $id_carga++;
                $filtros[$id_carga]['parametro'] = 'menor_igual';
                $filtros[$id_carga]['valor'] = mktime(date("G", $horas2['fecha_unix']), date("i", $horas2['fecha_unix']), date("s", $horas2['fecha_unix']), date("m", $horas2['fecha_unix']), date("d", $horas2['fecha_unix']), date("Y", $horas2['fecha_unix']));
                $filtros[$id_carga]['id'] = $id;
                $id_carga++;
                $filtros[$id_carga]['parametro'] = 'no_consulta_bd';
                $filtros[$id_carga]['valor'] = $valor;
                $filtros[$id_carga]['id'] = $id;
                $filtro_valido = true;
            }
        } elseif (($parametro == 'rango') && (trim($valor) != '')) {
            $valor_2 = explode(';', trim($valor));
            if (isset($valor_2[0]) && isset($valor_2[1]) && ($valor_2[0] < $valor_2[1])) {
                $filtros[$id_carga]['parametro'] = 'mayor_igual';
                $filtros[$id_carga]['valor'] = $valor_2[0];
                $filtros[$id_carga]['id'] = $id;
                $id_carga++;
                $filtros[$id_carga]['parametro'] = 'menor_igual';
                $filtros[$id_carga]['valor'] = $valor_2[1];
                $filtros[$id_carga]['id'] = $id;
                $id_carga++;
                $filtros[$id_carga]['parametro'] = 'no_consulta_bd';
                $filtros[$id_carga]['valor'] = $valor;
                $filtros[$id_carga]['id'] = $id;
                $filtro_valido = true;
            }
        }
        if ($filtro_valido === true) {

            $resultado['filtros'] = $filtros;
            $resultado['filtro_valido'] = $filtro_valido;
            $resultado['id_carga'] = $id_carga;

            return $resultado;
        } else {
            return false;
        }
    }

}

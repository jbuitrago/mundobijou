<?php

class ArmadoMenu {

    private $_tabla_nombre;
    private $_id_tabla;
    private $_clase_de_bd;
    static private $_link;
    static private $_id_menu;
    static private $_class_general;
    static private $_class_actual;
    static private $_class_general_nivel1;
    static private $_class_actual_nivel1;
    static private $_class_general_nivel2;
    static private $_class_actual_nivel2;
    static private $_class_general_nivel3;
    static private $_class_actual_nivel3;
    static private $_class_general_nivel4;
    static private $_class_actual_nivel4;
    static private $_class_general_nivel5;
    static private $_class_actual_nivel5;
    static private $_class_general_nivel6;
    static private $_class_actual_nivel6;
    static private $_class_general_nivel7;
    static private $_class_actual_nivel7;
    static private $_class_general_nivel8;
    static private $_class_actual_nivel8;
    static private $_class_general_nivel9;
    static private $_class_actual_nivel9;
    static private $_class_general_nivel10;
    static private $_class_actual_nivel10;

    function __construct() {
        $this->_clase_de_bd = 'ArmadoMenu' . ucfirst(VariableGet::sistema('tipo_base'));
    }

    public function obtenerMenu() {

        $parametros = $this->_obtenerParametros();

        eval('$menus = ' . $this->_clase_de_bd . '::obtenerElementos($this->_tabla_nombre);');

        $menu_arbol = self::_armarMenu($menus, $parametros['numero_niveles'], $parametros['ultimo_nivel_habilitado']);

        return $menu_arbol;
    }

    public function obtenerMenuArray() {

        eval('$parametros_tabla = ' . $this->_clase_de_bd . '::obtenerParametros($this->_id_tabla);');

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        eval('$menus = ' . $this->_clase_de_bd . '::obtenerElementos($this->_tabla_nombre);');

        return $menus;
    }

    public function obtenerNivelesIdMenu() {
        if (isset(self::$_id_menu)) {

            $niveles = false;

            eval('$parametros_tabla = ' . $this->_clase_de_bd . '::obtenerParametros($this->_id_tabla);');

            if (is_array($parametros_tabla)) {
                $parametros = array();
                foreach ($parametros_tabla as $valor) {
                    $parametros[$valor['parametro']] = $valor['valor'];
                }
            }

            eval('$menus = ' . $this->_clase_de_bd . '::obtenerNivelesIdMenu($this->_tabla_nombre, self::$_id_menu);');

            if (is_array($menus)) {

                $id_nivel_menu2 = 'IS NULL';
                $id_nivel_menu3 = 'IS NULL';
                $id_nivel_menu4 = 'IS NULL';
                $id_nivel_menu5 = 'IS NULL';
                $id_nivel_menu6 = 'IS NULL';
                $id_nivel_menu7 = 'IS NULL';
                $id_nivel_menu8 = 'IS NULL';
                $id_nivel_menu9 = 'IS NULL';
                $id_nivel_menu10 = 'IS NULL';

                $cont = 1;
                foreach ($menus[0] as $menus_ids) {

                    if ($menus_ids != '') {

                        eval('$id_nivel_menu' . $cont . ' = "= \'' . $menus_ids . '\'";');

                        eval('$menus_nombres = ' . $this->_clase_de_bd . '::obtenerNivelesIdMenuNombres($this->_tabla_nombre, $id_nivel_menu1, $id_nivel_menu2, $id_nivel_menu3, $id_nivel_menu4, $id_nivel_menu5, $id_nivel_menu6, $id_nivel_menu7, $id_nivel_menu8, $id_nivel_menu9, $id_nivel_menu10 );');

                        $niveles[$cont - 1] = $menus_nombres[0]['menu_nombre'];

                        $cont++;
                    } else {
                        break;
                    }
                }
            }

            return $niveles;
        } else {
            return false;
        }
    }

    public function obtenerNivelesIdMenuDetalles() {
        if (isset(self::$_id_menu)) {

            $niveles = false;

            eval('$parametros_tabla = ' . $this->_clase_de_bd . '::obtenerParametros($this->_id_tabla);');

            if (is_array($parametros_tabla)) {
                $parametros = array();
                foreach ($parametros_tabla as $valor) {
                    $parametros[$valor['parametro']] = $valor['valor'];
                }
            }

            eval('$menus = ' . $this->_clase_de_bd . '::obtenerNivelesIdMenu($this->_tabla_nombre, self::$_id_menu);');

            if (is_array($menus)) {

                $id_nivel_menu2 = 'IS NULL';
                $id_nivel_menu3 = 'IS NULL';
                $id_nivel_menu4 = 'IS NULL';
                $id_nivel_menu5 = 'IS NULL';
                $id_nivel_menu6 = 'IS NULL';
                $id_nivel_menu7 = 'IS NULL';
                $id_nivel_menu8 = 'IS NULL';
                $id_nivel_menu9 = 'IS NULL';
                $id_nivel_menu10 = 'IS NULL';

                $cont = 1;
                foreach ($menus[0] as $menus_ids) {

                    if ($menus_ids != '') {

                        eval('$id_nivel_menu' . $cont . ' = "= \'' . $menus_ids . '\'";');

                        eval('$menus_nombres = ' . $this->_clase_de_bd . '::obtenerNivelesIdMenuNombres($this->_tabla_nombre, $id_nivel_menu1, $id_nivel_menu2, $id_nivel_menu3, $id_nivel_menu4, $id_nivel_menu5, $id_nivel_menu6, $id_nivel_menu7, $id_nivel_menu8, $id_nivel_menu9, $id_nivel_menu10 );');

                        $niveles[$cont - 1]['nombre'] = $menus_nombres[0]['menu_nombre'];
                        $niveles[$cont - 1]['id'] = $menus_nombres[0]['id_menu_nombre'];

                        $cont++;
                    } else {
                        break;
                    }
                }
            }

            return $niveles;
        } else {
            return false;
        }
    }

    public function obtenerNivelesNombres() {

        $elementos_menu = $this->obtenerMenuArray();

        $niveles_1 = '';
        $niveles_2 = '';
        $niveles_3 = '';
        $niveles_4 = '';
        $niveles_5 = '';
        $niveles_6 = '';
        $niveles_7 = '';
        $niveles_8 = '';
        $niveles_9 = '';
        $niveles_10 = '';
        $primero = true;

        $niveles = array();
        foreach ($elementos_menu as $linea) {

            $nivel = self::_obtenerNivel($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden'], $linea['nivel5_orden'], $linea['nivel6_orden'], $linea['nivel7_orden'], $linea['nivel8_orden'], $linea['nivel9_orden'], $linea['nivel10_orden']);
            switch ($nivel) {
                case 1:
                    $niveles['"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_1 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 2:
                    $niveles[$niveles_1 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_2 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 3:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_3 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 4:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_4 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 5:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/' . $niveles_4 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_5 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 6:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/' . $niveles_4 . '/' . $niveles_5 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_6 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 7:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/' . $niveles_4 . '/' . $niveles_5 . '/' . $niveles_6 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_7 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 8:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/' . $niveles_4 . '/' . $niveles_5 . '/' . $niveles_6 . '/' . $niveles_7 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_8 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 9:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/' . $niveles_4 . '/' . $niveles_5 . '/' . $niveles_6 . '/' . $niveles_7 . '/' . $niveles_8 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    $niveles_9 = '"' . $linea['menu_nombre'] . '"';
                    break;
                case 10:
                    $niveles[$niveles_1 . '/' . $niveles_2 . '/' . $niveles_3 . '/' . $niveles_4 . '/' . $niveles_5 . '/' . $niveles_6 . '/' . $niveles_7 . '/' . $niveles_8 . '/' . $niveles_9 . '/"' . $linea['menu_nombre'] . '"'] = $linea['id_menu'];
                    break;
            }
        }

        return $niveles;
    }

    public function obtenerNivelesNombresArray() {

        $parametros = $this->_obtenerParametros();

        $elementos_menu = $this->obtenerMenuArray();

        $niveles_1 = '';
        $niveles_2 = '';
        $niveles_3 = '';
        $niveles_4 = '';
        $niveles_5 = '';
        $niveles_6 = '';
        $niveles_7 = '';
        $niveles_8 = '';
        $niveles_9 = '';
        $niveles_10 = '';
        $primero = true;

        $niveles = array();
        if (is_array($elementos_menu)) {
            foreach ($elementos_menu as $linea) {

                $nivel = self::_obtenerNivel($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden'], $linea['nivel5_orden'], $linea['nivel6_orden'], $linea['nivel7_orden'], $linea['nivel8_orden'], $linea['nivel9_orden'], $linea['nivel10_orden']);
                switch ($nivel) {
                    case 1:
                        eval("\$niveles[\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 1)) . " = \$linea['id_menu'];");
                        $niveles_1 = $linea['menu_nombre'];
                        break;
                    case 2:
                        eval("\$niveles[\$niveles_1][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 2)) . " = \$linea['id_menu'];");
                        $niveles_2 = $linea['menu_nombre'];
                        break;
                    case 3:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 3)) . " = \$linea['id_menu'];");
                        $niveles_3 = $linea['menu_nombre'];
                        break;
                    case 4:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$niveles_3][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 4)) . " = \$linea['id_menu'];");
                        $niveles_4 = $linea['menu_nombre'];
                        break;
                    case 5:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$niveles_3][\$niveles_4][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 5)) . " = \$linea['id_menu'];");
                        $niveles_5 = $linea['menu_nombre'];
                        break;
                    case 6:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$niveles_3][\$niveles_4][\$niveles_5][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 6)) . " = \$linea['id_menu'];");
                        $niveles_6 = $linea['menu_nombre'];
                        break;
                    case 7:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$niveles_3][\$niveles_4][\$niveles_5][\$niveles_6][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 7)) . " = \$linea['id_menu'];");
                        $niveles_7 = $linea['menu_nombre'];
                        break;
                    case 8:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$niveles_3][\$niveles_4][\$niveles_5][\$niveles_6][\$niveles_7][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 8)) . " = \$linea['id_menu'];");
                        $niveles_8 = $linea['menu_nombre'];
                        break;
                    case 9:
                        eval("\$niveles[\$niveles_1][\$niveles_2][\$niveles_3][\$niveles_4][\$niveles_5][\$niveles_6][\$niveles_7][\$niveles_8][\$linea['menu_nombre']]" . str_repeat('[]', ($parametros['numero_niveles'] - 9)) . " = \$linea['id_menu'];");
                        $niveles_9 = $linea['menu_nombre'];
                        break;
                    case 10:
                        $niveles[$niveles_1][$niveles_2][$niveles_3][$niveles_4][$niveles_5][$niveles_6][$niveles_7][$niveles_8][$niveles_9][$linea['menu_nombre']] = $linea['id_menu'];
                        break;
                }
            }
        }

        return $niveles;
    }

    public function obtenerIdMenu($nivel_1, $nivel_2 = '', $nivel_3 = '', $nivel_4 = '', $nivel_5 = '', $nivel_6 = '', $nivel_7 = '', $nivel_8 = '', $nivel_9 = '', $nivel_10 = '') {
        $menu = $this->obtenerNivelesNombresArray();

        $nivel = self::_obtenerNivel($nivel_1, $nivel_2, $nivel_3, $nivel_4, $nivel_5, $nivel_6, $nivel_7, $nivel_8, $nivel_9, $nivel_10);
        $id_menu = false;

        $parametros = $this->_obtenerParametros();

        switch ($nivel) {
            case 1:
                eval("\$id_menu = \$menu[\$nivel_1]" . str_repeat('[0]', ($parametros['numero_niveles'] - 1)) . ";");
                break;
            case 2:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2]" . str_repeat('[0]', ($parametros['numero_niveles'] - 2)) . ";");
                break;
            case 3:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3]" . str_repeat('[0]', ($parametros['numero_niveles'] - 3)) . ";");
                break;
            case 4:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4]" . str_repeat('[0]', ($parametros['numero_niveles'] - 4)) . ";");
                break;
            case 5:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4][\$nivel_5]" . str_repeat('[0]', ($parametros['numero_niveles'] - 5)) . ";");
                break;
            case 6:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4][\$nivel_5][\$nivel_6]" . str_repeat('[0]', ($parametros['numero_niveles'] - 6)) . ";");
                break;
            case 7:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4][\$nivel_5][\$nivel_6][\$nivel_7]" . str_repeat('[0]', ($parametros['numero_niveles'] - 7)) . ";");
                break;
            case 8:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4][\$nivel_5][\$nivel_6][\$nivel_7][\$nivel_8]" . str_repeat('[0]', ($parametros['numero_niveles'] - 8)) . ";");
                break;
            case 9:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4][\$nivel_5][\$nivel_6][\$nivel_7][\$nivel_8][\$nivel_9]" . str_repeat('[0]', ($parametros['numero_niveles'] - 9)) . ";");
                break;
            case 10:
                eval("\$id_menu = \$menu[\$nivel_1][\$nivel_2][\$nivel_3][\$nivel_4][\$nivel_5][\$nivel_6][\$nivel_7][\$nivel_8][\$nivel_9][\$nivel_10];");
                break;
        }

        return $id_menu;
    }

    public function obtenerIdRelacionados($id_menu) {

        $parametros = $this->_obtenerParametros();
        eval('$datos_relacionados = ' . $this->_clase_de_bd . '::obtenerTablaCampoRelacionados($parametros["destino_id_cp"]);');
        eval('$id_relacionados = ' . $this->_clase_de_bd . '::obtenerIdRelacionados($this->_tabla_nombre, $datos_relacionados["tabla"], $id_menu);');

        $id_relacionados_return = array();

        if (is_array($id_relacionados)) {
            foreach ($id_relacionados as $id) {
                $id_relacionados_return[] = $id['id'];
            }
        }

        return $id_relacionados_return;
    }

    public function obtenerNivelNombre($id_menu) {

        $array_menus = $this->obtenerNivelesNombres();

        foreach ($array_menus as $nombre_menu => $id_menu_array) {
            if ($id_menu_array == $id_menu) {
                return $nombre_menu;
            }
        }

        return false;
    }

    public function obtenerNombreDeArray($nombre) {
        $nombre_limpio = substr($nombre, 1, -1);
        return explode('"/"', $nombre_limpio);
    }

    public function obtenerIdMenuPadre($id_menu) {
        $array_id_menu = $this->obtenerNombreDeArray($this->obtenerNivelNombre($id_menu));
        if (is_array($array_id_menu) && (count($array_id_menu) > 1)) {
            array_pop($array_id_menu);
            eval("\$array = \$this->obtenerIdMenu('" . implode("', '", $array_id_menu) . "');");
            return $array;
        } else {
            return false;
        }
    }

    public function obtenerIdMenuHijos($id_menu) {
        $array_id_menu = $this->obtenerNombreDeArray($this->obtenerNivelNombre($id_menu));
        $array_nombres = $this->obtenerNivelesNombresArray();
        if (is_array($array_id_menu) && is_array($array_nombres)) {
            $array_final = array();
            @eval("\$array_nvo = \$array_nombres['" . implode("']['", $array_id_menu) . "'];");
            if (is_array($array_nvo)) {
                foreach ($array_nvo as $nombre_menu => $id_menu_array) {
                    if ($nombre_menu != '0') {
                        eval("\$id_menu_nombre = \$this->obtenerIdMenu('" . implode("', '", $array_id_menu) . "', '" . $nombre_menu . "' );");
                        $array_final[] = $id_menu_nombre;
                    }
                }
                return $array_final;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function buscarIdRelacionadosNombreMenu($texto = null) {

        if (trim($texto) === null) {
            return false;
        }

        $array_menus = $this->obtenerNivelesNombres();

        $id_menu = array();
        foreach ($array_menus as $nombre_menu => $id_menu_array) {
            if (stripos($nombre_menu, $texto) !== false) {
                $id_menu_relacionados = $this->obtenerIdRelacionados($id_menu_array);
                if (is_array($id_menu_relacionados)) {
                    foreach ($id_menu_relacionados as $id_menu_relacionados_ids) {
                        if (array_search($id_menu_relacionados_ids, $id_menu) === false) {
                            $id_menu[] = $id_menu_relacionados_ids;
                        }
                    }
                }
            }
        }

        if (count($id_menu) > 0) {
            return $id_menu;
        } else {
            return false;
        }
    }

    public function tablaNombre($tabla_nombre) {
        $this->_tabla_nombre = $tabla_nombre;

        $tabla_nombre_elementos = explode('_', $tabla_nombre);
        $c_tabla_prefijo = array_shift($tabla_nombre_elementos);
        $c_tabla_nombre = implode('_', $tabla_nombre_elementos);
        eval('$c_id_tabla = ' . $this->_clase_de_bd . '::obtenerIdTabla($c_tabla_prefijo, $c_tabla_nombre);');

        $this->_id_tabla = $c_id_tabla[0]['id_tabla'];
    }

    public function links($link) {
        self::$_link = $link;
    }

    public function idMenu($id_menu) {
        self::$_id_menu = $id_menu;
    }

    public function classGeneral($class_general) {
        self::$_class_general = $class_general;
    }

    public function classActual($class_actual) {
        self::$_class_actual = $class_actual;
    }

    public function classGeneralNivel1($class_general) {
        self::$_class_general_nivel1 = $class_general;
    }

    public function classActualNivel1($class_actual) {
        self::$_class_actual_nivel1 = $class_actual;
    }

    public function classGeneralNivel2($class_general) {
        self::$_class_general_nivel2 = $class_general;
    }

    public function classActualNivel2($class_actual) {
        self::$_class_actual_nivel2 = $class_actual;
    }

    public function classGeneralNivel3($class_general) {
        self::$_class_general_nivel3 = $class_general;
    }

    public function classActualNivel3($class_actual) {
        self::$_class_actual_nivel3 = $class_actual;
    }

    public function classGeneralNivel4($class_general) {
        self::$_class_general_nivel4 = $class_general;
    }

    public function classActualNivel4($class_actual) {
        self::$_class_actual_nivel4 = $class_actual;
    }

    public function classGeneralNivel5($class_general) {
        self::$_class_general_nivel5 = $class_general;
    }

    public function classActualNivel5($class_actual) {
        self::$_class_actual_nivel5 = $class_actual;
    }

    public function classGeneralNivel6($class_general) {
        self::$_class_general_nivel6 = $class_general;
    }

    public function classActualNivel6($class_actual) {
        self::$_class_actual_nivel6 = $class_actual;
    }

    public function classGeneralNivel7($class_general) {
        self::$_class_general_nivel7 = $class_general;
    }

    public function classActualNivel7($class_actual) {
        self::$_class_actual_nivel7 = $class_actual;
    }

    public function classGeneralNivel8($class_general) {
        self::$_class_general_nivel8 = $class_general;
    }

    public function classActualNivel8($class_actual) {
        self::$_class_actual_nivel8 = $class_actual;
    }

    public function classGeneralNivel9($class_general) {
        self::$_class_general_nivel9 = $class_general;
    }

    public function classActualNivel9($class_actual) {
        self::$_class_actual_nivel9 = $class_actual;
    }

    public function classGeneralNivel10($class_general) {
        self::$_class_general_nivel10 = $class_general;
    }

    public function classActualNivel10($class_actual) {
        self::$_class_actual_nivel10 = $class_actual;
    }

    private function _obtenerParametros() {

        $parametros = array();

        eval('$parametros_tabla = ' . $this->_clase_de_bd . '::obtenerParametros($this->_id_tabla);');

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        return $parametros;
    }

    static private function _armarMenu($menus, $numero_niveles, $solo_ultimo_nivel_habilitado) {
        $menu_armar = '';
        $nivel_ant = array('', '', '', '');
        if (is_array($menus)) {
            foreach ($menus as $linea) {
                $nivel_act = array($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden'], $linea['nivel5_orden'], $linea['nivel6_orden'], $linea['nivel7_orden'], $linea['nivel8_orden'], $linea['nivel9_orden'], $linea['nivel10_orden']);
                $nivel = self::_obtenerNivel($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden'], $linea['nivel5_orden'], $linea['nivel6_orden'], $linea['nivel7_orden'], $linea['nivel8_orden'], $linea['nivel9_orden'], $linea['nivel10_orden']);
                $menu_armar .= self::_nivelIr($numero_niveles, $solo_ultimo_nivel_habilitado, $nivel_ant, $nivel_act, $linea['id_menu'], $linea['menu_nombre'], $linea['cantidad'], $nivel);
                $nivel_ant = $nivel_act;
            }
            // cierre del menu
            $menu_armar .= self::_cerrarMenu($nivel_act);
        }
        return $menu_armar;
    }

    static private function _nivelIr($numero_niveles, $solo_ultimo_nivel_habilitado, $nivel_ant, $nivel_act, $id_menu, $menu_nombre, $cantidad, $nivel) {
        $subir = '';
        $bajar = '';
        for ($i = 0; $i < $numero_niveles; ++$i) {
            if (isset($nivel_ant[$i]) && ($nivel_ant[$i] != '') && ($nivel_act[$i] == '')) {
                $bajar .= str_repeat(' ', $nivel) . '</ul>' . "\n";
            }
            if (($nivel_act[$i] != '') && ($nivel_ant[$i] == '')) {
                $subir .= str_repeat(' ', $nivel) . '<ul>' . "\n";
            }
        }

        if ($solo_ultimo_nivel_habilitado != 's') {
            $cantidad_elementos = 'cantidad="' . $cantidad . '"';
        } elseif (($solo_ultimo_nivel_habilitado == 's') && ($numero_niveles != $nivel)) {
            $cantidad_elementos = '';
        } else {
            $cantidad_elementos = 'cantidad="' . $cantidad . '"';
        }

        $class = self::$_class_general;

        if (isset(self::$_class_actual) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
            $class = self::$_class_actual;
        }

        switch ($nivel) {
            case 1:
                if (isset(self::$_class_actual_nivel1) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel1;
                } elseif (isset(self::$_class_general_nivel1)) {
                    $class = self::$_class_general_nivel1;
                }
                break;

            case 2:
                if (isset(self::$_class_actual_nivel2) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel2;
                } elseif (isset(self::$_class_general_nivel2)) {
                    $class = self::$_class_general_nivel2;
                }
                break;
            case 3:
                if (isset(self::$_class_actual_nivel3) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel3;
                } elseif (isset(self::$_class_general_nivel3)) {
                    $class = self::$_class_general_nivel3;
                }
                break;
            case 4:
                if (isset(self::$_class_actual_nivel4) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel4;
                } elseif (isset(self::$_class_general_nivel4)) {
                    $class = self::$_class_general_nivel4;
                }
                break;
            case 5:
                if (isset(self::$_class_actual_nivel5) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel5;
                } elseif (isset(self::$_class_general_nivel5)) {
                    $class = self::$_class_general_nivel5;
                }
                break;
            case 6:
                if (isset(self::$_class_actual_nivel6) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel6;
                } elseif (isset(self::$_class_general_nivel6)) {
                    $class = self::$_class_general_nivel6;
                }
                break;
            case 7:
                if (isset(self::$_class_actual_nivel7) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel7;
                } elseif (isset(self::$_class_general_nivel7)) {
                    $class = self::$_class_general_nivel7;
                }
                break;
            case 8:
                if (isset(self::$_class_actual_nivel8) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel8;
                } elseif (isset(self::$_class_general_nivel8)) {
                    $class = self::$_class_general_nivel8;
                }
                break;
            case 9:
                if (isset(self::$_class_actual_nivel9) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel9;
                } elseif (isset(self::$_class_general_nivel9)) {
                    $class = self::$_class_general_nivel9;
                }
                break;
            case 10:
                if (isset(self::$_class_actual_nivel10) && isset(self::$_id_menu) && (self::$_id_menu == $id_menu)) {
                    $class = self::$_class_actual_nivel10;
                } elseif (isset(self::$_class_general_nivel10)) {
                    $class = self::$_class_general_nivel10;
                }
                break;
        }

        if ((!isset(self::$_id_menu) || (self::$_id_menu != $id_menu)) && ($cantidad_elementos != '')) {
            $link_inicio = '<a href="' . ArmadoLinks::url(array(self::$_link, array($menu_nombre, $id_menu))) . '">';
            $link_fin = '</a>';
        } else {
            $link_inicio = '';
            $link_fin = '';
        }

        $subir .= str_repeat(' ', $nivel) . '<li id="' . $id_menu . '" class="' . $class . '" nivel="' . $nivel . '" ' . $cantidad_elementos . '>' . $link_inicio . $menu_nombre . $link_fin . "<li>\n";

        return $bajar . $subir;
    }

    static private function _cerrarMenu($nivel_act) {
        if ($nivel_act[9] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[8] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[7] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[6] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[5] != '') {
            return '</ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[4] != '') {
            return '</ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[3] != '') {
            return '</ul></ul></ul></ul>';
        } elseif ($nivel_act[2] != '') {
            return '</ul></ul></ul>';
        } elseif ($nivel_act[1] != '') {
            return '</ul></ul>';
        } elseif ($nivel_act[0] != '') {
            return '</ul>';
        }
    }

    static private function _obtenerNivel($nivel1, $nivel2, $nivel3, $nivel4, $nivel5, $nivel6, $nivel7, $nivel8, $nivel9, $nivel10) {
        if ($nivel10 != '') {
            return 10;
        }
        if ($nivel9 != '') {
            return 9;
        }
        if ($nivel8 != '') {
            return 8;
        }
        if ($nivel7 != '') {
            return 7;
        }
        if ($nivel6 != '') {
            return 6;
        }
        if ($nivel5 != '') {
            return 5;
        }
        if ($nivel4 != '') {
            return 4;
        }
        if ($nivel3 != '') {
            return 3;
        }
        if ($nivel2 != '') {
            return 2;
        }
        return 1;
    }

}

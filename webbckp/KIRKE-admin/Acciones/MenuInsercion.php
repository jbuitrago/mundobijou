<?php

class Acciones_MenuInsercion extends Armado_Plantilla {

    private $_agregarNivel;
    private $_idMenu;
    private $_idTabla;
    private $_nivel1Insertar;
    private $_nivel2Insertar;
    private $_nivel3Insertar;
    private $_nivel4Insertar;
    private $_nivel;
    private $_idioma = array();
    private $_idiomaTexto = array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        Armado_Menu::reinciarMenu();

        if (is_array(Inicio::confVars('idiomas'))) {
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                if (trim($_POST['etiqueta_' . $value]) != '') {
                    $this->_idioma[$contador] = $value;
                    $this->_idiomaTexto[$contador] = $_POST['etiqueta_' . $value];
                    $contador++;
                }
            }
        }

        $this->_agregarNivel = $_POST['agregar_nivel'];
        $this->_idMenu = $_POST['id_menu'];

        if (($this->_agregarNivel == 'agregar_nivel_inicio') && ($contador > 0)) {
            $this->_agregarNivel = 'mismo_nivel';
            $this->_insertarMenu();
        } elseif (($this->_agregarNivel == 'mismo_nivel') && ($contador > 0)) {
            $this->_insertarMenu();
        } elseif (($this->_agregarNivel == 'agregar_subnivel') && ($contador > 0)) {
            $this->_insertarMenu();
        } elseif (($this->_agregarNivel == 'modificar') && ($contador > 0)) {
            $this->_modificarMenu();
        } elseif ($this->_agregarNivel == 'eliminar') {
            $this->_eliminarMenu();
            $this->_eliminarNivel();
        }

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '15');
        $armado_botonera->armar('redirigir', $parametros);
    }

    // cuando se carga cualquier nivel salvo el 'nivel inicial'

    private function _insertarMenu() {

        // elementos del menu
        $matriz = Consultas_Menu::RegistroConsultaTodos(__FILE__, __LINE__);

        if (!is_array($matriz)) {
            $this->_insertarNivel('1');
            return true;
        }

        // recorro los valores de los menus existentes para modificar los valores
        $primera_vez = true;
        foreach ($matriz as $linea => $valor) {

            // se obtiene el ultimo nivel que cambia y cargo los valores de los niveles
            if ($valor['id_menu'] == $this->_idMenu) {

                $this->_nivel = Generales_MenuObtenerNivel::nivel($valor['nivel1_orden'], $valor['nivel2_orden'], $valor['nivel3_orden'], $valor['nivel4_orden']);
                $this->_nivel1Insertar = $valor['nivel1_orden'];
                $this->_nivel2Insertar = $valor['nivel2_orden'];
                $this->_nivel3Insertar = $valor['nivel3_orden'];
                $this->_nivel4Insertar = $valor['nivel4_orden'];
                $primera_vez = true;
                // sino es el primer nivel que se carga
            } elseif ($this->_idMenu == 0) {

                $this->_nivel = 1;
                $this->_nivel1Insertar = 0;
                $this->_nivel2Insertar = '';
                $this->_nivel3Insertar = '';
                $this->_nivel4Insertar = '';

                if (!isset($matriz[1])) {
                    $primera_vez = true;
                }
            }

            // cuando se llega al nivel que se modifica, se realiza lo siguiente
            if ($this->_nivel != '') {

                // se agrega en el mismo nivel
                if ($this->_agregarNivel == 'mismo_nivel') {

                    if ($primera_vez == true) {
                        // insertar en vinel actual
                        $this->_insertarNivel($this->_nivel);
                        $primera_vez = false;
                    }

                    if (
                            ($this->_nivel == 4) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] > $this->_nivel4Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], $this->_nivel, $valor['nivel4_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 3) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] > $this->_nivel3Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], $this->_nivel, $valor['nivel3_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 2) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] > $this->_nivel2Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], $this->_nivel, $valor['nivel2_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 1) &&
                            ($valor['nivel1_orden'] > $this->_nivel1Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], $this->_nivel, $valor['nivel1_orden'] + 1);
                    }
                    // se agrega en un nivel inferiorel al seleccionado
                } elseif ($this->_agregarNivel == 'agregar_subnivel') {

                    // en nivel 4 no se pueden agregar subniveles
                    if (( $primera_vez == true ) && ( $this->_nivel != 4 )) {
                        // un nivel mas que el actual
                        $this->_insertarNivel($this->_nivel + 1);
                        $primera_vez = false;
                    }

                    if ($this->_nivel == 4) {
                        // en nivel 4 no se pueden agregar subniveles
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] > 0) &&
                            ($this->_nivel == 3)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], 4, $valor['nivel4_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] > 0) &&
                            ($this->_nivel == 2)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], 3, $valor['nivel3_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] > 0) &&
                            ($this->_nivel == 1)
                    ) {
                        $this->_actualizarNivel($valor['id_menu'], 2, $valor['nivel2_orden'] + 1);
                    }
                }
            }
        }
    }

    private function _modificarMenu() {

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_nombre', 'id_menu', $this->_idMenu);

        // carga las etiquetas del menu
        foreach ($this->_idioma as $key => $value) {

            // crear parametro idiomas
            Consultas_MenuNombre::RegistroCrear(__FILE__, __LINE__, $this->_idMenu, $this->_idioma[$key], $this->_idiomaTexto[$key]);
        }
    }

    private function _eliminarMenu() {

        // elementos del menu
        $matriz = Consultas_Menu::RegistroConsultaTodos(__FILE__, __LINE__);
        $primer_elemento = false;

        // recorro los valores de los menus existentes para modificar los valores
        if (is_array($matriz)) {
            foreach ($matriz as $linea => $valor) {

                // se obtiene el ultimo nivel que cambia y cargo los valores de los niveles
                if ($valor['id_menu'] == $this->_idMenu) {
                    $this->_nivel = Generales_MenuObtenerNivel::nivel($valor['nivel1_orden'], $valor['nivel2_orden'], $valor['nivel3_orden'], $valor['nivel4_orden']);
                    $this->nivel1_modificar = $valor['nivel1_orden'];
                    $this->nivel2_modificar = $valor['nivel2_orden'];
                    $this->nivel3_modificar = $valor['nivel3_orden'];
                    $this->nivel4_modificar = $valor['nivel4_orden'];

                    // cuando el elemento borrado es el primero en su nivel, hay que eliminar ese nivel y correr todos los elementos un nivel.
                    if (
                            (
                            ($valor['nivel' . $this->_nivel . '_orden'] == 1) &&
                            ($this->_nivel < 4) &&
                            isset($matriz[$linea + 1]) &&
                            ($matriz[$linea + 1]['nivel' . ($this->_nivel + 1) . '_orden'] == 1)
                            ) || (
                            ($this->_nivel == 1) &&
                            isset($matriz[$linea + 2]) &&
                            ($matriz[$linea + 2]['nivel3_orden'] == 1)
                            )
                    ) {
                        $primer_elemento = 'correr_niveles';
                    } elseif (
                            ($this->_nivel == 1) &&
                            isset($matriz[$linea + 2]) &&
                            ($matriz[$linea + 2]['nivel1_orden'] == $valor['nivel1_orden']) &&
                            ($matriz[$linea + 2]['nivel3_orden'] == '')
                    ) {
                        // ver ya que en realidad se debería correr todo un nivel
                        $primer_elemento = 'suma_nivel_1';
                    }
                }

                // cuando se llega al nivel que se modifica, se realiza lo siguiente
                if ($this->_nivel != '') {
                    if (
                            ($this->_nivel == 4) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] > $this->nivel4_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_menu'], $this->_nivel, $valor['nivel4_orden'] - 1);
                    } elseif (
                            ($this->_nivel == 3) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel3_orden'] > $this->nivel3_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_menu'], $this->_nivel, $valor['nivel3_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel3_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_menu'], 3, $valor['nivel4_orden']);
                            $this->_actualizarNivelEliminar($valor['id_menu'], 4, '');
                        }
                    } elseif (
                            ($this->_nivel == 2) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel2_orden'] > $this->nivel2_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_menu'], $this->_nivel, $valor['nivel2_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel2_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_menu'], 2, $valor['nivel3_orden']);
                            $this->_actualizarNivelEliminar($valor['id_menu'], 3, $valor['nivel4_orden']);
                            $this->_actualizarNivelEliminar($valor['id_menu'], 4, '');
                        }
                    } elseif (
                            ($this->_nivel == 1)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel1_orden'] > $this->nivel1_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_menu'], $this->_nivel, $valor['nivel1_orden'] - 1);
                        } elseif (
                                $primer_elemento == 'correr_niveles'
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_menu'], 2, $valor['nivel3_orden']);
                            $this->_actualizarNivelEliminar($valor['id_menu'], 3, $valor['nivel4_orden']);
                            $this->_actualizarNivelEliminar($valor['id_menu'], 4, '');
                        } elseif (
                                ($primer_elemento == 'suma_nivel_1') &&
                                ($valor['id_menu'] != $this->_idMenu)
                        ) {
                            if (!isset($primero)) {
                                $this->_actualizarNivelEliminar($valor['id_menu'], 2, '');
                                $nivel1_ant = $valor['nivel1_orden'];
                                $nivel2_ant = $valor['nivel2_orden'];
                                $primero = false;
                            } else {
                                if (
                                        ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                                        ($nivel2_ant != $valor['nivel2_orden'])
                                ) {
                                    $nivel1_ant++;
                                }
                                if ($valor['nivel1_orden'] == $this->nivel1_modificar) {
                                    $this->_actualizarNivelEliminar($valor['id_menu'], 1, $nivel1_ant);
                                    $this->_actualizarNivelEliminar($valor['id_menu'], 2, $valor['nivel3_orden']);
                                    $this->_actualizarNivelEliminar($valor['id_menu'], 3, $valor['nivel4_orden']);
                                    $this->_actualizarNivelEliminar($valor['id_menu'], 4, '');
                                    $nivel2_ant = $valor['nivel2_orden'];
                                } else {
                                    if (!isset($primero_otros)) {
                                        $nivel1_ant++;
                                        $dif_nivel1_otros = $nivel1_ant - $valor['nivel1_orden'];
                                        $primero_otros = false;
                                    } else {
                                        $nivel1_ant = $valor['nivel1_orden'] + $dif_nivel1_otros;
                                    }
                                    $this->_actualizarNivelEliminar($valor['id_menu'], 1, $nivel1_ant);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function _insertarNivel($nivel) {

        if ($nivel == 1) {
            $nivel1 = $this->_nivel1Insertar + 1;
            $nivel2 = 'NULL';
            $nivel3 = 'NULL';
            $nivel4 = 'NULL';
        } elseif ($nivel == 2) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar + 1;
            $nivel3 = 'NULL';
            $nivel4 = 'NULL';
        } elseif ($nivel == 3) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar + 1;
            $nivel4 = 'NULL';
        } elseif ($nivel == 4) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar + 1;
        }

        // se inserta el nuevo nivel
        $id_insertado = Consultas_Menu::Bases_RegistroCrear(__FILE__, __LINE__, $nivel1, $nivel2, $nivel3, $nivel4);
        $id_menu_crear = $id_insertado['id'];

        // carga las etiquetas del menu
        if (count($this->_idioma) > 0) {
            foreach ($this->_idioma as $key => $value) {
                // crear parametro idiomas
                Consultas_MenuNombre::RegistroCrear(__FILE__, __LINE__, $id_menu_crear, $this->_idioma[$key], $this->_idiomaTexto[$key]);
            }
        }
    }

    private function _eliminarNivel() {

        $id_menu_link = Consultas_MenuLink::RegistroConsultaIdMenu(__FILE__, __LINE__, $this->_idMenu);
        if (is_array($id_menu_link)) {
            $id_menu_link = $id_menu_link[0]['id_menu_link'];
            // elimino los registros de kirke_menu_link_nombre
            Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link_nombre', 'id_menu_link', $id_menu_link);
            // elimino los registros de kirke_menu_link_parametro
            Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link_parametro', 'id_menu_link', $id_menu_link);
        }
        // elimino los registros de kirke_menu
        Consultas_Menu::RegistroEliminar(__FILE__, __LINE__, $this->_idMenu);
        // elimino los registros de kirke_menu_nombre
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_nombre', 'id_menu', $this->_idMenu);
        // elimino los registros de kirke_menu_link
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link', 'id_menu', $this->_idMenu);
    }

    private function _actualizarNivel($id_nivel, $nivel, $nivel_valor) {
        if ($nivel_valor != '') {
            Consultas_Menu::RegistroModificar(__FILE__, __LINE__, $nivel, $nivel_valor, $id_nivel);
        }
    }

    private function _actualizarNivelEliminar($id_nivel, $nivel, $nivel_valor) {

        // actualizo los niveles que correspondan
        if ($nivel_valor < 1) {
            $nivel_valor = 'NULL';
        }
        Consultas_Menu::RegistroModificar(__FILE__, __LINE__, $nivel, $nivel_valor, $id_nivel);
    }

    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|MenuInsercion';

        $contenido .= '|agregarNivel:' . $this->_agregarNivel;
        $contenido .= '|id_menu:' . $this->_idMenu;

        $contenido .= '|idiomas:';
        $coma = '';
        if (is_array(Inicio::confVars('idiomas'))) {
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                if (trim($_POST['etiqueta_' . $value]) != '') {
                    $contenido .= $coma . '[' . $value . ':' . $_POST['etiqueta_' . $value] . ']';
                    $coma = ',';
                }
            }
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

<?php

class Acciones_RegistroMenuInsercion {

    private $_agregarNivel;
    private $_idMenu;
    private $_idMenuNuevo;
    private $_idTabla;
    private $_tablaNombre;
    private $_nivel1Insertar;
    private $_nivel2Insertar;
    private $_nivel3Insertar;
    private $_nivel4Insertar;
    private $_nivel5Insertar;
    private $_nivel6Insertar;
    private $_nivel7Insertar;
    private $_nivel8Insertar;
    private $_nivel9Insertar;
    private $_nivel10Insertar;
    private $_nivel;
    private $_idioma = array();
    private $_idiomaTexto = array();
    private $_numero_niveles;
    private $_niveles_protegidos;
    private $_tabla_relacionada;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $this->_validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'ver');

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

        // se obtiene el nombre de la pagina
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $this->_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        $this->_agregarNivel = $_POST['agregar_nivel'];
        $this->_idMenu = $_POST['id_menu'];

        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $_GET['id_tabla']);

        if (is_array($parametros_tabla)) {
            $parametros = array();
            foreach ($parametros_tabla as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        $this->_numero_niveles = $parametros['numero_niveles'];
        $this->_niveles_protegidos = $parametros['niveles_protegidos'];

        $accion = false;
        if (($this->_agregarNivel == 'agregar_nivel_inicio') && ($contador > 0)) {
            $this->_agregarNivel = 'mismo_nivel';
            $this->_insertarMenu();
            $accion = 'a';
        } elseif (($this->_agregarNivel == 'mismo_nivel') && ($contador > 0)) {
            $this->_insertarMenu();
            $accion = 'a';
        } elseif (($this->_agregarNivel == 'agregar_subnivel') && ($contador > 0)) {
            $this->_insertarMenu();
            $accion = 'a';
        } elseif (($this->_agregarNivel == 'modificar') && ($contador > 0)) {
            $this->_modificarMenu();
            $accion = 'm';
        } elseif ($this->_agregarNivel == 'eliminar') {
            $this->_eliminarMenu();
            $this->_eliminarNivel();
            $accion = 'b';
        }

        // llamado al proceso especial de la pagina
        if (file_exists(Inicio::path() . '/ProcesosEspeciales/' . $this->_tablaNombre . '.php')) {

            include_once( Inicio::path() . '/ProcesosEspeciales/' . $this->_tablaNombre . '.php' );

            $clase = $this->_tablaNombre;
            $proceso_especial = new $clase();

            if ($accion == 'a') {
                $proceso_especial->Alta($this->_idMenuNuevo, $this->_tablaNombre);
            } elseif ($accion == 'b') {
                $proceso_especial->Baja($this->_idMenu, $this->_tablaNombre);
            } elseif ($accion == 'm') {
                $proceso_especial->Modificacion($this->_idMenu, $this->_tablaNombre);
            }
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => 0);
        $armado_botonera->armar('redirigir', $parametros);
    }

    // cuando se carga cualquier nivel salvo el 'nivel inicial'

    private function _insertarMenu() {

        // elementos del menu
        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tablaNombre);
        $consulta->tablas($this->_tablaNombre . '_trd');
        $consulta->campos($this->_tablaNombre, 'id_' . $this->_tablaNombre);
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
        $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre);
        $consulta->condiciones('y', $this->_tablaNombre . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
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
        $matriz = $consulta->realizarConsulta();

        if (!is_array($matriz)) {
            $this->_insertarNivel('1');
            return true;
        }

        // recorro los valores de los menus existentes para modificar los valores
        $primera_vez = true;
        foreach ($matriz as $linea => $valor) {

            // se obtiene el ultimo nivel que cambia y cargo los valores de los niveles
            if ($valor['id_' . $this->_tablaNombre] == $this->_idMenu) {

                $this->_nivel = Generales_MenuObtenerNivel::nivel($valor['nivel1_orden'], $valor['nivel2_orden'], $valor['nivel3_orden'], $valor['nivel4_orden'], $valor['nivel5_orden'], $valor['nivel6_orden'], $valor['nivel7_orden'], $valor['nivel8_orden'], $valor['nivel9_orden'], $valor['nivel10_orden']);
                $this->_nivel1Insertar = $valor['nivel1_orden'];
                $this->_nivel2Insertar = $valor['nivel2_orden'];
                $this->_nivel3Insertar = $valor['nivel3_orden'];
                $this->_nivel4Insertar = $valor['nivel4_orden'];
                $this->_nivel5Insertar = $valor['nivel5_orden'];
                $this->_nivel6Insertar = $valor['nivel6_orden'];
                $this->_nivel7Insertar = $valor['nivel7_orden'];
                $this->_nivel8Insertar = $valor['nivel8_orden'];
                $this->_nivel9Insertar = $valor['nivel9_orden'];
                $this->_nivel10Insertar = $valor['nivel10_orden'];
                $primera_vez = true;
                // sino es el primer nivel que se carga
            } elseif ($this->_idMenu == 0) {

                $this->_nivel = 1;
                $this->_nivel1Insertar = 0;
                $this->_nivel2Insertar = '';
                $this->_nivel3Insertar = '';
                $this->_nivel4Insertar = '';
                $this->_nivel5Insertar = '';
                $this->_nivel6Insertar = '';
                $this->_nivel7Insertar = '';
                $this->_nivel8Insertar = '';
                $this->_nivel9Insertar = '';
                $this->_nivel10Insertar = '';

                if (!isset($matriz[1])) {
                    $primera_vez = true;
                }
            }

            // cuando se llega al nivel que se modifica, se realiza lo siguiente
            if (
                    ($this->_nivel != '') &&
                    (
                    ($this->_nivel > $this->_niveles_protegidos) && ($this->_agregarNivel == 'mismo_nivel') ||
                    ($this->_nivel >= $this->_niveles_protegidos) && ($this->_agregarNivel == 'agregar_subnivel')
                    )
            ) {

                // se agrega en el mismo nivel
                if ($this->_agregarNivel == 'mismo_nivel') {

                    if ($primera_vez == true) {
                        // insertar en vinel actual
                        $this->_insertarNivel($this->_nivel);
                        $primera_vez = false;
                    }

                    if (
                            ($this->_nivel == 10) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] == $this->_nivel7Insertar) &&
                            ($valor['nivel8_orden'] == $this->_nivel8Insertar) &&
                            ($valor['nivel9_orden'] == $this->_nivel9Insertar) &&
                            ($valor['nivel10_orden'] > $this->_nivel10Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel10_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 9) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] == $this->_nivel7Insertar) &&
                            ($valor['nivel8_orden'] == $this->_nivel8Insertar) &&
                            ($valor['nivel9_orden'] > $this->_nivel9Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel9_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 8) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] == $this->_nivel7Insertar) &&
                            ($valor['nivel8_orden'] > $this->_nivel8Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel8_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 7) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] > $this->_nivel7Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel7_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 6) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] > $this->_nivel6Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel6_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 5) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] > $this->_nivel5Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel5_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 4) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] > $this->_nivel4Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel4_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 3) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] > $this->_nivel3Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel3_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 2) &&
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] > $this->_nivel2Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel2_orden'] + 1);
                    } elseif (
                            ($this->_nivel == 1) &&
                            ($valor['nivel1_orden'] > $this->_nivel1Insertar)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel1_orden'] + 1);
                    }
                    // se agrega en un nivel inferiorel al seleccionado
                } elseif ($this->_agregarNivel == 'agregar_subnivel') {

                    // en el ultimo nivel no se pueden agregar subniveles
                    if (( $primera_vez == true ) && ( $this->_nivel != $this->_numero_niveles )) {
                        // un nivel mas que el actual
                        $this->_insertarNivel($this->_nivel + 1);
                        $primera_vez = false;
                    }

                    if ($this->_nivel >= $this->_numero_niveles) {
                        // en ultimo nivel no se pueden agregar subniveles
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] == $this->_nivel7Insertar) &&
                            ($valor['nivel8_orden'] == $this->_nivel8Insertar) &&
                            ($valor['nivel9_orden'] == $this->_nivel9Insertar) &&
                            ($valor['nivel10_orden'] > 0) &&
                            ($this->_nivel == 9)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 10, $valor['nivel10_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] == $this->_nivel7Insertar) &&
                            ($valor['nivel8_orden'] == $this->_nivel8Insertar) &&
                            ($valor['nivel9_orden'] > 0) &&
                            ($this->_nivel == 8)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 9, $valor['nivel9_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] == $this->_nivel7Insertar) &&
                            ($valor['nivel8_orden'] > 0) &&
                            ($this->_nivel == 7)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 8, $valor['nivel8_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] == $this->_nivel6Insertar) &&
                            ($valor['nivel7_orden'] > 0) &&
                            ($this->_nivel == 6)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 7, $valor['nivel7_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] == $this->_nivel5Insertar) &&
                            ($valor['nivel6_orden'] > 0) &&
                            ($this->_nivel == 5)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 6, $valor['nivel6_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] == $this->_nivel4Insertar) &&
                            ($valor['nivel5_orden'] > 0) &&
                            ($this->_nivel == 4)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 5, $valor['nivel5_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] == $this->_nivel3Insertar) &&
                            ($valor['nivel4_orden'] > 0) &&
                            ($this->_nivel == 3)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 4, $valor['nivel4_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] == $this->_nivel2Insertar) &&
                            ($valor['nivel3_orden'] > 0) &&
                            ($this->_nivel == 2)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 3, $valor['nivel3_orden'] + 1);
                    } elseif (
                            ($valor['nivel1_orden'] == $this->_nivel1Insertar) &&
                            ($valor['nivel2_orden'] > 0) &&
                            ($this->_nivel == 1)
                    ) {
                        $this->_actualizarNivel($valor['id_' . $this->_tablaNombre], 2, $valor['nivel2_orden'] + 1);
                    }
                }
            }
        }
    }

    private function _modificarMenu() {

        if ($this->_niveles_protegidos > 0) {

            // elementos del menu
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->_tablaNombre);
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
            $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', '', '', $this->_idMenu);
            //$consulta->verConsulta();
            $matriz = $consulta->realizarConsulta();

            $this->_nivel = Generales_MenuObtenerNivel::nivel($matriz[0]['nivel1_orden'], $matriz[0]['nivel2_orden'], $matriz[0]['nivel3_orden'], $matriz[0]['nivel4_orden'], $matriz[0]['nivel5_orden'], $matriz[0]['nivel6_orden'], $matriz[0]['nivel7_orden'], $matriz[0]['nivel8_orden'], $matriz[0]['nivel9_orden'], $matriz[0]['nivel10_orden']);

            if (($this->_nivel == '') || ($this->_nivel <= $this->_niveles_protegidos)) {
                return false;
            }
        }

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre, $this->_idMenu);

        // carga las etiquetas del menu
        foreach ($this->_idioma as $key => $value) {

            // crear parametro idiomas
            $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
            $consulta->tabla($this->_tablaNombre . '_trd');
            $consulta->campoValor($this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre, $this->_idMenu);
            $consulta->campoValor($this->_tablaNombre . '_trd', 'idioma', $this->_idioma[$key]);
            $consulta->campoValor($this->_tablaNombre . '_trd', 'menu_nombre', $this->_idiomaTexto[$key]);
            $consulta->realizarConsulta();
        }
    }

    private function _eliminarMenu() {

        // elementos del menu
        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tablaNombre);
        $consulta->tablas($this->_tablaNombre . '_trd');
        $consulta->campos($this->_tablaNombre, 'id_' . $this->_tablaNombre);
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
        $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre);
        $consulta->condiciones('y', $this->_tablaNombre . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
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
        $matriz = $consulta->realizarConsulta();

        $primer_elemento = false;

        // recorro los valores de los menus existentes para modificar los valores
        if (is_array($matriz)) {
            foreach ($matriz as $linea => $valor) {

                // se obtiene el ultimo nivel que cambia y cargo los valores de los niveles
                if ($valor['id_' . $this->_tablaNombre] == $this->_idMenu) {
                    $this->_nivel = Generales_MenuObtenerNivel::nivel($valor['nivel1_orden'], $valor['nivel2_orden'], $valor['nivel3_orden'], $valor['nivel4_orden'], $valor['nivel5_orden'], $valor['nivel6_orden'], $valor['nivel7_orden'], $valor['nivel8_orden'], $valor['nivel9_orden'], $valor['nivel10_orden']);
                    $this->nivel1_modificar = $valor['nivel1_orden'];
                    $this->nivel2_modificar = $valor['nivel2_orden'];
                    $this->nivel3_modificar = $valor['nivel3_orden'];
                    $this->nivel4_modificar = $valor['nivel4_orden'];
                    $this->nivel5_modificar = $valor['nivel5_orden'];
                    $this->nivel6_modificar = $valor['nivel6_orden'];
                    $this->nivel7_modificar = $valor['nivel7_orden'];
                    $this->nivel8_modificar = $valor['nivel8_orden'];
                    $this->nivel9_modificar = $valor['nivel9_orden'];
                    $this->nivel10_modificar = $valor['nivel10_orden'];

                    if (($this->_nivel == '') || ($this->_nivel <= $this->_niveles_protegidos)) {
                        return false;
                    }

                    // cuando el elemento borrado es el primero en su nivel, hay que eliminar ese nivel y correr todos los elementos un nivel.
                    if (
                            (
                            ($valor['nivel' . $this->_nivel . '_orden'] == 1) &&
                            ($this->_nivel < $this->_numero_niveles) &&
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
                            ($this->_numero_niveles == 10) &&
                            ($this->_nivel == 10) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] == $this->nivel7_modificar) &&
                            ($valor['nivel8_orden'] == $this->nivel8_modificar) &&
                            ($valor['nivel9_orden'] == $this->nivel9_modificar) &&
                            ($valor['nivel10_orden'] > $this->nivel10_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel10_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 9) &&
                            ($this->_nivel == 9) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] == $this->nivel7_modificar) &&
                            ($valor['nivel8_orden'] == $this->nivel8_modificar) &&
                            ($valor['nivel9_orden'] > $this->nivel9_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel9_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 8) &&
                            ($this->_nivel == 8) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] == $this->nivel7_modificar) &&
                            ($valor['nivel8_orden'] > $this->nivel8_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel8_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 7) &&
                            ($this->_nivel == 7) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] > $this->nivel7_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel7_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 6) &&
                            ($this->_nivel == 6) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] > $this->nivel6_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel6_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 5) &&
                            ($this->_nivel == 5) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] > $this->nivel5_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel5_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 4) &&
                            ($this->_nivel == 4) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] > $this->nivel4_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel4_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 3) &&
                            ($this->_nivel == 3) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] > $this->nivel3_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel3_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 2) &&
                            ($this->_nivel == 2) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] > $this->nivel2_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel2_orden'] - 1);
                    } elseif (
                            ($this->_numero_niveles == 1) &&
                            ($this->_nivel == 1) &&
                            ($valor['nivel1_orden'] > $this->nivel1_modificar)
                    ) {
                        $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel1_orden'] - 1);
                    } elseif (
                            ($this->_nivel == 9) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] == $this->nivel7_modificar) &&
                            ($valor['nivel8_orden'] == $this->nivel8_modificar) &&
                            ($valor['nivel9_orden'] == $this->nivel9_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel10_orden'] > $this->nivel10_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel10_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel10_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 8) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] == $this->nivel7_modificar) &&
                            ($valor['nivel8_orden'] == $this->nivel8_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel9_orden'] > $this->nivel9_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel9_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel9_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 7) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar) &&
                            ($valor['nivel7_orden'] == $this->nivel7_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel8_orden'] > $this->nivel8_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel8_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel8_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 6) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar) &&
                            ($valor['nivel6_orden'] == $this->nivel6_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel7_orden'] > $this->nivel7_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel7_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel7_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 5) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar) &&
                            ($valor['nivel5_orden'] == $this->nivel5_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel6_orden'] > $this->nivel6_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel6_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel6_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 6, $valor['nivel7_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 5) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar) &&
                            ($valor['nivel4_orden'] == $this->nivel4_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel5_orden'] > $this->nivel5_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel5_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel5_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 5, $valor['nivel6_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 6, $valor['nivel7_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 4) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar) &&
                            ($valor['nivel3_orden'] == $this->nivel3_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel4_orden'] > $this->nivel4_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel4_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel4_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 4, $valor['nivel5_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 5, $valor['nivel6_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 6, $valor['nivel7_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 3) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar) &&
                            ($valor['nivel2_orden'] == $this->nivel2_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel3_orden'] > $this->nivel3_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel3_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel3_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 3, $valor['nivel4_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 4, $valor['nivel5_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 5, $valor['nivel6_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 6, $valor['nivel7_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 2) &&
                            ($valor['nivel1_orden'] == $this->nivel1_modificar)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel2_orden'] > $this->nivel2_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel2_orden'] - 1);
                        } elseif (
                                ($primer_elemento == 'correr_niveles') &&
                                ($valor['nivel2_orden'] > 0)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 2, $valor['nivel3_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 3, $valor['nivel4_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 4, $valor['nivel5_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 5, $valor['nivel6_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 6, $valor['nivel7_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        }
                    } elseif (
                            ($this->_nivel == 1)
                    ) {
                        if (
                                ($primer_elemento === false) &&
                                ($valor['nivel1_orden'] > $this->nivel1_modificar)
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], $this->_nivel, $valor['nivel1_orden'] - 1);
                        } elseif (
                                $primer_elemento == 'correr_niveles'
                        ) {
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 2, $valor['nivel3_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 3, $valor['nivel4_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 4, $valor['nivel5_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 5, $valor['nivel6_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 6, $valor['nivel7_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 7, $valor['nivel8_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 8, $valor['nivel9_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 9, $valor['nivel10_orden']);
                            $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 10, '');
                        } elseif (
                                ($primer_elemento == 'suma_nivel_1') &&
                                ($valor['id_' . $this->_tablaNombre] != $this->_idMenu)
                        ) {
                            if (!isset($primero)) {
                                $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 2, '');
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
                                    $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 1, $nivel1_ant);
                                    $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 2, $valor['nivel3_orden']);
                                    $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 3, $valor['nivel4_orden']);
                                    $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 4, '');
                                    $nivel2_ant = $valor['nivel2_orden'];
                                } else {
                                    if (!isset($primero_otros)) {
                                        $nivel1_ant++;
                                        $dif_nivel1_otros = $nivel1_ant - $valor['nivel1_orden'];
                                        $primero_otros = false;
                                    } else {
                                        $nivel1_ant = $valor['nivel1_orden'] + $dif_nivel1_otros;
                                    }
                                    $this->_actualizarNivelEliminar($valor['id_' . $this->_tablaNombre], 1, $nivel1_ant);
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
            $nivel5 = 'NULL';
            $nivel6 = 'NULL';
            $nivel7 = 'NULL';
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 2) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar + 1;
            $nivel3 = 'NULL';
            $nivel4 = 'NULL';
            $nivel5 = 'NULL';
            $nivel6 = 'NULL';
            $nivel7 = 'NULL';
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 3) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar + 1;
            $nivel4 = 'NULL';
            $nivel5 = 'NULL';
            $nivel6 = 'NULL';
            $nivel7 = 'NULL';
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 4) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar + 1;
            $nivel5 = 'NULL';
            $nivel6 = 'NULL';
            $nivel7 = 'NULL';
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 5) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar;
            $nivel5 = $this->_nivel5Insertar + 1;
            $nivel6 = 'NULL';
            $nivel7 = 'NULL';
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 6) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar;
            $nivel5 = $this->_nivel5Insertar;
            $nivel6 = $this->_nivel6Insertar + 1;
            $nivel7 = 'NULL';
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 7) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar;
            $nivel5 = $this->_nivel5Insertar;
            $nivel6 = $this->_nivel6Insertar;
            $nivel7 = $this->_nivel7Insertar + 1;
            $nivel8 = 'NULL';
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 8) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar;
            $nivel5 = $this->_nivel5Insertar;
            $nivel6 = $this->_nivel6Insertar;
            $nivel7 = $this->_nivel7Insertar;
            $nivel8 = $this->_nivel8Insertar + 1;
            $nivel9 = 'NULL';
            $nivel10 = 'NULL';
        } elseif ($nivel == 9) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar;
            $nivel5 = $this->_nivel5Insertar;
            $nivel6 = $this->_nivel6Insertar;
            $nivel7 = $this->_nivel7Insertar;
            $nivel8 = $this->_nivel8Insertar;
            $nivel9 = $this->_nivel9Insertar + 1;
            $nivel10 = 'NULL';
        } elseif ($nivel == 10) {
            $nivel1 = $this->_nivel1Insertar;
            $nivel2 = $this->_nivel2Insertar;
            $nivel3 = $this->_nivel3Insertar;
            $nivel4 = $this->_nivel4Insertar;
            $nivel5 = $this->_nivel5Insertar;
            $nivel6 = $this->_nivel6Insertar;
            $nivel7 = $this->_nivel7Insertar;
            $nivel8 = $this->_nivel8Insertar;
            $nivel9 = $this->_nivel9Insertar;
            $nivel10 = $this->_nivel10Insertar + 1;
        }

        // se inserta el nuevo nivel
        $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
        $consulta->tabla($this->_tablaNombre);
        $consulta->campoValor($this->_tablaNombre, 'nivel1_orden', $nivel1);
        $consulta->campoValor($this->_tablaNombre, 'nivel2_orden', $nivel2);
        $consulta->campoValor($this->_tablaNombre, 'nivel3_orden', $nivel3);
        $consulta->campoValor($this->_tablaNombre, 'nivel4_orden', $nivel4);
        $consulta->campoValor($this->_tablaNombre, 'nivel5_orden', $nivel5);
        $consulta->campoValor($this->_tablaNombre, 'nivel6_orden', $nivel6);
        $consulta->campoValor($this->_tablaNombre, 'nivel7_orden', $nivel7);
        $consulta->campoValor($this->_tablaNombre, 'nivel8_orden', $nivel8);
        $consulta->campoValor($this->_tablaNombre, 'nivel9_orden', $nivel9);
        $consulta->campoValor($this->_tablaNombre, 'nivel10_orden', $nivel10);
        $consulta->campoValor($this->_tablaNombre, 'habilitado', 's');
        $id_insertado = $consulta->realizarConsulta();

        $this->_idMenuNuevo = $id_insertado['id'];

        // carga las etiquetas del menu
        if (count($this->_idioma) > 0) {
            foreach ($this->_idioma as $key => $value) {
                // crear parametro idiomas
                $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
                $consulta->tabla($this->_tablaNombre . '_trd');
                $consulta->campoValor($this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre, $this->_idMenuNuevo);
                $consulta->campoValor($this->_tablaNombre . '_trd', 'idioma', $this->_idioma[$key]);
                $consulta->campoValor($this->_tablaNombre . '_trd', 'menu_nombre', $this->_idiomaTexto[$key]);
                $consulta->realizarConsulta();
            }
        }
    }

    private function _eliminarNivel() {

        if (($this->_nivel == '') || ($this->_nivel <= $this->_niveles_protegidos)) {
            return false;
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_tablaNombre . '_rel');
        $consulta->campos($this->_tablaNombre . '_rel', 'id_' . $this->_tablaNombre . '_rel');
        $consulta->condiciones('', $this->_tablaNombre . '_rel', 'id_' . $this->_tablaNombre, 'iguales', '', '', $this->_idMenu);
        $id_menu_link = $consulta->realizarConsulta();

        // elimino los registros de kirke_menu
        Consultas_Menu::RegistroEliminar(__FILE__, __LINE__, $this->_idMenu);
        // elimino los registros de kirke_menu_nombre
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $this->_tablaNombre . '_trd', 'id_' . $this->_tablaNombre, $this->_idMenu);
        // elimino los registros de kirke_menu_link
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $this->_tablaNombre . '_rel', 'id_' . $this->_tablaNombre, $this->_idMenu);
    }

    private function _actualizarNivel($id_nivel, $nivel, $nivel_valor) {

        if ($nivel_valor != '') {
            $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
            $consulta->tabla($this->_tablaNombre);
            $consulta->campoValor($this->_tablaNombre, 'nivel' . $nivel . '_orden', $nivel_valor);
            $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', '', '', $id_nivel);
            $consulta->realizarConsulta();
        }
    }

    private function _actualizarNivelEliminar($id_nivel, $nivel, $nivel_valor) {

        // actualizo los niveles que correspondan
        if ($nivel_valor < 1) {
            $nivel_valor = 'NULL';
        }
        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla($this->_tablaNombre);
        $consulta->campoValor($this->_tablaNombre, 'nivel' . $nivel . '_orden', $nivel_valor);
        $consulta->condiciones('', $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'iguales', '', '', $id_nivel);
        $consulta->realizarConsulta();
    }

}

<?php

class Acciones_RegistroModificacionInsercion {

    private $_tablaNombre;
    private $_campoInsercion = Array();
    private $_valorInsercion = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'datos');

        // se obtiene el nombre de la pagina y el id de la misma
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $this->_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tabla_tipo = $datos_tabla['tipo'];

        Generales_ControlTablasModificadas::control($this->_tablaNombre);

        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        // llamo al metodo del tipo de tabla correspondiente para obtener los valores anteriores
        // (si lo agrego mas abajo, los valores ya habrian sido modificados)
        if ($tabla_tipo == 'variables') {
            $matriz_valores_ant = Generales_ObtenerValoresTbVariables::armado($this->_tablaNombre, $matriz_componentes);
        } elseif ($tabla_tipo == 'registros') {
            $matriz_valores_ant = Generales_ObtenerValoresTbRegistros::armado($this->_tablaNombre, $matriz_componentes);
        }

        if ($matriz_componentes) {
            $i = 0;
            foreach ($matriz_componentes as $id => $value) {
                if (isset($_POST['cp_' . $value['cp_id']])) {
                    $valor = $_POST['cp_' . $value['cp_id']];
                } else {
                    $valor = '';
                }

                $valor_insertar = Generales_LlamadoAComponentesYTraduccion::armar('RegistroInsercion', '', $valor, $value, $value['cp_nombre'], $value['cp_id'], $_GET['id_tabla_registro']);

                // si la funcion de arriba devuelve 'false' no se inserta el valor
                if ($valor_insertar !== false) {
                    $this->_campoInsercion[$value['tb_campo']] = $valor_insertar;
                }
            }
        }

        // llamo al metodo del tipo de tabla correspondiente
        $agregara_registro = '_agregarRegistro' . ucwords($tabla_tipo);
        $this->$agregara_registro();

        // llamado al proceso especial de la pagina
        if (file_exists(Inicio::path() . '/ProcesosEspeciales/' . $this->_tablaNombre . '.php')) {

            include_once( Inicio::path() . '/ProcesosEspeciales/' . $this->_tablaNombre . '.php' );

            $clase = $this->_tablaNombre;
            $proceso_especial = new $clase();
            $proceso_especial->Modificacion($_GET['id_tabla_registro'], $matriz_valores_ant);
        }

        if (Inicio::confVars('generar_log') == 's') {

            if ($tabla_tipo == 'variables') {
                $matriz_valores_nvo = Generales_ObtenerValoresTbVariables::armado($this->_tablaNombre, $matriz_componentes);
            } elseif ($tabla_tipo == 'registros') {
                $matriz_valores_nvo = Generales_ObtenerValoresTbRegistros::armado($this->_tablaNombre, $matriz_componentes);
            }

            $matriz_valores_dif = array_diff($matriz_valores_nvo, $matriz_valores_ant);

            if (count($matriz_valores_dif) > 0) {

                $url_actual = getcwd();
                chdir(Inicio::path());
                chdir('Logs');
                $directorio = getcwd();
                chdir($url_actual);

                $contenido = date('Y-m-d') . '|' . date('H:i:s') . '|M|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|' . $_SERVER['HTTP_USER_AGENT'] . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $this->_tablaNombre . '|' . $_GET['id_tabla_registro'];
                foreach ($matriz_valores_dif as $k => $v) {
                    $contenido .= '|' . $k . ':' . str_replace("|", ";", $v);
                }

                file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-M_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
            }
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        if (ucwords($tabla_tipo) == 'Registros') {

            if (!isset($_GET['siguiente'])) {
                $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
            } elseif ($_GET['siguiente'] == 'ver') {
                $parametros = array('kk_generar' => '0', 'accion' => '45', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $_GET['id_tabla_registro']);
            }
        } elseif (ucwords($tabla_tipo) == 'Variables') {

            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
            $armado_botonera->armar('redirigir', $parametros);
        }

        $armado_botonera->armar('redirigir', $parametros);
        return true;
    }

    private function _agregarRegistroRegistros() {

        if (count($this->_campoInsercion) > 0) {
            // campo orden
            Consultas_RegistroModificar::armado(__FILE__, __LINE__, $this->_tablaNombre, $this->_campoInsercion, 'id_' . $this->_tablaNombre, $_GET['id_tabla_registro']);
        }
    }

    private function _agregarRegistroVariables() {

        foreach ($this->_campoInsercion as $id => $valor) {

            Consultas_RegistroModificar::armado(__FILE__, __LINE__, $this->_tablaNombre, array('valores' => $valor), 'variables', $id);
        }
    }

}

<?php

class Acciones_RegistroAltaInsercion {

    private $_tablaNombre;
    private $_campoInsercion = Array();
    private $_valorInsercion = Array();
    private $_matrizComponentes = Array();
    private $_id_insertado;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'insercion');

        // se obtiene el nombre de la pagina y el id de la misma
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $this->_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tabla_tipo = $datos_tabla['tipo'];

        Generales_ControlTablasModificadas::control($this->_tablaNombre);

        // creo una matriz con los campos de los componentes de la pagina
        $this->_matrizComponentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        $this->_agregarRegistroRegistros();

        // llamado al proceso especial de la pagina
        if (file_exists(Inicio::path() . '/ProcesosEspeciales/' . $this->_tablaNombre . '.php')) {

            include_once( Inicio::path() . '/ProcesosEspeciales/' . $this->_tablaNombre . '.php' );

            $clase = $this->_tablaNombre;
            $proceso_especial = new $clase();
            $proceso_especial->Alta($this->_id_insertado, false);
        }

        if (!isset($_GET['tipo_pagina']) || ($_GET['tipo_pagina'] != 'pop')) {

            // la redireccion va al final
            $armado_botonera = new Armado_Botonera();

            if (!isset($_GET['siguiente'])) {
                $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
            } elseif ($_GET['siguiente'] == 'ver') {
                $parametros = array('kk_generar' => '0', 'accion' => '45', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $this->_id_insertado);
            }
        }

        if (Inicio::confVars('generar_log') == 's') {

            if ($tabla_tipo == 'variables') {
                $matriz_valores_dif = Generales_ObtenerValoresTbVariables::armado($this->_tablaNombre, $this->_matrizComponentes);
            } elseif ($tabla_tipo == 'registros') {
                $matriz_valores_dif = Generales_ObtenerValoresTbRegistros::armado($this->_tablaNombre, $this->_matrizComponentes, $this->_id_insertado);
            }

            if (count($matriz_valores_dif) > 0) {

                $url_actual = getcwd();
                chdir(Inicio::path());
                chdir('Logs');
                $directorio = getcwd();
                chdir($url_actual);

                $contenido = date('Y-m-d') . '|' . date('H:i:s') . '|A|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|' . $_SERVER['HTTP_USER_AGENT'] . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $this->_tablaNombre . '|' . $this->_id_insertado;
                foreach ($matriz_valores_dif as $k => $v) {
                    $contenido .= '|' . $k . ':' . str_replace("|", ";", $v);
                }

                file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-A_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
            }
        }

        if (!isset($_GET['tipo_pagina']) || ($_GET['tipo_pagina'] != 'pop')) {
            $armado_botonera->armar('redirigir', $parametros);
        } else {
            echo '<!DOCTYPE html>
                <html>
                    <head>
                        <script type="text/javascript">
                            window.parent.actualizar_componentes();
                            window.parent.jQuery.colorbox.close();
                        </script>
                    </head>
                </html>
                ';
            exit;
        }

        return true;
    }

    private function _agregarRegistroRegistros() {

        // campo orden
        $orden = Consultas_ObtenerRegistroMaximo::armado($this->_tablaNombre, 'orden');
        $orden = $orden[0]['orden'] + 1;

        $id_insertado = Consultas_RegistroCrear::armado(__FILE__, __LINE__, $this->_tablaNombre, array('orden' => $orden));
        $this->_id_insertado = $id_insertado['id'];

        // ahora le agrego los valores
        if (count($this->_matrizComponentes) > 0) {

            $registro_insercion = array();

            $i = 0;
            foreach ($this->_matrizComponentes as $id => $value) {
                if (isset($_POST['cp_' . $value['cp_id']])) {
                    $campo_id = $_POST['cp_' . $value['cp_id']];
                } else {
                    $campo_id = '';
                }

                $valor_insertar = Generales_LlamadoAComponentesYTraduccion::armar('RegistroInsercion', '', $campo_id, $value, $value['cp_nombre'], $value['cp_id'], $this->_id_insertado);

                // si la funcion de arriba devuelve 'false' no se inserta el valor
                if ($valor_insertar !== false) {
                    $registro_insercion[$value['tb_campo']] = $valor_insertar;
                    $i++;
                }
            }

            if ($i > 0) {
                Consultas_RegistroModificar::armado(__FILE__, __LINE__, $this->_tablaNombre, $registro_insercion, 'id_' . $this->_tablaNombre, $this->_id_insertado);
            }
        }
    }

}

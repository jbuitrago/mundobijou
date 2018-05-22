<?php

class Acciones_RegistroBaja {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'datos');

        // se obtiene el nombre de la pagina y el id de la misma
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $tabla_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tabla_tipo = $datos_tabla['tipo'];

        Generales_ControlTablasModificadas::control($tabla_nombre);

        // creo una matriz con los campos de los componentes de la pagina
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        // llamo al metodo del tipo de tabla correspondiente para obtener los valores anteriores
        // (si lo agrego mas abajo, los valores ya habrian sido modificados)
        if ($tabla_tipo == 'variables') {
            $matriz_valores_ant = Generales_ObtenerValoresTbVariables::armado($tabla_nombre, $matriz_componentes);
        } elseif ($tabla_tipo == 'registros') {
            $matriz_valores_ant = Generales_ObtenerValoresTbRegistros::armado($tabla_nombre, $matriz_componentes);
        }

        if ($matriz_componentes) {
            foreach ($matriz_componentes as $id => $value) {
                if (isset($value['eliminacion_especial']) && ($value['eliminacion_especial'] == 's')) {
                    Generales_LlamadoAComponentesYTraduccion::armar('RegistroBaja', '', '', $value, $value['cp_nombre'], $value['cp_id'], $_GET['id_tabla_registro']);
                }
            }
        }

        if (Inicio::confVars('generar_log') == 's') {

            $url_actual = getcwd();
            chdir(Inicio::path());
            chdir('Logs');
            $directorio = getcwd();
            chdir($url_actual);

            $contenido = date('Y-m-d') . '|' . date('H:i:s') . '|B|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|' . $_SERVER['HTTP_USER_AGENT'] . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $tabla_nombre . '|' . $_GET['id_tabla_registro'];

            file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-B_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
        }

        // llamado al proceso especial de la pagina
        // en este caso se hace ante del proceso de los registros, para poder trabajar con los datos del mismo
        if (file_exists(Inicio::path() . '/ProcesosEspeciales/' . $tabla_nombre . '.php')) {

            include_once( Inicio::path() . '/ProcesosEspeciales/' . $tabla_nombre . '.php' );

            $clase = $tabla_nombre;
            $proceso_especial = new $clase();
            $proceso_especial->Baja($_GET['id_tabla_registro'], $matriz_valores_ant);
        }

        // elimino el campo de la tabla
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $tabla_nombre, 'id_' . $tabla_nombre, $_GET['id_tabla_registro']);

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('redirigir', $parametros);
    }

}

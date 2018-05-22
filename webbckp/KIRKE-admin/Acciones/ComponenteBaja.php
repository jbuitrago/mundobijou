<?php

class Acciones_ComponenteBaja {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // creo una matriz con los campos de los componentes de la pagina
        $dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($_GET['cp_id']);

        // borro del atributo del usuario si tiene oculto este componente
        Armado_DesplegableOcultos::eliminarComponenteOculto($_GET['id_tabla'], $_GET['cp_id']);

        if (is_array($dcp)) {

            // llama al componente para eliminarlo
            $llamado_componente = Generales_LlamadoAComponentesYTraduccion::armar('ComponenteBaja', '', '', $dcp, $dcp['cp_nombre'], $_GET['cp_id'], '', '', false);
            
            // si el objeto anterior devuelve true
            if (isset($llamado_componente) && ($llamado_componente == true)) {

                $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($_GET['id_tabla']);
                $tabla_tipo = $datos_tabla['tipo'];

                // elimina la columna si la tabla es tipo 'registro'  o crea el registro para
                // cargar el valor de la variable
                if ($tabla_tipo == 'registros') {

                    // elimino el campo de la tabla
                    Consultas_CampoEliminar::armado(__FILE__, __LINE__, $dcp['tb_prefijo'] . '_' . $dcp['tb_nombre'], $dcp['tb_campo']);
                } elseif ($tabla_tipo == 'variables') {

                    // elimino el campo de la tabla
                    Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $dcp['tb_prefijo'] . '_' . $dcp['tb_nombre'], 'variables', $dcp['tb_campo']);
                }
            }
        }

        // condiciones para eliminar los registros que definen al componente
        // elimino el componente de 'kirke_componente'
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_componente', 'id_componente', $_GET['cp_id']);

        // elimino el componente de 'kirke_componente_parametro'
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_componente_parametro', 'id_componente', $_GET['cp_id']);
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }        

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('redirigir', $parametros);
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|ComponenteBaja';

        $contenido .= '|get:';
        $coma = '';
        foreach ($_GET as $id => $valor) {
            if (is_array($valor)) {
                $valor = implode(';' . $valor);
            }
            $contenido .= $coma . '[' . $id . ':' . $valor . ']';
            $coma = ',';
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

<?php

class Acciones_RolModificacionInsercion {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $this->_actualizarRol($_POST['rol'], $_POST['descripcion']);

        // actualizacion de los roles, previa eliminacion de los anteriores
        $this->_eliminarRolesDetalle();

        foreach ($_POST as $key => $valor) {

            $nombre = explode("_", $key);

            // control de las paginas
            if ($nombre[0] == 'pagina') {
                $this->_insertarRolDetalle('pagina', $nombre[1], $valor);
            }

            // control de los informes
            if ($nombre[0] == 'informe') {
                $this->_insertarRolDetalle('informe', $nombre[1], $valor);
            }

            // control de los informes
            if ($nombre[0] == 'desarrollo') {
                array_shift($nombre);
                $nombre = implode('_', $nombre);
                $this->_insertarRolDetalle('desarrollo', $nombre, $valor);
            }
        }
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '49');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _actualizarRol($rol, $detalle) {

        // crear rol
        Consultas_Roll::RegistroModificar(__FILE__, __LINE__, $rol, $detalle, $_GET['id_rol']);
    }

    private function _eliminarRolesDetalle() {

        // elimino los roles anteriores
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_rol_detalle', 'id_rol', $_GET['id_rol']);
    }

    private function _insertarRolDetalle($elemento, $id_elemento = null, $permiso) {

        // crear detalles del rol
        Consultas_RollDetalle::RegistroCrear(__FILE__, __LINE__, $_GET['id_rol'], $elemento, $id_elemento, $permiso);

        return true;
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|RolModificacionInsercion';

        $contenido .= '|descripcion:' . $_POST['descripcion'];

        $contenido .= '|roles:';
        $coma = '';
        foreach ($_POST as $key => $valor) {

            $nombre = explode("_", $key);

            // control de las paginas
            if ($nombre[0] == 'pagina') {
                $this->_insertarRolDetalle('pagina', $nombre[1], $valor);
                $contenido .= $coma.'[pagina-'.$nombre[1].'-'.$valor.']';
                $coma = ',';
            }

            // control de los informes
            if ($nombre[0] == 'informe') {
                $contenido .= $coma.'[informe-'.$nombre[1].'-'.$valor.']';
                $coma = ',';
            }

            // control de los informes
            if ($nombre[0] == 'desarrollo') {
                array_shift($nombre);
                $nombre = implode('_', $nombre);
                $contenido .= $coma.'[desarrollo-'.$nombre.'-'.$valor.']';
                $coma = ',';
            }
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}


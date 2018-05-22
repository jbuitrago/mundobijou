<?php

class Acciones_PrefijoAltaInsercion {

    private $_prefijo;
    private $_descripcion;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $this->_prefijo = $_POST['prefijo'];
        $this->_descripcion = $_POST['descripcion'];
        $this->_insertarPrefijo();
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '35');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarPrefijo() {

        $orden = Consultas_ObtenerRegistroMaximo::armado('kirke_tabla_prefijo', 'orden');

        // crear prefijo
        Consultas_TablaPrefijo::RegistroCrear(__FILE__, __LINE__, $orden[0]['orden'] + 1, $this->_prefijo, $this->_descripcion);

        return true;
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|PrefijoAltaInsercion';

        $contenido .= '|prefijo:' . $this->_prefijo;
        $contenido .= '|descripcion:' . $this->_descripcion;

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}


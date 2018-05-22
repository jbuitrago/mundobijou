<?php

class Acciones_DatosPersonalesModificacionInsercion {

    private $_mail;
    private $_telefono;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        $this->_mail = $_POST['mail'];
        $this->_telefono = $_POST['telefono'];

        $this->_actualizarDatos();

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '13');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _actualizarDatos() {

        // modificar usuario
        Consultas_Usuario::RegistroModificarMailTelefono(__FILE__, __LINE__, Inicio::usuario('id'), $this->_mail, $this->_telefono);

        return true;
    }

    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SIS|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|DatosPersonalesModificacionInsercion|mail:' . $this->_mail . '|telefono:' . $this->_telefono;

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISMOD_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

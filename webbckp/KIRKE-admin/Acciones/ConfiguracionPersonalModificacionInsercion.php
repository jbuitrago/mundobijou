<?php

class Acciones_ConfiguracionPersonalModificacionInsercion {

    private $_atributos = Array();
    private $_atributosPost = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        foreach ($_POST as $id => $value) {
            if (($id != 'x') && ($id != 'y')) {
                $this->_atributosPost[$id] = $value;
            }
        }

        $this->_actualizarDatos();
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '10');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _actualizarDatos() {

        // modificar usuario
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_usuario_atributo', 'id_usuario', Inicio::usuario('id'));

        foreach ($this->_atributosPost as $atributo_nombre => $atributo_valor) {

            // crear usuario
            Consultas_UsuarioAtributo::RegistroCrear(__FILE__, __LINE__, Inicio::usuario('id'), $atributo_nombre, $atributo_valor);

            if ($atributo_nombre == 'idioma') {
                Generales_Idioma::asignarPorUsuario($atributo_valor);
            }
        }

        return true;
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SIS|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|ConfiguracionPersonalModificacionInsercion';
        
        foreach ($this->_atributosPost as $atributo_nombre => $atributo_valor) {
            $contenido .= '|'.$atributo_nombre.':'.$atributo_valor;
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISMOD_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

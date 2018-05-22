<?php

class Acciones_UsuarioBaja {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('administrador_usuarios');


        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] != $_GET['id_usuario']) {

            Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_usuario', 'id_usuario', $_GET['id_usuario']);

            Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_usuario_rol', 'id_usuario', $_GET['id_usuario']);

            if (Inicio::confVars('generar_log') == 's') {
                $this->_cargaLog($clave);
            }
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '57', 'id_usuario' => $_GET['id_usuario']);
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _cargaLog($clave) {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|UsuarioBaja';

        $contenido .= '|id_usuario:' . $_GET['id_usuario'];

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

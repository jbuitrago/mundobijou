<?php

class Acciones_UsuarioModificacionInsercion {

    private $_nombre;
    private $_apellido;
    private $_email;
    private $_telefono;
    private $_habilitado;
    private $_usuario;
    private $_idUsuarioCrear;
    private $_nuevaClave;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('administrador_usuarios');

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        if (($_GET['id_usuario'] == '1') && (Inicio::usuario('tipo') != 'administrador general')) {
            $parametros = array('kk_generar' => '0', 'accion' => '57');
            $armado_botonera->armar('redirigir', $parametros);
        }

        $this->_nombre = $_POST['nombre'];
        $this->_apellido = $_POST['apellido'];
        $this->_email = $_POST['email'];
        $this->_telefono = $_POST['telefono'];
        $this->_habilitado = $_POST['habilitado'];
        if (isset($_POST['nueva_clave']) && ($_POST['nueva_clave'] == 's')) {
            $this->_nuevaClave = true;
        } else {
            $this->_nuevaClave = false;
        }



        $this->_idUsuarioCrear = $this->_actualizarDatos();

        $this->_eliminarRoles();

        foreach ($_POST as $key => $value) {
            $nombre = explode("_", $key);
            $valor = $value;

            if ($nombre[0] == 'rol') {
                $this->_insertarRoles($nombre[1]);
            }
        }

        $parametros = array('kk_generar' => '0', 'accion' => '57');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _actualizarDatos() {

        if ($this->_nuevaClave === true) {
            $clave = Seguridad_GenerarClave::armar(10);
            $clave = md5($clave);

            $usuario = Consultas_Usuario::RegistroConsulta(__FILE__, __LINE__, $_GET['id_usuario'], 'usuario');
            $this->_usuario = $usuario[0]['usuario'];

            $this->_envioMail($clave);
        } elseif (($_POST['clave'] != '') && ($_POST['clave2'] != '') && ($_POST['clave'] == $_POST['clave2']) && (strlen($_POST['clave']) >= 6)) {
            $clave = $_POST['clave'];
            $clave = md5($clave);
        } else {
            $clave = false;
        }
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog($clave);
        }

        // modificar usuario
        Consultas_Usuario::RegistroModificarTodo(__FILE__, __LINE__, $_GET['id_usuario'], $this->_nombre, $this->_apellido, $this->_email, $this->_telefono, $this->_habilitado, $clave);

        return true;
    }

    private function _eliminarRoles() {

        // elimino los roles anteriores
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_usuario_rol', 'id_usuario', $_GET['id_usuario']);
    }

    private function _insertarRoles($id_rol) {

        // crear detalles del rol
        Consultas_UsuarioRoll::RegistroCrear(__FILE__, __LINE__, $_GET['id_usuario'], $id_rol);
    }

    private function _envioMail($clave) {

        $mail1 = new Armado_Mail;

        $mail1->servidorMail(Inicio::confVars('mail_origen'));
        $mail1->servidorNombre(Inicio::confVars('nombre_servidor'));

        $mail1->mailDestinatario($this->_email, $this->_apellido . ', ' . $this->_nombre);
        $mail1->mailRespuesta(Inicio::confVars('mail_origen'));

        $mail1->asunto('Envio de Clave y Usuario desde el sitio');

        $mail1->datos('', 'Envio de Clave y Usuario desde el Servidor ' . Inicio::confVars('nombre_servidor'), 'titulo');
        $mail1->datos('', 'Los siguientes datos son necesarios para ingresar en la Administraci&oacute;n del Sitio Web', 'titulo');
        $mail1->datos('Clave:', $clave, 'texto');
        $mail1->datos('Usuario:', $this->_usuario, 'texto');
        $mail1->datos('Direcci&oacute;n:', "<a href='./' target='_blank' class='Datos'>Sitio de Administraci&oacute;n</a>", 'texto');

        $mail1->envio();
    }

    private function _cargaLog($clave) {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|UsuarioModificacionInsercion';

        $contenido .= '|nombre:' . $this->_nombre;
        $contenido .= '|apellido:' . $this->_apellido;
        $contenido .= '|email:' . $this->_email;
        $contenido .= '|telefono:' . $this->_telefono;
        $contenido .= '|habilitado:' . $this->_habilitado;
        if ($clave !== false) {
            $contenido .= '|clave_nueva:s|clave:' . substr($clave, 0, 10);
        } else {
            $contenido .= '|clave_nueva:n';
        }

        $contenido .= '|roles:';
        $coma = '';
        foreach ($_POST as $key => $value) {
            $nombre = explode("_", $key);
            if ($nombre[0] == 'rol') {
                $contenido .= $coma . $nombre[1];
                $coma = ',';
            }
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

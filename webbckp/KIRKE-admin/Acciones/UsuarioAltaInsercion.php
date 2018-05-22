<?php

class Acciones_UsuarioAltaInsercion {

    private $_nombre;
    private $_apellido;
    private $_email;
    private $_telefono;
    private $_habilitado;
    private $_usuario;
    private $_idUsuarioCrear;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('administrador_usuarios');

        $this->_nombre = $_POST['nombre'];
        $this->_apellido = $_POST['apellido'];
        $this->_email = $_POST['email'];
        $this->_telefono = $_POST['telefono'];
        $this->_habilitado = $_POST['habilitado'];
        $this->_usuario = preg_replace("/[^ A-Za-z0-9_]/", "", $_POST['usuario']);

        // controlo si el usuario existe
        $id_usuario = Consultas_Usuario::ControlUsuario(__FILE__, __LINE__, $this->_usuario);
        if (!isset($id_usuario[0]['id_usuario']) && ((($_POST['clave'] != '') && (strlen($_POST['clave']) >= 6)) || ($_POST['clave'] == ''))) {

            $this->_idUsuarioCrear = $this->_insertarDatos();

            foreach ($_POST as $key => $value) {
                $nombre = explode("_", $key);
                $valor = $value;

                if ($nombre[0] == 'rol') {
                    $this->_insertarRoles($nombre[1]);
                }
            }
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '57');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarDatos() {

        if (($_POST['clave'] != '') && ($_POST['clave2'] != '') && ($_POST['clave'] == $_POST['clave2'])) {
            $clave = $_POST['clave'];
            $enviar_mail = false;
        } else {
            $clave = Seguridad_GenerarClave::armar(10);
            $enviar_mail = true;
        }

        $clave_encriptada = md5($clave);
        $orden = Consultas_ObtenerRegistroMaximo::armado('kirke_usuario', 'orden');

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog($clave_encriptada);
        }

        // crear usuario
        $id_insertado = Consultas_Usuario::RegistroCrear(__FILE__, __LINE__, ($orden[0]['orden'] + 1), $this->_nombre, $this->_apellido, $this->_usuario, $clave_encriptada, $this->_email, $this->_telefono, $this->_habilitado);

        $id_usuario_crear = $id_insertado['id'];

        if ($enviar_mail === true) {
            $this->_envioMail($clave);
        }

        return $id_usuario_crear;
    }

    private function _insertarRoles($id_rol) {

        // crear detalles del rol
        if ($id_rol != '1') {
            Consultas_UsuarioRoll::RegistroCrear(__FILE__, __LINE__, $this->_idUsuarioCrear, $id_rol);
        } elseif (($id_rol == '1') && (Inicio::usuario('tipo') == 'administrador general')) {
            Consultas_UsuarioRoll::RegistroCrear(__FILE__, __LINE__, $this->_idUsuarioCrear, $id_rol);
        }
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
        $mail1->datos('Usuario:', $this->_usuario, 'texto');
        $mail1->datos('Clave:', $clave, 'texto');
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

        $contenido .= '|UsuarioAltaInsercion';

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

<?php

class Seguridad_UsuarioValidacion {

    public function consultaInicio($usuario, $clave) {

        $validacion = Consultas_Roll::RegistroConsultaUsuario(__FILE__, __LINE__, $usuario);

        $id_usuario = $validacion[0]['id_usuario'];
        $nombre = $validacion[0]['nombre'];
        $apellido = $validacion[0]['apellido'];
        $usuario = $validacion[0]['usuario'];
        $clave_base = $validacion[0]['clave'];

        if ($this->_validarClave($clave, $clave_base) === true) {

            $datos['id'] = $id_usuario;
            $datos['nombre'] = $nombre;
            $datos['apellido'] = $apellido;
            $datos['usuario'] = $usuario;
            $datos['valor'] = substr($clave_base, 2, 2) . substr($clave_base, 12, 2) . substr($clave_base, 22, 2);
            
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario'] = $datos;

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_usuario_rol');
            $consulta->tablas('kirke_rol');
            $consulta->campos('kirke_rol', 'id_rol');
            $consulta->condiciones('', 'kirke_usuario_rol', 'id_rol', 'iguales', 'kirke_rol', 'id_rol');
            $consulta->condiciones('y', 'kirke_usuario_rol', 'id_usuario', 'iguales', '', '', $id_usuario);
            $validacion = $consulta->realizarConsulta();

            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] = 'usuario general';

            if (is_array($validacion)) {
                foreach ($validacion as $key => $value) {
                    if ($value['id_rol'] == 1) {
                        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] = 'administrador general';
                    } elseif (($value['id_rol'] == 2) && ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] != 'administrador general')) {
                        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] = 'administrador de usuarios';
                    }
                }
            }

            Generales_Idioma::asignarInicio();

            return true;
        } else {
            return false;
        }
    }

    private function _validarClave($clave, $clave_base) {

        if (md5($clave) === $clave_base) {
            return true;
        } else {
            return false;
        }
    }

    public function consultaUsuarioSesion() {

        // verifico que exista la sesion
        if (
                !isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id']) ||
                !isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['nombre']) ||
                !isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['apellido']) ||
                !isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['idioma'])
        ) {
            $this->_rechazoUsuario();
        }
    }

    public function consultaElemento($elemento = null, $id_elemento = null, $permiso = null, $redireccion = null) {

        if (!isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'])) {
            $this->_rechazoUsuario();
        }

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            return 'datos';
        }

        if ($elemento == 'administrador_usuarios') {
            if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador de usuarios') {
                return true;
            } else {
                $this->_rechazoUsuario();
            }
        }

        if ($this->_validarUsuarioElemento($elemento, $id_elemento, $permiso)) {
            return $this->_validarUsuarioElemento($elemento, $id_elemento, $permiso);
        } else {
            if (!$redireccion) {
                $this->_rechazoUsuario();
            } elseif ($redireccion == 'no') {
                return false;
            }
        }
    }

    private function _rechazoUsuario() {

        // cuando no pasa el ingreso, el sistema da una pausa
        sleep(5);
        session_destroy();

        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '14');
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _validarUsuarioElemento($elemento, $id_elemento, $permiso) {

        if ($permiso == 'ver') {
            if ($this->_validarUsuarioElementoConsulta($elemento, $id_elemento, 'datos')) {
                return 'datos';
            }
            if ($this->_validarUsuarioElementoConsulta($elemento, $id_elemento, 'insercion')) {
                return 'insercion';
            }
            if ($this->_validarUsuarioElementoConsulta($elemento, $id_elemento, 'ver')) {
                return 'ver';
            }
        } elseif ($permiso == 'insercion') {
            if ($this->_validarUsuarioElementoConsulta($elemento, $id_elemento, 'datos')) {
                return 'datos';
            }
            if ($this->_validarUsuarioElementoConsulta($elemento, $id_elemento, 'insercion')) {
                return 'insercion';
            }
        } elseif ($permiso == 'datos') {
            if ($this->_validarUsuarioElementoConsulta($elemento, $id_elemento, 'datos')) {
                return 'datos';
            }
        }
        return false;
    }

    private function _validarUsuarioElementoConsulta($elemento, $id_elemento, $permiso) {

        // consulta para obtener los roles particulares
        $consulta = Consultas_RollDetalle::RegistroConsultaIdUsuario(__FILE__, __LINE__, $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'], $elemento, $id_elemento, $permiso);

        if (is_array($consulta)) {
            return true;
        } else {
            return false;
        }
    }

}

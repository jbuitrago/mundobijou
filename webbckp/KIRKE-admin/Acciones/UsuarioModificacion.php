<?php

class Acciones_UsuarioModificacion extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('administrador_usuarios');

        $armado_botonera = new Armado_Botonera();

        if(($_GET['id_usuario'] == '1') && (Inicio::usuario('tipo') != 'administrador general') ){
            $parametros = array('kk_generar' => '0', 'accion' => '57');
            $armado_botonera->armar('redirigir', $parametros);
        }
        
        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));
        
        $parametros = array('kk_generar' => '0', 'accion' => '59', 'id_usuario' => $_GET['id_usuario']);
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '57');
        $armado_botonera->armar('volver', $parametros);

        $this->_tabla = Consultas_Usuario::RegistroConsulta(__FILE__, __LINE__, $_GET['id_usuario'], 'nombre,apellido,mail,telefono,habilitado');

        $usuario_modificacion_cuerpo = $this->usuarioDatos();
        
        $usuario_modificacion_cuerpo .= '<script type="text/javascript" language="javascript" src="./js/opciones_avanzadas.js"></script>' . "\n";
        $usuario_modificacion_cuerpo .= '<script type="text/javascript" language="javascript" src="./js/control_usuario_clave.js"></script>' . "\n";
        
        $usuario_modificacion_cuerpo .= Armado_PlantillasInternas::acciones('UsuarioAlta_clave');

        return $usuario_modificacion_cuerpo;
    }

    public function usuarioDatos() {

        $plantilla['nombre'] = $this->_tabla[0]['nombre'];
        $plantilla['apellido'] = $this->_tabla[0]['apellido'];
        $plantilla['mail'] = $this->_tabla[0]['mail'];
        $plantilla['telefono'] = $this->_tabla[0]['telefono'];
        if ($this->_tabla[0]['habilitado'] == 's') {
            $plantilla['habilitado_s'] = 'checked';
            $plantilla['habilitado_n'] = '';
        } elseif ($this->_tabla[0]['habilitado'] == 'n') {
            $plantilla['habilitado_s'] = '';
            $plantilla['habilitado_n'] = 'checked';
        }
        $habilitado = $this->_tabla[0]['habilitado'];

        $plantilla['listadoRoles'] = $this->_listadoRoles();

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

    private function _listadoRoles() {

        // ver roles anteriores
        $roles_anteriores = Consultas_UsuarioRoll::RegistroConsulta(__FILE__, __LINE__, $_GET['id_usuario']);

        // ver lsitado de roles
        $roles = Consultas_Roll::RegistroConsulta(__FILE__, __LINE__);

        $mostrar_roles = Armado_PlantillasInternas::acciones('UsuarioModificacion_listadoRoles');

        if (is_array($roles)) {

            $plantilla['listado'] = '';

            foreach ($roles as $valor) {
                $chequeado = '';

                if (is_array($roles_anteriores)) {
                    foreach ($roles_anteriores as $valores_anteriores) {
                        if ($valor['id_rol'] == $valores_anteriores['id_rol']) {
                            $chequeado = ' checked="checked"';
                        }
                    }
                }

                if ($valor['id_rol'] > 2) {
                    $plantilla['listado'] .= '<input name="rol_' . $valor['id_rol'] . '" type="checkbox" id="rol_' . $valor['id_rol'] . '" value="s" ' . $chequeado . '> ' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']<br />';
                } elseif ($valor['id_rol'] == 1) {
                    if (Inicio::usuario('tipo') == 'administrador general') {
                        $plantilla['listado'] .= '<input name="rol_' . $valor['id_rol'] . '" type="checkbox" id="rol_' . $valor['id_rol'] . '" value="s" ' . $chequeado . '> ' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']<br />';
                    }
                } elseif ($valor['id_rol'] == 2) {
                    if (( Inicio::usuario('tipo') == 'administrador general') || (Inicio::usuario('tipo') == 'administrador de usuarios')) {
                        $plantilla['listado'] .= '<input name="rol_' . $valor['id_rol'] . '" type="checkbox" id="rol_' . $valor['id_rol'] . '" value="s" ' . $chequeado . '> ' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']<br />';
                    } else {
                        $plantilla['listado'] .= '<input type="hidden" name="rol_' . $valor['id_rol'] . '" value="s">';
                        $plantilla['listado'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']<br />';
                    }
                }
            }

            return Armado_PlantillasInternas::acciones('UsuarioModificacion_listadoRoles_detalle', $plantilla);
        } else {

            return false;
        }
    }

}


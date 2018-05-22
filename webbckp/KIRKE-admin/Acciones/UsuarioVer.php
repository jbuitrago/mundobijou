<?php

class Acciones_UsuarioVer extends Armado_Plantilla {

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
        
        $parametros = array('kk_generar' => '0', 'accion' => '58', 'id_tabla' => $_GET['id_tabla'], 'id_usuario' => $_GET['id_usuario']);
        $armado_botonera->armar('editar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '57');
        $armado_botonera->armar('volver', $parametros);

        $this->_tabla = Consultas_Usuario::RegistroConsulta(__FILE__, __LINE__, $_GET['id_usuario'], 'nombre,apellido,mail,telefono,habilitado');

        return $this->usuarioVer();
    }

    public function usuarioVer() {

        $plantilla['nombre'] = $this->_tabla[0]['nombre'];
        $plantilla['apellido'] = $this->_tabla[0]['apellido'];
        $plantilla['mail'] = $this->_tabla[0]['mail'];
        $plantilla['telefono'] = $this->_tabla[0]['telefono'];
        if ($this->_tabla[0]['habilitado'] == 's') {
            $plantilla['habilitado'] = "{TR|o_si}";
        } elseif ($this->_tabla[0]['habilitado'] == 'n') {
            $plantilla['habilitado'] = "{TR|o_no}";
        }

        $ver = Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);

        $ver .= $this->_listadoRoles();

        return $ver;
    }

    private function _listadoRoles() {

        // ver roles asignados
        $roles = Consultas_Roll::RegistroConsultaIdUsuario(__FILE__, __LINE__, $_GET['id_usuario']);

        $mostrar_roles = '';
        if (is_array($roles)) {
            foreach ($roles as $valor) {

                $plantilla['rol'] = $valor['rol'];
                $plantilla['descripcion'] = $valor['descripcion'];

                $mostrar_roles .= Armado_PlantillasInternas::acciones('UsuarioVer_listadoRoles', $plantilla);

                unset($plantilla);
            }
        } else {
            return false;
        }
        return $mostrar_roles;
    }

}


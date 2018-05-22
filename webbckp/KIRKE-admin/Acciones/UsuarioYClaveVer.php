<?php

class Acciones_UsuarioYClaveVer extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '61');
        $armado_botonera->armar('editar', $parametros);

        $pagina_alta_cuerpo = $this->_editarDatos();

        return $pagina_alta_cuerpo;
    }

    private function _editarDatos() {

        $this->_tabla = Consultas_Usuario::RegistroConsulta(__FILE__, __LINE__, Inicio::usuario('id'), 'nombre,apellido,usuario,telefono');

        $plantilla['nombre'] = $this->_tabla[0]['nombre'];
        $plantilla['apellido'] = $this->_tabla[0]['apellido'];
        $plantilla['usuario'] = $this->_tabla[0]['usuario'];
        $plantilla['clave'] = '**********';

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}


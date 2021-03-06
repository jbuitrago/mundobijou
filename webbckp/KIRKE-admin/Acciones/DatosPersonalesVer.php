<?php

class Acciones_DatosPersonalesVer extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('general'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '11');
        $armado_botonera->armar('editar', $parametros);

        $this->_tabla = Consultas_Usuario::RegistroConsulta(__FILE__, __LINE__, Inicio::usuario('id'), 'nombre,apellido,mail,telefono');

        $plantilla['nombre'] = $this->_tabla[0]['nombre'];
        $plantilla['apellido'] = $this->_tabla[0]['apellido'];
        $plantilla['mail'] = $this->_tabla[0]['mail'];
        $plantilla['telefono'] = $this->_tabla[0]['telefono'];

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}


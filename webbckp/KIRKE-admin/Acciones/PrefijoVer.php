<?php

class Acciones_PrefijoVer extends Armado_Plantilla {

    private $_tabla = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();
        
        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '35');
        $armado_botonera->armar('volver', $parametros);

        $this->_tabla = Consultas_TablaPrefijo::RegistroConsulta(__FILE__, __LINE__, $_GET['id_tabla_prefijo'], 'descripcion');

        return $this->prefijoVer();
    }

    public function prefijoVer() {

        $plantilla['prefijo'] = $this->_tabla[0]['prefijo'];
        $plantilla['descripcion'] = $this->_tabla[0]['descripcion'];

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}


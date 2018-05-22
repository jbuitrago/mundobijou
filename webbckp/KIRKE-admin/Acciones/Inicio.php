<?php

class Acciones_Inicio extends Armado_Plantilla {

    public function armado() {

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '14');
        $armado_botonera->armar('inicio_ingreso', $parametros, 'ingresar');

        if (isset($_POST['usuario']) && isset($_POST['clave'])) {

            $pagina_alta_cuerpo = $this->_validacion();
        } else {

            $plantilla['campo_obligatorio'] = '<img src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/inicio_obligatorio.gif" />';

            return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
        }

        return $pagina_alta_cuerpo;
    }

    private function _validacion() {

        // seguridad del usuario
        $usuario = preg_replace("/[^ A-Za-z0-9_]/", "", $_POST['usuario']);
        $clave = preg_replace("/[^A-Za-z0-9]/", "", trim($_POST['clave']));

        $usuario = Bases_InyeccionSql::consulta($usuario);
        $clave = Bases_InyeccionSql::consulta($clave);

        $validacion = new Seguridad_UsuarioValidacion();

        $armado_botonera = new Armado_Botonera();

        if ($validacion->consultaInicio($usuario, $clave, 'inicio') === true) {

            // la redireccion va al final
            $parametros = array('kk_generar' => '0', 'accion' => '1');
            $armado_botonera->armar('redirigir', $parametros);
        } else {

            if (isset($_SESSION)) {
                session_destroy();
            }

            // la redireccion va al final
            $parametros = array('kk_generar' => '0', 'accion' => '14');
            $armado_botonera->armar('redirigir', $parametros);
        }
    }

}


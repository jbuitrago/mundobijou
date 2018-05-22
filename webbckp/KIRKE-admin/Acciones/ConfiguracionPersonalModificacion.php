<?php

class Acciones_ConfiguracionPersonalModificacion extends Armado_Plantilla {

    private $_atributos = Array();
    private $_atributosDelUsuario = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '9');
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '10');
        $armado_botonera->armar('volver', $parametros);

        $this->_atributosDelUsuario();

        $pagina_alta_cuerpo = $this->_editarDatos();

        return '<div class="contenido_separador"></div>' . $pagina_alta_cuerpo;
    }

    private function _atributosDelUsuario() {
        $this->_atributosDelUsuario = array(
            'idioma',
            'plantilla',
        );
    }

    private function _editarDatos() {

        $this->_atributos = Consultas_UsuarioAtributo::RegistroConsulta(__FILE__, __LINE__, Inicio::usuario('id'));

        $ver = '';

        foreach ($this->_atributosDelUsuario as $value) {

            $metodo = "_atributo" . ucwords($value);

            $valor = '';
            if (is_array($this->_atributos)) {
                foreach ($this->_atributos as $id => $value_at) {
                    if ($value == $value_at['atributo_nombre']) {
                        $valor = $value_at['atributo_valor'];
                    }
                }
            }
            $ver .= $this->$metodo($valor);
        }

        return $ver;
    }

    private function _atributoIdioma($valor) {

        if ($valor == '') {
            $idioma = Inicio::confVars('idiomas');
            $valor = $idioma[0];
        }

        $idiomas = Inicio::confVars('idiomas');       
        sort($idiomas);

        $desplegable = '<select name="idioma" id="idioma">';

        if (is_array($idiomas)) {
            foreach ($idiomas as $idioma) {
                if ($idioma == $valor) {
                    $seleccionado = 'selected';
                } else {
                    $seleccionado = '';
                }
                $desplegable .= '<option value="' . $idioma . '" ' . $seleccionado . '>{TR|o_internacional_' . $idioma . '}</option>';
            }
        }

        $desplegable .= '</select>';

        $ver = '<div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_idioma}</div>
    <div class="contenido_7">' . $desplegable . '</div>';

        return $ver;
    }

    private function _atributoPlantilla($valor) {

        if ($valor == '') {
            $valor = Inicio::confVars('plantilla');
        }

        $plantillas = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Plantillas/');
        sort($plantillas);

        $desplegable = '<select name="plantilla" id="plantilla">';

        if (is_array($plantillas)) {
            foreach ($plantillas as $plantilla) {
                if ($plantilla == $valor) {
                    $seleccionado = 'selected';
                } else {
                    $seleccionado = '';
                }
                $desplegable .= '<option value="' . $plantilla . '" ' . $seleccionado . '>' . $plantilla . '</option>';
            }
        }

        $desplegable .= '</select>';

        $ver = '<div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_plantilla}</div>
    <div class="contenido_7">' . $desplegable . '</div>';

        return $ver;
    }

}


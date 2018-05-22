<?php

class Acciones_ConfiguracionPersonalVer extends Armado_Plantilla {

    private $_atributos = Array();
    private $_atributosDelUsuario = Array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '8');
        $armado_botonera->armar('editar', $parametros);

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

        if (is_array($this->_atributosDelUsuario)) {

            foreach ($this->_atributosDelUsuario as $value) {

                $metodo = "_atributo" . ucwords($value);

                $valor = '';
                if (is_array($this->_atributos)) {
                    foreach ($this->_atributos as $id => $value_at) {
                        if ($value == $value_at['atributo_nombre']) {
                            $valor .= $value_at['atributo_valor'];
                        }
                    }
                }
                $ver .= $this->$metodo($valor);
            }
        }

        return $ver;
    }

    private function _atributoIdioma($valor) {
        if ($valor == '') {
            $idioma = Inicio::confVars('idiomas');
            $valor = $idioma[0];
        }
        $ver = '<div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_idioma}</div>
    <div class="contenido_7">' . $valor . '</div>';

        return $ver;
    }

    private function _atributoPlantilla($valor) {
        if ($valor == '') {
            $valor = Inicio::confVars('plantilla');
        }
        $ver = '<div class="contenido_margen"></div>
    <div class="contenido_titulo">{TR|o_plantilla}</div>
    <div class="contenido_7">' . $valor . '</div>';

        return $ver;
    }

}


<?php

class Armado_Botonera extends Armado_Plantilla {

    private $_tipo;
    private $_metodo;
    private $_nombre;
    private $_etiqueta;
    private $_link;
    private $_botonera = '';
    static private $_form_enviado = false;

    public function __construct($botonera_nva = null) {

        if ($botonera_nva != '') {
            $this->_botonera = '_' . $botonera_nva;
        }
    }

    public function armar($tipo, $parametros, $nombre = null, $url_nva = null, $obtener = false) {

        $this->_tipo = $tipo;

        if ($nombre) {
            $this->_nombre = $nombre;
        } else {
            $this->_etiqueta = $tipo;
            $this->_nombre = $tipo;
        }

        $this->_link = './index.php?' . Generales_VariablesGet::armar($parametros, 's');

        // botones generales $_GET
        if (
                ($tipo == 'volver') || ($tipo == 'siguiente') || ($tipo == 'editar') || ($tipo == 'ver') || ($tipo == 'nuevo') || ($tipo == 'volver_punteado') || ($tipo == 'exportar_der') || ($tipo == 'refrescar') || ($tipo == 'mostrar') || ($tipo == 'ocultar') || ($tipo == 'eliminar') || ($tipo == 'volver_a_registros')
        ) {

            $this->_metodo = 'get';
            $boton['contenido'] = $this->_boton();

            if ($obtener === false) {
                $this->_armadoPlantillaSet('botonera' . $this->_botonera, $boton, 'contenido_unico');
                return true;
            } else {
                return $boton;
            }

            // botones generales $_POST
        } elseif (
                $tipo == 'guardar' || $tipo == 'guardar_ver' || $tipo == 'inicio_ingreso' || $tipo == 'vista_previa' || $tipo == 'volver_post' || $tipo == 'filtrar'
        ) {

            $this->_metodo = 'post';
            $boton['contenido'] = $this->_boton();

            if ($obtener === false) {

                if (self::$_form_enviado === false) {
                    $this->_armadoPlantillaSet('formulario_inicio' . $this->_botonera, "<form enctype=\"multipart/form-data\" name=\"form" . $this->_botonera . "\" id=\"form" . $this->_botonera . "\" target=\"_self\" method=\"post\" action=\"\">");
                    $this->_armadoPlantillaSet('formulario_fin' . $this->_botonera, '</form>');
                    self::$_form_enviado = true;
                }

                $this->_armadoPlantillaSet('botonera' . $this->_botonera, $boton, 'contenido_unico');
                return true;
            } else {

                return $boton['contenido'];
            }

            // botones generales redirigir
        } elseif ($tipo == 'redirigir') {

            header('Location: ' . $this->_link);
            exit();
        }

        return false;
    }

    private function _boton() {

        if ($this->_metodo == 'get') {
            $armado = '<a href="' . $this->_link . '" class="bt_' . $this->_tipo . '"><div class="bt_get">{TR|o_' . $this->_nombre . '}</div></a>';
        } elseif ($this->_metodo == 'post') {
            $armado = '<div class="bt_' . $this->_tipo . '"><div class="bt_post"><input type="submit" id="link_submit" accion="' . $this->_link . '" class="link_submit" value="{TR|o_' . $this->_nombre . '}" /></div></div>';
        }

        return $armado;
    }

}

<?php

class Componentes_ImagenBase_RegistroInsercion {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_directorio;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {

        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_idRegistro = $datos[4];
        $_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        if (is_array($this->_dcp)) {
            $this->_dcp = array_merge($_pv, $this->_dcp);
        } else {
            $this->_dcp = $_pv;
        }
    }

    public function get() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        if (!isset($this->_valor['1']) && ($_FILES[$id_campo]['tmp_name'][0] == '')) {
            return false;
        }

        if (isset($_GET['guardar']) && ($_GET['guardar'] == 'dir')) {


            if (isset($this->_valor['1'])) {
                $accion = $this->_valor['1'];
            } else {
                $accion = '';
            }
            $img_nombre = '';

            if (!isset($this->_valor['1']) && isset($_FILES[$id_campo]['tmp_name'][0])) {

                $this->_obtenerDirectorio();

                $imagen = $_FILES[$id_campo]['tmp_name'][0];

                move_uploaded_file($imagen, $this->_dcp['directorio'] . '/t_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0]);

                $img_nombre = 't_' . $this->_dcp['cp_id'] . '_' . $this->_idRegistro . '_' . $_FILES[$id_campo]['name'][0];
                $accion = 'actualizar';

                $_POST[$id_campo . '_x'] = '';
                $_POST[$id_campo . '_y'] = '';
                $_POST[$id_campo . '_w'] = '';
                $_POST[$id_campo . '_h'] = '';
                $_POST[$id_campo . '_ancho'] = '';
                $_POST[$id_campo . '_alto'] = '';
            }
        } else {

            if (isset($this->_valor['0'])) {
                $img_nombre = $this->_valor['0'];   // nombre de la imagen
            } else {
                $img_nombre = '';
            }
            if (isset($this->_valor['1'])) {
                $accion = $this->_valor['1'];   // accion a realizar con la foro
            } else {
                $accion = '';
            }
        }

        if ($accion != 'eliminar') {

            $this->_obtenerDirectorio();

            $imagen = $this->_dcp['directorio'] . '/' . $img_nombre;
            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            if (
                    ( ($this->_dcp['ancho_final'] != '') || ($this->_dcp['alto_final'] != '') ) && ($accion == 'actualizar')
            ) {
                $imagen_cambiar_tamano['imagen'] = $imagen;
                $imagen_cambiar_tamano['ancho_final'] = $this->_dcp['ancho_final'];
                $imagen_cambiar_tamano['alto_final'] = $this->_dcp['alto_final'];
                $imagen_cambiar_tamano['x'] = $_POST[$id_campo . '_x'];
                $imagen_cambiar_tamano['y'] = $_POST[$id_campo . '_y'];
                $imagen_cambiar_tamano['w'] = $_POST[$id_campo . '_w'];
                $imagen_cambiar_tamano['h'] = $_POST[$id_campo . '_h'];
                $imagen_cambiar_tamano['ancho_muestra'] = $_POST[$id_campo . '_ancho'];
                $imagen_cambiar_tamano['alto_muestra'] = $_POST[$id_campo . '_alto'];

                Generales_ImagenRecortar::recortar($imagen_cambiar_tamano);
            }

            if ($accion == 'actualizar') {

                // renombro la imagen para que pase a ser definitiva
                $img_nombre_matriz = explode("_", $img_nombre);
                $img_nombre_matriz = array_slice($img_nombre_matriz, 3);

                if (count($img_nombre_matriz) > 1) {

                    $img_nombre_definitivo = $this->_idComponente . '_' . $this->_idRegistro . '_' . implode('_', $img_nombre_matriz);
                    $img_nombre_definitivo = preg_replace("/[^a-zA-Z0-9.()]/", '_', $img_nombre_definitivo);
                }
            } else {

                if (isset($_POST[$id_campo . '_nombre'])) {

                    $img_nombre_definitivo = $this->_idComponente . '_' . $this->_idRegistro . '_' . $_POST[$id_campo . '_nombre'];
                }
            }

            if (isset($img_nombre_definitivo)) {

                $archivo = $this->_dcp['directorio'] . '/' . $img_nombre;
                if (file_exists($archivo) && is_file($archivo)) {
                    rename($this->_dcp['directorio'] . '/' . $img_nombre, $this->_dcp['directorio'] . '/' . $img_nombre_definitivo);
                }

                $imagen = $this->_dcp['directorio'] . '/' . $img_nombre_definitivo;

                if (file_exists($imagen)) {

                    $imagen_muestra = $this->_dcp['directorio'] . '/kirke_imagen_muestra_' . $img_nombre_definitivo;

                    if (copy($imagen, $imagen_muestra)) {

                        chmod($imagen_muestra, 0775);
                        $imagen_cambiar_tamano['imagen'] = $imagen_muestra;
                        $imagen_cambiar_tamano['ancho_final'] = 50;
                        $imagen_cambiar_tamano['alto_final'] = 50;
                        $imagen_cambiar_tamano['x'] = 0;
                        $imagen_cambiar_tamano['y'] = 0;

                        Generales_ImagenRecortar::recortar($imagen_cambiar_tamano);
                    }

                    list( $ancho, $alto, $tipo ) = getimagesize($imagen);
                    // tipos de archivos 1=GIF, 2=JPG, 3=PNG

                    $this->_valor = $tipo;

                    $this->_valor .= ';' . $img_nombre_definitivo;

                    $fp = fopen($imagen, 'rb');
                    $this->_valor .= ';' . base64_encode(fread($fp, filesize($imagen)));
                    fclose($fp);

                    $fp = fopen($imagen_muestra, 'rb');
                    $this->_valor .= ';' . base64_encode(fread($fp, filesize($imagen_muestra)));
                    fclose($fp);

                    unlink($imagen);
                    unlink($imagen_muestra);
                }
            } else {

                $this->_valor = '';
            }
        } elseif ($accion == 'eliminar') {

            $this->_valor = '';
        }

        return $this->_valor;
    }

    private function _obtenerDirectorio() {

        // obtengo el directirio donde se guardaran las imaganes
        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('tmp');
        $this->_dcp['directorio'] = getcwd();
        chdir($url_actual);
    }

}

<?php

class Componentes_ImagenDirectorio_RegistroInsercion {

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

        if (isset($_GET['guardar']) && ($_GET['guardar'] == 'dir') && ($_FILES[$id_campo]['tmp_name'][0] != '')) {

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
            }
        } else {
            if (isset($this->_valor['0'])) {
                $this->_obtenerDirectorio();
                if (strpos($this->_valor['0'], '/') === false) {
                    $img_nombre = 't_' . $this->_valor['0'];
                } else {
                    $img_nombre = str_replace('/', '/t_', $this->_valor['0']);
                }
                copy($this->_dcp['directorio'] . '/' . $this->_valor['0'], $this->_dcp['directorio'] . '/' . $img_nombre);
            } else {
                $img_nombre = '';
            }
            if (isset($this->_valor['1'])) {
                $accion = $this->_valor['1'];   // accion a realizar con la foro
            } else {
                $accion = '';
            }
        }
        if (($accion == 'actualizar') && ($img_nombre != '')) {

            $this->_obtenerDirectorio();

            // elimina la imagen anterior 'no temporal'
            $this->_eliminarArchivo($this->_dcp['directorio'], $this->_idComponente . '_' . $this->_idRegistro);

            $imagen = $this->_dcp['directorio'] . '/' . $img_nombre;

            list( $ancho, $alto, $tipo ) = getimagesize($imagen);
            // tipos de archivos 1=GIF, 2=JPG, 3=PNG

            if ((isset($_POST[$id_campo . '_corte']) && isset($_POST[$id_campo . '_x']) && ($_POST[$id_campo . '_corte'] == 'recorte')) || !isset($_POST[$id_campo . '_corte'])) {

                if (isset($_POST[$id_campo . '_corte'])) {
                    if (($_POST[$id_campo . '_alto'] <= $alto) || ($_POST[$id_campo . '_ancho'] <= $ancho)) {
                        $ancho_corte = round((($_POST[$id_campo . '_x2'] - $_POST[$id_campo . '_x']) * $ancho / $_POST[$id_campo . '_ancho']), 0);
                        $alto_corte = round((($_POST[$id_campo . '_y2'] - $_POST[$id_campo . '_y']) * $alto / $_POST[$id_campo . '_alto']), 0);
                        $x = round(($_POST[$id_campo . '_x'] * $ancho / $_POST[$id_campo . '_ancho']), 0);
                        $y = round(($_POST[$id_campo . '_y'] * $alto / $_POST[$id_campo . '_alto']), 0);
                    }
                } else {

                    $indice_ancho = $ancho / $this->_dcp['ancho_final'];
                    $indice_alto = $alto / $this->_dcp['alto_final'];

                    if ($indice_ancho > $indice_alto) {
                        // cortar a ancho
                        $ancho_corte = $alto * $this->_dcp['ancho_final'] / $this->_dcp['alto_final'];
                        $alto_corte = $alto;
                        $x = round((($ancho - $ancho_corte) / 2), 0);
                        $y = 0;
                    } else {
                        // cortar a alto
                        $ancho_corte = $ancho;
                        $alto_corte = round(($ancho * $this->_dcp['alto_final'] / $this->_dcp['ancho_final']), 0);
                        $x = 0;
                        $y = round((($alto - $alto_corte) / 2), 0);
                    }
                }

                if (($this->_dcp['ancho_final'] != '') && ($this->_dcp['alto_final'] != '')) {
                    $ancho_final = $this->_dcp['ancho_final'];
                    $alto_final = $this->_dcp['alto_final'];
                } elseif ($this->_dcp['ancho_final'] != '') {
                    $ancho_final = $this->_dcp['ancho_final'];
                    $alto_final = round(($this->_dcp['ancho_final'] * $alto_corte / $ancho_corte), 0);
                } elseif ($this->_dcp['alto_final'] != '') {
                    $alto_final = $this->_dcp['alto_final'];
                    $ancho_final = round(($this->_dcp['alto_final'] * $ancho_corte / $alto_corte), 0);
                } else {
                    $ancho_final = $ancho_corte;
                    $alto_final = $alto_corte;
                }

                $recortar = new Generales_ImagenRecortar();
                $recortar->imagen($imagen);
                $recortar->anchoFinal($ancho_final);
                $recortar->altoFinal($alto_final);
                $recortar->corteX($x);
                $recortar->corteY($y);
                $recortar->corteAncho($ancho_corte);
                $recortar->corteAlto($alto_corte);
                $recortar->recortar();
            } elseif (isset($_POST[$id_campo . '_corte']) && isset($_POST[$id_campo . '_color_fondo']) && ($_POST[$id_campo . '_corte'] == 'color')) {

                $ancho_corte = $ancho;
                $alto_corte = $alto;

                $x = 0;
                $y = 0;

                $indice_ancho = $ancho / $this->_dcp['ancho_final'];
                $indice_alto = $alto / $this->_dcp['alto_final'];

                if ($indice_ancho > $indice_alto) {
                    // cortar a ancho
                    $ancho_final = $this->_dcp['ancho_final'];
                    $alto_final = round(($alto * $this->_dcp['ancho_final'] / $ancho), 0);
                } else {
                    // cortar a alto
                    $ancho_final = round(($ancho * $this->_dcp['alto_final'] / $alto), 0);
                    $alto_final = $this->_dcp['alto_final'];
                }

                $recortar = new Generales_ImagenRecortar();
                $recortar->imagen($imagen);
                $recortar->anchoFinal($ancho_final);
                $recortar->altoFinal($alto_final);
                $recortar->corteX($x);
                $recortar->corteY($y);
                $recortar->corteAncho($ancho_corte);
                $recortar->corteAlto($alto_corte);
                $recortar->recortar();

                switch ($tipo) {
                    case 1:
                        $imagen_temporal = imagecreatefromgif($imagen);
                        break;
                    case 2:
                        $imagen_temporal = imagecreatefromjpeg($imagen);
                        break;
                    case 3:
                        $imagen_temporal = imagecreatefrompng($imagen);
                        break;
                }

                $fondo = imagecreatetruecolor($this->_dcp['ancho_final'], $this->_dcp['alto_final']);

                list($r, $g, $b) = sscanf('#' . $_POST[$id_campo . '_color_fondo'], "#%02x%02x%02x");
                imagealphablending($fondo, false);
                imagesavealpha($fondo, true);
                $fondo_color = imagecolorallocatealpha($fondo, $r, $g, $b, 0);
                imagefilledrectangle($fondo, 0, 0, $this->_dcp['ancho_final'], $this->_dcp['alto_final'], $fondo_color);

                $x_margen = ($this->_dcp['ancho_final'] - $ancho_final) / 2;
                $y_margen = ($this->_dcp['alto_final'] - $alto_final) / 2;

                imagecopy($fondo, $imagen_temporal, $x_margen, $y_margen, 0, 0, $ancho_final, $alto_final);

                switch ($tipo) {
                    case 1: imagegif($fondo, $imagen);
                        break;
                    case 2: imagejpeg($fondo, $imagen, 90);
                        break;
                    case 3: imagepng($fondo, $imagen);
                        break;
                }

                ImageDestroy($imagen_temporal);
                ImageDestroy($fondo);
            }

            // renombro la imagen para que pase a ser definitiva
            $img_nombre_matriz = explode("_", $img_nombre);
            $img_nombre_matriz = array_slice($img_nombre_matriz, 3);

            $img_nombre_definitivo = $this->_idComponente . '_' . $this->_idRegistro . '_' . implode('_', $img_nombre_matriz);
            $img_nombre_definitivo = preg_replace("/[^a-zA-Z0-9\.]/", '_', $img_nombre_definitivo);

            $archivo = $this->_dcp['directorio'] . '/' . $img_nombre;

            $sub_dir = $this->_controlarCrearDirectorioMesAnio();

            if (file_exists($archivo) && is_file($archivo)) {
                rename($archivo, $this->_dcp['directorio'] . '/' . $sub_dir . $img_nombre_definitivo);
            }

            $this->_valor = $sub_dir . $img_nombre_definitivo;

            $imagen = $this->_dcp['directorio'] . '/' . $sub_dir . $img_nombre_definitivo;
            $imagen_muestra = $this->_dcp['directorio'] . '/.' . $this->_dcp['cp_nombre'] . '.kirke/kk_' . $img_nombre_definitivo;

            // directorio de imagenes de muestra
            $dir_muestra = $this->_dcp['directorio'] . '/.' . $this->_dcp['cp_nombre'] . '.kirke';
            if (!file_exists($dir_muestra)) {
                mkdir($dir_muestra, 0775);
            }

            if (copy($imagen, $imagen_muestra)) {
                $this->_recortarOtrasImagenes($imagen_muestra, 50, 50);
            }

            for ($i = 1; $i <= 10; $i++) {
                if ($this->_dcp['prefijo_' . $i] != '') {
                    $imagen_sub = $this->_dcp['directorio'] . '/' . $sub_dir . $this->_dcp['prefijo_' . $i] . '_' . $img_nombre_definitivo;
                    if (copy($imagen, $imagen_sub)) {
                        chmod($imagen_sub, 0775);
                        $this->_recortarOtrasImagenes($imagen_sub, $this->_dcp['ancho_' . $i], $this->_dcp['alto_' . $i]);
                    }
                }
            }
        } elseif ($accion == 'eliminar') {

            $this->_obtenerDirectorio();

            $archivo = $this->_idComponente . '_' . $this->_idRegistro;

            // elimina el archivo anterior 'no temporal'
            $this->_eliminarArchivo($this->_dcp['directorio'], $archivo);

            $this->_valor = '';
        } elseif (!$img_nombre) {
            return false;
        }

        return $this->_valor;
    }

    private function _obtenerDirectorio() {

        $buscar = array("//", "\\\\");
        $sustituir = array("/", "\\");
        $this->_dcp['directorio'] = str_replace($buscar, $sustituir, $this->_dcp['directorio']);

        // obtengo el directirio donde se guardaran las imaganes
        $url_actual = getcwd();
        chdir(Inicio::pathPublico());
        chdir($this->_dcp['directorio']);
        $this->_dcp['directorio'] = getcwd();
        chdir($url_actual);
    }

    private function _eliminarArchivo($directorio, $archivo) {
        $this->_eliminarArchivoDirectorio($directorio, $directorio, $archivo);
        $matriz_subdir = glob($directorio . '/*', GLOB_ONLYDIR);
        if (is_array($matriz_subdir)) {
            foreach ($matriz_subdir as $matriz_subdir_nombre) {
                $this->_eliminarArchivoDirectorio($directorio, $matriz_subdir_nombre, $archivo);
            }
        }
    }

    private function _eliminarArchivoDirectorio($directorio, $dub_directorio, $archivo) {
        // elimina el archivo anterior 'no temporal'
        $matriz = glob($dub_directorio . '/' . $archivo . '_*.*');
        if (is_array($matriz)) {
            foreach ($matriz as $dir_nombre_archivo) {
                $nombre_archivo_encontrado = basename($dir_nombre_archivo);
                if (is_file($dir_nombre_archivo)) {
                    unlink($dir_nombre_archivo);
                    for ($i = 1; $i <= 10; $i++) {
                        if ($this->_dcp['prefijo_' . $i] != '') {
                            $imagen_sub = $dub_directorio . '/' . $this->_dcp['prefijo_' . $i] . '_' . $nombre_archivo_encontrado;
                            if (is_file($imagen_sub)) {
                                unlink($imagen_sub);
                            }
                        }
                    }
                }
                if (is_file($directorio . '/.' . $this->_dcp['cp_nombre'] . '.kirke/kk_' . $nombre_archivo_encontrado)) {
                    unlink($directorio . '/.' . $this->_dcp['cp_nombre'] . '.kirke/kk_' . $nombre_archivo_encontrado);
                }
            }
        }
    }

    private function _controlarCrearDirectorioMesAnio() {

        if ($this->_dcp['separar_en_directorios'] == 'n') {
            return '';
        } elseif ($this->_dcp['separar_en_directorios'] == 'mes') {
            $sub_dir = date('ym');
        } elseif ($this->_dcp['separar_en_directorios'] == 'anio') {
            $sub_dir = date('y');
        }

        if (!is_dir($this->_dcp['directorio'] . '/' . $sub_dir)) {
            mkdir($this->_dcp['directorio'] . '/' . $sub_dir);
        }

        return $sub_dir . '/';
    }

    private function _recortarOtrasImagenes($imagen, $ancho_final, $alto_final) {

        list( $ancho, $alto, $tipo ) = getimagesize($imagen);
        // tipos de archivos 1=GIF, 2=JPG, 3=PNG

        $indice_ancho = $ancho / $ancho_final;
        $indice_alto = $alto / $alto_final;

        if ($indice_ancho > $indice_alto) {
            // cortar a ancho
            $ancho_corte = $alto * $ancho_final / $alto_final;
            $alto_corte = $alto;
            $x = round((($ancho - $ancho_corte) / 2), 0);
            $y = 0;
        } else {
            // cortar a alto
            $ancho_corte = $ancho;
            $alto_corte = round(($ancho * $alto_final / $ancho_final), 0);
            $x = 0;
            $y = round((($alto - $alto_corte) / 2), 0);
        }

        $recortar = new Generales_ImagenRecortar();
        $recortar->imagen($imagen);
        $recortar->anchoFinal($ancho_final);
        $recortar->altoFinal($alto_final);
        $recortar->corteX($x);
        $recortar->corteY($y);
        $recortar->corteAncho($ancho_corte);
        $recortar->corteAlto($alto_corte);
        $recortar->recortar();
    }

}

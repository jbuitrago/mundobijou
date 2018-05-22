<?php

class ImagenCambiarTamano {

    private $_datos;
    private $_imagenOrigPath;
    private $_imagenOrigNombre;
    private $_imagenFinalPath;
    private $_imagenFinalNombre;
    private $_anchoFinal;
    private $_altoFinal;
    private $_tipo;
    private $_imagenOrig;
    private $_imagenTipoNueva;
    private $_recortar = true;
    private $_fondoColor;
    private $_fondoImagen;

    public function imagenOrigenPath($imagen_path) {
        $this->_imagenOrigPath = $imagen_path;
    }

    public function imagenOrigenNombre($imagen_nombre) {
        $this->_imagenOrigNombre = $imagen_nombre;
    }

    public function imagenFinalPath($imagen_path) {
        $this->_imagenFinalPath = $imagen_path;
    }

    public function imagenFinalNombre($imagen_nombre) {
        $this->_imagenFinalNombre = $imagen_nombre;
    }

    public function anchoFinal($ancho) {
        $this->_anchoFinal = $ancho;
    }

    public function altoFinal($alto) {
        $this->_altoFinal = $alto;
    }

    public function agregarFondoColor($color) {
        $this->_recortar = false;
        $this->_fondoColor = $color;
    }

    public function agregarFondoImagen($imagen_path) {
        $this->_recortar = false;
        $this->_fondoImagen = $imagen_path;
    }

    public function obtenerImagen() {
        if ((isset($this->_anchoFinal) || isset($this->_altoFinal)) && isset($this->_imagenOrigPath) && isset($this->_imagenOrigNombre)) {

            if (!isset($this->_imagenFinalPath)) {
                $this->_imagenFinalPath = $this->_imagenOrigPath;
            }

            if (!isset($this->_imagenFinalNombre)) {
                $this->_imagenFinalNombre = $this->_imagenOrigNombre;
            }

            // se obtiene informacion de la imagen orginal
            list($ancho_orig, $alto_orig, $this->_tipo) = getimagesize($this->_imagenOrigPath . '/' . $this->_imagenOrigNombre);
            // tipos de archivos 1=GIF, 2=JPG, 3=PNG
            switch ($this->_tipo) {
                case 1: $this->_imagenOrig = imagecreatefromgif($this->_imagenOrigPath . '/' . $this->_imagenOrigNombre);
                    break;
                case 2: $this->_imagenOrig = imagecreatefromjpeg($this->_imagenOrigPath . '/' . $this->_imagenOrigNombre);
                    break;
                case 3: $this->_imagenOrig = imagecreatefrompng($this->_imagenOrigPath . '/' . $this->_imagenOrigNombre);
                    break;
                default:
                    return false;
            }

            if (!isset($this->_anchoFinal)) {
                $this->_anchoFinal = $ancho_orig * $this->_altoFinal / $alto_orig;
            }

            if (!isset($this->_altoFinal)) {
                $this->_altoFinal = $alto_orig * $this->_anchoFinal / $ancho_orig;
            }

            if ($this->_recortar === true) {
                $this->recorteSobrante($ancho_orig, $alto_orig);
            } else {
                $this->agregarSobrante($ancho_orig, $alto_orig);
            }
            return true;
        }
        return false;
    }

    private function recorteSobrante($ancho_orig, $alto_orig) {

        // verifico si relacionada con el original proporcionalmente
        // es mas alto o ancho
        $ancho_ratio = $ancho_orig / $this->_anchoFinal;
        $alto_ratio = $alto_orig / $this->_altoFinal;

        // calculo el corte del sobrante
        if ($alto_ratio >= $ancho_ratio) {
            $src_w = $ancho_orig;
            $src_h = $ancho_ratio * $this->_altoFinal;
            $src_y = round((( $alto_orig - $src_h ) / 2), 0);
            $src_x = 0;
        } else {
            $src_w = $alto_ratio * $this->_anchoFinal;
            $src_h = $alto_orig;
            $src_y = 0;
            $src_x = round((( $ancho_orig - $src_w ) / 2), 0);
        }
        $dst_x = 0;
        $dst_y = 0;

        // creo la imagen de destino y le cargo la imagen temporal
        $dst_image = imagecreatetruecolor($this->_anchoFinal, $this->_altoFinal);

        $this->procesoImagen($dst_image, $dst_x, $dst_y, $src_x, $src_y, $this->_anchoFinal, $this->_altoFinal, $src_w, $src_h);
    }

    private function agregarSobrante($ancho_orig, $alto_orig) {

        if ($this->_fondoImagen !== null) {

            list($this->_anchoFinal, $this->_altoFinal, $tipo) = getimagesize($this->_fondoImagen);
            // tipos de archivos 1=GIF, 2=JPG, 3=PNG
            switch ($tipo) {
                case 1: $dst_image = imagecreatefromgif($this->_fondoImagen);
                    break;
                case 2: $dst_image = imagecreatefromjpeg($this->_fondoImagen);
                    break;
                case 3: $dst_image = imagecreatefrompng($this->_fondoImagen);
                    break;
                default:
                    return false;
            }
        } else {
            if ($this->_fondoColor === null) {
                $this->_fondoColor = 'FFFFFF';
            }

            $dst_image = imagecreatetruecolor($this->_anchoFinal, $this->_altoFinal);
            list($r, $g, $b) = sscanf('#' . $this->_fondoColor, "#%02x%02x%02x");
            imagealphablending($dst_image, false);
            imagesavealpha($dst_image, true);
            $fondo_color = imagecolorallocatealpha($dst_image, $r, $g, $b, 0);
            imagefilledrectangle($dst_image, 0, 0, $this->_anchoFinal, $this->_altoFinal, $fondo_color);
        }

        // verifico si relacionada con el original proporcionalmente
        // es mas alto o ancho
        $ancho_ratio = $ancho_orig / $this->_anchoFinal;
        $alto_ratio = $alto_orig / $this->_altoFinal;

        // calculo el corte del sobrante
        if ($alto_ratio <= $ancho_ratio) {
            $dst_x = 0;
            $src_x = 0;
            $src_y = 0;
            $dst_w = $this->_anchoFinal;
            $src_w = $ancho_orig;
            $src_h = $alto_orig;
            $dst_h = round($src_h * $dst_w / $src_w, 0);
            $dst_y = round(($this->_altoFinal - $dst_h) / 2, 0);
        } else {
            $dst_y = 0;
            $src_y = 0;
            $src_x = 0;
            $dst_h = $this->_altoFinal;
            $src_h = $alto_orig;
            $src_w = $ancho_orig;
            $dst_w = round($src_w * $dst_h / $src_h, 0);
            ;
            $dst_x = round(($this->_anchoFinal - $dst_w) / 2, 0);
        }

        $this->procesoImagen($dst_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
    }

    private function procesoImagen($dst_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h) {

        imagecopyresampled($dst_image, $this->_imagenOrig, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        switch ($this->_tipo) {
            case 1: imagegif($dst_image, $this->_imagenFinalPath . '/' . $this->_imagenFinalNombre);
                break;
            case 2: imagejpeg($dst_image, $this->_imagenFinalPath . '/' . $this->_imagenFinalNombre);
                break;
            case 3: imagepng($dst_image, $this->_imagenFinalPath . '/' . $this->_imagenFinalNombre);
                break;
        }

        // destruyo las imagenes innecesarias
        ImageDestroy($this->_imagenOrig);
        ImageDestroy($dst_image);

        return true;
    }

}

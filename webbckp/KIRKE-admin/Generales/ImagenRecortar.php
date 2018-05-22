<?php

class Generales_ImagenRecortar {

    private $_imagen = '';
    private $_ancho_final = 0;
    private $_alto_final = 0;
    private $_x = 0;
    private $_y = 0;
    private $_w = 0;
    private $_h = 0;

    public function imagen($imagen = '') {
        $this->_imagen = $imagen;
    }

    public function anchoFinal($ancho_final = 0) {
        $this->_ancho_final = $ancho_final;
    }

    public function altoFinal($alto_final = 0) {
        $this->_alto_final = $alto_final;
    }

    public function corteX($x = 0) {
        $this->_x = $x;
    }

    public function corteY($y = 0) {
        $this->_y = $y;
    }

    public function corteAncho($w = 0) {
        $this->_w = $w;
    }

    public function corteAlto($h = 0) {
        $this->_h = $h;
    }

    public function recortar() {

        if (!file_exists($this->_imagen)) {
            return false;
        }

        if (!@gd_info()) {
            echo 'falta librería GD';
        }

        // se obtiene informacion de la imagen orginal
        list( $ancho, $alto, $tipo ) = getimagesize($this->_imagen);
        // tipos de archivos 1=GIF, 2=JPG, 3=PNG

        switch ($tipo) {
            case 1:
                $imagen_temporal = imagecreatefromgif($this->_imagen);
                break;
            case 2:
                $imagen_temporal = imagecreatefromjpeg($this->_imagen);
                break;
            case 3:
                $imagen_temporal = imagecreatefrompng($this->_imagen);
                break;
        }

        // creo la imagen de destino y le cargo la imagen temporal
        $imagen_destino = imagecreatetruecolor($this->_ancho_final, $this->_alto_final);
        if ($tipo != 2) {
            // si tiene transparencia
            imagecolortransparent($imagen_destino, imagecolorallocatealpha($imagen_destino, 0, 0, 0, 127));
            imagealphablending($imagen_destino, false);
            imagesavealpha($imagen_destino, true);
        }

        imagecopyresampled(
                $imagen_destino, // Destino del recurso de enlace a una imagen.
                $imagen_temporal, // Origen del recurso de enlace a una imagen.
                0, // Coordenada x del punto de destino.
                0, // Coordenada y del punto de destino.
                $this->_x, // Coordenada x del punto de origen.
                $this->_y, // Coordenada y del punto de origen.
                $this->_ancho_final, // Ancho del destino.
                $this->_alto_final, // Alto del destino.
                $this->_w, // Ancho del origen.
                $this->_h // Alto del origen.
        );

        switch ($tipo) {
            case 1: imagegif($imagen_destino, $this->_imagen);
                break;
            case 2: imagejpeg($imagen_destino, $this->_imagen, 90);
                break;
            case 3: imagepng($imagen_destino, $this->_imagen);
                break;
        }

        // destruyo las imagenes innecesarias
        ImageDestroy($imagen_temporal);
        ImageDestroy($imagen_destino);

        $imagen_tipo_nueva['imagen'] = $this->_imagen;
        $imagen_tipo_nueva['tipo'] = $tipo;

        return $imagen_tipo_nueva;
    }

    private function _tamanioFinal($alto_intermedio, $ancho_intermedio) {

        if ($ancho_intermedio > $this->_ancho_final) {
            $indice_ancho = $ancho_intermedio / $this->_ancho_final;
        }
        if ($alto_intermedio > $this->_alto_final) {
            $indice_alto = $alto_intermedio / $this->_alto_final;
        }
        if ($indice_ancho > $indice_alto) {
            $ancho_nvo = $this->_ancho_final;
            $alto_nvo = $alto_intermedio * $this->_ancho_final / $ancho_intermedio;
        } else {
            $alto_nvo = $this->_alto_final;
            $ancho_nvo = $ancho_intermedio * $this->_alto_final / $alto_intermedio;
        }

        $tamanio['ancho'] = round($ancho_nvo, 0);
        $tamanio['alto'] = round($alto_nvo, 0);

        return $tamanio;
    }

}

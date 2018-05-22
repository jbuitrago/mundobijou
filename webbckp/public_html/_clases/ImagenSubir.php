<?php

class ImagenSubir {

    private $_imagenAltoFinal;
    private $_imagenAnchoFinal;
    private $_nombreCampo;
    private $_directorio;
    private $_nombreFinal;
    private $_componenteId;
    private $_idRegistro;
    private $_control = false;
    private $_extension = false;

    public function imagenAltoFinal($imagen_alto_final) {
        $this->_imagenAltoFinal = $imagen_alto_final;
    }

    public function imagenAnchoFinal($imagen_ancho_final) {
        $this->_imagenAnchoFinal = $imagen_ancho_final;
    }

    public function nombreCampo($nombre_campo) {
        $this->_nombreCampo = $nombre_campo;
    }

    public function directorio($directorio) {
        $this->_directorio = $directorio;
    }

    public function nombreFinal($nombre_final) {
        $this->_nombreFinal = $nombre_final;
    }

    public function componenteId($componente_id) {
        $this->_componenteId = $componente_id;
    }

    public function idRegistro($id_registro) {
        $this->_idRegistro = $id_registro;
    }

    public function subir() {

        $this->_extension = $this->_controlExtension();

        if ($this->_extension === false) {
            return false;
        }

        move_uploaded_file($_FILES[$this->_nombreCampo]['tmp_name'], $this->_directorio . '/' . $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name']);

        $nueva_imagen = new ImagenCambiarTamano();
        $nueva_imagen->imagenOrigenPath($this->_directorio);
        $nueva_imagen->imagenOrigenNombre($this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name']);

        if (!isset($this->_nombreFinal)) {
            $nueva_imagen->imagenFinalNombre($this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name']);
        } else {
            $nueva_imagen->imagenFinalNombre($this->_componenteId . '_' . $this->_idRegistro . '_' . $this->_nombreFinal . '.' . $this->_extension);
        }

        $nueva_imagen->anchoFinal($this->_imagenAnchoFinal);
        $nueva_imagen->altoFinal($this->_imagenAltoFinal);
        if ($nueva_imagen->obtenerImagen()) {

            if (isset($this->_nombreFinal)) {
                unlink($this->_directorio . '/' . $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name']);
            }

            $this->_control = true;
            return true;
            ;
        } else {
            return false;
        }
    }

    public function obtenerNombre() {

        if ($this->_control === false) {
            return false;
        } elseif (isset($this->_nombreFinal)) {
            return $this->_componenteId . '_' . $this->_idRegistro . '_' . $this->_nombreFinal . '.' . $this->_extension;
        } elseif ($_FILES[$this->_nombreCampo]['name'] != '') {
            return $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name'];
        } else {
            return false;
        }
    }

    public function obtenerNombreLimpio() {

        if ($this->_control === false) {
            return false;
        } elseif (isset($this->_nombreFinal)) {
            return $this->_nombreFinal . '.' . $this->_extension;
        } elseif ($_FILES[$this->_nombreCampo]['name'] != '') {
            return $_FILES[$this->_nombreCampo]['name'];
        } else {
            return false;
        }
    }

    private function _controlExtension() {

        $path_parts = pathinfo($_FILES[$this->_nombreCampo]['name']);

        if (isset($path_parts['extension'])) {
            switch ($path_parts['extension']) {
                case 'jpg':
                    $this->_control = true;
                    return 'jpg';
                    break;
                case 'gif':
                    $this->_control = true;
                    return 'gif';
                    break;
                case 'png':
                    $this->_control = true;
                    return 'png';
                    break;
                default:
                    return false;
            }
        } else {
            return false;
        }
    }

}

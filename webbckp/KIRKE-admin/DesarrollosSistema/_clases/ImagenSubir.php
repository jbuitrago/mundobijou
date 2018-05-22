<?php

class ImagenSubir {

    private $_imagenAltoFinal;
    private $_imagenAnchoFinal;
    private $_nombreCampo;
    private $_directorio;
    private $_componenteId;
    private $_idRegistro;
    private $_control = true;

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

    public function componenteId($componente_id) {
        $this->_componenteId = $componente_id;
    }

    public function idRegistro($id_registro) {
        $this->_idRegistro = $id_registro;
    }

    public function subir() {

        if ($this->_controlExtension() === false) {
            return false;
        }

        $imagen_cambiar_tamano['imagen'] = $_FILES[$this->_nombreCampo]['tmp_name'];
        $imagen_cambiar_tamano['ancho_final'] = $this->_imagenAnchoFinal;
        $imagen_cambiar_tamano['alto_final'] = $this->_imagenAltoFinal;

        $nueva_imagen = new ImagenCambiarTamano();

        if ($nueva_imagen->ingresarDatos($imagen_cambiar_tamano) === false) {
            $this->_control = false;
            return false;
        }

        $nueva_imagen = $nueva_imagen->obtenerImagen();

        move_uploaded_file($nueva_imagen['imagen'], $this->_directorio . '/' . $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name']);
    }

    public function obtenerNombre() {

        if ($this->_control === false) {
            return false;
        }

        if ($_FILES[$this->_nombreCampo]['name'] != '') {
            return $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name'];
        } else {
            return false;
        }
    }

    private function _controlExtension() {

        $path_parts = pathinfo($_FILES[$this->_nombreCampo]['name']);
        $extension = $path_parts['extension'];

        if (
                ($extension != 'jpg') && ($extension != 'gif') && ($extension != 'png')
        ) {
            $this->_control = false;
            return false;
        } else {
            return true;
        }
    }

}

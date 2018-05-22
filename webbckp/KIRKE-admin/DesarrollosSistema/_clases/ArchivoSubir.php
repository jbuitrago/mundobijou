<?php

class ArchivoSubir {

    private $_nombreCampo;
    private $_directorio;
    private $_componenteId;
    private $_idRegistro;

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
        move_uploaded_file($_FILES[$this->_nombreCampo]['tmp_name'], $this->_directorio . '/' . $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name']);
    }

    public function obtenerNombre() {
        if ($_FILES[$this->_nombreCampo]['name'] != '') {
            return $this->_componenteId . '_' . $this->_idRegistro . '_' . $_FILES[$this->_nombreCampo]['name'];
        } else {
            return false;
        }
    }

}

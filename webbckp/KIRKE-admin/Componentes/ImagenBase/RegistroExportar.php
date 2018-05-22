<?php

class Componentes_ImagenBase_RegistroExportar extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_imagen;
    private $_tipo;

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
        $this->_dcp = array_merge($_pv, $this->_dcp);
        $titulo = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        $this->_titulo = strtolower(str_replace(' ', '_', $titulo));
    }

    public function get() {
        if ($this->_valor != '') {
            $this->_imagen = $this->_valor;
            $solo_nombre = explode(';', $this->_valor);
            $this->_tipo = $solo_nombre[0];
            $nombre_real = explode('_', $solo_nombre[1], 3);
            $nombre_real = $nombre_real[2];
            $this->_valor = $nombre_real;
        }
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _excel() {
        $valor['titulo'] = str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_titulo, '<a>'));
        $valor['valor'] = '<td bgcolor="#eeeeec">' . str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($this->_valor, '<a>')) . '</td>';
        return $valor;
    }

    private function _pdf() {

        if ($this->_imagen != '') {
            $tipos_imagenes = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
            $uri = explode('/', $_SERVER['REQUEST_URI']);
            array_pop($uri);
            array_shift($uri);
            $uri = implode('/', $uri);
            $filename = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . $uri . '/index.php?kk_generar=2&tipo=ImagenBase&tabla_tipo=especifica&id_componente=' . $this->_idComponente . '&id_registro=' . $this->_idRegistro . '&variables=imagen_base&tipo_imagen=original';
            $valor['imagen'] = $filename;
            $valor['imagen_tipo'] = $tipos_imagenes[$this->_tipo];
        }
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = $this->_valor;
        return $valor;
    }

    private function _cvs() {
        $valor['titulo'] = str_replace("\n", ' ', $this->_titulo);
        $valor['valor'] = str_replace(';', ',', str_replace(array("\r\n", "\n", "\r"), ' ', $this->_valor)) . ';';
        return $valor;
    }

    private function _xml() {
        $this->_titulo = strtolower(str_replace(' ', '_', $this->_titulo));
        return '    <' . $this->_titulo . '><![CDATA[' . $this->_valor . ']]></' . $this->_titulo . '>' . "\n";
    }

    private function _html() {
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = '<td>' . $this->_valor . '</td>';
        return $valor;
    }

    private function _sql() {
        $valor['titulo'] = $this->_titulo;
        $valor['valor'] = "'" . str_replace("'", '\\\'', $this->_valor) . "', ";
        return $valor;
    }

    private function _sqlEstructura() {
        $titulo[0] = '  `' . $this->_dcp['tb_campo'] . '` longtext,' . "\n";
        $titulo[1] = '`' . $this->_dcp['tb_campo'] . '`, ';
        return $titulo;
    }

}

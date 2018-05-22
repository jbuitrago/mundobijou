<?php

class Archivos_ImagenDirectorioTemporal {

    private $_dcp;

    public function set($parametros) {

        $this->_dcp = $parametros;
        $this->_armado();
    }

    private function _armado() {

        if ($_GET['ver'] == 'temporal') {

            $imagen_nombre = 't_' . $_GET['foto_nombre'];
        } elseif ($_GET['ver'] == 'definitiva') {

            $imagen_nombre = $_GET['foto_nombre'];
        }

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('tmp');
        $directorio = getcwd();
        chdir($url_actual);

        $path_parts = pathinfo($imagen_nombre);
        $extension = $path_parts['extension'];

        switch ($extension) {
            case 'gif':
                Header("Content-Type: image/gif");
                break;
            case 'jpg':
                Header("Content-Type: image/jpg");
                break;
            case 'png':
                Header("Content-Type: image/png");
                break;
        }

        if (file_exists($directorio . '/' . $imagen_nombre)) {
            echo file_get_contents($directorio . '/' . $imagen_nombre);
        } else {
            echo 'problema en ImagenDirectorio';
        }
    }

}


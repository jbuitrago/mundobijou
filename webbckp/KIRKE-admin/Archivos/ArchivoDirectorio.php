<?php

class Archivos_ArchivoDirectorio {

    private $_dcp;

    public function set($parametros) {

        $this->_dcp = $parametros;
        $this->_armado();
    }

    private function _armado() {

        if ($_GET['ver'] == 'temporal') {

            $archivo_nombre = 't_' . $_GET['archivo_nombre'];
            $nombre = $_GET['nombre'];
        } elseif ($_GET['ver'] == 'definitiva') {

            $archivo_nombre = $_GET['archivo_nombre'];
            $nombre = $_GET['nombre'];
        }

        $url_actual = getcwd();
        chdir(Inicio::pathPublico());
        chdir($this->_dcp['directorio']);
        $directorio = getcwd();
        chdir($url_actual);

        $file = $directorio . '/' . $archivo_nombre;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $nombre);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        } else {
            echo 'problema en ArchivoDirectorio';
        }
    }

}

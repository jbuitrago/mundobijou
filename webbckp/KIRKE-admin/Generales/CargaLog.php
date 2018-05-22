<?php

class Generales_CargaLog {

    static public function datos($elemento = null, $pagina = null, $nombre = null) {
        
        if (Inicio::usuario('id')) {

            if (Inicio::confVars('generar_log') == 's') {

                $url_actual = getcwd();
                chdir(Inicio::path());
                chdir('Logs');
                $directorio = getcwd();
                chdir($url_actual);
                
                if($nombre != null){
                    $nombre = '|nombre:' . $nombre;
                }else{
                    $nombre = '';
                }

                $contenido = date('Y-m-d H:i:s') . '|SIS|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'] . '|elemento:' . $elemento . '|pagina:' . $pagina . $nombre;

                file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SIS_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
            }
            return true;
        }
        return false;
    }

    static public function navegador($navegador) {

        $nombre = '?';
        $sistema_operativo = '?';
        $version = '';

        if (preg_match('/linux/i', $navegador)) {
            $sistema_operativo = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $navegador)) {
            $sistema_operativo = 'Mac';
        } elseif (preg_match('/windows|win32/i', $navegador)) {
            $sistema_operativo = 'Windows';
        }

        if (preg_match('/MSIE/i', $navegador) && !preg_match('/Opera/i', $navegador)) {
            $nombre = 'Internet Explorer';
            $nombre_p_version = 'MSIE';
        } elseif (preg_match('/Firefox/i', $navegador)) {
            $nombre = 'Mozilla Firefox';
            $nombre_p_version = 'Firefox';
        } elseif (preg_match('/Chrome/i', $navegador)) {
            $nombre = 'Google Chrome';
            $nombre_p_version = 'Chrome';
        } elseif (preg_match('/Safari/i', $navegador)) {
            $nombre = 'Apple Safari';
            $nombre_p_version = 'Safari';
        } elseif (preg_match('/Opera/i', $navegador)) {
            $nombre = 'Opera';
            $nombre_p_version = 'Opera';
        } elseif (preg_match('/Netscape/i', $navegador)) {
            $nombre = 'Netscape';
            $nombre_p_version = 'Netscape';
        }

        $version_desconocida = array('Version', $nombre_p_version, 'other');
        $patron_busqueda = '#(?<browser>' . join('|', $version_desconocida) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($patron_busqueda, $navegador, $encontrados)) {
            
        }

        $i = count($encontrados['browser']);
        if ($i != 1) {
            if (strripos($navegador, "Version") < strripos($navegador, $nombre_p_version)) {
                $version = $encontrados['version'][0];
            } else {
                $version = $encontrados['version'][1];
            }
        } else {
            $version = $encontrados['version'][0];
        }

        if ($version == null || $version == "") {
            $version = '?';
        }

        return $nombre . ' (' . $version . ') ' . $sistema_operativo;
    }

}

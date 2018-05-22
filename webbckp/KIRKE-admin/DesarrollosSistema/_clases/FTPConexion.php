<?php

class FTPConexion {

    private $servidor;
    private $puerto = 0;
    private $usuario;
    private $clave;
    private $_conexion_segura = false;
    private $_modo_pasivo = false;
    private $_conexion_ssl = false;
    private $_conexion_id = false;
    private $_transferencia = 'binaria';

    public function servidor($servidor) {
        $this->servidor = $servidor;
    }

    public function puerto($puerto) {
        $this->puerto = $puerto;
    }

    public function usuario($usuario) {
        $this->usuario = $usuario;
    }

    public function clave($clave) {
        $this->clave = $clave;
    }

    public function conexionSegura() {
        $this->_conexion_ssl = true;
    }

    public function modoPasivo() {
        $this->_modo_pasivo = true;
    }

    public function transferenciaASCII() {
        $this->_transferencia = 'ascii';
    }

    private function desconectar() {
        return ftp_close($this->_conexion_id);
    }

    public function archivoSubir($archivo_remoto, $archivo_local) {
        if ($this->_conectar()) {
            if (($this->_transferencia == 'binaria') && ftp_put($this->_conexion_id, $archivo_remoto, $archivo_local, FTP_BINARY)) {
                return true;
            } elseif (ftp_put(($this->_transferencia == 'ascii') && $this->_conexion_id, $archivo_remoto, $archivo_local, FTP_ASCII)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function archivoBajar($archivo_remoto, $archivo_local) {
        if ($this->_conectar()) {
            if (($this->_transferencia == 'binaria') && ftp_get($this->_conexion_id, $archivo_local, $archivo_remoto, FTP_BINARY, 0)) {
                return true;
            } elseif (($this->_transferencia == 'ascii') && ftp_get($this->_conexion_id, $archivo_local, $archivo_remoto, FTP_ASCII, 0)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function archivoEliminar($archivo_remoto) {
        if ($this->_conectar()) {
            if (ftp_delete($this->_conexion_id, $archivo_remoto)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function directorioCrear($directorio) {
        if ($this->_conectar()) {
            if (ftp_mkdir($this->_conexion_id, $directorio)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function directorioEliminar($directorio) {
        if ($this->_conectar()) {
            if (ftp_rmdir($this->_conexion_id, $directorio)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function renombrar($nombre, $nombre_nvo) {
        if ($this->_conectar()) {
            if (ftp_rename($this->_conexion_id, $nombre, $nombre_nvo)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function tamanoArchivo($archivo_remoto) {
        if ($this->_conectar()) {
            $res = ftp_size($this->_conexion_id, $archivo_remoto);
            if ($res != -1) {
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function listarArchivos($detalle = false) {
        if ($this->_conectar()) {
            if (($detalle === false) && (ftp_nlist($this->_conexion_id, ".") !== false)) {
                return ftp_nlist($this->_conexion_id, ".");
            } elseif (($detalle !== false) && (ftp_rawlist($this->_conexion_id, ".") !== false)) {
                $systype = ftp_systype($this->_conexion_id);
                switch ($systype) {
                    case "Windows_NT":
                        foreach (ftp_rawlist($this->_conexion_id, ".") as $v) {
                            preg_match("/([0-9]{2})(\/|-)([0-9]{2})(\/|-)([0-9]{2,4})[\s]+([0-9]{2}):([0-9]{2})[\s]+(AM|PM)[\s]+([0-9.\,.\.]+|<DIR>)[\s]+(.+)/", $v, $vinfo);
                            if (is_array($vinfo)) {
                                $info['permisos'] = '';
                                if ($vinfo[9] != '<DIR>') {
                                    $info['tipo'] = 'archivo';
                                } else {
                                    $info['tipo'] = 'directorio';
                                }
                                $info['isdir'] = ($vinfo[7] == "<DIR>");

                                if ($info['tipo'] == 'archivo') {
                                    $vinfo[9] = str_replace(array(',', '.'), '', $vinfo[9]);
                                    $info['tamanio'] = $vinfo[9];
                                    $info['tamanio_reducido'] = $this->_obtenerTamanio($vinfo[9]);
                                }
                                $info['usuario'] = '';
                                $info['grupo'] = '';
                                if (($vinfo[5] < 1900) && ($vinfo[5] < 70)) {
                                    $vinfo[5]+=2000;
                                } elseif (($vinfo[7] < 1900) && ($vinfo[5] >= 70)) {
                                    $vinfo[5]+=1900;
                                }
                                $info['fecha'] = mktime($vinfo[6], $vinfo[7], 0, $vinfo[1], $vinfo[3], $vinfo[5]);
                                $info['nombre'] = $vinfo[10];
                                $rawlist[$info['nombre']] = $info;
                            }
                        }
                        break;
                    case "UNIX":
                        foreach (ftp_rawlist($this->_conexion_id, ".") as $v) {
                            $info = array();
                            $vinfo = preg_split("/[\s]+/", $v, 9);
                            if ($vinfo[0] !== "total") {
                                $info['permisos'] = $this->_obtenerPermisosUnix($vinfo[0]);
                                $info['tipo'] = $this->_tipoUnix($vinfo[0], $vinfo[8]);
                                $info['usuario'] = $vinfo[2];
                                $info['grupo'] = $vinfo[3];
                                if ($info['tipo'] == 'archivo') {
                                    $info['tamanio'] = $vinfo[4];
                                    $info['tamanio_reducido'] = $this->_obtenerTamanio($vinfo[4]);
                                }
                                $info['fecha'] = $this->_obtenerFechaUnix($vinfo[6], $vinfo[5], $vinfo[7]);
                                $info['nombre'] = $vinfo[8];
                                $rawlist[$info['nombre']] = $info;
                            }
                        }
                        break;
                }

                return $rawlist;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function permisosCambiar($archivo, $permisos) {
        if ($this->_conectar()) {
            $permisos = octdec(str_pad($permisos, 4, '0', STR_PAD_LEFT));
            $permisos = (int) $permisos;
            if (ftp_chmod($this->_conexion_id, $permisos, $archivo) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function rutaVer() {
        if ($this->_conectar()) {
            return ftp_pwd($this->_conexion_id);
        } else {
            return false;
        }
    }

    public function rutaIr($ruta) {
        if ($this->_conectar()) {
            if (ftp_chdir($this->_conexion_id, $ruta)) {
                return true;
            } else {
                false;
            }
        } else {
            return false;
        }
    }

    public function rutaPadreIr() {
        if ($this->_conectar()) {
            return ftp_cdup($this->_conexion_id);
        } else {
            return false;
        }
    }

    public function tipoServidor() {
        if ($this->_conectar()) {
            if ($tipo_servidor = ftp_systype($this->_conexion_id)) {
                return $tipo_servidor;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function _conectar() {
        if ($this->_conexion_id !== false) {
            return true;
        } elseif ($this->_conexion_id === false) {

            if (VariableGet::sistema('mostrar_errores') === true) {
                if ($this->_conexion_ssl === false) {
                    $conexion_id = ftp_connect($this->servidor, $this->puerto) or die('No se puede conectar a ' . $this->servidor);
                } else {
                    $conexion_id = ftp_ssl_connect($this->servidor, $this->puerto) or die('No se puede conectar a ' . $this->servidor);
                }
            } else {
                if ($this->_conexion_ssl === false) {
                    $conexion_id = ftp_connect($this->servidor, $this->puerto);
                } else {
                    $conexion_id = ftp_ssl_connect($this->servidor, $this->puerto);
                }
            }

            $conexion_resultado = ftp_login($conexion_id, $this->usuario, $this->clave);
            if ($conexion_id && $conexion_resultado) {
                if ($this->_modo_pasivo === true) {
                    ftp_pasv($conexion_id, true);
                }
                $this->_conexion_id = $conexion_id;
                return true;
            }
            return false;
        }
    }

    private function _obtenerPermisosUnix($permisos) {
        $opciones = array('-' => '0', 'r' => '4', 'w' => '2', 'x' => '1');
        $permisos = substr(strtr($permisos, $opciones), 1);
        $array = str_split($permisos, 3);
        return array_sum(str_split($array[0])) . array_sum(str_split($array[1])) . array_sum(str_split($array[2]));
    }

    private function _obtenerTamanio($tamanio) {
        if ($tamanio < 1024) {
            return round($tamanio, 2) . ' Byte';
        } elseif ($tamanio < (1024 * 1024)) {
            return round(($tamanio / 1024), 2) . ' MB';
        } elseif ($tamanio < (1024 * 1024 * 1024)) {
            return round((($tamanio / 1024) / 1024), 2) . ' GB';
        } elseif ($tamanio < (1024 * 1024 * 1024 * 1024)) {
            return round(((($tamanio / 1024) / 1024) / 1024), 2) . ' TB';
        }
    }

    private function _tipoUnix($tipo, $nombre) {
        if ((str_replace('//', '', $nombre) == '.') || (str_replace('//', '', $nombre) == '..')) {
            return false;
        }
        if (substr($tipo, 0, 1) == "d") {
            return 'directorio';
        } elseif (substr($tipo, 0, 1) == "l") {
            return 'link';
        } else {
            return 'archivo';
        }
    }

    private function _obtenerFechaUnix($dia, $mes, $hora_anio) {
        if (strpos($hora_anio, ':') === false) {
            $anio = $hora_anio;
            $hora = 0;
            $minutos = 0;
        } else {
            $anio = date("Y");
            $hora_minutos = explode(':', $hora_anio);
            $hora = $hora_minutos[0];
            $minutos = $hora_minutos[1];
        }
        $meses = array("Jan" => 1, "Feb" => 2, "Mar" => 3, "Apr" => 4, "May" => 5, "Jun" => 6, "Jul" => 7, "Aug" => 8, "Sep" => 9, "Oct" => 10, "Nov" => 11, "Dec" => 12);
        $mes = $meses[$mes];
        return mktime($hora, $minutos, 0, $mes, $dia, $anio);
    }

}

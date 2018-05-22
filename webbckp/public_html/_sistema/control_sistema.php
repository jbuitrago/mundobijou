<?php

new ControlSistema();

class ControlSistema {

    private static $contador = 0;

    function __construct() {

        if (VariableControl::getParam('borrar')) {
            $this->_borrarCache(VariableControl::getParam('borrar'));
        }

        $directorios = array(
            array('nombre' => '_php', 'directorio' => VariableGet::sistema('directorios_php')),
            array('nombre' => '_clases', 'directorio' => VariableGet::sistema('directorio_clases')),
            array('nombre' => '_bases', 'directorio' => VariableGet::sistema('directorio_bases')),
        );

        $directorios_cache = array(
            array('nombre' => 'plantillas', 'directorio' => VariableGet::sistema('directorio_cache_plantillas'), 'todo' => true),
            array('nombre' => 'compilados', 'directorio' => VariableGet::sistema('directorio_cache_compilados'), 'todo' => true),
            array('nombre' => 'base', 'directorio' => VariableGet::sistema('directorio_cache_base'), 'todo' => true),
            array('nombre' => 'base_tablas', 'directorio' => VariableGet::sistema('directorio_cache_base_tablas'), 'todo' => false),
            array('nombre' => 'sistema', 'directorio' => VariableGet::sistema('directorio_cache_sistema'), 'todo' => true),
            array('nombre' => 'links', 'directorio' => VariableGet::sistema('directorio_cache_links'), 'todo' => true),
        );

        echo '
        <script type="text/javascript">
            $(document).ready(function(){
                $(".kk_informe_errores").click(function() {
                    var id = $(this).attr("id");
                    $( "#cont_"+id ).toggle( "slow" );
                });
            });
        </script>
        <div style="width:90%;background-color:#FFF;font-family:arial,verdana,sans-serif;padding:20px;font-size:12px;color:#424242;">
        ';

        foreach ($directorios as $directorio) {
            $datos = $this->_controlContenido($directorio['nombre'], $directorio['directorio']);
            echo $this->_imprimirInformeContenido($directorio['nombre'], $datos['cantidad'], $datos['mas_antiguo'], $datos['mas_nuevo'], $directorio['directorio'], $datos['directorio_absoluto'], $datos['no_existe']);
        }

        foreach ($directorios_cache as $directorio) {
            $datos = $this->_controlContenido($directorio['nombre'], $directorio['directorio']);
            echo $this->_imprimirInformeCache($directorio['nombre'], $datos['cantidad'], $datos['mas_antiguo'], $datos['mas_nuevo'], $directorio['directorio'], $datos['directorio_absoluto'], $datos['no_existe'], $directorio['todo']);
        }

        include PathUrl::$conf . '_configuraciones/bases.php';

        if (file_exists(PathUrl::$conf . '_configuraciones/bases.php') === false) {
            echo $this->_imprimirInforme('Conexion con base de datos', 'No se puede acceder al archivo de configuracion de base de datos', false);
        } elseif (
                isset($bases['servidor']) && ($bases['servidor'] != '' ) && isset($bases['base']) && ( $bases['base'] != '' ) && isset($bases['usuario']) && ( $bases['usuario'] != '' ) && isset($bases['clave']) && ( $bases['clave'] != '' )
        ) {
            switch (VariableGet::sistema('tipo_base')) {
                case 'mysql':
                    $bd = @mysql_connect($bases['servidor'], $bases['usuario'], $bases['clave']);
                    $bd_seleccionada = @mysql_select_db($bases['base'], $bd);
                    break;
            }
            if (!$bd_seleccionada && $bd) {
                echo $this->_imprimirInforme('Conexion con base de datos (nombre de la base)', '', false);
            } elseif (!$bd) {
                echo $this->_imprimirInforme('Conexion con base de datos', '', false);
            } else {
                echo $this->_imprimirInforme('Conexion con base de datos', '', true);
            }
        } else {
            echo $this->_imprimirInforme('Conexion con base de datos', 'Falta alguna variable de conexion', false);
        }

        if (!file_exists('sitemap_automatico_principal.xml')) {
            echo $this->_imprimirInforme('Archivo sitemap_automatico_principal.xml no existe', '', false);
        } elseif (is_writable('sitemap_automatico_principal.xml')) {
            echo $this->_imprimirInforme('Escritura archivo sitemap_automatico_principal.xml', '', true);
        } else {
            echo $this->_imprimirInforme('Escritura archivo sitemap_automatico_principal.xml', '', false);
        }

        if (!file_exists('sitemap_automatico_secundario.xml')) {
            echo $this->_imprimirInforme('Archivo sitemap_automatico_secundario.xml no existe', '', false);
        } elseif (is_writable('sitemap_automatico_secundario.xml')) {
            echo $this->_imprimirInforme('Escritura archivo sitemap_automatico_secundario.xml', '', true);
        } else {
            echo $this->_imprimirInforme('Escritura archivo sitemap_automatico_secundario.xml ', '', false);
        }

        echo $this->_imprimirMail();

        echo "\n" . '</div>';
    }

    private function _controlContenido($nombre, $directorio) {
        $cantidad = 0;
        $mas_antiguo = 1000000000000000;
        $mas_nuevo = 0;
        $no_existe = false;
        $directorio_absoluto = $this->_obtenerDirectorio($directorio);

        if (is_dir($directorio_absoluto)) {
            if ($directorio_contenido = opendir($directorio_absoluto)) {
                while (($archivo = readdir($directorio_contenido)) !== false) {
                    if (filetype($directorio_absoluto . '/' . $archivo) == 'file') {
                        $hora = filemtime($directorio_absoluto . '/' . $archivo);
                        if ($hora < $mas_antiguo) {
                            $mas_antiguo = $hora;
                        }
                        if ($hora > $mas_nuevo) {
                            $mas_nuevo = $hora;
                        }
                        $cantidad++;
                    }
                }
                closedir($directorio_contenido);
            }
        } else {
            $no_existe = true;
        }

        $return['cantidad'] = $cantidad;
        $return['mas_antiguo'] = $mas_antiguo;
        $return['mas_nuevo'] = $mas_nuevo;
        $return['directorio_absoluto'] = $directorio_absoluto;
        $return['no_existe'] = $no_existe;

        return $return;
    }

    private function _imprimirMail() {

        if (isset($_POST['kk_mail_origen']) && isset($_POST['kk_mail_destino'])) {
            $mail = new ArmadoMail();
            $mail->servidorMail($_POST['kk_mail_origen']);
            $mail->mailDestinatario($_POST['kk_mail_destino']);
            $mail->asunto('KIRKE control mail: ' . $_SERVER['HTTP_HOST']);
            $mail->html('Prueba mail');
            $mail->envio();
            //$mail->verCodigo();
            $color = 'verde';
        } else {
            $color = 'rojo';
        }

        $impresion = "\n" . '<form method="post" name="KK_formulario">';
        $impresion .= "\n" . '<div style="width:100%;height:20px;color:#000;background-color:#FFF;"><div style="float:left;clear:left;width:20px;">' . $this->_semaforo($color) . '</div><div style="float:left;width:400px;">Control mail:</div></div>';
        $impresion .= "\n" . '<div style="padding-left:20px;margin-bottom:20px;display:none;clear:both"></div>';

        $impresion .= "\n" . '<div style="width:100%;height:20px;color:#000;background-color:#FFF;"><div style="float:left;clear:left;width:20px;">&nbsp;</div><div style="float:left;width:100px;">Mail origen: </div><div style="float:left;width:300px;"><input type="text" name="kk_mail_origen" style="width:300px;color:#000;" maxlength="100"></div></div>';
        $impresion .= "\n" . '<div style="padding-left:20px;margin-bottom:20px;display:none;clear:both"></div>';

        $impresion .= "\n" . '<div style="width:100%;height:20px;color:#000;background-color:#FFF;"><div style="float:left;clear:left;width:20px;">&nbsp;</div><div style="float:left;width:100px;">Mail destino: </div><div style="float:left;width:300px;"><input type="text" name="kk_mail_destino" style="width:300px;color:#000;" maxlength="100"></div></div>';
        $impresion .= "\n" . '<div style="padding-left:20px;margin-bottom:20px;display:none;clear:both"></div>';

        $impresion .= "\n" . '<div style="width:100%;height:20px;color:#000;background-color:#FFF;"><div style="float:left;clear:left;width:20px;">&nbsp;</div><div style="float:left;width:400px;"><input type="submit" value="Enviar mail" /></div></div>';
        $impresion .= "\n" . '<div style="padding-left:20px;margin-bottom:20px;display:none;clear:both"></div>';
        $impresion .= "\n" . '</form>';

        echo $impresion;
    }

    private function _imprimirInformeContenido($nombre, $cantidad, $mas_antiguo, $mas_nuevo, $directorio, $directorio_absoluto, $no_existe) {
        if ($no_existe === true) {
            $titulo = 'El directorio "' . $nombre . '" no existe';
            $contenido = '<span style="width:100px;">Directorio:</span> ' . $directorio;
            $estado = false;
        } elseif ($cantidad == 0) {
            $titulo = 'El directorio "' . $nombre . '" no contine archivos';
            $contenido = '<span style="width:100px;">Directorio:</span> ' . $directorio_absoluto;
            $estado = false;
        } else {
            $titulo = 'Directorio "' . $nombre . '"';
            $contenido = '<span style="width:100px;">Directorio:</span> ' . $directorio_absoluto . '<br />';
            $contenido .= '<span style="width:100px;">Cantidad:</span> ' . $cantidad . '<br />';
            $contenido .= '<span style="width:100px;">Mas antiguo:</span> ' . date('Y-m-d G:i:s', $mas_antiguo) . '<br />';
            $contenido .= '<span style="width:100px;">Mas nuevo:</span> ' . date('Y-m-d G:i:s', $mas_nuevo);
            $estado = true;
        }
        return $this->_imprimirInforme($titulo, $contenido, $estado);
    }

    private function _imprimirInformeCache($nombre, $cantidad, $mas_antiguo, $mas_nuevo, $directorio, $directorio_absoluto, $no_existe, $todo) {

        $eliminar = null;

        if ((VariableGet::sistema('subniveles_inferiores') != false) && (VariableGet::sistema('subniveles_inferiores') != '')) {
            $url_acciones = VariableGet::sistema('subniveles_inferiores') . '/';
        } else {
            $url_acciones = '/';
        }

        $contenido = '';
        if ($no_existe === true) {
            $titulo = 'El directorio "' . $nombre . '" no existe';
            $contenido = '<span style="width:100px;float:left">Directorio:</span> ' . $directorio;
            $estado = false;
        } elseif ($todo === true) {
            $lectura = true;
            if (@file_put_contents($directorio_absoluto . '/prueba', 'alta') !== false) {
                $confirmado = '';
                if (@file_exists($directorio_absoluto . '/prueba')) {
                    $confirmado = ' (confirmada)';
                }
                $contenido .= '<span style="width:160px;">Alta' . $confirmado . ':</span> OK<br />';
            } else {
                $contenido .= '<span style="width:160px;">Alta:</span> ERROR<br />';
                $lectura = false;
            }
            if (@file_get_contents($directorio_absoluto . '/prueba') !== false) {
                $confirmado = '';
                if (@file_get_contents($directorio_absoluto . '/prueba') == 'alta') {
                    $confirmado = ' (confirmada)';
                }
                $contenido .= '<span style="width:160px;">Lectura' . $confirmado . ':</span> OK<br />';
            } else {
                $contenido .= '<span style="width:160px;">Lectura:</span> ERROR<br />';
                $lectura = false;
            }
            if (@file_put_contents($directorio_absoluto . '/prueba', 'modificacion') !== false) {
                $confirmado = '';
                if (@file_get_contents($directorio_absoluto . '/prueba') == 'modificacion') {
                    $confirmado = ' (confirmada)';
                }
                $contenido .= '<span style="width:160px;">Modificacion' . $confirmado . ':</span> OK<br />';
            } else {
                $contenido .= '<span style="width:160px;">Modificacion:</span> ERROR<br />';
                $lectura = false;
            }
            if (@unlink($directorio_absoluto . '/prueba')) {
                $confirmado = '';
                if (@file_exists($directorio_absoluto . '/prueba') === false) {
                    $confirmado = ' (confirmada)';
                }
                $contenido .= '<span style="width:160px;">Eliminacion' . $confirmado . ':</span> OK<br />';
            } else {
                $contenido .= '<span style="width:160px;">Eliminacion:</span> ERROR<br />';
                $lectura = false;
            }
            if ($lectura === false) {
                $titulo = 'El cache "' . $nombre . '" tiene problemas';
                $eliminar = '<a href="' . $url_acciones . '?borrar=' . $nombre . '" style="text-decoration:none;font-size:9px;color:#FF0000;">Eliminar cache</a>';
                $estado = false;
            } else {
                $titulo = 'Cache "' . $nombre . '"';
                $eliminar = '<a href="' . $url_acciones . '?borrar=' . $nombre . '" style="text-decoration:none;font-size:9px;color:#FF0000;">Eliminar cache</a>';
                $estado = true;
            }
            $contenido .= '<br />';
            $contenido .= '<span style="width:160px;">Directorio:</span> ' . $directorio_absoluto . '<br />';
            $contenido .= '<span style="width:160px;">Cantidad:</span> ' . $cantidad . '<br />';
            $contenido .= '<span style="width:160px;">Mas antiguo:</span> ' . date('Y-m-d G:i:s', $mas_antiguo) . '<br />';
            $contenido .= '<span style="width:160px;">Mas nuevo:</span> ' . date('Y-m-d G:i:s', $mas_nuevo);
        } elseif ($todo === false) {
            if ($cantidad > 0) {
                $titulo = 'Cache "' . $nombre . '" OK';
                $eliminar = '<a href="' . $url_acciones . '?borrar=' . $nombre . '" style="text-decoration:none;font-size:9px;color:#FF0000;">Eliminar cache</a>';
                $contenido .= '<span style="width:160px;">Lectura:</span> OK<br />';
                $estado = true;
            } else {
                $titulo = 'El cache "' . $nombre . '" no confirmado';
                $eliminar = '<a href="' . $url_acciones . '?borrar=' . $nombre . '" style="text-decoration:none;font-size:9px;color:#FF0000;">Eliminar cache</a>';
                $contenido .= '<span style="width:160px;">Lectura:</span> NO CONFIRMADA<br />';
                $estado = null;
            }
            $contenido .= '<br /><br />';
            $contenido .= '<span style="width:160px;">Directorio:</span> ' . $directorio_absoluto . '<br />';
            $contenido .= '<span style="width:160px;">Cantidad:</span> ' . $cantidad . '<br />';
            $contenido .= '<span style="width:160px;">Mas antiguo:</span> ' . date('Y-m-d G:i:s', $mas_antiguo) . '<br />';
            $contenido .= '<span style="width:160px;">Mas nuevo:</span> ' . date('Y-m-d G:i:s', $mas_nuevo);
        }

        return $this->_imprimirInforme($titulo, $contenido, $estado, $eliminar);
    }

    private function _imprimirInforme($titulo, $contenido = null, $estado, $eliminar = null) {
        if ($estado === true) {
            $color = 'verde';
        } elseif ($estado === false) {
            $color = 'rojo';
        } elseif ($estado == null) {
            $color = 'amarillo';
        }
        if ($contenido != null) {
            $cursor = 'cursor:hand;cursor:pointer;';
            $class = 'class="kk_informe_errores"';
        } else {
            $cursor = '';
            $class = '';
        }
        if ($eliminar == null) {
            $eliminar = '';
        }
        $impresion = "\n" . '<div style="width:100%;height:20px;color:#000;background-color:#FFF;' . $cursor . '" id="kk_informe_errores_' . self::$contador . '" ' . $class . '><div style="float:left;clear:left;width:20px;">' . $this->_semaforo($color) . '</div><div style="float:left;width:400px;">' . $titulo . '</div><div style="float:left;width:150px;">' . $eliminar . '</div></div>';
        if ($contenido != null) {
            $impresion .= "\n" . '<div style="padding-left:20px;margin-bottom:20px;display:none;clear:both" id="cont_kk_informe_errores_' . self::$contador . '">' . $contenido . '</div>';
        }
        self::$contador++;
        return $impresion;
    }

    private function _borrarCache($cache) {

        switch ($cache) {
            case 'plantillas':
                $directorios[0] = VariableGet::sistema('directorio_cache_plantillas');
                break;
            case 'compilados':
                $directorios[0] = VariableGet::sistema('directorio_cache_compilados');
                break;
            case 'base':
                $directorios[0] = VariableGet::sistema('directorio_cache_base');
                break;
            case 'base_tablas':
                $directorios[0] = VariableGet::sistema('directorio_cache_base_tablas');
                break;
            case 'sistema':
                $directorios[0] = VariableGet::sistema('directorio_cache_sistema');
                break;
            case 'links':
                $directorios[0] = VariableGet::sistema('directorio_cache_links');
                $directorios[1] = VariableGet::sistema('directorio_cache_links') . '/principal';
                $directorios[2] = VariableGet::sistema('directorio_cache_links') . '/secundario';
                break;
            default:
                return false;
        }
        
        foreach ($directorios as $directorio) {

            $directorio_absoluto = $this->_obtenerDirectorio($directorio);

            if (is_dir($directorio_absoluto)) {
                if ($directorio_contenido = opendir($directorio_absoluto)) {
                    while (($archivo = readdir($directorio_contenido)) !== false) {
                        if (filetype($directorio_absoluto . '/' . $archivo) == 'file') {
                            unlink($directorio_absoluto . '/' . $archivo);
                        }
                    }
                }
            }
        }
        closedir($directorio_contenido);
    }

    private function _semaforo($color) {

        switch ($color) {
            case 'rojo':
                $imagen = 'iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAYAAACprHcmAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wDHRUnNzRu9ZcAAACjSURBVBjTjdGxagJREIXhb6++guBgZbm9Tap9AyEgJJAqre9kerEQtxUEq1TWi1WqcPMGqQJp7jYS4v7dMP+cYk71FqGnznmJB8zwgXMXcer34yJNsEPjhjrnLV67iO9xnfMIh5L4F0/4wUv1zqqk3mORsDSMx4TpQHmS8DlQ/kpoB8pt6iL2ON4RN13EJZXh+Z+DDdZQ3TTYlH/PcS0NXvr9L+viJznZaP3UAAAAAElFTkSuQmCC';
                break;
            case 'verde':
                $imagen = 'iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAYAAACprHcmAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wDHRUrL4u3Is0AAACXSURBVBjTjdExCsJQEEXRk2+2IAhW7sHGKjsISAQFK1s7F5QFiATRVhAEWxdgZSW4AyvB5osQxOR2w9x5xbzEypdCjhH6uOGkcvys0yh1sUGmTmGNhcozVehgFxN/McUL88TZJKY2MQzItWMc0GspdwPuLeVHwL6lvA8qWxwaxFLlEuIw+3NQYglJrcEs/nuAa2zw8lm/ATCgHHjAiWqzAAAAAElFTkSuQmCC';
                break;
            case 'amarillo':
                $imagen = 'iVBORw0KGgoAAAANSUhEUgAAAAsAAAALCAYAAACprHcmAAAAAXNSR0IArs4c6QAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wDHRUuKGikQysAAACoSURBVBjTjZExCsJAEEXfjl4hIFjZL2xjY5UbBARBwcp2u1xmuxxALMS0guABUixsIxZWgjewEmw2QVDMvm6YN7+Yryjp8FYXwAwYAzfgbFw4tXtFCd7qDNgBOd9sgY1x4Tn0Vg+AQ0z8xRJ4AWvlr3oRU/uYClCQxlyAUaKcCXBPlB8C1IlyLcaFPXDsESvjQiNxWP05qADblfLRYB7/PQEuscGm3b8BoZsmkybPAX8AAAAASUVORK5CYII=';
                break;
        }

        return '<img src="data:image/gif;base64,' . $imagen . '">';
    }

    private function _obtenerDirectorio($directorio) {

        $url_actual = getcwd();
        if (@chdir($directorio)) {
            $directorio = getcwd();
        } else {
            $directorio = false;
        }
        chdir($url_actual);

        return $directorio;
    }

}

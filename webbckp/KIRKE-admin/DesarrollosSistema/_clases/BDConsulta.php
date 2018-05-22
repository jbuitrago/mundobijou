<?php

class BDConsulta {

    static public function consulta($nombre_consulta, $valores = array(), $errores = null, $cache_segundos = null) {

        BDConexion::consulta();

        $tardanza = time();

        // nombre real del archivo, este se toma directamente del script que llamo al metodo
        $archivo_origen = debug_backtrace(false);
        $archivo = preg_replace("/([a-zA-Z0-9._.-.]+)\.php/", "\${1}", basename($archivo_origen[0]['file']));

        if (($cache_segundos != null) && VariableGet::sistema('generar_cache')) {

            if (count($valores) > 0) {
                $valores_cache = implode('<#>', $valores);
            } else {
                $valores_cache = '';
            }

            $archivo_cache = VariableGet::sistema('directorio_cache_base') . '/' . sha1($archivo . '-' . $nombre_consulta . '-' . $valores_cache) . '.cache';

            if (CacheVariables::control($archivo_cache, $cache_segundos) !== false) {
                return CacheVariables::obtener($archivo_cache);
            }
        }

        include_once( VariableGet::sistema('directorio_bases') . '/' . $archivo . '.php' );

        $_clases_sitio = explode('/', dirname($archivo_origen[0]['file']));
        if (end($_clases_sitio) == '_clases_sitio') {
            $archivo = '_' . $archivo;
        }

        $bd = new $archivo;

        $clase_de_bd = 'BD' . ucfirst(VariableGet::sistema('tipo_base')) . 'Consulta';

        eval('$valores = ' . $clase_de_bd . '::validaciones($valores);');

        $obtengo_query = $bd->$nombre_consulta($valores);

        eval('$bd_consulta = ' . $clase_de_bd . '::consulta($obtengo_query, $errores);');

        if (($cache_segundos != null) && VariableGet::sistema('generar_cache')) {

            CacheVariables::cache($archivo_cache, $cache_segundos, $bd_consulta);
        }

        $tardanza = time() - $tardanza;

        if ($tardanza > 5) {
            ReporteErrores::error('base', $archivo, $nombre_consulta, $obtengo_query, $tardanza);
        }

        return $bd_consulta;
    }

}

<?php

class BDConsultaVariables {

    static public function consulta($nombre_tabla, $errores = null, $cache = null) {

        BDConexion::consulta();

        $tardanza = time();

        // nombre real del archivo, este se toma directamente del script que llamo al metodo
        $archivo_origen = debug_backtrace(false);
        $archivo = preg_replace("/([a-zA-Z0-9._.-.]+)\.php/", "\${1}", basename($archivo_origen[0]['file']));

        if (($cache != null) && VariableGet::sistema('generar_cache')) {

            $archivo_cache = VariableGet::sistema('directorio_cache_base') . '/' . sha1($archivo . '-' . $nombre_tabla) . '.cache';

            if (is_numeric($cache)) {
                if (CacheVariables::control($archivo_cache, $cache) !== false) {
                    return CacheVariables::obtener($archivo_cache);
                }
            } elseif (is_array($cache)) {
                if (file_exists($archivo_cache)) {
                    $cache_existente = filemtime($archivo_cache);
                    $leer_cache = true;
                    foreach ($cache as $tabla) {
                        $archivo_cache_tabla = VariableGet::sistema('directorio_cache_base_tablas') . '/' . sha1($tabla) . '.cache';
                        if ($leer_cache && (!file_exists($archivo_cache_tabla) || ($cache_existente <= filemtime($archivo_cache_tabla)))) {
                            $leer_cache = false;
                        }
                    }
                    if ($leer_cache) {
                        return CacheVariables::obtener($archivo_cache);
                    }
                }
            }
        }

        $clase_de_bd = 'BD' . ucfirst(VariableGet::sistema('tipo_base')) . 'ConsultaVariables';

        eval('$bd_consulta = ' . $clase_de_bd . '::consulta($nombre_tabla, $errores);');

        if (($cache != null) && VariableGet::sistema('generar_cache')) {
            if (is_numeric($cache)) {
                CacheVariables::cache($archivo_cache, $cache, $bd_consulta);
            } elseif (is_array($cache)) {
                CacheVariables::generar($archivo_cache, $bd_consulta);
            }
        }
        $tardanza = time() - $tardanza;

        if ($tardanza > 5) {
            ReporteErrores::error(__FILE__, __LINE__, 'base', $archivo, $nombre_tabla, $obtengo_query, $tardanza);
        }

        return $bd_consulta;
    }

}

<?php

// ### version 5.0 ###
// Agregado en #KIRKE-admin# (inicio) *********************
// control de acceso a la pagina
$validacion = new Seguridad_UsuarioValidacion();
$validacion = $validacion->consultaElemento('desarrollo', $_GET['kk_desarrollo'] . ':' . $_GET['0'], 'ver');

$path_desarrollo = Inicio::path() . '/Desarrollos/' . $_GET['kk_desarrollo'] . '/';

if (is_dir($path_desarrollo) === false) {
    header('Location: index.php');
    exit();
}

// === CONFIGURACION DEL SISTEMA ===================================================

$sistema['directorios_php'] = $path_desarrollo . '_php';
$sistema['directorio_clases'] = Inicio::path() . '/DesarrollosSistema/_clases';
$sistema['directorio_bases'] = $path_desarrollo . '_bases';
$sistema['directorio_plantillas'] = $path_desarrollo . '_plantillas';
$sistema['directorio_plantillas_varias'] = $path_desarrollo . '_plantillas_varias';

$sistema['directorio_cache_plantillas'] = $path_desarrollo . '_cache/_plantillas';
$sistema['directorio_cache_compilados'] = $path_desarrollo . '_cache/_compilados';
$sistema['directorio_cache_base'] = $path_desarrollo . '_cache/_bases';
$sistema['directorio_cache_sistema'] = $path_desarrollo . '_cache/_sistema';
$sistema['directorio_cache_links'] = $path_desarrollo . '_cache/_links';

// con esta variable se define el nivel en que se encuentra la variable GET que define la secccion en la URL (esta siempre es $_GET), y se obtiene con $_GET['0']
$sistema['seccion_actual_nivel'] = 0;

// con esta variable se definen los subniveles inferiores, por si el sistema se instala en subdirectorios de la URL
$sistema['subniveles_inferiores'] = ''; // Ej: '/sitio_muestra'
// si el sistema no debe publicarse se elimina la generacion de armado sitemap con generar_sitemap == false
$sistema['generar_sitemap'] = true;

// variables de mails:

$sistema['mail_servidor'] = Inicio::confVars('mail_origen');
$sistema['nombre_servidor'] = Inicio::confVars('nombre_servidor');
$sistema['mail_responsable'] = Inicio::confVars('mail_origen');
$sistema['nombre_responsable'] = Inicio::confVars('nombre_servidor');

//desarrollo.php
$desarrollo['generar_cache'] = false;    // generar cache
if (Inicio::confVars('errores_control') == 's') {
    $desarrollo['mostrar_errores'] = true;
} else {
    $desarrollo['mostrar_errores'] = false;
}

require_once $path_desarrollo . '/_configuraciones/sitio.php';
require_once $path_desarrollo . '/_configuraciones/variables.php';
// Agregado en #KIRKE-admin# (fin) ************************
// asignacion de variables de sistema
foreach ($sistema as $id => $valor) {
    VariableSet::sistema($id, $valor);
}
VariableSet::sistema('generar_cache', $desarrollo['generar_cache']);
VariableSet::sistema('mostrar_errores', $desarrollo['mostrar_errores']);
VariableSet::sistema('tipo_base', Inicio::confVars('bases_tipo')); // Comentario KIRKE-admin (se llama a la variable desde la clase) *********************
VariableSet::sistema('seccion_inicio', $sitio['seccion_inicio']);
VariableSet::sistema('enviar_mail_debug', $sitio['enviar_mail_debug']);
VariableControl::bloquearSistema();

// asignacion de variables del sitio
VariableSet::globales('sitio_nombre', $sitio['sitio_nombre'], true);
VariableSet::globales('sitio_description', $sitio['sitio_description']);
VariableSet::globales('sitio_palabras_claves', $sitio['sitio_palabras_claves']);

// asignacion de variables generales
if (isset($variables)) {
    foreach ($variables as $id => $valor) {
        VariableSet::globales($id, $valor);
    }
}

// Comentario #KIRKE-admin# (se quito la conexion a base de datos) *********************
// elimino todas las variables de configuracion
// Comentario #KIRKE-admin# (se quito la eliminacion de la variable $bases) *********************
unset($sitio);
unset($desarrollo);
unset($variables);

// Agregado en #KIRKE-admin# (inicio) *********************
include_once $path_desarrollo . '/_funciones/kk_funcion_inicio.php';
include_once $path_desarrollo . '/_funciones/kk_funciones_sitio.php';
// Agregado en #KIRKE-admin# (fin) ************************

if (isset($_GET[0]) && ($_GET[0] != '')) {

// Modificado #KIRKE-admin# (inicio) *******************
    $nombre = strtr($_GET[0], '-', '_');
// Modificado #KIRKE-admin# (fin) **********************

    $archivo_nombre = PlantillaFunciones::_obtenerNombreTpl($nombre, true);

    if (($archivo_nombre[0] != '/') && (strlen($archivo_nombre[0]) > 1) && !file_exists($archivo_nombre[0])) {
        $nombre = VariableGet::sistema('seccion_inicio');
    }
} else {

    $nombre = VariableGet::sistema('seccion_inicio');
}


VariableSet::globales('seccion_actual', $nombre);

ob_start();

$plantilla_tipo = PlantillaFunciones::_obtenerNombreTpl($nombre, true);

if ($plantilla_tipo[1] == 'php') {
    require_once $plantilla_tipo[0];
} else {
    $tpl = new PlantillaReemplazos();
    $tpl->nombreArchivo($nombre);
    $tpl->obtenerPlantilla();
}

$plantilla_seccion = ob_get_contents();
ob_end_clean();
ob_start();  // Comentario KIRKE-admin (se obtiene la seccion) *********************

VariableSet::seccion($plantilla_seccion);

ArmadoEncabezados::set();

// Agregado en #KIRKE-admin# (inicio) *********************

if (file_exists($path_desarrollo . '_php/' . VariableGet::indexMarco() . '.php') !== false) {
    require_once $path_desarrollo . '_php/' . VariableGet::indexMarco() . '.php';
} else {
    $tpl = new PlantillaReemplazos();
    $tpl->nombreArchivo(VariableGet::indexMarco());
    $tpl->obtenerPlantilla();
}

$plantilla_seccion = ob_get_contents();

if (Marco::sinMarcoControl() === false) {
    $patrones = array(
        '/<!DOCTYPE[^>]*>/',
        '/<html[^>]*>/',
        '/<head[^>]*>/',
        '/<meta[^>]*>/',
        '/<title[^>]*>.*<\/title>/',
        '/<link rel="icon" [^>]*>/',
        '/<link rel="shortcut icon" [^>]*>/',
        '/<script type="text\/javascript" language="javascript" src="\/js\/jquery.js"><\/script>/',
        '/<body>/',
        '/<\/head>/',
        '/<\/body>/',
        '/<\/html>/'
    );
} else {
    $patrones = array();
}

$plantilla_seccion = preg_replace($patrones, '', $plantilla_seccion);
$plantilla_seccion = preg_replace('/ href="\/css\//', ' href="index.php?kk_generar=7&kk_desarrollo=' . $_GET['kk_desarrollo'] . '&archivo=', $plantilla_seccion);
$plantilla_seccion = preg_replace('/ src="\/js\//', ' src="index.php?kk_generar=7&kk_desarrollo=' . $_GET['kk_desarrollo'] . '&archivo=', $plantilla_seccion);
$plantilla_seccion = preg_replace('/ src="\/img\//', ' src="index.php?kk_generar=7&kk_desarrollo=' . $_GET['kk_desarrollo'] . '&archivo=', $plantilla_seccion);

ob_end_clean();

include_once $path_desarrollo . '/_funciones/kk_funcion_fin.php';

if (Marco::sinMarcoControl() === true) {
    die($plantilla_seccion);
}

class Marco {

    static private $_sin_marco = false;

    static public function sinMarco() {

        self::$_sin_marco = true;
    }

    static public function sinMarcoControl() {

        return self::$_sin_marco;
    }

}

// Agregado en #KIRKE-admin# (fin) ************************

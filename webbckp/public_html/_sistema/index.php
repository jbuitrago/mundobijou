<?php

if (!isset($conf)) {
    $conf = '../';
}

session_start();

// para que ciertas funciones se realicen en UTF-8
mb_internal_encoding("UTF-8");

function clasesObtener($nombre_clase) {
    if (file_exists('_clases/' . $nombre_clase . '.php')) {
        include '_clases/' . $nombre_clase . '.php';
    } elseif (file_exists('_clases_sitio/' . $nombre_clase . '.php')) {
        include '_clases_sitio/' . $nombre_clase . '.php';
    }
}

spl_autoload_extensions('.php');
spl_autoload_register('clasesObtener');

// se elimina la vairable request para que no sea utilizada
unset($_REQUEST);

class PathUrl {

    // en public_html/_clases/BDConexion.php tambien se usa
    public static $conf;

    public static function setUrl($url) {
        self::$conf = $url;
    }

}

PathUrl::setUrl($conf);

require(PathUrl::$conf . '_configuraciones/desarrollo.php');

if ($desarrollo['mostrar_errores'] === false) {
    ini_set('display_errors', 0);
    error_reporting(0);
} elseif ($desarrollo['mostrar_errores'] === true) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

if (
        ($desarrollo['generar_cache'] === true) &&
        file_exists(PathUrl::$conf . '_cache/_sistema/variables.php')
) {
    $variables_time = filemtime(PathUrl::$conf . '_cache/_sistema/variables.php');
}

if (isset($cron) && ($cron == true)) {
    VariableSet::sistema('cron', $cron);
}

if (
        isset($variables_time) &&
        ($variables_time > filemtime(PathUrl::$conf . '_configuraciones/desarrollo.php')) &&
        ($variables_time > filemtime(PathUrl::$conf . '_configuraciones/sistema.php')) &&
        ($variables_time > filemtime(PathUrl::$conf . '_configuraciones/sitio.php')) &&
        ($variables_time > filemtime(PathUrl::$conf . '_configuraciones/variables.php')) &&
        (VariableGet::sistema('cron') == false)
) {
    VariableControl::setCache(unserialize(file_get_contents(PathUrl::$conf . '_cache/_sistema/variables.php')));
} else {
    require(PathUrl::$conf . '_configuraciones/sistema.php');
    require(PathUrl::$conf . '_configuraciones/sitio.php');
    require(PathUrl::$conf . '_configuraciones/variables.php');

    // asignacion de variables de sistema
    foreach ($sistema as $id => $valor) {
        VariableSet::sistema($id, $valor);
    }

    VariableSet::sistema('generar_cache', $desarrollo['generar_cache']);
    VariableSet::sistema('mostrar_errores', $desarrollo['mostrar_errores']);
    if (isset($desarrollo['control_sistema'])) {
        VariableSet::sistema('control_sistema', $desarrollo['control_sistema']);
    }
    VariableSet::sistema('seccion_inicio', $sitio['seccion_inicio']);
    VariableSet::sistema('enviar_mail_debug', $sitio['enviar_mail_debug']);

    // asignacion de variables del sitio
    VariableSet::globales('sitio_nombre', $sitio['sitio_nombre'], true);
    VariableSet::globales('sitio_description', $sitio['sitio_description']);
    VariableSet::globales('sitio_palabras_claves', $sitio['sitio_palabras_claves']);

    if (isset($variables)) {
        foreach ($variables as $id_variable => $variable) {
            VariableSet::globales($id_variable, $variable);
        }
    }

    // control de configuraciones del sitio y logueo
    if (
            (isset($_GET['kk_administracion']) ||
            (
            (isset($_SERVER['KIRKE_VISITA_USUARIO']) && ($_SERVER['KIRKE_VISITA_USUARIO'] != '')) &&
            (substr($_SERVER['REMOTE_ADDR'], 0, 8) != "192.168.") &&
            ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") && !isset($_SESSION['kk_administracion_logueo'])
            )) &&
            !VariableGet::sistema('eliminar_logueo_externo')
    ) {
        require(PathUrl::$conf . '_configuraciones/bases.php');
        require('_sistema/administracion.php');
        exit();
    }

    // elimino todas las variables de configuracion
    unset($sitio);
    unset($desarrollo);
    unset($variables);

    if ((VariableGet::sistema('generar_cache') === true) && (VariableGet::sistema('cron') == false)) {
        file_put_contents(PathUrl::$conf . '_cache/_sistema/variables.php', serialize(VariableControl::getCache()));
    }
}

VariableControl::bloquearSistema();

if (VariableGet::sistema('timezone') != '') {
    date_default_timezone_set(VariableGet::sistema('timezone'));
}

if (VariableGet::sistema('cron') == false) {

    if (isset($_GET['kk_captcha']) && ($_GET['kk_captcha'] == 'captcha')) {
        require_once('_sistema/captcha.php');
        exit();
    }

    // importacion y destrucción de variables $_GET y parametros para casos especiales
    ArmadoLinks::setVariablesGet();

    if (file_exists('_clases_sitio/ProcesosInicio.php')) {
        new ProcesosInicio();
    }

    if (isset($_GET[0]) && ($_GET[0] != '')) {

        $variables_url = explode("/", ArmadoLinks::urlNvaLimpia());
        array_shift($variables_url);

        $nombre = strtr($variables_url[VariableGet::sistema('seccion_actual_nivel')], '-', '_');

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
        require_once($plantilla_tipo[0]);
    } else {
        $tpl = new PlantillaReemplazos();
        $tpl->nombreArchivo($nombre);
        $tpl->obtenerPlantilla();
    }

    $plantilla_seccion = ob_get_contents();
    ob_end_clean();

    VariableSet::seccion($plantilla_seccion);

    ob_start();

    if (file_exists('_php/' . VariableGet::indexMarco() . '.php') !== false) {
        require('_php/' . VariableGet::indexMarco() . '.php');
    } else {
        $tpl = new PlantillaReemplazos();
        $tpl->nombreArchivo(VariableGet::indexMarco());
        $tpl->obtenerPlantilla();
    }

    ArmadoEncabezados::set();

    $plantilla_con_marco = ob_get_contents();
    ob_end_clean();
    echo $plantilla_con_marco = str_replace('{headers}', VariableGet::globales('kk_encabezados'), $plantilla_con_marco);

    if (PlantillaReemplazos::$cacheMarcoGenerar) {
        file_put_contents(PlantillaReemplazos::$cacheMarcoNombre, $plantilla_con_marco, LOCK_EX);
    }

    if (file_exists('_clases_sitio/ProcesosFin.php')) {
        new ProcesosFin();
    }

    if (VariableGet::sistema('generar_sitemap') === true) {
        ArmadoLinks::generar_xml();
    }
}

class Version {

    public static function get() {
        return '7.0';
    }

}

if (VariableGet::sistema('kk_control_sistema') === true) {
    include '_sistema/control_sistema.php';
}

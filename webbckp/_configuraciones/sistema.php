<?php

// === CONFIGURACION DEL SISTEMA ===================================================

$sistema['tipo_base'] = 'mysqli';

$sistema['directorios_php'] = '_php';
$sistema['directorio_clases'] = '_clases';
$sistema['directorio_bases'] = '_bases';
$sistema['directorio_plantillas'] = PathUrl::$conf . '_plantillas';
$sistema['directorio_plantillas_varias'] = PathUrl::$conf . '_plantillas_varias';

$sistema['directorio_cache_plantillas'] = PathUrl::$conf . '_cache/_plantillas';
$sistema['directorio_cache_compilados'] = PathUrl::$conf . '_cache/_compilados';
$sistema['directorio_cache_base'] = PathUrl::$conf . '_cache/_bases';
$sistema['directorio_cache_base_tablas'] = PathUrl::$conf . '_cache/_bases/tablas';
$sistema['directorio_cache_sistema'] = PathUrl::$conf . '_cache/_sistema'; // no modificar
$sistema['directorio_cache_links'] = PathUrl::$conf . '_cache/_links';

// logueo desde afuera de KIRKE
$sistema['eliminar_logueo_externo'] = false;

// con esta variable se define el nivel en que se encuentra la variable GET que define la secccion en la URL (esta siempre es $_GET), y se obtiene con $_GET['0']
$sistema['seccion_actual_nivel'] = 0;

// con esta variable se definen los subniveles inferiores, por si el sistema se instala en subdirectorios de la URL
$sistema['subniveles_inferiores'] = ''; // Ej: '/sitio_muestra'

// con esta variable se definen los subniveles inferiores, por si el sistema se instala en subdirectorios de la URL, relacinado con los js css, etc.
$sistema['subniveles_inferiores_css_js'] = ''; // Ej: '/sitio_muestra/css/*'

// si el sistema no debe publicarse se elimina la generacion de armado sitemap con generar_sitemap == false
$sistema['generar_sitemap'] = true;

// variables de mails:

$sistema['mail_servidor'] = 'info@kirke.ws';
$sistema['nombre_servidor'] = 'Servidor sitio muestra';
$sistema['mail_responsable'] = 'control@kirke.ws';
$sistema['nombre_responsable'] = 'Control sitio muestra';

<?php

/*
#Sistema: KIRKE-admin
#Direccion Web: www.kirke.ws/KIRKE-admin
#Autor: Nicas Nirich
#Descripcion: 
#Fecha creacion: 2007-12-15
#Fecha de mod.1: 
*/

//# Tipo de base de datos a utilizar

$var['basedatos_tipo'] = 'mysqli';

//# Variables de acceso al servidor

$var['servidor'] = 'localhost';
$var['basedatos'] = $_SERVER['KIRKE_BASE'];
$var['usuario'] = $_SERVER['KIRKE_BASE_USUARIO'];
$var['clave'] = $_SERVER['KIRKE_BASE_CLAVE'];

//# -------------------------------------------------------------------------------
//# IMPORTANTE: Todos los parametros figuracion deben escribirse en minusculas 
//# ------------------------------------------------------------------------------- 

//# path relativo a 'index.php' del directorio de archivos externos

$var['archivos_externos'] = 'archivos';

//# Variables de diso

$var['plantilla'] = 'kirke';
$var['estilos'] = 'kirke';

//# variables de desarrollo: 's','n'

$var['errores_control'] = 'n';

//# idiomas de carga de datos al sistema, 
//# el primero funciona como idioma predefinido
//# en cuanto a los idiomas que muestra para el administrador deberia depender de los archivos xx.txt cargados
//# en el directorio de traduccion

$var['idiomas'] = 'es';

//# encriptado de URL

$var['encriptar_url'] = 'n';

//# nombre del servidor para identificarlo en los envios de mail

$var['nombre_servidor'] = 'KIRKE-admin';

//# direccion de mail de origen, para los envios de mail

$var['mail_origen'] = 'nnirich@kirke.ws';

//# directorio de información de tablas actualizadas, si es relativa, se toma desde el path del KIRKE-admin '$_pathAdm'

$var['dir_tablas_modificadas'] = '../_cache/_bases/tablas/';

//# generar log

$var['generar_log'] = 's';

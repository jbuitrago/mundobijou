<?php

//--- CONFIGURACION INICIO ----------------------------

$pathAdm = '../../KIRKE-admin';
$archConf = 'configuracion.php';

//--- CONFIGURACION FIN -------------------------------

session_start();

class KIRKE_admin {

    public function inicio($pathAdm, $archConf) {

        $path_publico = getcwd();
        chdir($pathAdm);

        require_once( getcwd() . '/index.php');
        new Sistema($archConf, $path_publico);
    }

}

// Inicio de sistema
$sistema = new KIRKE_admin();
$sistema->Inicio($pathAdm, $archConf);

<?php

class Sistema {

    private static $_pathConf = '../';
    private static $_ejecutado = false;

    public function __construct($arch_conf, $path_publico) {

        if (self::$_ejecutado === false) {

            self::$_ejecutado = true;

            require_once( getcwd() . '/Inicio/Inicio.php');

            Inicio::obtenerConfiguracion($path_publico, self::$_pathConf, $arch_conf);

            if (isset($_GET['var_kk'])) {
                $encriptar = 's';
            } else {
                $encriptar = 'n';
            }

            $this->_armado();
        }
    }

    private function _armado() {

        if (!isset($_GET['kk_generar'])) {
            $_GET['kk_generar'] = '';
        }
        if (!isset($_GET['accion'])) {
            $_GET['accion'] = '';
        }

        if (isset($_GET['kk_generar'])) {
            switch ($_GET['kk_generar']) {
                case 1:
                    new Elementos_ElementosPlantilla();
                    exit();
                    break;
                case 2:
                    Generales_CargaLog::datos('Archivo externo', $_GET['tipo']);
                    new Elementos_ArchivoExterno();
                    exit();
                    break;
                case 3:
                    new Elementos_ElementosComponente();
                    exit();
                    break;
                case 0:
                    Generales_FiltrosOrden::insertarIdAccion($_GET['accion']);
                    $_GET['accion'] = Inicio_AccionesDefinidas::obtener($_GET['accion']);
                    Generales_CargaLog::datos('Pagina', $_GET['accion']);
                    new Elementos_Pagina();
                    break;
                case 4:
                    Generales_CargaLog::datos('Informes', $_GET['pagina'], $_GET['informe']);
                    new Elementos_Informes();
                    exit();
                    break;
                case 5:
                    new Elementos_ElementosAcciones();
                    exit();
                    break;
                case 6:
                    Generales_CargaLog::datos('Desarrollos', $_GET['0'], $_GET['kk_desarrollo']);
                    new Elementos_Desarrollos();
                    exit();
                    break;
                case 7:
                    new Elementos_ElementosDesarrollos();
                    exit();
                    break;
                case 8:
                    new Elementos_ProcesosEspeciales($_GET['accion'], $_GET['tabla']);
                    exit();
                    break;
            }
        }
    }

}

class Version {

    const version = "{TR|s_version} 0.9.8";

}

<?php

class Informes_EstadisticasDeVisitas {

    public function armado() {

        $direccion = Inicio::path() . '/Informes/' . $_GET['informe'] . '/';

        $archivo = 'configuraciones.php';
        $leer_archivo = new Generales_ArchivoVariables();
        $componente_configuraciones = $leer_archivo->archivoLeer($direccion, $archivo, 'EstadisticasDeVisitas TXT 1');

        $archivo = Generales_Idioma::obtener() . '.php';
        $leer_archivo = new Generales_ArchivoVariables();
        $componente_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'EstadisticasDeVisitas TXT 2');

        $id_informe = $componente_configuraciones['id_informe'];

        // control de acceso al informe
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('informe', $id_informe, 'ver');

        $archivo = Inicio::path() . '/Informes/' . $_GET['informe'] . '/' . $_GET['pagina'] . '.php';
        if (is_file($archivo)) {
            require_once $archivo;
        }

        $armado_clase = 'Informes_' . $_GET['informe'] . '_' . $_GET['pagina'];

        $armado_cuerpo = new $armado_clase();
        $cuerpo = $armado_cuerpo->armado();

        return $cuerpo;
    }

}


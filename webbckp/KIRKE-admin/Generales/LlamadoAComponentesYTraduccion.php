<?php

class Generales_LlamadoAComponentesYTraduccion extends Generales_Traduccion {

    static private $id_cp = array();

    static public function armar($clase, $metodo = null, $valor = null, $cp_parametros = null, $cp_nombre = null, $id_componente = null, $id_registro = null, $es_tabla = false, $traducir = true) {

        if ((count(self::$id_cp) == 0) && file_exists(Inicio::path() . '/Configuraciones/anular_componentes.php')) {
            require( Inicio::path() . '/Configuraciones/anular_componentes.php' );
            self::$id_cp = $id_cp;
        }

        if (in_array($id_componente, self::$id_cp)) {
            return false;
        }

        $datos[0] = $valor;
        $datos[1] = $metodo;
        $datos[2] = $cp_parametros;
        $datos[3] = $id_componente;
        $datos[4] = $id_registro;

        if ($cp_nombre == NULL) {
            $cp_nombre = $cp_parametros['cp_nombre'];
        }

        // llamada al componente
        $contenido_cuerpo_componente = Componentes_Componente::componente($cp_nombre, $clase, $datos);
        
        if($traducir === false){
            return $contenido_cuerpo_componente;
        }

        if ($contenido_cuerpo_componente !== false) {
            if ($es_tabla === false) {
                // traduccion del componente
                if (file_exists(Inicio::path() . '/Componentes/' . $cp_nombre . '/' . Generales_Idioma::obtener() . '.php')) {
                    $archivo_de_idioma = Generales_Idioma::obtener();
                } else {
                    $idiomas = Inicio::confVars('idiomas');
                    $archivo_de_idioma = $idiomas[0];
                }

                $componente_traducido = self::traduccion($contenido_cuerpo_componente, Inicio::path() . '/Componentes/' . $cp_nombre . '/', $archivo_de_idioma, '/\{TR\|([a-z])_(.*?)\}/');

                return $componente_traducido;
            } else {
                return $contenido_cuerpo_componente;
            }
        } else {
            return false;
        }
    }

}

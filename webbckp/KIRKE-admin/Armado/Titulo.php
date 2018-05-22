<?php

class Armado_Titulo extends Armado_Plantilla {

    private static $forzarTitulo = false;

    public function __construct() {
        $this->_armadoPlantillaSet('titulo', $this->armadoTitulo());
    }

    public function armadoTitulo() {
        if (isset($_GET['id_tabla']) || (self::$forzarTitulo !== false)) {
            $titulo_armar = '';
            $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
            if (self::$forzarTitulo !== false) {
                $titulo_armar .= self::$forzarTitulo;
            } elseif (isset($datos_tabla['nombre_idioma']) && ($datos_tabla['nombre_idioma'] != '')) {
                $titulo_armar .= $datos_tabla['nombre_idioma'];
            }
            if (Inicio::usuario('tipo') == 'administrador general') {
                if (isset($datos_tabla['nombre']) && ($datos_tabla['nombre'] != '')) {
                    $titulo_armar .= ' <span class="texto_nombre_campos">( ' . $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'] . ' ) </span><span class="texto_id_campos"> ( ' . $datos_tabla['id_tabla'] . ' ) </span>';
                }
                if (isset($datos_tabla['tipo']) && ($datos_tabla['tipo'] != '')) {
                    $titulo_armar .= '<span class="texto_extra_campos"> ( ' . $datos_tabla['tipo'] . ' )</span>';
                }
            }
            if($_GET['id_tabla']){
                $titulo_armar .= ' | ';
            }
            $titulo_armar .= '{TR|o_acc_' . $_GET['accion'] . '}';
        } elseif (isset($_GET['accion'])) {
            $titulo_armar = '{TR|o_acc_' . $_GET['accion'] . '}';
        } else {
            $titulo_armar = '{TR|o_acc_inicio}';
        }

        return $titulo_armar;
    }

    static public function forzarTitulo($titulo) {
        self::$forzarTitulo = $titulo;
    }

}

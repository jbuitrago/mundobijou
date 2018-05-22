<?php

class Consultas_Menu {

    static public function RegistroConsultaTodos($archivo, $linea) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu');
        $consulta->tablas('kirke_menu_nombre');
        $consulta->campos('kirke_menu', 'id_menu');
        $consulta->campos('kirke_menu', 'nivel1_orden');
        $consulta->campos('kirke_menu', 'nivel2_orden');
        $consulta->campos('kirke_menu', 'nivel3_orden');
        $consulta->campos('kirke_menu', 'nivel4_orden');
        $consulta->campos('kirke_menu_nombre', 'menu_nombre');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_nombre', 'id_menu');
        $consulta->condiciones('y', 'kirke_menu_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->orden('kirke_menu', 'nivel1_orden');
        $consulta->orden('kirke_menu', 'nivel2_orden');
        $consulta->orden('kirke_menu', 'nivel3_orden');
        $consulta->orden('kirke_menu', 'nivel4_orden');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }
    
    static public function RegistroConsultaTodosCantidadLinks($archivo, $linea) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu');
        $consulta->tablas('kirke_menu_nombre');
        $consulta->campos('kirke_menu', 'id_menu');
        $consulta->campos('kirke_menu', 'nivel1_orden');
        $consulta->campos('kirke_menu', 'nivel2_orden');
        $consulta->campos('kirke_menu', 'nivel3_orden');
        $consulta->campos('kirke_menu', 'nivel4_orden');
        $consulta->campos('kirke_menu_nombre', 'menu_nombre');
        $consulta->campos('kirke_menu_link', 'id_menu_link', 'cantidad', 'contador');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_nombre', 'id_menu');
        $consulta->condiciones('y', 'kirke_menu_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->unionIzquierdaTablas('relacion_1', 'kirke_menu', '', 'kirke_menu_link');        
        $consulta->unionIzquierdaCondiciones('relacion_1', '', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_link', 'id_menu');
        $consulta->grupo('kirke_menu', 'id_menu');
        $consulta->orden('kirke_menu', 'nivel1_orden');
        $consulta->orden('kirke_menu', 'nivel2_orden');
        $consulta->orden('kirke_menu', 'nivel3_orden');
        $consulta->orden('kirke_menu', 'nivel4_orden');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsulta($archivo, $linea) {

        if (
                isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo']) &&
                ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general')
        ) {
            $administrador = true;
        } else {
            $administrador = false;
        }

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu');
        $consulta->tablas('kirke_menu_nombre');
        $consulta->tablas('kirke_menu_link');
        if ($administrador === false) {
            $consulta->tablas('kirke_usuario_rol');
            $consulta->tablas('kirke_rol_detalle');
        }
        $consulta->tablas('kirke_menu_link_nombre');
        $consulta->campos('kirke_menu', 'id_menu');
        $consulta->campos('kirke_menu', 'nivel1_orden');
        $consulta->campos('kirke_menu', 'nivel2_orden');
        $consulta->campos('kirke_menu', 'nivel3_orden');
        $consulta->campos('kirke_menu', 'nivel4_orden');
        $consulta->campos('kirke_menu_nombre', 'menu_nombre');
        $consulta->campos('kirke_menu_link', 'id_menu_link');
        $consulta->campos('kirke_menu_link', 'id_elemento');
        $consulta->campos('kirke_menu_link', 'elemento');
        $consulta->campos('kirke_menu_link_nombre', 'menu_link_nombre');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_nombre', 'id_menu');
        $consulta->condiciones('y', 'kirke_menu', 'habilitado', 'iguales', '', '', 's');
        $consulta->condiciones('y', 'kirke_menu_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->condiciones('y', 'kirke_menu_link', 'id_menu', 'iguales', 'kirke_menu', 'id_menu');
        if ($administrador === false) {
            $consulta->condiciones('y', 'kirke_menu_link', 'id_elemento', 'iguales', 'kirke_rol_detalle', 'id_elemento');
            $consulta->condiciones('y', 'kirke_menu_link', 'elemento', 'iguales', 'kirke_rol_detalle', 'elemento');
        }
        $consulta->condiciones('y', 'kirke_menu_link', 'id_menu_link', 'iguales', 'kirke_menu_link_nombre', 'id_menu_link');
        $consulta->condiciones('y', 'kirke_menu_link_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        if ($administrador === false) {
            $consulta->condiciones('y', 'kirke_rol_detalle', 'permiso', 'distintos', '', '', 'no_habilitado');
            $consulta->condiciones('y', 'kirke_usuario_rol', 'id_rol', 'iguales', 'kirke_rol_detalle', 'id_rol');
            $consulta->condiciones('y', 'kirke_usuario_rol', 'id_usuario', 'iguales', '', '', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id']);
        }
        $consulta->orden('kirke_menu', 'nivel1_orden');
        $consulta->orden('kirke_menu', 'nivel2_orden');
        $consulta->orden('kirke_menu', 'nivel3_orden');
        $consulta->orden('kirke_menu', 'nivel4_orden');
        $consulta->orden('kirke_menu_link', 'orden');
        $consulta->grupo('kirke_menu_link', 'id_menu_link');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $nivel, $nivel_valor, $id_nivel) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_menu');
        $consulta->campoValor('kirke_menu', 'nivel' . $nivel . '_orden', $nivel_valor);
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', '', '', $id_nivel);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function Bases_RegistroCrear($archivo, $linea, $nivel1, $nivel2, $nivel3, $nivel4) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_menu');
        $consulta->campoValor('kirke_menu', 'nivel1_orden', $nivel1);
        $consulta->campoValor('kirke_menu', 'nivel2_orden', $nivel2);
        $consulta->campoValor('kirke_menu', 'nivel3_orden', $nivel3);
        $consulta->campoValor('kirke_menu', 'nivel4_orden', $nivel4);
        $consulta->campoValor('kirke_menu', 'habilitado', 's');
        return $consulta->realizarConsulta();
    }

    static public function RegistroEliminar($archivo, $linea, $id_menu) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla('kirke_menu');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', '', '', $id_menu);
        return $consulta->realizarConsulta();
    }

}

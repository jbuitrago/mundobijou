<?php

class ArmadoMenuMysql {

    public static function obtenerIdTabla($t_prefijo, $t_nombre) {

        $consulta = "
            SELECT  `kirke_tabla`.`id_tabla`
            FROM    `kirke_tabla`
            ,       `kirke_tabla_prefijo`
            WHERE   `kirke_tabla`.`id_tabla_prefijo` = `kirke_tabla_prefijo`.`id_tabla_prefijo`
            AND     `kirke_tabla`.`tipo` = 'menu'
            AND     `kirke_tabla_prefijo`.`prefijo` = '" . $t_prefijo . "'
            AND     `kirke_tabla`.`tabla_nombre` = '" . $t_nombre . "'
            ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerParametros($id_tabla) {

        $consulta = "
            SELECT  `kirke_tabla_parametro`.`tipo` AS link
            ,       `kirke_tabla_parametro`.`parametro`
            ,       `kirke_tabla_parametro`.`valor`
            FROM    `kirke_tabla_parametro`
            WHERE   `kirke_tabla_parametro`.`id_tabla` = '" . $id_tabla . "'
            ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerElementos($tabla_nombre) {

        $consulta = "
            SELECT      `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "` AS id_menu
            ,           `" . $tabla_nombre . "`.`nivel1_orden`
            ,           `" . $tabla_nombre . "`.`nivel2_orden`
            ,           `" . $tabla_nombre . "`.`nivel3_orden`
            ,           `" . $tabla_nombre . "`.`nivel4_orden`
            ,           `" . $tabla_nombre . "`.`nivel5_orden`
            ,           `" . $tabla_nombre . "`.`nivel6_orden`
            ,           `" . $tabla_nombre . "`.`nivel7_orden`
            ,           `" . $tabla_nombre . "`.`nivel8_orden`
            ,           `" . $tabla_nombre . "`.`nivel9_orden`
            ,           `" . $tabla_nombre . "`.`nivel10_orden`
            ,           `" . $tabla_nombre . "_trd`.`menu_nombre`
            ,           COUNT(`" . $tabla_nombre . "_rel`.`id_" . $tabla_nombre . "_rel`) AS cantidad
            FROM        `" . $tabla_nombre . "`
            LEFT JOIN   `" . $tabla_nombre . "_rel` ON `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "` = `" . $tabla_nombre . "_rel`.`id_" . $tabla_nombre . "`
            ,           `" . $tabla_nombre . "_trd`
            WHERE       `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "` = `" . $tabla_nombre . "_trd`.`id_" . $tabla_nombre . "`
            AND         `" . $tabla_nombre . "_trd`.`idioma` = 'es'
            GROUP BY    " . $tabla_nombre . ".id_" . $tabla_nombre . "
            ORDER BY    `" . $tabla_nombre . "`.`nivel1_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel2_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel3_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel4_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel5_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel6_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel7_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel8_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel9_orden` ASC
            ,           `" . $tabla_nombre . "`.`nivel10_orden` ASC
            ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerNivelesIdMenu($tabla_nombre, $id_menu) {

        $consulta = "
            SELECT      `" . $tabla_nombre . "`.`nivel1_orden`
            ,           `" . $tabla_nombre . "`.`nivel2_orden`
            ,           `" . $tabla_nombre . "`.`nivel3_orden`
            ,           `" . $tabla_nombre . "`.`nivel4_orden`
            ,           `" . $tabla_nombre . "`.`nivel5_orden`
            ,           `" . $tabla_nombre . "`.`nivel6_orden`
            ,           `" . $tabla_nombre . "`.`nivel7_orden`
            ,           `" . $tabla_nombre . "`.`nivel8_orden`
            ,           `" . $tabla_nombre . "`.`nivel9_orden`
            ,           `" . $tabla_nombre . "`.`nivel10_orden`
            FROM        `" . $tabla_nombre . "`
            ,           `" . $tabla_nombre . "_trd`
            WHERE       `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "` = `" . $tabla_nombre . "_trd`.`id_" . $tabla_nombre . "`
            AND         `" . $tabla_nombre . "_trd`.`idioma` = 'es'
            AND         `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "` = '" . $id_menu . "'
            GROUP BY    " . $tabla_nombre . ".id_" . $tabla_nombre . "
            ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerNivelesIdMenuNombres($tabla_nombre, $id_nivel_menu1, $id_nivel_menu2, $id_nivel_menu3, $id_nivel_menu4, $id_nivel_menu5, $id_nivel_menu6, $id_nivel_menu7, $id_nivel_menu8, $id_nivel_menu9, $id_nivel_menu10) {

        $consulta = "
            SELECT      `" . $tabla_nombre . "_trd`.`menu_nombre`
            ,		`" . $tabla_nombre . "_trd`.`id_" . $tabla_nombre . "` AS id_menu_nombre
            FROM        `" . $tabla_nombre . "`
            ,           `" . $tabla_nombre . "_trd`
            WHERE       `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "` = `" . $tabla_nombre . "_trd`.`id_" . $tabla_nombre . "`
            AND         `" . $tabla_nombre . "_trd`.`idioma` = 'es'
            AND         `" . $tabla_nombre . "`.`nivel1_orden` " . $id_nivel_menu1 . "
            AND         `" . $tabla_nombre . "`.`nivel2_orden` " . $id_nivel_menu2 . "
            AND         `" . $tabla_nombre . "`.`nivel3_orden` " . $id_nivel_menu3 . "
            AND         `" . $tabla_nombre . "`.`nivel4_orden` " . $id_nivel_menu4 . "
            AND         `" . $tabla_nombre . "`.`nivel5_orden` " . $id_nivel_menu5 . "
            AND         `" . $tabla_nombre . "`.`nivel6_orden` " . $id_nivel_menu6 . "
            AND         `" . $tabla_nombre . "`.`nivel7_orden` " . $id_nivel_menu7 . "
            AND         `" . $tabla_nombre . "`.`nivel8_orden` " . $id_nivel_menu8 . "
            AND         `" . $tabla_nombre . "`.`nivel9_orden` " . $id_nivel_menu9 . "
            AND         `" . $tabla_nombre . "`.`nivel10_orden` " . $id_nivel_menu10 . "
            ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerTablaCampoRelacionados($id_cp) {
        $consulta = "
            SELECT  `kirke_tabla_prefijo`.`prefijo`
            ,       `kirke_tabla`.`tabla_nombre`
            ,       `kirke_componente`.`tabla_campo`
            FROM    `kirke_componente`
            ,       `kirke_tabla`
            ,       `kirke_tabla_prefijo`
            WHERE   `kirke_componente`.`id_componente` = '".$id_cp."'
            AND     `kirke_componente`.`id_tabla` = `kirke_tabla`.`id_tabla`
            AND     `kirke_tabla`.`id_tabla_prefijo` = `kirke_tabla_prefijo`.`id_tabla_prefijo`
            ;"
        ;
        $componente = self::_enviarResultados($consulta);
        
        $datos['tabla'] = $componente[0]['prefijo'].'_'.$componente[0]['tabla_nombre'];
        $datos['campo'] = $componente[0]['tabla_campo'];
        
        return $datos;
        
    }

    public static function obtenerIdRelacionados($tabla_nombre, $tabla_rel_nombre, $id_menu) {

        $consulta = "
            SELECT      `" . $tabla_nombre . "_rel`.`id_".$tabla_rel_nombre."` AS id
            FROM        `" . $tabla_nombre . "_rel`
            WHERE       `" . $tabla_nombre . "_rel`.`id_" . $tabla_nombre . "` = '" . $id_menu . "'
            ;"
        ;

        return self::_enviarResultados($consulta);
    }

    private static function _enviarResultados($consulta) {

        BDConexion::consulta();

        $resultado = mysql_query($consulta);

        if (@mysql_num_rows($resultado)) {
            while ($linea = mysql_fetch_array($resultado, MYSQL_ASSOC)) {
                $resultado_matriz[] = $linea;
            }
            mysql_free_result($resultado);
            return $resultado_matriz;
        } else {
            return false;
        }
    }

}

<?php

class ArmadoTabuladoresMysql {

    public static function obtenerIdTabla($t_prefijo, $t_nombre) {

        $consulta = "
            SELECT  `kirke_tabla`.`id_tabla`
            FROM    `kirke_tabla`
            ,       `kirke_tabla_prefijo`
            WHERE   `kirke_tabla`.`id_tabla_prefijo` = `kirke_tabla_prefijo`.`id_tabla_prefijo`
            AND     `kirke_tabla`.`tipo` = 'tabuladores'
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
            WHERE   `kirke_tabla_parametro`.`id_tabla` = '" . $id_tabla . "';"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerTablaNombre($id_tabla) {

        $consulta = "
            SELECT  `kirke_tabla`.`tabla_nombre`
            ,       `kirke_tabla_prefijo`.`prefijo`
            FROM    `kirke_tabla`
            ,       `kirke_tabla_prefijo`
            WHERE   `kirke_tabla`.`id_tabla_prefijo` = `kirke_tabla_prefijo`.`id_tabla_prefijo`
            AND     `kirke_tabla`.`id_tabla` = '" . $id_tabla . "'  ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerTablaNombreComponente($id_componente) {

        $consulta = "
            SELECT  `kirke_tabla`.`tabla_nombre`
            ,       `kirke_tabla_prefijo`.`prefijo`
            ,       `kirke_componente`.`tabla_campo`
            FROM    `kirke_tabla`
            ,       `kirke_tabla_prefijo`
            ,       `kirke_componente`
            WHERE   `kirke_tabla`.`id_tabla_prefijo` = `kirke_tabla_prefijo`.`id_tabla_prefijo`
            AND     `kirke_tabla`.`id_tabla` = `kirke_componente`.`id_tabla`
            AND     `kirke_componente`.`id_componente` = '" . $id_componente . "'  ;"
        ;

        return self::_enviarResultados($consulta);
    }

    public static function obtenerElementos($tabla_nombre, $id_tabla_registro = null, $tabla_tabuladores_nombre, $idioma, $tabla_grupo_tabuladores = null, $id_grupo_tabuladores = null, $grupo_tab_campo = null) {

        $consulta = "
            SELECT      `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_tabuladores_nombre . "` AS id_tabulador
            ,           `" . $tabla_tabuladores_nombre . "_trd`.`tabulador_nombre`
            ,		`" . $tabla_tabuladores_nombre . "_rel`.`tabulador` AS valor
            ,		`" . $tabla_tabuladores_nombre . "_rel`.`id_tab_prd` AS id_predefinido";
        if ($tabla_grupo_tabuladores !== null) {
            $consulta .= "    
            ,           `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_grupo_tabuladores . "` AS id_grupo_tabuladores
            ,           `" . $tabla_grupo_tabuladores . "`.`" . $grupo_tab_campo . "` AS grupo_tabuladores_nombre";
        }
        $consulta .= "
            FROM        `" . $tabla_tabuladores_nombre . "_trd`
            ,           `" . $tabla_tabuladores_nombre . "`
            LEFT JOIN   `" . $tabla_tabuladores_nombre . "_rel`
            ON          `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_tabuladores_nombre . "` = `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_tabuladores_nombre . "`
            INNER JOIN  `" . $tabla_nombre . "`
            ON          `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "` = `" . $tabla_nombre . "`.`id_" . $tabla_nombre . "`";
        if ($tabla_grupo_tabuladores !== null) {
        $consulta .= "
            LEFT JOIN   `" . $tabla_grupo_tabuladores . "`
            ON          `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_grupo_tabuladores . "` = `" . $tabla_grupo_tabuladores . "`.`id_" . $tabla_grupo_tabuladores . "`";
        }
        $consulta .= "
            WHERE       `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_tabuladores_nombre . "` = `" . $tabla_tabuladores_nombre . "_trd`.`id_" . $tabla_tabuladores_nombre . "`
            ";
        if ($id_grupo_tabuladores !== null) {
            $consulta .= "
            AND `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_grupo_tabuladores . "` = '" . $id_grupo_tabuladores . "'
            ";
        }
        $consulta .= "
            AND         (
                `" . $tabla_tabuladores_nombre . "_rel`.`idioma` = '" . $idioma . "'
                OR `" . $tabla_tabuladores_nombre . "_rel`.`idioma` = 'multi'
                OR `" . $tabla_tabuladores_nombre . "_rel`.`idioma` = ''
            )
            ";
        if ($id_tabla_registro !== null) {
            $consulta .= "AND         `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "` = '" . $id_tabla_registro . "'
            ";
        }
        $consulta .= "AND         `" . $tabla_tabuladores_nombre . "_trd`.`idioma` = '" . $idioma . "'
            ";
        if ($id_tabla_registro === null) {
            $consulta .= "
            GROUP BY `" . $tabla_tabuladores_nombre . "_rel`.`id_adm_tabuladores`,
                     `" . $tabla_tabuladores_nombre . "_rel`.`tabulador`,
                     `" . $tabla_tabuladores_nombre . "_rel`.`id_tab_prd`";
        }
        $consulta .= "
            ORDER BY `" . $tabla_tabuladores_nombre . "`.`orden` ASC;
        ";

        return self::_enviarResultados($consulta);
    }

    public static function buscar($tabla_nombre, $tabla_tabuladores_nombre, $idioma, $tabla_grupo_tabuladores = null, $id_grupo_tabuladores = null, $buscar_nombre, $buscar_valor_predefinido, $buscar_valor) {

        $consulta = "SELECT tabla.id FROM (";
        if (count($buscar_nombre) > 0) {
            foreach ($buscar_nombre as $buscar_nombre_dato) {
                if (isset($buscar1)) {
                    $consulta .= " UNION ";
                }
                $consulta .= "
            (SELECT      `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "` AS id
            FROM        `" . $tabla_tabuladores_nombre . "_rel`
            ,           `" . $tabla_tabuladores_nombre . "_trd`
            ,           `" . $tabla_tabuladores_nombre . "`
            WHERE       `" . $tabla_tabuladores_nombre . "_trd`.`tabulador_nombre` = '" . $buscar_nombre_dato . "'
            AND         `" . $tabla_tabuladores_nombre . "_rel`.`id_adm_tabuladores` = `" . $tabla_tabuladores_nombre . "_trd`.`id_adm_tabuladores`
            AND         `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_tabuladores_nombre . "` = `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_tabuladores_nombre . "`
            ";
                if ($id_grupo_tabuladores !== null) {
                    $consulta .= "
                AND `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_grupo_tabuladores . "` = '" . $id_grupo_tabuladores . "'
                ";
                }
                $consulta .= "GROUP BY    `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "`)
            ";
                $buscar1 = true;
            }
        }

        if (count($buscar_valor_predefinido) > 0) {
            foreach ($buscar_valor_predefinido as $buscar_valor_predefinido_dato) {
                if (isset($buscar1) || isset($buscar2)) {
                    $consulta .= " UNION ";
                }
                $consulta .= "
            (SELECT      `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "` AS id
            FROM        `" . $tabla_tabuladores_nombre . "_rel`
            ,           `" . $tabla_tabuladores_nombre . "_prd`
            ,           `" . $tabla_tabuladores_nombre . "`
            WHERE       `" . $tabla_tabuladores_nombre . "_prd`.`valor` = '" . $buscar_valor_predefinido_dato . "'
            AND         `" . $tabla_tabuladores_nombre . "_rel`.`id_tab_prd` = `" . $tabla_tabuladores_nombre . "_prd`.`id_tab_prd`
            AND         `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_tabuladores_nombre . "` = `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_tabuladores_nombre . "`
            ";
                if ($id_grupo_tabuladores !== null) {
                    $consulta .= "
                AND `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_grupo_tabuladores . "` = '" . $id_grupo_tabuladores . "'
                ";
                }
                $consulta .= "GROUP BY    `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "`)
            ";
                $buscar2 = true;
            }
        }

        if (count($buscar_valor) > 0) {
            foreach ($buscar_valor as $buscar_valor_dato) {
                if (isset($buscar1) || isset($buscar2) || isset($buscar3)) {
                    $consulta .= " UNION ";
                }
                $consulta .= "
            (SELECT      `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "` AS id
            FROM        `" . $tabla_tabuladores_nombre . "_rel`
            ,           `" . $tabla_tabuladores_nombre . "`
            WHERE       `" . $tabla_tabuladores_nombre . "_rel`.`tabulador` = '" . $buscar_valor_dato . "'
            AND         `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_tabuladores_nombre . "` = `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_tabuladores_nombre . "`
            ";
                if ($id_grupo_tabuladores !== null) {
                    $consulta .= "
                AND `" . $tabla_tabuladores_nombre . "`.`id_" . $tabla_grupo_tabuladores . "` = '" . $id_grupo_tabuladores . "'
                ";
                }
                $consulta .= "GROUP BY    `" . $tabla_tabuladores_nombre . "_rel`.`id_" . $tabla_nombre . "`)
            ";
                $buscar3 = true;
            }
        }

        $consulta .= ") AS tabla GROUP BY tabla.id;";

        return self::_enviarResultados($consulta);
    }

    public static function obtenerValorPredefinido($tabla_tabuladores_nombre, $idioma, $id_tabulador) {

        $consulta = "
            SELECT      `" . $tabla_tabuladores_nombre . "_prd`.`id_tab_prd` AS id
            ,           `" . $tabla_tabuladores_nombre . "_prd`.`valor` AS nombre
            FROM        `" . $tabla_tabuladores_nombre . "_prd`
            WHERE       `" . $tabla_tabuladores_nombre . "_prd`.`idioma` = '" . $idioma . "'
            AND         `" . $tabla_tabuladores_nombre . "_prd`.`id_" . $tabla_tabuladores_nombre . "` = '" . $id_tabulador . "'
            ;
        ";

        return self::_enviarResultados($consulta);
    }

    public static function insertarTabulador($tabla_tabuladores_nombre, $tabla_destino_nombre, $idioma, $id_tabulador, $valor_no_predefinido = '', $id_valor_predefinido = 0, $id_registro) {

        $consulta = "
            INSERT INTO 	`" . $tabla_tabuladores_nombre . "_rel` 
            (                   `orden`
            ,                   `id_" . $tabla_tabuladores_nombre . "`
            ,                   `idioma`
            ,                   `tabulador`
            ,                   `id_tab_prd`
            ,                   `id_" . $tabla_destino_nombre . "`
            ) VALUES (          '" . BDMysqlObtenerOrden::consulta('adm_tabuladores_rel') . "'
            ,                   '" . $id_tabulador . "'
            ,                   '" . $idioma . "'
            ,                   '" . $valor_no_predefinido . "'
            ,                   '" . $id_valor_predefinido . "'
            ,                   '" . $id_registro . "'
            );
        ";

        return self::_enviarResultados($consulta);
    }

    public static function actualizarTabulador($tabla_tabuladores_nombre, $tabla_destino_nombre, $idioma, $id_tabulador, $valor_no_predefinido, $id_valor_predefinido, $id_registro) {

        $consulta = "
            UPDATE              `" . $tabla_tabuladores_nombre . "_rel` 
            SET                 `tabulador` = '" . $valor_no_predefinido . "'
            ,                   `id_tab_prd` = '" . $id_valor_predefinido . "'
            WHERE               `id_" . $tabla_tabuladores_nombre . "` = '" . $id_tabulador . "'
            AND                 `id_" . $tabla_destino_nombre . "` = '" . $id_registro . "'
            ;
        ";

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

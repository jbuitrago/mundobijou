<?php

class Acciones_UsuarioAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('administrador_usuarios');

        if (isset($_GET['usuario']) && ($_GET['usuario'] == 'control')) {
            $id_usuario = Consultas_Usuario::ControlUsuario(__FILE__, __LINE__, $_POST['usuario']);
            if (isset($id_usuario[0]['id_usuario'])) {
                echo '1';
            } else {
                echo '0';
            }
            exit;
        }

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '55');
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '57');
        $armado_botonera->armar('volver', $parametros);

        $pagina_alta_cuerpo = Armado_PlantillasInternas::acciones('UsuarioAlta_datos');

        $pagina_alta_cuerpo .= $this->_listadoRoles();

        $pagina_alta_cuerpo .= '<script type="text/javascript" language="javascript" src="./js/opciones_avanzadas.js"></script>' . "\n";
        $pagina_alta_cuerpo .= '<script type="text/javascript" language="javascript" src="./js/control_usuario_clave.js"></script>' . "\n";

        $parametros = array('kk_generar' => '0', 'accion' => '54', 'usuario' => 'control');
        $link = './index.php?' . Generales_VariablesGet::armar($parametros, 's');

        $pagina_alta_cuerpo .= '<script type="text/javascript">
        $("#usuario").focusout(function () {
            var usuario = $(this).val();
            $.ajax({url: "' . $link . '",
                type: "POST",
                data: {usuario: usuario},
                success: function (result) {
                    if (result == "1") {
                        alert("{TR|o_el_usuario_ya_existe}");
                    }
                }
            });
        });' . "\n" . '</script>' . "\n";

        $pagina_alta_cuerpo .= Armado_PlantillasInternas::acciones('UsuarioAlta_clave');

        return $pagina_alta_cuerpo;
    }

    private function _listadoRoles() {

        // ver detalles del rol
        $roles = Consultas_Roll::RegistroConsulta(__FILE__, __LINE__);

        $mostrar_roles = Armado_PlantillasInternas::acciones('UsuarioAlta_listadoRoles');

        if (is_array($roles)) {
            foreach ($roles as $valor) {

                $mostrar_roles_detalle = '';

                if ($valor['id_rol'] > 2) {
                    $mostrar_roles_detalle = '<input name = "rol_' . $valor['id_rol'] . '" type = "checkbox" id = "rol_' . $valor['id_rol'] . '" value = "s">' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']';
                } elseif (($valor['id_rol'] == 1) && ( Inicio::usuario('tipo') == 'administrador general' )) {
                    $mostrar_roles_detalle = '<input name = "rol_' . $valor['id_rol'] . '" type = "checkbox" id = "rol_' . $valor['id_rol'] . '" value = "s">' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']';
                } elseif (($valor['id_rol'] == 2) && ( ( Inicio::usuario('tipo') == 'administrador general' ) || ( Inicio::usuario('tipo') == 'administrador de usuarios' ) )) {
                    $mostrar_roles_detalle = '<input name = "rol_' . $valor['id_rol'] . '" type = "checkbox" id = "rol_' . $valor['id_rol'] . '" value = "s">' . $valor['rol'] . ' - [' . $valor['descripcion'] . ']';
                }

                if ($mostrar_roles_detalle != '') {
                    $plantilla['mostrar_roles_detalle'] = $mostrar_roles_detalle;
                    $mostrar_roles .= Armado_PlantillasInternas::acciones('UsuarioAlta_listadoRoles_detalle', $plantilla);
                }
            }
        }

        return $mostrar_roles;
    }

}

<?php

class AOPCodeigniter extends CI_Hooks {

    private $CI;
    private $controller_excluidos = array('login', 'verifylogin', 'web', 'upload_file', 'email_notificaciones', 'send_mailing_list', 'front', 'api', 'app','sitemap2');
    private $permisos = array();

    public function __construct() {
        $this->CI = &get_instance();
    }

    public function is_logged_in() {

        $class = $this->CI->router->class;

        if (!in_array($class, $this->controller_excluidos)) {

            if ($this->CI->session->userdata('logged_in')) {

                $session_data = $this->CI->session->userdata('logged_in');

                $this->set_permisos($session_data['rol']);

                $this->CI->user = $session_data['username'];

                if (!in_array($class, $this->permisos)) {

                    redirect('web', 'refresh');
                }

                $this->CI->filtro_por_rol = $this->get_filtro($session_data['rol'], $session_data['id']);

                $this->CI->permisos_rol = $this->get_permisos($session_data['rol'], $session_data['id']);
            } else {

                redirect('web', 'refresh');
            }
        }
    }

    public function get_menu($rol) {

        $html = '';

        /*$this->CI->db->select('*');
        $this->CI->db->from('menu');
        $this->CI->db->join('menu_usuario', 'menu.id = menu_usuario.idmenu');
        $this->CI->db->where('menu_usuario.rol_usuario', $rol);
        $this->CI->db->order_by("orden", "asc");
        $query = $this->CI->db->get();*/
        
      $this->CI->db->select('menu.*', FALSE);
        $this->CI->db->from('menu_rol_accion');
        $this->CI->db->join('roles', 'roles.id = menu_rol_accion.rol_id');
        $this->CI->db->join('acciones', 'acciones.id = menu_rol_accion.accion_id');
        $this->CI->db->join('menu', 'menu.id = menu_rol_accion.menu_id');
        $this->CI->db->where('(menu.borrado="no")');
        $this->CI->db->where('menu_rol_accion.rol_id', $rol);
        $this->CI->db->order_by('menu.orden', 'asc');
        $query = $this->CI->db->get();
        

        foreach ($query->result() as $row) {

            $html .= ' <li class="sub-menu">
                  <a href="javascript:;" class="">
                      <i class="icon-user"></i>
                      <span>' . $row->nombre . '</span>
                      <span class="arrow"></span>
                  </a>
                  <ul class="sub">
                      <li><a class="" href="' . site_url($row->add) . '">Agregar</a></li>
                      <li><a class="" href="' . site_url($row->list) . '">Listar</a></li>
                      
                  </ul>
              </li>';
        }
        return $html;
    }

    public function set_permisos($rol) {

        $html = '';

        /*$this->CI->db->select('*');
        $this->CI->db->from('menu');
        $this->CI->db->join('menu_usuario', 'menu.id = menu_usuario.idmenu');
        $this->CI->db->where('menu_usuario.rol_usuario', $rol);
        $this->CI->db->order_by("orden", "asc");*/
        
        $this->CI->db->select('distinct(menu.id),menu.controller, acciones.nombre as accion, ', FALSE);
        $this->CI->db->from('menu_rol_accion');
        $this->CI->db->join('roles', 'roles.id = menu_rol_accion.rol_id');
        $this->CI->db->join('acciones', 'acciones.id = menu_rol_accion.accion_id');
        $this->CI->db->join('menu', 'menu.id = menu_rol_accion.menu_id');
        $this->CI->db->where('(menu.borrado="no")');
        $this->CI->db->where('menu_rol_accion.rol_id', $rol);
        $this->CI->db->order_by('menu.orden', 'asc');
        
        $query = $this->CI->db->get();

        foreach ($query->result() as $row) {

            $this->permisos[] = $row->controller;
        }
        $this->permisos[] = "tablero";
        $this->permisos[] = "welcome";
        $this->permisos[] = "edificios";
        return $html;
    }

    public function tiene_permisos($controller, $tipousuario) {
        $this->CI->db->select('*');
        $this->CI->db->from('permisos');
        $this->CI->db->where('idtipousuario', $tipousuario);
        $this->CI->db->where('habilitado', 'si');
        $query = $this->CI->db->get();

        foreach ($query->result() as $row) {

            if ($controller == $row->controller) {
                return true;
            }
        }
        return false;
    }

    function get_filtro($rol, $idusuario) {

        switch ($rol) {
            /* case 1: //Administrador
              return array("usuarios_id",$idusuario);
              break; */
            case 2: //Vendedor 
                return array("vendedor_id", $idusuario);
                break;
            case 3: //Encargado
                return array("usuarios_id", $idusuario);
                break;
            /* case 4: //Sistema
              return array("usuarios_id",$idusuario);
              break; */
            default:
                return null;
        }
    }

    function get_permisos($rol, $idusuario) {

        $this->CI->db->select('menu.controller, acciones.nombre as accion, ', FALSE);
        $this->CI->db->from('menu_rol_accion');
        $this->CI->db->join('roles', 'roles.id = menu_rol_accion.rol_id');
        $this->CI->db->join('acciones', 'acciones.id = menu_rol_accion.accion_id');
        $this->CI->db->join('menu', 'menu.id = menu_rol_accion.menu_id');
        $this->CI->db->where('(menu.borrado="no")');
        $this->CI->db->where('menu_rol_accion.rol_id', $rol);
        $this->CI->db->order_by('menu.orden', 'asc');
        $query = $this->CI->db->get();
        
        $arr = array();
        
        foreach ($query->result() as $row)
        {
            $arr[$row->controller][$row->accion] = $row->accion;
        }
        
        return $arr;
        
        //print_r($arr); die;
        
       /* switch ($rol) {
            case 1: //Administrador
                return array("usuarios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "productos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "clientes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "ventas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "pagos" => array("B", "M", "E", "V", "I", "O"),
                    "edificios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "reportes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "bancos" => array("A", "B", "M", "E", "V", "I", "O"),
                    
                    "estadisticas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "categorias_productos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "condiciones_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "estados_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "galeria" => array("A", "B", "M", "V", "I", "O"),
                    "grupos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "roles" => array("A", "B", "M", "E", "V", "I", "O"),
                    "tipos_turnos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "sistemas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "servicios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "unidades_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "suscriptores" => array("A", "B", "M", "E", "V", "I", "O"),
                    "templates" => array("A", "B", "M", "V", "I", "O"),
                    "envios" => array("A", "B", "M", "I", "O"),
                    "menu" => array("M", "V", "I", "O"),
                    "banners" => array("A", "B", "M", "E", "V", "I", "O"),
                    "categorias" => array("A", "B", "M", "E", "V", "I", "O"),
                    "barrios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "masajistas" => array("A", "B", "M", "E", "V", "I", "O")
                );
                break;
            case 2: //Vendedor 
                return array(
                    "clientes" => array("A", "M", "V", "I"),
                    "ventas" => array("V"),
                    "tablero" => array("A", "B", "M", "E", "V", "I")
                );
                break;
            case 3: //Encargado
                return array("usuarios_id", $idusuario);
                break;
            case 4: //Sistema
                return array("usuarios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "productos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "clientes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "ventas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "pagos" => array("B", "M", "E", "V", "I", "O"),
                    "edificios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "reportes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "bancos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "estadisticas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "tablero" => array("A", "B", "M", "E", "V", "I", "O"),
                    "categorias_productos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "condiciones_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "estados_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "galeria" => array("A", "B", "M", "V", "I", "O"),
                    "grupos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "roles" => array("A", "B", "M", "E", "V", "I", "O"),
                    "tipos_turnos" => array("A", "B", "M", "V", "I", "O"),
                    "sistemas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "servicios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "unidades_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "suscriptores" => array("A", "B", "M", "E", "V", "I", "O"),
                    "proveedores" => array("A", "B", "M", "E", "V", "I", "O"),
                    "templates" => array("A", "B", "M", "V", "I", "O"),
                    "envios" => array("A", "B", "M", "I", "O"),
                    "menu" => array("A","M", "V", "I", "O"),
                    "planes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "banners" => array("A", "B", "M", "E", "V", "I", "O"),
                    "categorias" => array("A", "B", "M", "E", "V", "I", "O"),
                    "barrios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "masajistas" => array("A", "B", "M", "E", "V", "I", "O")
                );
                break;
            case 9: //Administrativo
                return array("usuarios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "productos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "clientes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "ventas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "pagos" => array("B", "M", "E", "V", "I", "O"),
                    "edificios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "reportes" => array("A", "B", "M", "E", "V", "I", "O"),
                    "bancos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "estadisticas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "categorias_productos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "condiciones_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "estados_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "galeria" => array("A", "B", "M", "V", "I", "O"),
                    "grupos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "roles" => array("A", "B", "M", "E", "V", "I", "O"),
                    "tipos_turnos" => array("A", "B", "M", "E", "V", "I", "O"),
                    "sistemas" => array("A", "B", "M", "E", "V", "I", "O"),
                    "servicios" => array("A", "B", "M", "E", "V", "I", "O"),
                    "unidades_venta" => array("A", "B", "M", "E", "V", "I", "O"),
                    "banners" => array("A", "B", "M", "E", "V", "I", "O"),
                    "masajistas" => array("A", "B", "M", "E", "V", "I", "O")
                );
                break;
            default:
                return null;
        }*/
    }

}

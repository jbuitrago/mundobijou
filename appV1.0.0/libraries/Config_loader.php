<?php

defined('BASEPATH') OR exit('No direct script access allowed.');

class Config_loader {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance(); //read manual: create libraries

        $dataX = array(); // set here all your vars to views

        $session_data = $this->CI->session->userdata('logged_in');

        $dataX['sistema7888'] = $session_data['sistema_actual'];
        
        $dataX['Mymenu'] = $this->get_menu($session_data['rol']);

        $dataX['inicio'] = $this->get_inicio($session_data['rol']);
        
        $dataX['nombre_sistema'] = $this->get_nombre_sistema();

        $dataX['usuarioactual'] = $session_data['username'];
        
        $dataX['imagen_user'] = $session_data['imagen'];
        
        $dataX['rol_usuarioactual'] = $session_data['rol_nombre'];

        $this->CI->load->vars($dataX);
    }

    public function get_menu($rol) {

        $html = '';

        $this->CI->db->select('distinct(menu.id), menu.*, (SELECT GROUP_CONCAT("|",m2.nombre,"#",m2.list) FROM menu m2 WHERE m2.borrado="no" AND m2.padre_id=menu.id AND m2.id IN(SELECT menu_id FROM menu_rol_accion WHERE rol_id="'.$rol.'")) AS hijos', FALSE);
        $this->CI->db->from('menu_rol_accion');
        $this->CI->db->join('roles', 'roles.id = menu_rol_accion.rol_id');
        $this->CI->db->join('acciones', 'acciones.id = menu_rol_accion.accion_id');
        $this->CI->db->join('menu', 'menu.id = menu_rol_accion.menu_id');
        $this->CI->db->where('(menu.borrado="no")');
        $this->CI->db->where('menu.padre_id', "");
        $this->CI->db->where('menu_rol_accion.rol_id', $rol);
        $this->CI->db->order_by('menu.orden', 'asc');
        
        $query = $this->CI->db->get();
        
        /*$this->CI->db->select('*, (SELECT GROUP_CONCAT("|",nombre,"#",menu.add) FROM menu WHERE padre_id=m.id AND id IN(SELECT idmenu FROM menu_usuario WHERE rol_usuario="'.$rol.'")) AS hijos', FALSE);
        $this->CI->db->from('menu AS m');
        $this->CI->db->join('menu_usuario', 'm.id = menu_usuario.idmenu');
        $this->CI->db->where('menu_usuario.rol_usuario', $rol);
        $this->CI->db->where('m.padre_id', "");
        $this->CI->db->order_by("m.orden", "asc");
        $query = $this->CI->db->get();*/

        foreach ($query->result() as $row) {

           // print_r($row);
            
            //$html .= '<li style="margin:0;padding:0;height: 40px"><a href="' . site_url($row->add) . '"></a></li>';
            
            if ($row->hijos != NULL) {
                
                $hijos = explode("|", $row->hijos);

                $html .=' <li style="" id="'.$row->nombre.'">
                            <a href="#'.$row->nombre.'"><i class="fa fa-' . $row->icono . ' nav_icon"></i>' . strtoupper($row->nombre) . ' <span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level collapse">';

                foreach ($hijos as $h) {
                    if ($h != '') {
                        $data_h = explode("#", $h);

                        $html.=' <li> <a href="' . site_url(str_replace(',', '', $data_h[1])) . '"> ' . strtoupper($data_h[0]) . ' <span class="nav-badge-btm"></span></a> </li>';
                    }
                }
                $html .=' </ul> </li>';
            } else {

                $html .='<li style=""> <a href="' . site_url($row->add) . '"><i class="fa fa-' . $row->icono . ' nav_icon"></i>' . strtoupper($row->nombre) . '</a></li>';
            }
        }
        
        return $html;
    }

    function get_inicio($rol) {

        switch ($rol) {
            case 1: //Administrador
                return site_url("productos");
                break;
            case 10: //Vendedor 
                return site_url("productos");
                break;
            case 3: //Encargado
                return site_url("ventas/listado");
                break;
            case 4: //Sistema
                return site_url("panel");
                break;
            default:
                return null;
        }
    }

    function get_nombre_sistema() {
        
        $this->CI->db->select('*', FALSE);
        
        $this->CI->db->from('config');

        $query = $this->CI->db->get();

        $row = $query->row();

        return $row->nombre_sistema;
    }

}

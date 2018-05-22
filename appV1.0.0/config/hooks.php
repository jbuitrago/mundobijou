<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook["post_controller_constructor"] = array(
    "class"    => "AOPCodeigniter",
    "function" => "is_logged_in",
    "filename" => "AOPCodeigniter.php",
    "filepath" => "hooks"
);

$hook['post_controller'] = array(
    'class' => 'Db_query_log', 
    'function' => 'log_db_queries', 
    'filename' => 'db_log.php', 
    'filepath' => 'hooks'        
);
/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
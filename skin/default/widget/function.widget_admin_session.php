<?php
function smarty_function_widget_admin_session($params, $template){
    $adminSession = false;
    if (isset($_COOKIE['mc_admin'])) {
        $sql = 'SELECT mas.id_admin_session,mas.id_admin,mae.pseudo_admin,maar.id_role
				FROM mc_admin_session mas
				JOIN mc_admin_employee mae ON (mas.keyuniqid_admin = mae.keyuniqid_admin)
				JOIN mc_admin_access_rel maar ON (mas.id_admin = maar.id_admin)
				WHERE id_admin_session = :id_admin_session';

        $session = component_routing_db::layer()->fetch($sql, ['id_admin_session'=>$_COOKIE['mc_admin']]);

        if( !empty($session) ){
            $template->assign('adminProfile',$session);
            $adminSession = true;
        }
    }
    $template->assign('displayAdminPanel',$adminSession);
}
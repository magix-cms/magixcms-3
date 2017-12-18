<?php
function smarty_function_widget_menu_data($params, $template){
    $menu = new frontend_model_menu($template);

    if($params['conf']) {
		if($params['conf']['type'] == 'plugin') {
			$template->assign('pages',$menu->getPluginPages($params['conf']));
		}
	}
	else {
		$template->assign('links',$menu->setLinksData($params['lang']));
	}
}
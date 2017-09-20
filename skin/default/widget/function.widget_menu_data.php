<?php
function smarty_function_widget_menu_data($params, $template){
    $menu = new frontend_model_menu($template);

    $template->assign('links',$menu->setLinksData($params['lang']));
    //$template->assign('companyData',$about->getCompanyData());
}
?>
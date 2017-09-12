<?php
function smarty_function_widget_about_data($params, $template){
    $abotu = new frontend_model_about($template);

    $template->assign('about',$abotu->getContentData());
    $template->assign('companyData',$abotu->getCompanyData());
}
?>
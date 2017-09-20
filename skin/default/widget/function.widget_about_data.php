<?php
function smarty_function_widget_about_data($params, $template){
    $about = new frontend_model_about($template);

    $template->assign('about',$about->getContentData());
    $template->assign('companyData',$about->getCompanyData());
}
?>
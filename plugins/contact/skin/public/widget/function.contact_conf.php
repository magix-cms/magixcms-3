<?php
function smarty_function_contact_conf($params, $template){
    $contact = new plugins_contact_public();
	$modelTemplate = new frontend_model_template();
	$modelTemplate->addConfigFile([component_core_system::basePath().'/plugins/contact/i18n/'], ['public_local_']);
	$modelTemplate->configLoad();
	$template->assign('contact_config',$contact->getContactConf());
}
<?php
function smarty_function_contact_conf($params, $template){
    $contact = new plugins_contact_public();
    $conf = $contact->getContactConf();
	$modelTemplate = new frontend_model_template();
	$modelTemplate->addConfigFile(
		array(component_core_system::basePath().'/plugins/contact/i18n/'),
		array('public_local_'),
		false
	);
	$modelTemplate->configLoad();
	$template->assign('address_enabled',$conf['address_enabled']);
	$template->assign('address_required',$conf['address_required']);
	if(isset($conf['recaptcha'])) {
		$template->assign('recaptcha',$conf['recaptcha']);
	}
}
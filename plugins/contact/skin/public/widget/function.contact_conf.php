<?php
function smarty_function_contact_conf($params, $smarty){
    $modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
	$modelTemplate->addConfigFile([component_core_system::basePath().'/plugins/contact/i18n/'], ['public_local_'], false);
	$modelTemplate->configLoad();
    $contact = new plugins_contact_public();
    $conf = $contact->getContactConf();
	$smarty->assign('address_enabled',$conf['address_enabled']);
	$smarty->assign('address_required',$conf['address_required']);
	if(isset($conf['recaptcha'])) {
		$smarty->assign('recaptcha',$conf['recaptcha']);
	}
}
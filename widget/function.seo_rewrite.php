<?php
/**
 * @category   Controller
 * @package    CMS
 * @copyright  Copyright (c) 2010 - 2011 Dance connexion
 * @license    Proprietary software
 * @version    1.0
 * @author Gérits Aurélien <aurelien@magix-cms.com>
 * SEO REWRITE METAS
 *
 */
/**
 * Smarty {seo_rewrite module=""} function plugin
 *
 * Type:     function
 * Name:     SEO REWRITE METAS
 * Date:     JUNY 29, 2011
 * Update 		25/07/2011
 * Purpose:  
 * Examples: {seo_rewrite config_param=['level'=>'3','idmetas'=>'1','default'=>''] category="" subcategory="" record=""}
 * Output:   
 * @link 
 * @author   Gerits Aurelien
 * @version  1.1
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_seo_rewrite($params, $template){
	if (!isset($params['conf'])) {
	 	trigger_error("config_param: missing 'config_param' parameter");
		return;
	}

	if(is_array($params['conf'])){
		$conf = $params['conf'];
		$parent = $params['parent'] ?? '';
		$record = $params['record'] ?? '';

		if(isset($_GET['controller'])){
			$controller = form_inputEscape::simpleClean($_GET['controller']);

			if(isset($_GET['plugin'])){
				$plugin = form_inputEscape::simpleClean($_GET['plugin']);
			}

			$module = isset($plugin) ? $plugin : $controller;

			$seo = new frontend_model_seo($module, $conf['level'], $conf['type']);

			if($seo->replace_var_rewrite($parent,$record) != null) {
				return $seo->replace_var_rewrite($parent,$record);
			}
			else {
				if (!isset($conf['default'])) {
					trigger_error("default: missing 'default' parameter");
				}
				else {
					return $conf['default'];
				}
			}
		}
	}
}
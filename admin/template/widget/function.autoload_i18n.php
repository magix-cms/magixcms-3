<?php
/**
 * Smarty {autoload_i18n} function plugin
 *
 * Type:     function
 * Name:     
 * Date:     
 * Update    
 * Purpose:  
 * Examples: 
 * Output:   
 * @link 
 * @author   Gerits Aurelien
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_autoload_i18n($params, $template){
	backend_model_template::configLoad(
        'local_fr.conf'
    );
	//backend_model_language::current_Language()
}
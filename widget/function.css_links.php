<?php
function smarty_function_css_links($params, $smarty){
	if(!empty($params)) {
		if(isset($params['css_files']) && isset($params['dev']) && isset($params['theme'])) {
			foreach ($params['css_files'] as $k => &$css) {
				$css = is_string($k) ? $css : '/skin/'.$params['theme'].'/css/'.$css.(!$params['dev'] ? '.min' : '').'.css';
			}
			$smarty->assign('css_files',$params['css_files']);
		}
	}
}
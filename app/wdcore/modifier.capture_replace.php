<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */
function smarty_modifier_capture_replace($content,$capture) {
	if(!empty($content) && !empty($capture)) {
		//return preg_replace_callback('\[\[([A-Z\-])\]\]',function ($matches) { return ; },$content);
		/*return preg_replace_callback('(\[\[[A-Z\-]+\]\])',
			function($matches) use ($capture) {
			var_dump($matches);
			//var_dump($capture);
			return '';
			},$content);*/

		$matches = [];
		preg_match_all('(\[\[[A-Z\-]+\]\])',$content,$matches);
		if(!empty($matches)) {
			foreach ($matches[0] as $match) {
				$content = str_replace($match,$capture[$match],$content);
			}
		}
		return $content;
	}
	//echo implode('',$components);
}

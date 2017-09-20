<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {amp_img} function plugin
 * Type:     function<br>
 * Name:     amp_img<br>
 * Date:     May 21, 2002
 * Purpose:  replace every img tag by an amp-img tag in the content<br>
 *
 * @link     http://www.smarty.net/manual/en/language.function.mailto.php {mailto}
 *           (Smarty online manual)
 * @version  1.2
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @author   credits to Jason Sweat (added cc, bcc and subject functionality)
 *
 * @param array $params parameters
 *
 * @return string
 */
function smarty_function_amp_img($params)
{
	$content = $params['content'];

	if(preg_match('/<img.*\/>/i',$content,$matches)) {
		$rpl = array();
		$DOM = new DOMDocument();
		$DOM->loadHTML($content);
		$imgs = $DOM->getElementsByTagName('img');
		$k = 0;

		for ($k = 0;$k < $imgs->length; $k ++) {
			$src = $imgs->item($k)->getAttribute('src');
			$title = $imgs->item($k)->getAttribute('title');
			$alt = $imgs->item($k)->getAttribute('alt');
			$rpl[$k] = '<div class="img-container"><amp-img src="'. $src .'" title="'. $title .'" alt="'. $alt .'" class="contain" layout="fill"></amp-img></div>';
		}

		if(count($matches) === count($rpl)) {
			$content = preg_replace_callback('/<img.*\/>/i', function($match) use (&$rpl) {
				return array_shift($rpl);
			}, $content);
			//return $content;
		}
	}

	echo $content;
}

<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {amp_components} function plugin
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
function smarty_function_amp_components($params)
{
	$content = $params['content'];
	if(!isset($params['image'])) $params['image'] = true;
	if(!isset($params['gallery'])) $params['gallery'] = true;
	if(!isset($params['carousel'])) $params['carousel'] = true;
    if(!isset($params['youtube'])) $params['youtube'] = true;
	$components = array();

	// --- Search for img-zoom anchor to convert them into apm-image-lightbox components
	if($params['image']) {
		if(preg_match('/<a(.(?!<\/a>))*(img-zoom)(.(?!<a))*<\/a>/i',$content) || preg_match('/(.(?!<amp-image-lightbox))+/i',$content)) {
			$components[] = '<script async custom-element="amp-image-lightbox" src="https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js"></script>';
		}
	}

	// --- Search for img-gallery anchor to convert them into apm-lightbox gallery
	if($params['gallery']) {
		if(preg_match('/<a(.(?!<\/a>))*(img-gallery)(.(?!<a))*<\/a>/i',$content)) {
			$components[] = '<script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>';
			if($params['carousel']) $components[] = '<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>';
		}
	}
    // --- Search for iframe youtube anchor to convert them into amp-youtube
    if($params['youtube']) {
        if(preg_match('/<iframe(.(?!<\/iframe>)).*(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11}).*(.(?!<iframe))*<\/iframe>/i',$content)) {
            $components[] = '<script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>';
        }
    }

	echo implode('',$components);
}

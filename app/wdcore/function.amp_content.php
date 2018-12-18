<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {amp_content} function plugin
 * Type:     function<br>
 * Name:     amp_img<br>
 * Date:     November, 2018
 * Purpose:  replace every img tag by an amp-img tag in the content<br>
 *
 * @param array $params parameters
 * @param $template
 *
 *
 * @return string
 */
function smarty_function_amp_content($params,$template)
{
    $content = $params['content'];
//	var_dump($content);
    $DOM = new DOMDocument();
    $DOM->loadXML('<html>'.$content.'</html>');

    // --- Search for img-zoom anchor to convert them into apm-image-lightbox components
    $pattern = '/<a(.(?!<\/a>))*(img-zoom)(.(?!<a))*<\/a>/i';
    if(preg_match_all($pattern,$content,$matches)) {
        $rpl = array();
        $links = $DOM->getElementsByTagName('a');
        $i = 0;

        for ($k = 0;$k < $links->length; $k ++) {
            $classes = $links->item($k)->getAttribute('class');
            if(strpos($classes, 'img-zoom') !== false) {
                $src = $links->item($k)->getAttribute('href');
                $parseUrl = parse_url($src);
                if(!isset($parseUrl['scheme'])) {
                    if(file_exists(component_core_system::basePath().$src)) {
                        list($width, $height, $type, $attr) = getimagesize('.' . $src);
                        $title = $links->item($k)->getAttribute('title');
                        $imgs = $links->item($k)->getElementsByTagName('img');
                        $alt = $imgs->item(0)->getAttribute('alt');

                        $rpl[$i] = '<figure>
							<amp-img on="tap:img-zoom' . ($i + 1) . '"
									role="button"
									tabindex="' . $i . '" 
									src="' . $src . '" 
									title="' . $title . '" 
									alt="' . $alt . '" 
									layout="responsive" 
									height="' . $height . '" 
									width="' . $width . '"></amp-img>
									<figcaption class="hidden">' . $title . '</figcaption>
						</figure>
    					<amp-image-lightbox id="img-zoom' . ($i + 1) . '" layout="nodisplay"></amp-image-lightbox>';

                        $i++;

                    }else{
                        $rpl[$i] = '';
                    }
                }else{
                    $rpl[$i] = '';
                }
            }
        }

        if(count($matches[0]) === count($rpl)) {
            $content = preg_replace_callback($pattern, function($match) use (&$rpl) {
                return array_shift($rpl);
            }, $content);
        }
    }

    $DOM = new DOMDocument();
    $DOM->loadXML('<html>'.$content.'</html>');

    // --- Search for img-gallery anchor to convert them into apm-lightbox gallery
    $pattern = '/<a(.(?!<\/a>))*(img-gallery)(.(?!<a))*<\/a>/i';
    if(preg_match_all($pattern,$content,$matches)) {
        $lbGal = '<amp-lightbox id="lightbox-gallery" layout="nodisplay"><div class="lightbox"><amp-carousel id="carousel" layout="fill" type="slides">';
        $slides = array();

        $rpl = array();

        $links = $DOM->getElementsByTagName('a');
        $i = 0;

        for ($k = 0;$k < $links->length; $k ++) {
            $classes = $links->item($k)->getAttribute('class');
            if(strpos($classes, 'img-gallery') !== false)
            {
                $src = $links->item($k)->getAttribute('href');

                $parseUrl = parse_url($src);
                if(!isset($parseUrl['scheme'])) {
                    if(file_exists(component_core_system::basePath().$src)) {
                        list($width, $height, $type, $attr) = getimagesize('.' . $src);
                        $title = $links->item($k)->getAttribute('title');
                        $imgs = $links->item($k)->getElementsByTagName('img');
                        $alt = $imgs->item(0)->getAttribute('alt');

                        $rpl[$i] = '<figure>
                                        <amp-img on="tap:lightbox-gallery,carousel.goToSlide(index=' . $i . ')"
                                            role="button"
                                            tabindex="'. $i .'" 
                                            src="' . $src . '" 
                                            title="' . $title . '" 
                                            alt="' . $alt . '" 
                                            layout="responsive" 
                                            height="' . $height . '" 
                                            width="' . $width . '"></amp-img>
                                        <figcaption class="hidden">'. $title .'</figcaption>
                                    </figure>';

                        $slides[$i] = '<figure>
                                        <amp-img on="tap:lightbox-gallery.close"
                                                role="button"
                                                tabindex="'. $i .'"
                                                src="' . $src . '"
                                                layout="responsive" 
                                                height="' . $height . '" 
                                                width="' . $width . '"></amp-img>
                                        <figcaption>'. $title .'</figcaption>
                                    </figure>';
                        $i++;

                    }else{
                        $rpl[$i] = '';
                        $slides[$i]= '';
                    }
                }else{
                    $rpl[$i] = '';
                    $slides[$i]= '';
                }
            }
        }

        $lbGal .= implode('',$slides);
        $lbGal .= '</amp-carousel><div class="close-btn" on="tap:lightbox-gallery.close" role="button" tabindex="0"><i class="material-icons">close</i></div></div></amp-lightbox>';

        if(count($matches[0]) === count($rpl)) {
            $content = preg_replace_callback($pattern, function($match) use (&$rpl) {
                return array_shift($rpl);
            }, $content);
        }

        $content .= $lbGal;
    }

    $DOM = new DOMDocument();
    $DOM->loadXML('<html>'.$content.'</html>');

    // --- Search for img to convert them into apm-img components
    $pattern = '/<img(.(?!<))*(\/)?>/i';

    if(preg_match_all($pattern,$content,$matches)) {
        $rpl = array();
        $imgs = $DOM->getElementsByTagName('img');

        for ($k = 0;$k < $imgs->length; $k ++) {

            $src = $imgs->item($k)->getAttribute('src');
            /*print '<pre>';
            print_r(parse_url($src));
            print '</pre>';*/
            $parseUrl = parse_url($src);
            if(!isset($parseUrl['scheme'])){
                if(file_exists(component_core_system::basePath().$src)) {
                    if (!$src) $src = $imgs->item($k)->getAttribute('data-src');
                    list($width, $height, $type, $attr) = getimagesize('.' . $src);

                    $title = $imgs->item($k)->getAttribute('title');
                    $alt = $imgs->item($k)->getAttribute('alt');
                    $rpl[$k] = '<amp-img src="' . $src . '" title="' . $title . '" alt="' . $alt . '" layout="responsive" height="' . $height . '" width="' . $width . '"></amp-img>';
                }else{
                    $rpl[$k] = '';
                }
            }else{
                $rpl[$k] = '';
            }


        }
        if(count($matches[0]) === count($rpl)) {
            $content = preg_replace_callback($pattern, function($match) use (&$rpl) {
                return array_shift($rpl);
            }, $content);
        }
    }

    $DOM = new DOMDocument();
    $DOM->loadXML('<html>'.$content.'</html>');

    // --- Search for youtube anchor to convert them into apm-youtube
    $pattern = '/<iframe(.(?!<\/iframe>)).*(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11}).*(.(?!<iframe))*<\/iframe>/i';

    if(preg_match_all($pattern,$content,$matches)) {
        $rpl = array();
        $youtube = $DOM->getElementsByTagName('iframe');

        for ($k = 0;$k < $youtube->length; $k ++) {
            $src = $youtube->item($k)->getAttribute('src');

            $width = $youtube->item($k)->getAttribute('width');
            $height = $youtube->item($k)->getAttribute('height');

            $embedVideo = explode("/embed/", $src)[1];
            $videoId = explode("?",$embedVideo);

            $rpl[$k] = '<amp-youtube data-videoid="'. $videoId[0] .'" layout="responsive" height="'. $height .'" width="'. $width .'"></amp-youtube>';
        }

        if(count($matches[0]) === count($rpl)) {
            $content = preg_replace_callback($pattern, function($match) use (&$rpl) {
                return array_shift($rpl);
            }, $content);
        }
    }

    if(isset($params['assign']) && $params['assign']){
        $template->assign($params['assign'],$content);
    }else{
        echo $content;
    }
}
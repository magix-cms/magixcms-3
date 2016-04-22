<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Mage Pattern.
# The toolkit PHP for developer
# Copyright (C) 2012 - 2013 Gerits Aurelien contact[at]aurelien-gerits[dot]be
#
# OFFICIAL TEAM MAGE PATTERN:
#
#   * Gerits Aurelien (Author - Developer) contact[at]aurelien-gerits[dot]be
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# Redistributions of source code must retain the above copyright notice,
# this list of conditions and the following disclaimer.
#
# Redistributions in binary form must reproduce the above copyright notice,
# this list of conditions and the following disclaimer in the documentation
# and/or other materials provided with the distribution.
#
# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade Mage Pattern to newer
# versions in the future. If you wish to customize Mage Pattern for your
# needs please refer to http://www.magepattern.com for more information.
#
# -- END LICENSE BLOCK -----------------------------------

/**
 * Created by SC BOX.
 * User: aureliengerits
 * Date: 29/07/12
 * Time: 01:58
 *
 */
class pagination_factory{
    /**
     * calculate page offset
     * @access public
     * @param int $limit
     * @param $getpage
     * @return int
     */
    public function pageOffset($limit=10,$getpage){
        return $limit * (abs($getpage)-1);
    }
    /**
     * @param $getdata
     * @param int $limit
     * @param array $setConfig
     * @param array $setArrow
     * @param array $css_param
     * @param bool $debug
     * @return string
     * @example:
     *
        $max = 10
        $pagination = new pagination_factory();
        $pagination->pageOffset($max,$_GET['page']);
        $request = s_count_data_pager();
        $setConfig = array(
            'url'=>'http://www.mydomain/mypage/',
            'getPage'=> $this->getpage,
            'seo'=>'dash',
            'pageName'=>'page',
            'pageNumber'=> false,
            'pageNumberLight'=>true,
            'arrow'=>true,
            'arrowthick'=>false
        );
        $setArrow = array(
            'left'=>'Précédent',
            'right'=>'Suivant'
        );
        $css_param = array(
            'class_number'=>'block w2-16 lfloat',
            'class_arrow_left'=>'button lfloat block w2-16',
            'class_arrow_right'=>'button lfloat block w2-16 last'
        );
        $pagerdata = $pagination->setPagerData(
        $request['total'],$max,$setConfig,$setArrow,$css_param
        );
        $block = '<div class="block w7-16 rfloat pagination last">';
        $block .= $pagerdata;
        $block .= '</div>';
        print $block
     */
    public function setPagerData($getdata,$limit=10,$setConfig = array('url'=>'','getPage'=> '','seo'=>'dash', 'pageName'=>'','uriOption'=>'','pageNumber'=> true, 'pageNumberLight'=> false,'arrow'=>true,'arrowthick'=>true),$setArrow = array('left'=>'&#171;','thickleft'=>'&#171;&#171;','right'=>'&#187;','thickright'=>'&#187;&#187;'),$css_param = array('class_current'=>'current','class_delimiter'=>'delimiter','class_number'=>'number','class_arrow_left'=>'','class_arrowthick_left'=>'','class_arrow_right'=>'','class_arrowthick_right'=>''),$debug=false){
        if(is_array($setConfig)){
            if(array_key_exists('seo', $setConfig)){
                $seoConfig = $setConfig['seo'];
            }else{
                $seoConfig = 'dash';
            }
            if(array_key_exists('uriOption', $setConfig)){
                $uriOption = $setConfig['uriOption'];
            }else{
                $uriOption = '';
            }
            if(array_key_exists('pageNumber', $setConfig)){
                $pageNumber = $setConfig['pageNumber'];
            }else{
                $pageNumber = true;
            }
            if(array_key_exists('pageNumberLight', $setConfig)){
                $pageNumberLight = $setConfig['pageNumberLight'];
            }else{
                $pageNumberLight = false;
            }
            if(array_key_exists('arrow', $setConfig)){
                $arrow = $setConfig['arrow'];
            }else{
                $arrow = true;
            }
            if(array_key_exists('arrowthick', $setConfig)){
                $arrowthick = $setConfig['arrowthick'];
            }else{
                $arrowthick = true;
            }
            $num_pages = $getdata;
            $num_pages = ceil($num_pages/$limit);
            $page = max($setConfig['getPage'], 1);
            $page = min($setConfig['getPage'], $num_pages);
            if($setConfig['getPage'] > $limit || $setConfig['getPage'] <= 0) {
                $page = 1;
            }
            if($debug != false){
                self::debugPager($page,$num_pages,$limit,$setConfig['getPage']);
            }
            $offset = $setConfig['getPage'];
            if($offset > $num_pages)
            {
                $offset = $num_pages;
            }
            $offset = $this->pageOffset($limit,$setConfig['getPage']);
            switch($seoConfig){
                case 'dash':
                    $seo = $setConfig['pageName'].'-';
                    break;
                case 'none':
                    $seo = $setConfig['pageName'].'=';
                    break;
                case 'slash':
                    $seo = $setConfig['pageName'].'/';
                    break;
                default:
                    $seo = $setConfig['pageName'];
                    break;
            }
            $pager = '';
            if(array_key_exists('class_arrow_left', $css_param)){
                if($css_param['class_arrow_left'] != ''){
                    $class_arrow_left = ' class="'.$css_param['class_arrow_left'].'" ';
                }else{
                    $class_arrow_left = '';
                }
            }else{
                $class_arrow_left = '';
            }
            if(array_key_exists('class_arrowthick_left', $css_param)){
                if($css_param['class_arrowthick_left'] != ''){
                    $class_arrowthick_left = ' class="'.$css_param['class_arrowthick_left'].'" ';
                }else{
                    $class_arrowthick_left = '';
                }
            }else{
                $class_arrowthick_left = '';
            }
            if(array_key_exists('class_arrow_right', $css_param)){
                if($css_param['class_arrow_right'] != ''){
                    $class_arrow_right = ' class="'.$css_param['class_arrow_right'].'" ';
                }else{
                    $class_arrow_right = '';
                }
            }else{
                $class_arrow_right = '';
            }
            if(array_key_exists('class_arrowthick_right', $css_param)){
                if($css_param['class_arrowthick_right'] != ''){
                    $class_arrowthick_right = ' class="'.$css_param['class_arrowthick_right'].'" ';
                }else{
                    $class_arrowthick_right = '';
                }
            }else{
                $class_arrowthick_right = '';
            }
            if($pageNumberLight == true){
                $pager .= '<span class="'.$css_param['class_number'].'">';
                $pager .= ' Page ';
                //$pager .=  min($setConfig['getPage'], 1);
                $pager .=  $setConfig['getPage'];
                $pager .= ' sur ';
                $pager .=  max($setConfig['getPage'], $num_pages);
                $pager .= '</span>';
            }
            if($setConfig['getPage'] > 1)
            {
                if($arrowthick == true){
                    $pager .= '<a'.$class_arrowthick_left.' href="'.$setConfig['url'].$seo.(min($setConfig['getPage'], 1)).$setConfig['uriOption'].'">'.$setArrow['thickleft'].'</a>';
                }
                if($arrow == true){
                    $pager .= '<a'.$class_arrow_left.' href="'.$setConfig['url'].$seo.($setConfig['getPage'] - 1).$setConfig['uriOption'].'">'.$setArrow['left'].'</a>';
                }
            }
            if($pageNumber == true){
                if ( $num_pages > 20 ){
                    $points = false;
                    for ( $i = 1; $i <= $num_pages; $i++ ){
                        if ( $i == $setConfig['getPage'] ) {
                            $pager .= '<span class="'.$css_param['class_current'].'">'.$i.'</span>';
                        }elseif ( abs( $i - $setConfig['getPage'] ) <= 10 || $i == 1 || $i == $num_pages ) {
                            $pager .= '<a href="'.$setConfig['url'].$seo.$i.$uriOption.'">'.$i.'</a>';
                            $points = false;
                        }elseif ( $points == false ) {
                            $pager .= '<span class="'.$css_param['class_delimiter'].'">...</span>';
                            $points = true;
                        }
                    }
                }else{
                    if($num_pages > 1){
                        for($i=1; $i<=$num_pages; $i++){
                            if($i == $setConfig['getPage']){
                                $pager .= '<span class="'.$css_param['class_current'].'">'.$i.'</span>';
                            }else{
                                $pager .= '<a href="'.$setConfig['url'].$seo.$i.$uriOption.'">'.$i.'</a>';
                            }
                        }
                    }
                }
            }
            if ($setConfig['getPage'] < $num_pages)  {
                if($arrow == true){
                    $pager .= '<a'.$class_arrow_right.' href="'.$setConfig['url'].$seo.($setConfig['getPage'] + 1).$uriOption.'">'.$setArrow['right'].'</a>';
                }
                if($arrowthick == true){
                    $pager .= '<a'.$class_arrowthick_right.' href="'.$setConfig['url'].$seo.max($setConfig['getPage'], $num_pages).$uriOption.'">'.$setArrow['thickright'].'</a>';
                }
            }
            return $pager;
        }
    }
    /**
     * function debug pagination
     *
     * @param void $page
     * @param void $num_pages
     * @param int $limit
     * @param void $getpage
     */
    private function debugPager($page,$num_pages,$limit,$getpage){
        /*debug*/
        if(defined('MP_LOG')){
            if(MP_LOG == 'debug' AND MP_FIREPHP == true){
                $FirePHPOpt =  array('Collapsed' => false,'Color' => '#FF772F');
                debug_firephp::group('Test pagination',$FirePHPOpt);
                debug_firephp::log($page,'Page');
                debug_firephp::log($num_pages,'Page number');
                debug_firephp::log($limit,'Limit');
                debug_firephp::groupEnd();
                $page = max($getpage, 1);
                debug_firephp::group('Test pagination',$FirePHPOpt);
                debug_firephp::log($page,'Page');
                debug_firephp::log($num_pages,'Page number');
                debug_firephp::log($limit,'Limit');
                debug_firephp::groupEnd();
                $page = min($getpage, $num_pages);
                debug_firephp::group('Test pagination',$FirePHPOpt);
                debug_firephp::log($page,'Page');
                debug_firephp::log($num_pages,'Page number');
                debug_firephp::log($limit,'Limit');
                debug_firephp::groupEnd();
                if($getpage > $limit || $getpage <= 0) {
                    $page = 1;
                }
                debug_firephp::group('Test pagination',$FirePHPOpt);
                debug_firephp::log($page,'Page');
                debug_firephp::log($num_pages,'Page number');
                debug_firephp::log($limit,'Limit');
                debug_firephp::groupEnd();
            }
        }else{
            print 'Page : '.$page.'<br />Num_Pages : '.$num_pages.'<br />Limit : '.$limit.'##########<br />';
            $page = max($getpage, 1);
            print 'Page : '.$page.'<br />Num_Pages : '.$num_pages.'<br />Limit : '.$limit.'##########<br />';
            $page = min($getpage, $num_pages);
            print 'Page : '.$page.'<br />Num_Pages : '.$num_pages.'<br />Limit : '.$limit.'##########<br />';
            if($getpage > $limit || $getpage <= 0) {
                $page = 1;
            }
            print 'Page : '.$page.'<br />Num_Pages : '.$num_pages.'<br />Limit : '.$limit.'##########<br />';
        }
    }
}
?>
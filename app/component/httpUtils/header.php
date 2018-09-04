<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
#
# OFFICIAL TEAM :
#
#   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
#
# Redistributions of files must retain the above copyright notice.
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
# -- END LICENSE BLOCK -----------------------------------

# DISCLAIMER

# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/
/**
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 13/12/13
 * Time: 18:15
 * License: Dual licensed under the MIT or GPL Version
 */
class component_httpUtils_header{
    protected $template, $header;
    /**
     * @var bool
     */
    public $getHeader;

	/**
	 * component_httpUtils_header constructor.
	 * @param $t
	 */
    public function __construct($t){
        $this->template = $t;
        $this->header = new http_header();
        if(http_request::isGet('getHeader')) $this->getHeader = form_inputFilter::isNumeric($_GET['getHeader']);
    }

    /**
     * @param $http_error
     * @return string
     */
    private function setTitleHeader($http_error){
        if(isset($http_error)){
            $this->template->configLoad();
            switch($http_error){
                case 404:
                    $message = $this->template->getConfigVars('title_status_404');
                    $this->header->getStatus(404);
                    $status = $this->header->setStatusCode(404,$message);
                    break;
                case 403:
                    $message = $this->template->getConfigVars('title_status_403');
                    $this->header->getStatus(403);
                    $status = $this->header->setStatusCode(403,$message);
                    break;
                case 401:
                    $message = $this->template->getConfigVars('title_status_401');
                    $this->header->getStatus(401);
                    $status = $this->header->setStatusCode(401,$message);
                    break;
            }
            return $status;
        }
    }

    /**
     * Configuration du texte à retourner suivant le status du code d'erreur
     * @param $http_error
     * @return mixed
     */
    private function setTxtHeader($http_error){
        if(isset($http_error)){
            switch($http_error){
                case 404:
                    $message = $this->template->getConfigVars('txt_status_404');
                    $status = $this->header->setStatusCode(404,$message);
                    break;
                case 403:
                    $message = $this->template->getConfigVars('txt_status_403');
                    $status = $this->header->setStatusCode(403,$message);
                    break;
                case 401:
                    $message = $this->template->getConfigVars('txt_status_401');
                    $status = $this->header->setStatusCode(401,$message);
                    break;
            }
            return $status;
        }
    }
    /**
     * @param $http_error
     * @return string
     */
    public function getTitleHeader($http_error){
        return $this->setTitleHeader($http_error);
    }

    /**
     * @param $http_error
     * @return mixed
     */
    public function getTxtHeader($http_error){
        return $this->setTxtHeader($http_error);
    }

	function get_browser_name($user_agent)
	{
		if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
		elseif (strpos($user_agent, 'Edge')) return 'Edge';
		elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
		elseif (strpos($user_agent, 'Safari')) return 'Safari';
		elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
		elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'IE';

		return 'Other';
	}

    /**
     *
     */
    public function mobileDetect(){
		$detect = new Mobile_Detect;
		$viewport = 'desktop';
		$browser = '';
		$device = '';
		$touch = false;
		$mOS = false;
		$mobile = $detect->isMobile();
		$tablet = $detect->isTablet();

		if($mobile || $tablet) {
			$viewport = $tablet ? 'tablet' : 'mobile';
			$touch = true;

			if( $detect->isiOS() ){
				$mOS = ' IOS';

				if($mobile) {
					if($detect->is('iphone')) {
						$device = ' iphone';
					}
				}
				else {
					if($detect->is('ipad')) {
						$device = ' ipad';
					}
				}
			}
			elseif( $detect->isAndroidOS() ){
				$mOS = ' Android';
			}

			if($detect->is('IE')) $browser = 'IE';
			if($detect->is('Edge')) $browser = 'Edge';
			if($detect->is('Chrome')) $browser = 'Chrome';
			if($detect->is('Safari')) $browser = 'Safari';
			if($detect->is('UCBrowser')) $browser = 'UCBrowser';
			if($detect->is('Opera')) $browser = 'Opera';
		}
		else {
			//@Todo The best would be to use the get_browser function of php, need browscap set in the php.ini
			$browser = $this->get_browser_name($_SERVER['HTTP_USER_AGENT']);
		}

		$this->template->assign('bodyClass', $viewport.($browser ? ' '.$browser : '').$mOS.$device);
		$this->template->assign('browser', $browser);
		$this->template->assign('viewport', $viewport);
		$this->template->assign('touch', $touch);
		$this->template->assign('mOS', $mOS);
		header("Vary: User-Agent");
    }
}
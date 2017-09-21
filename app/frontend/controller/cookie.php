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
 * Date: 11/12/13
 * Time: 00:19
 * License: Dual licensed under the MIT or GPL Version
 */
class frontend_controller_cookie {
	/**
	 * @var
	 */
	protected $template,$header;

	/**
	 * @var bool
	 */
	public $cookie,$source;

	/**
	 * frontend_controller_home constructor.
	 */
	public function __construct(){
		$formClean = new form_inputEscape();
		$this->template = new frontend_model_template();
		$this->header = new http_header();

		if(http_request::isGet('__amp_source_origin')) {
			$this->source = $formClean->simpleClean($_GET['__amp_source_origin']);
		}
	}

	private function setCookie()
	{
		setcookie('complianceCookie','on',strtotime("+2 years"),'/');
	}

	/**
	 *
	 */
	public function run(){
		if(isset($this->source)) {
			$request_body = file_get_contents('php://input');
			$data = json_decode($request_body);
			if(!empty($data)) {
				$this->setCookie();
			}
			else if(isset($_COOKIE["complianceCookie"]) && $_COOKIE["complianceCookie"] == 'on') {
				$this->header->set_json_headers();
				echo json_encode(array('showNotification' => false), JSON_FORCE_OBJECT);
			}
			else {
				$this->header->set_json_headers();
				echo json_encode(array('showNotification' => true), JSON_FORCE_OBJECT);
			}
		}
	}
}
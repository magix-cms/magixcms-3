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
class backend_controller_dashboard{
    protected $router,$template,$employee,$news,$pages,$cats,$products;
    public $http_error;

	/**
	 * @param stdClass $t
	 * backend_controller_dashboard constructor.
	 */
    public function __construct($t = null){
		$this->template = $t ? $t : new backend_model_template;
		$this->employee = new backend_controller_employee($t);
		$this->news = new backend_controller_news($t);
		$this->pages = new backend_controller_pages($t);
		$this->cats = new backend_controller_category($t);
		$this->products = new backend_controller_product($t);
        if(http_request::isGet('http_error')){
            $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);
        }
    }

    public function run(){
        /**
         * Initalisation du système d'entête
         */
        $header = new component_httpUtils_header($this->template);
        if(isset($this->http_error)){
            $this->template->assign(
                'getTitleHeader',
                $header->getTitleHeader(
                    $this->http_error
                ),
                true
            );
            $this->template->assign(
                'getTxtHeader',
                $header->getTxtHeader(
                    $this->http_error
                ),
                true
            );

            $this->template->display('error/index.tpl');
        }else{
            $this->employee->getItemsEmployee();
			$this->news->getItemsNews();
			$this->pages->getItemsPages();
			$this->cats->getItemsCat();
			$this->products->getItemsProduct();
            $this->template->display('dashboard/index.tpl');
            // Create a Router
            /*$this->router->get('/', function(){
                print 'test';
            });*/
        }
    }
}
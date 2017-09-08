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
class frontend_controller_home extends frontend_db_home{
    /**
     * @var
     */
    protected $template,$header,$data;
    /**
     * @var bool
     */
    public $http_error,$getlang;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        if(http_request::isGet('http_error')){
            $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);
        }
        $this->getlang = $this->template->currentLanguage();
    }
    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }
    /**
     * set Data from database
     * @access private
     */
    private function getBuildItems()
    {
        $collection = $this->getItems('page',array(':iso'=>$this->getlang),'one',false);
        return array(
            'name'      =>  $collection['title_page'],
            'content'   =>  $collection['content_page'],
            'seoTitle'  =>  $collection['seo_title_page'],
            'seoDescr'  =>  $collection['seo_desc_page']
        );
    }

    /**
     *
     */
    public function run(){
        /**
         * Initalisation du système d'entête
         */

        if(isset($this->http_error)){
            $this->template->assign(
                'getTitleHeader',
                $this->header->getTitleHeader(
                    $this->http_error
                ),
                true
            );
            $this->template->assign(
                'getTxtHeader',
                $this->header->getTxtHeader(
                    $this->http_error
                ),
                true
            );

            $this->template->display('error/index.tpl');
        }else{
            $this->template->assign('home',$this->getBuildItems());
            $this->template->display('home/index.tpl');
        }
    }
}
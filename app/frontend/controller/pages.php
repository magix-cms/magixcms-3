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
class frontend_controller_pages extends frontend_model_db_cms{
    /**
     * @var
     */
    protected $router,$template,$inputEscape;

    /**
     * @param $router
     */
    public function __construct($router){
        $this->router = $router;
        $this->template = new frontend_model_template();
        $inputEscape   =   new form_inputEscape();
        /*$inputEscape->numeric();

        if ($FilterRequest->isGet('getidpage_p')) {
            $this->idParent = $FilterVar->isPostAlphaNumeric($_GET['getidpage_p']);

        }
		if ($FilterRequest->isGet('getidpage')) {
            $this->idPage = $FilterVar->isPostNumeric($_GET['getidpage']);

        }*/
    }

    /**
     * Assign page's data to smarty
     * @access private
     */
    private function getData($idPage, $idParent = false)
    {
        $ModelCms    =   new frontend_model_cms();

        $data = parent::s_page_data($idPage);

        $dataClean  =   $ModelCms->setItemData($data,0);
        $dataClean['seoTitle']  =   $data['seo_title_page'];
        $dataClean['seoDescr']  =   $data['seo_desc_page'];

        $this->template->assign('page',   $dataClean, true);

        // ** Assign parent page data
        if (isset($idParent)) {
            $parent = parent::s_page_data($idParent);
            $parentClean  =   $ModelCms->setItemData($parent,0);
            $this->template->assign('parent',$parentClean);
        }
    }

    /**
     * @access public
     * run app
     */
    public function run(){
        // Create a Router
        /*$this->router->get('/', function() {
            print 'pages';
        });*/
        $this->router->get('/(:num)-(:any)', function($idParent,$parentName) {
            //print 'pages name '.$parentName.'('.$idParent.')';
            $this->template->assign('getID',array('idParent'=>$idParent));
            $this->getData($idParent);
            $this->template->display('cms/index.tpl');
        });
        $this->router->get('/(:num)-(:any)/(:num)-(:any)', function($idParent,$parentName,$idChild,$childName) {
            /*print 'pages parent '.$parentName.'('.$idParent.')<br>';
            print 'pages child '.$childName.'('.$idChild.')';*/
            $this->template->assign('getID',array('idParent'=>$idParent));
            $this->getData($idChild,$idParent);
            $this->template->display('cms/index.tpl');
        });
    }
}
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
class frontend_controller_pages extends frontend_db_pages {
    /**
     * @var
     */
    protected $template,$header,$data,$modelPages,$modelCore;
    public $getlang,$http_error,$id;

	/**
	 * frontend_controller_pages constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null){
		$this->template = $t ? $t : new frontend_model_template();
		$formClean = new form_inputEscape();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->modelPages = new frontend_model_pages($this->template);
        if (http_request::isGet('id')) {
            $this->id = $formClean->numeric($_GET['id']);
        }
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
		$collection = $this->getItems('page',array('id'=>$this->id,'iso'=>$this->getlang),'one',false);
		return $this->modelPages->setItemData($collection,null);
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildChildItems()
    {
		$modelSystem = new frontend_model_core();
		$current = $modelSystem->setCurrentId();
		$data = $this->modelPages->getData(
			array(
				'context' => 'all',
				'select' => $this->id
			),
			$current
		);
		return $this->data->parseData($data,$this->modelPages,$current);
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildParent($page)
    {
        $collection = $this->getItems('page',array('id'=>$page['id_parent'],'iso'=>$this->getlang),'one',false);
        return $this->modelPages->setItemData($collection,null);
    }

    /**
     * @return array
     */
    private function getBuildItemsTree(){
        $modelSystem = new frontend_model_core();
		$current = $modelSystem->setCurrentId();
		$data = $this->modelPages->getData(
			array(
				'context' => 'all',
			),
			$current
		);
		return $this->data->parseData($data,$this->modelPages,$current);
    }

    /**
     * @return array
     */
    private function getBuildLangItems(){
        $collection = $this->getItems('langs',array('id'=>$this->id),'all',false);
        return $this->modelPages->setHrefLangData($collection);
    }
    /**
     * Assign page's data to smarty
     * @access private
     */
    private function getData()
    {
        $data = $this->getBuildItems();
        $parent = $data['id_parent'] !== null ? $this->getBuildParent($data) : null;
        $childs = $this->getBuildChildItems();
        $hreflang = $this->getBuildLangItems();
        $pagesTree = $this->getBuildItemsTree();
        $this->template->assign('pages',$data,true);
        $this->template->assign('parent',$parent,true);
        $this->template->assign('childs',$childs[0]['subdata'],true);
        $this->template->assign('pagesTree',$pagesTree,true);
        $this->template->assign('hreflang',$hreflang,true);
    }

    /**
     * @access public
     * run app
     */
    public function run(){
        if(isset($this->id)) {
            $this->getData();
            $this->template->display('pages/index.tpl');
        }
        else {
            $this->template->assign(
                'getTitleHeader',
                $this->header->getTitleHeader(
                    404
                ),
                true
            );
            $this->template->assign(
                'getTxtHeader',
                $this->header->getTxtHeader(
                    404
                ),
                true
            );

            $this->template->display('error/index.tpl');
        }
    }
}
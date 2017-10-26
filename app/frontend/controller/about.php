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
class frontend_controller_about extends frontend_db_about {
    /**
     * @var
     */
    protected $template,$header,$data,$modelPages,$modelCore;
    public $getlang,$id;

    /**
     * frontend_controller_pages constructor.
     */
    public function __construct(){
        $formClean = new form_inputEscape();
        $this->template = new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->modelPages = new frontend_model_about($this->template);

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
	 * @return array|null
	 */
	private function getBuildRootItems()
	{
		$collection = $this->getItems('root',array('iso'=>$this->getlang),'all',false);

		$newData = array();
		foreach ($collection as $item) {
			if($item['name_info'] === 'desc') {
				$newData['name'] = $item['value_info'];
			}
			else {
				$newData[$item['name_info']] = $item['value_info'];
			}
		}

		return $this->modelPages->setItemData($newData,null);
	}

	/**
	 * @return array|null
	 */
	private function getBuildPagesTree()
	{
		$conditions = ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.menu_pages = 1';
		$collection = parent::fetchData(
			array('context' => 'all', 'type' => 'pages', 'conditions' => $conditions),
			array('iso' => $this->getlang)
		);

		$newarr = array();
		foreach ($collection as $item) {
			$newarr[] = $this->modelPages->setItemData($item,null);
		}
		$newarr = $this->modelPages->setPagesTree($newarr);

		return $newarr;
	}

    /**
     * set Data from database
     * @access private
     */
    private function getBuildItems()
    {
        $collection = $this->getItems('page',array(':id'=>$this->id,':iso'=>$this->getlang),'one',false);
        return $this->modelPages->setItemData($collection,null);
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildParent($page)
    {
        $collection = $this->getItems('page',array(':id'=>$page['id_parent'],':iso'=>$this->getlang),'one',false);
        return $this->modelPages->setItemData($collection,null);
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
		$pages = $this->getBuildPagesTree();
		$this->template->assign('pagesTree',$pages,true);
		$data = $this->getBuildRootItems();
		$this->template->assign('root',$data,true);

		if(isset($this->id)) {
			$data = $this->getBuildItems();
			$parent = $data['id_parent'] !== null ? $this->getBuildParent($data) : null;
			$hreflang = $this->getBuildLangItems();
			$this->template->assign('parent',$parent,true);
			$this->template->assign('hreflang',$hreflang,true);
		}

		$this->template->assign('pages',$data,true);
    }

    /**
     * @access public
     * run app
     */
    public function run(){
		$this->getData();
        $this->template->display('about/index.tpl');
    }
}
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
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------
#
# DISCLAIMER
#
# Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
# versions in the future. If you wish to customize MAGIX CMS for your
# needs please refer to http://www.magix-cms.com for more information.
*/
class frontend_controller_about extends frontend_db_about {
    /**
     * @var frontend_model_template $template
     * @var component_httpUtils_header $header
     * @var frontend_model_data $data
     * @var frontend_model_about $modelPages
     */
	protected frontend_model_template $template;
	protected component_httpUtils_header $header;
	protected frontend_model_data $data;
	protected frontend_model_about $modelPages;

	/**
	 * @var int $id
	 */
    public int $id;
	/**
	 * @var string $lang
	 */
    public string $lang;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->header = new component_httpUtils_header($this->template);
        $this->data = new frontend_model_data($this,$this->template);
		$this->modelPages = new frontend_model_about($this->template);
		$this->lang = $this->template->lang;

        if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return array|bool
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	/**
	 * @return array
	 */
	private function getBuildRootItems(): array {
		$collection = $this->getItems('root',['iso'=>$this->lang],'all',false);
		$newData = [];
		if(!empty($collection)) {
			foreach ($collection as $item) {
				if ($item['name_info'] === 'desc') {
					$newData['name'] = $item['value_info'];
				}
				else {
					$newData[$item['name_info']] = $item['value_info'];
				}
			}
			$newData = $this->modelPages->setItemData($newData,[]);
		}
		return $newData;
	}

	/**
	 * @return array
	 */
	private function getBuildPagesTree(): array {
		$conditions = ' WHERE lang.iso_lang = :iso AND c.published_pages = 1 AND p.menu_pages = 1';
		$collection = parent::fetchData(
			['context' => 'all', 'type' => 'pages', 'conditions' => $conditions],
			['iso' => $this->lang]
		);

		$arr = [];
		if(!empty($collection)) {
			foreach ($collection as $item) {
				$arr[] = $this->modelPages->setItemData($item,[]);
			}
			$arr = $this->modelPages->setPagesTree($arr);
		}
		return $arr;
	}

    /**
     * set Data from database
     * @access private
     */
    private function getBuildItems(): array {
        $collection = $this->getItems('page',['id' => $this->id,'iso' => $this->lang],'one',false);
        return $this->modelPages->setItemData($collection,[]);
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildParent($page): array {
        $collection = $this->getItems('page',['id' => $page['id_parent'],'iso' => $this->lang],'one',false);
        return $this->modelPages->setItemData($collection,[]);
    }

    /**
     * @return array
     */
    private function getBuildLangItems(): array {
        $collection = $this->getItems('langs',['id'=>$this->id],'all',false);
        return $this->modelPages->setHrefLangData($collection);
    }

    /**
     * Assign page's data to smarty
     * @access private
     */
    private function getData() {
		$pages = $this->getBuildPagesTree();
		$this->template->assign('pagesTree',$pages,true);
		$data = $this->getBuildRootItems();
		$this->template->assign('root',$data,true);

		if(isset($this->id)) {
			$this->template->breadcrumb->addItem(
				$data['name'],
				'/'.$this->template->lang.($this->template->is_amp() ? '/amp' : '').'/about/',
				$data['name']
			);
			$data = $this->getBuildItems();
			$parent = $data['id_parent'] !== null ? $this->getBuildParent($data) : null;
			$hreflang = $this->getBuildLangItems();
			$this->template->assign('parent',$parent,true);
			$this->template->assign('hreflang',$hreflang,true);
		}
		$this->template->breadcrumb->addItem($data['name']);

		$this->template->assign('pages',$data,true);
    }

	/**
	 * @return void
	 */
    public function run() {
		$this->getData();
        $this->template->display('about/index.tpl');
    }
}
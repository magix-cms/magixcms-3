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
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var frontend_model_pages $modelPages
     * @var frontend_model_module $modelModule
     * @var component_httpUtils_header $header
     */
    protected frontend_model_template $template;
    protected frontend_model_data $data;
    protected frontend_model_pages $modelPages;
    protected frontend_model_module $modelModule;
    protected component_httpUtils_header $header;

    public
        $getlang,
        $id;

	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(frontend_model_template $t = null){
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->lang;
        $this->modelPages = new frontend_model_pages($this->template);
        $this->modelModule = new frontend_model_module($this->template);
        if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string|null $context
     * @param bool|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * set Data from database
     * @access private
     */
    private function getBuildPagesItems(): array {
        $override = $this->modelModule->getOverride('pages',__FUNCTION__);
        if(!$override) {
            $collection = $this->getItems('page', ['id' => $this->id, 'iso' => $this->getlang], 'one', false);
            $imgCollection = $this->getItems('imgs', ['id' => $this->id, 'iso' => $this->getlang], 'all', false);
            if ($imgCollection != null) $collection['img'] = $imgCollection;
            return $this->modelPages->setItemData($collection, []);
        }
        else {
            return $override;
        }
    }

    /**
     * @return array
     */
    private function getBuildPagesItemsTree(): array {
        $modelSystem = new frontend_model_core();
		$current = $modelSystem->setCurrentId();
		$data = $this->modelPages->getData(['context' => 'all'], $current);
		return !empty($data) ? $this->data->parseData($data, $this->modelPages, $current) : [];
    }

    /**
     * @return array
     */
    private function getBuildLangItems(): array {
        $collection = $this->getItems('langs',['id'=>$this->id],'all',false);
        return $this->modelPages->setHrefLangData($collection);
    }

    /**
     * @access public
     * run app
     */
    public function run() {
        if(isset($this->id)) {
            $data = $this->getBuildPagesItems();
            $hreflang = $this->getBuildLangItems();
            $pagesTree = $this->getBuildPagesItemsTree();
            $childs = $pagesTree[0]['subdata'] ?? [];
            $this->template->assign('pages',$data,true);
            if(!empty($data['id_parent'])) $this->template->breadcrumb->addItem(
                $data['parent']['name'],
                $data['parent']['url'],
                $this->template->getConfigVars('show_page').': '.$data['parent']['name']
            );
            $this->template->breadcrumb->addItem($data['name']);
            $this->template->assign('childs',$childs,true);
            $this->template->assign('pagesTree',$pagesTree,true);
            $this->template->assign('hreflang',$hreflang,true);

            $this->template->display('pages/index.tpl');
        }
        else {
            $this->header = new component_httpUtils_header($this->template);
            $this->template->assign('getTitleHeader', $this->header->getTitleHeader(404), true);
            $this->template->assign('getTxtHeader', $this->header->getTxtHeader(404), true);
            $this->template->display('error/index.tpl');
        }
    }
}
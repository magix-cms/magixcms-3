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
        $lang,
        $id;
    /**
     * @var array $filter
     */
    public array $filter;
	/**
	 * @param frontend_model_template|null $t
	 */
    public function __construct(frontend_model_template $t = null){
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this);
        $this->lang = $this->template->lang;
        $this->modelPages = new frontend_model_pages($this->template);
        $this->modelModule = new frontend_model_module($this->template);
        if (http_request::isGet('id')) $this->id = form_inputEscape::numeric($_GET['id']);
        if(http_request::isGet('filter')) $this->filter = form_inputEscape::arrayClean($_GET['filter']);
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
     * @deprecated
     * set Data from database
     * @access private
     */
    private function getBuildPagesItems(): array {
        $override = $this->modelModule->getOverride('pages',__FUNCTION__);
        if(!$override) {
            $collection = $this->getItems('page', ['id' => $this->id, 'iso' => $this->lang], 'one', false);
            $imgCollection = $this->getItems('imgs', ['id' => $this->id, 'iso' => $this->lang], 'all', false);
            if ($imgCollection != null) $collection['img'] = $imgCollection;
            return $this->modelPages->setItemData($collection, []);
        }
        else {
            return $override;
        }
    }

    /**
     * @param int|null $id
     * @return array
     * @throws Exception
     */
    public function getPagesData(int $id = null) : array {
        if($id !== null) $this->id = $id;
        $newTableArray = [];
        $override = $this->modelModule->extendDataArray('pages',__FUNCTION__);
        if($override) {
            foreach ($override as $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }
        }

        if(!$newTableArray){
            $collection = $this->getItems('pages', array('id' => $this->id, 'iso' => $this->lang), 'one', false);
        }
        else{
            $extendQueryParams = [];
            $extendQueryParams[] = $newTableArray['extendQueryParams'];

            $params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                }
            }
            $collection = $this->getItems('pages', array_merge(array('id' => $this->id, 'iso' => $this->lang),$params), 'one', false);
        }
        $imgCollection = $this->getItems('imgs', array('id' => $this->id, 'iso' => $this->lang), 'all', false);

        if ($imgCollection != null) $collection['img'] = $imgCollection;

        if(!$newTableArray){
            $extendProductData = $this->modelModule->extendDataArray('pages','extendPagesData', $collection);
            $newRow = [];
            if($extendProductData) {
                $extendRow = [];
                foreach ($extendProductData as $value) {
                    foreach ($value['newRow'] as $key => $item) {
                        $extendRow['newRow'][$key] = $item;
                        $extendRow['collection'][$key] = $value['collection'];
                        $extendRow['data'][$key] = $value['data'];
                        $collection[$value['collection']] = $value['data'];
                    }
                }
                $newRow = $extendRow['newRow'];
            }
            return $this->modelPages->setItemData($collection, [], $newRow);
        }
        else{
            if(isset($newTableArray['collection'])){
                $extendFormArray = [];
                if(is_array($newTableArray['collection'])) {
                    foreach ($newTableArray['collection'] as $key => $value) {
                        $extendFormArray[] = $value;
                    }
                }else{
                    $extendFormArray[] = $newTableArray['collection'];
                }
                $extendFormData = $this->modelModule->extendDataArray('pages','extendPages', $collection);
                foreach ($extendFormData as $key => $value) {
                    $collection[$extendFormArray[$key]] = $value;
                }
            }

            $extendProductData = $this->modelModule->extendDataArray('pages','extendPagesData', $collection);
            if($extendProductData) {
                $extendRow = [];
                foreach ($extendProductData as $value) {
                    foreach ($value['newRow'] as $key => $item) {
                        $extendRow['newRow'][$key] = $item;
                        $extendRow['collection'][$key] = $value['collection'];
                        $extendRow['data'][$key] = $value['data'];
                        $collection[$value['collection']] = $value['data'];
                    }
                }
                $newRow = array_merge($newTableArray['newRow'], $extendRow['newRow']);
            }
            else{
                $newRow = $newTableArray['newRow'];
            }

            return $this->modelPages->setItemData($collection, [], $newRow);
        }
    }

    /**
     * @deprecated
     * @return array
     */
    private function getBuildPagesChildren(): array {
        $modelSystem = new frontend_model_core();
		$current = $modelSystem->setCurrentId();
		$data = $this->modelPages->getData(['context' => 'all', 'select' => $this->id], $current);
		if(!empty($data)) {
			$page = $this->data->parseData($data, $this->modelPages, $current);
			return $page[0]['subdata'] ?? [];
		}
		return [];
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
     * @param $id_parent
     * @param string|NULL $listids
     * @param $order
     * @param array $filter
     * @return array
     */
    public function getPagesList($id_parent = NULL, string $listids = NULL, $order = NULL, array $filter = []) : array {
        if(isset($this->filter)) $filter = $this->filter;

        $newTableArray = [];
        $override = $this->modelModule->extendDataArray('pages',__FUNCTION__, $filter);

        /*if($override) {
            foreach ($override as $key => $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }

        }*/
        if(!empty($override)) {
            foreach ($override as $value) {
                $newTableArray = array_merge_recursive($newTableArray, $value);
            }
        }

        $params = [
            'iso' => $this->lang/*,
            'where' => [
                ['type' => 'WHERE',
                    'condition' => 'lang.iso_lang = :iso'
                ],
                ['type' => 'AND',
                    'condition' => 'pc.published_pages = 1'
                ],
                ['type'=> 'AND',
                    'condition'=>'(img.default_img = 1 OR img.default_img IS NULL)'
                ]
            ]*/
        ];

        if(!empty($listids)) $params['listids'] = $listids;
        if ($order !== NULL) {
            // On l'adapte au format attendu par votre fonction db (tableau de tableaux)
            $params['order'] = is_array($order) ? $order : [[$order]];
        }

        if($newTableArray) {
            //print_r(array_merge($newTableArray['extendQueryParams'], $newTableArray['filterQueryParams']));
            $extendQueryParams = [];
            $extendQueryParams[] = $newTableArray['extendQueryParams'];
            //print_r($extendQueryParams);
            //$params = [];
            if(!empty($extendQueryParams)) {
                foreach ($extendQueryParams as $extendParams) {
                    if(isset($extendParams['select']) && !empty($extendParams['select'])) $params['select'][] = $extendParams['select'];
                    if(isset($extendParams['join']) && !empty($extendParams['join'])) $params['join'][] = $extendParams['join'];
                    if(isset($extendParams['where']) && !empty($extendParams['where'])) $params['where'][] = $extendParams['where'];
                    if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'][] = $extendParams['order'];

                    if(!empty($filter)){
                    if(isset($extendParams['limit']) && !empty($extendParams['limit'])) $params['limit'][] = $extendParams['limit'];
                        //if(isset($extendParams['order']) && !empty($extendParams['order'])) $params['order'] = $extendParams['order'];
                    if(isset($extendParams['filter']) && !empty($extendParams['filter'])) $params['where'][] = is_array($extendParams['where']) ? array_merge($extendParams['where'],$extendParams['filter']) : $extendParams['filter'];
                    }
                }
            }
            /*print '<pre>';
            print_r($params);
            print '</pre>';*/
            //$collection = $this->getItems('category', array_merge($defaultParams,$params), 'all', false);
        }

        $collection = $this->getItems('pages',$params,'all',false);
        unset($params);

        /*print '<pre>';
        print_r($collection);
        print '</pre>';*/

        $newRow = [];
        if($newTableArray) {
            if(isset($newTableArray['collection'])){
                $extendFormArray = [];
                if(is_array($newTableArray['collection'])){
                    foreach ($newTableArray['collection'] as $value){
                        $extendFormArray[] = $value;
                    }
                }
                else{
                    $extendFormArray[] = $newTableArray['collection'];
                }
                $extendFormData = $this->modelModule->extendDataArray('pages','extendListPages', $collection);
                foreach ($collection as $key => $value){
                    foreach ($extendFormData as $key1 => $value1) {
                        $collection[$key][$extendFormArray[$key1]] = $value1[$key];
                    }
                }
                $newRow = $newTableArray['newRow'];
                $newTree = $newTableArray['type'] ?? [];
			}
        }
        $setTree = !empty($newTree) ? $newTree : 'root';
        $isFlatMode = !empty($listids);
        /*print '<pre>';
        print_r($collection);
        print '</pre>';*/

        $newSetArray = [];
        if(!empty($collection)) {
            $newSetArray = $this->data->setPagesTree($collection,'pages', $id_parent ?? $setTree ,'all',$this->modelPages,false,$newRow, $isFlatMode);
            //$newSetArray = $newSetArray ?? [];
            /*print '<pre>';
            print_r($newSetArray);
            print '</pre>';*/
            /*foreach ($collection as $item) {
                $newSetArray[] = $this->modelPages->setItemData($item, [], $newRow);
            }*/

            if($id_parent !== null) $newSetArray = empty($newSetArray[0]['subdata']) ? [] : $newSetArray[0]['subdata'];
        }

        return $newSetArray;
    }

    /**
     * @access public
     * run app
     * @throws Exception
     */
    public function run() {
        if(isset($this->id)) {
            $data = $this->getPagesData($this->id);//$this->getBuildPagesItems();
            $hreflang = $this->getBuildLangItems();
            $pagesTree = $this->getBuildPagesItemsTree();
            $childs = $this->getPagesList($this->id);
			//$childs = $this->getBuildPagesChildren();
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
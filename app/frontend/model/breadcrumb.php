<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2013 sc-box.com <support@magix-cms.com>
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
class frontend_model_breadcrumb extends frontend_db_menu {
	protected $template, $data, $modelSystem;
	public $about, $pages, $catalog, $category, $controller, $id, $id_parent, $date, $year, $month, $tag, $page;

	/**
	 * frontend_model_menu constructor.
	 * @param stdClass $t
	 */
	public function __construct($t = null)
	{
		$this->template = $t ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
		$this->modelSystem = new frontend_model_core();
		$formClean = new form_inputEscape();

		if (http_request::isGet('controller')) {
			$this->controller = $formClean->simpleClean($_GET['controller']);
		}
		if (http_request::isGet('id')) {
			$this->id = $formClean->numeric($_GET['id']);
		}
		if (http_request::isGet('id_parent')) {
			$this->id_parent = $formClean->numeric($_GET['id_parent']);
		}
		if (http_request::isGet('date')) {
			$this->date = $formClean->simpleClean($_GET['date']);
		}
		if (http_request::isGet('year')) {
			$this->year = $formClean->simpleClean($_GET['year']);
		}
		if (http_request::isGet('month')) {
			$this->month = $formClean->simpleClean($_GET['month']);
		}
		if (http_request::isGet('tag')) {
			$this->tag = $formClean->simpleClean($_GET['tag']);
		}
		if (http_request::isGet('page')) {
			$this->page = $formClean->simpleClean($_GET['page']) - 1;
		}
	}

	/**
	 * @param $iso
	 * @return array
	 */
	public function getBreadcrumb($iso) {
		$current = $this->modelSystem->setCurrentId();

		$bread = array();
		$bread[] = array('name' => '');
		if($this->controller != 'home') {
			$bread[0]['url'] = http_url::getUrl().'/'.$iso.($this->template->is_amp() ? '/amp/':'/');
			$bread[0]['title'] = '';
		}

		if(in_array($this->controller, array('about','pages','catalog','news'))) {
			$model = 'frontend_model_'.$this->controller;
			$model = new $model($this->template);
			if($this->controller !== 'pages') {
				$bread[1] = array('name' => '', 'title' => '');
				if($this->id || $this->tag || $this->date || $this->year || $this->month) $bread[1]['url'] = http_url::getUrl().'/'.$iso.($this->template->is_amp() ? '/amp/':'/').$this->controller.'/';
			}

			if($model && $this->id) {
				if($this->id_parent) {
					$dataPage = $model->getShortData(
						array(
							'context' => 'product',
							'select' => $this->id
						),
						$current
					);
					if($dataPage) {
						$dataPage = $this->data->parseData($dataPage,$model,$current,false,true);
						if(method_exists($model,'getParents')) {
							$ids = $model->getParents($this->id_parent);
							if(count($ids) > 1) {
								$ids = array_reverse($ids);
								foreach($ids as $id) {
									$data = $model->getShortData(
										array(
											'context' => 'category',
											'select' => $id
										),
										$current
									);
									if($data) {
										$data = $this->data->parseData($data,$model,$current,false,true);
										$bread[] = array(
											'name' => $data[0]['name'],
											'url' => $data[0]['url'],
											'title' => $data[0]['name']
										);
									}
								}
							}
						}
						$bread[] = array('name' => $dataPage[0]['name']);
					}
				}
				else {
					$dataPage = $model->getShortData(
						array(
							'context' => 'all',
							'select' => $this->id
						),
						$current
					);
					if($dataPage) {
						$dataPage = $this->data->parseData($dataPage,$model,$current,false,true);
						if(method_exists($model,'getParents')) {
							$ids = $model->getParents($this->id);
							if(count($ids) > 1) {
								array_shift($ids);
								$ids = array_reverse($ids);
								$data = $model->getShortData(
									array(
										'context' => 'all',
										'select' => $ids
									),
									$current
								);
								if($data) {
									$data = $this->data->parseData($data,$model,$current,false,true);
									foreach($data as $item) {
										$bread[] = array(
											'name' => $item['name'],
											'url' => $item['url'],
											'title' => $item['name']
										);
									}
								}
							}
						}
						$bread[] = array('name' => $dataPage[0]['name']);
					}
				}
			}

			$this->template->assign('bread',$bread);
		}
	}
}
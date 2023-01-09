<?php
/*
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of MAGIX CMS.
# MAGIX CMS, The content management system optimized for users
# Copyright (C) 2008 - 2023 sc-box.com <support@magix-cms.com>
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
    /**
     * @var string $iso
     */
	public string $iso;

    /**
     * @var array $breadcrumbs
     */
    public array $breadcrumbs;

    /**
     * @param string $iso
     */
	public function __construct(string $iso) {
        $this->iso = $iso;
	}

	/**
	 * @param string $iso
	 * @return void
	 */
	/*public function getBreadcrumb(string $iso) {
		$current = $this->modelSystem->setCurrentId();

		$bread = [];
		$bread[] = ['name' => ''];
		if($this->controller !== 'home') {
			$bread[0]['url'] = http_url::getUrl().'/'.$iso.($this->template->is_amp() ? '/amp/':'/');
			$bread[0]['title'] = '';
		}

		if(in_array($this->controller, ['about','pages','catalog','news'])) {
			$model = 'frontend_model_'.$this->controller;
			$model = new $model($this->template);
			if($this->controller !== 'pages') {
				$bread[1] = ['name' => '', 'title' => ''];
				if($this->id || $this->tag || $this->date || $this->year || $this->month) $bread[1]['url'] = http_url::getUrl().'/'.$iso.($this->template->is_amp() ? '/amp/':'/').$this->controller.'/';
			}

			if($model && $this->id) {
				if($this->id_parent) {
					$dataPage = $model->getShortData(
						['context' => 'product', 'select' => $this->id],
						$current
					);
					if($dataPage) {
						$dataPage = $this->data->parseData($dataPage,$model,$current,[],true);
						if(method_exists($model,'getParents')) {
							$ids = $model->getParents($this->id_parent);
							if(count($ids) > 1) {
								$ids = array_reverse($ids);
								foreach($ids as $id) {
									$data = $model->getShortData(
										['context' => 'category', 'select' => $id],
										$current
									);
									if($data) {
										$data = $this->data->parseData($data,$model,$current,[],true);
										$bread[] = [
											'name' => $data[0]['name'],
											'url' => $data[0]['url'],
											'title' => $data[0]['name']
										];
									}
								}
							}
						}
						$bread[] = ['name' => $dataPage[0]['name']];
					}
				}
				else {
					$dataPage = $model->getShortData(
						['context' => 'all', 'select' => $this->id],
						$current
					);
					if($dataPage) {
						$dataPage = $this->data->parseData($dataPage,$model,$current,[],true);
						if(method_exists($model,'getParents')) {
							$ids = $model->getParents($this->id);
							if(count($ids) > 1) {
								array_shift($ids);
								$ids = array_reverse($ids);
								$data = $model->getShortData(
									['context' => 'all', 'select' => $ids],
									$current
								);
								if($data) {
									$data = $this->data->parseData($data,$model,$current,[],true);
									foreach($data as $item) {
										$bread[] = [
											'name' => $item['name'],
											'url' => $item['url'],
											'title' => $item['name']
										];
									}
								}
							}
						}
						$bread[] = ['name' => $dataPage[0]['name']];
					}
				}
			}
		}

		$this->template->assign('bread',$bread);
	}*/

    /**
     * @param string $name
     * @param string $url
     * @param string $title
     * @param int|null $key
     * @return void
     */
    public function addItem(string $name, string $url = null, string $title = '', int $key = null) {
        $item = ['name' => $name];
        if($url !== null) {
            $item['title'] = $title;
            $item['url'] = $url;
        }
        if(!empty($key)) array_splice($this->breadcrumbs, $key, 0, $item);
        else $this->breadcrumbs[] = $item;
    }

    /**
     * @return array
     */
    public function getBreadcrumb(): array {
        return $this->breadcrumbs;
    }
}
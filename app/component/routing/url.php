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
class component_routing_url {

    protected $dateFormat, $setBuildUrl, $amp;

	/**
	 * @var filesystem_makefile $makeFile
	 */
	protected filesystem_makefile $makeFile;

    public function __construct() {
        $this->setBuildUrl = new http_url();
		$this->amp = http_request::isGet('amp') ? true : false;
    }

    /**
     * @param $data
     * @return string
     * @throws Exception
     */
	public function getBuildUrl($data) {
        if(isset($this->dateFormat)) $this->dateFormat = new date_dateformat();
        if(is_array($data)) {
            $iso = $data['iso'];
            $type = $data['type'];
            switch($type){
                case 'pages':
                case 'about':
					$url = '/'.$iso.($this->amp ? '/amp' : '').'/'.$type.'/'.$data['id'].'-'.$data['url'].'/';
                    break;
                case 'category':
                    $url = '/'.$iso.($this->amp ? '/amp' : '').'/catalog/'.$data['id'].'-'.$data['url'].'/';
                    break;
                case 'product':
                    if(isset($data['id_parent']) && isset($data['url_parent'])) {
                        $url = '/' . $iso .($this->amp ? '/amp' : ''). '/catalog/' . $data['id_parent'] . '-' . $data['url_parent'] . '/' . $data['id'] . '-' . $data['url'] . '/';
                    }
                    break;
                case 'news':
                    $url = '/'.$iso.($this->amp ? '/amp' : '').'/news/'.$this->dateFormat->dateToDefaultFormat($data['date']).'/'.$data['id'].'-'.$data['url'].'/';
                    break;
                case 'date':
                    $url = '/'.$iso.($this->amp ? '/amp' : '').'/news/'.$data['year'].'/'.(isset($data['month']) ? ($data['month'] < 10 ? '0':'').$data['month'].'/' : '');
                    break;
                case 'tag':
                    $url = '/'.$iso.($this->amp ? '/amp' : '').'/news/tag/'.$data['id'].'-'.$this->setBuildUrl->clean($data['url']).'/';
                    break;
            }
            return $url;
        }
    }

	/**
	 * Retourne le chemin depuis la racine
	 * @param string $pathUpload
	 * @return string
	 */
	public function basePath(string $pathUpload): string {
		return component_core_system::basePath().$pathUpload;
	}

	/**
	 * Return path string for upload
	 * @param string $path
	 * @param bool $basePath
	 * @return string
	 */
	public function dirUpload(string $path, bool $basePath): string {
        if(!isset($this->makeFile)) $this->makeFile = new filesystem_makefile();
		$path = rtrim($path,DS).DS;
		if(!file_exists($this->basePath($path))) $this->makeFile->mkdir($this->basePath($path));
		return $basePath ? $this->basePath($path) : $path;
	}

	/**
	 * Return path collection for upload
	 * @param string $root
	 * @param array $directories
	 * @param bool $basePath
	 * @return array
	 */
	public function dirUploadCollection(string $root, array $directories = [], bool $basePath = true): array {
		$url = [];
		if(!empty($directories)) {
			foreach($directories as $dir) {
				$path = rtrim($root,DS).DS;
				$url[] = $this->dirUpload($path.$dir,$basePath);
			}
		}
		return $url;
	}
}
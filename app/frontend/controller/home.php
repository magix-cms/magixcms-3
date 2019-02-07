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
class frontend_controller_home extends frontend_db_home
{
    /**
     * @var
     */
    protected $template,$data;
    /**
     * @var bool
     */
    public $lang;

    /**
	 * @param stdClass $t
     * frontend_controller_home constructor.
     */
    public function __construct($t = null){
        $this->template = $t ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this, $this->template);
		$this->lang = $this->template->lang;
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
		$string_format = new component_format_string();
        $collection = $this->getItems('page',array('iso'=>$this->lang),'one',false);
        return array(
            'name' => $collection['title_page'],
            'content' => $collection['content_page'],
			'seo' => array(
				'title' => $collection['seo_title_page'] ? $collection['seo_title_page'] : ($collection['title_page'] ? $collection['title_page'] : $this->template->getConfigVars('home')),
				'description' => $collection['seo_desc_page'] ? $collection['seo_desc_page'] : ($collection['content_page'] ? $string_format->truncate(strip_tags($collection['content_page'])) : ($collection['title_page'] ? $collection['title_page'] : $this->template->getConfigVars('home')))
			)
        );
    }

    /**
     *
     */
    public function run(){
		$this->template->assign('home',$this->getBuildItems());
		$this->template->display('home/index.tpl');
    }
}
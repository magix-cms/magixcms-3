<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2015 magix-cms.com <support@magix-cms.com>
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
 * Smarty {widget_lang_data} function plugin
 *
 * Type:     function
 * Name:     widget_lang_data
 * Date:     24/03/2015
 * Update:   31/08/2017
 * Output:
 * @author   Gerits Aurélien (http://www.magix-cms.com)
 * @link
 * @version  1.0
 * @param $params
 * @param $template
 * @return string
 */
function smarty_function_widget_lang_data($params, $template)
{
    $collectionsLang = new component_collections_language();

    // *** Catch location var
    $iso_current = http_request::isGet('strLangue');

    // *** Load SQL DATA
	if (!$iso_current) {
		$default = $collectionsLang->fetchData(array('context'=>'unique','type'=>'default'));
		$template->assign('defaultLang',$default);
	}

    $data = $collectionsLang->fetchData(array('context'=>'all','type'=>'active'));
    $assign = isset($params['assign']) ? $params['assign'] : 'data';
    $template->assign($assign,$data);
}
?>
<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Magix CMS.
# Magix CMS, a CMS optimized for SEO
# Copyright (C) 2010 - 2011  Gerits Aurelien <aurelien@magix-cms.com>
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
/**
 * MAGIX CMS
 * @category   extends 
 * @package    Smarty
 * @subpackage function
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2017 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    plugin version
 * @author Gérits Aurélien <aurelien@magix-cms.com> <aurelien@magix-dev.be>
 *
 */
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {widget_cms_data} function plugin
 *
 * Type:     function
 * Name:     widget_cms_data
 * Date:     29/12/2012
 * Update:   06/09/2017
 * @author   Gerits Aurélien (http://www.magix-cms.com)
 * @version  3.0
 * @param array
 * @param Smarty
 * @return string
 *
 * display parent
 * {widget_cms_data
    conf = [
        'context' => 'parent',
        'limit'=>1
    ]
    assign="pages"
  }
 * display all (parent => child) but with select
 {widget_cms_data
    conf = [
        'context' => 'all',
        select' => ["fr" => "31"]
    ]
    assign="pages"
  }
 *
 *  Override :
 * {widget_cms_data
        conf = [
            'context' => 'parent',
            'limit'=>1,
            'plugins' => [
                'override'  => 'plugins_test_public',
                'item' => [
                    'published_pages'  =>  'published_pages'
                ]
            ]
        ]
        assign="pages"
    }
 */
function smarty_function_widget_cms_data ($params, $smarty)
{
	if(!empty($params)) {
		$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
		$modelSystem = new frontend_model_core($modelTemplate);
		$modelPages = new frontend_model_pages($modelTemplate);

		// Set and load data
		$current  = $modelSystem->setCurrentId();
		$conf     = (is_array($params['conf'])) ? $params['conf'] : array();
		$override = $params['conf']['plugins']['override'] ? $params['conf']['plugins']['override'] : '';
		$data     = (isset($params['datatype']) && $params['datatype'] === 'short') ?  $modelPages->getShortData($conf,$current) : $modelPages->getData($conf,$current,$override);
		$newRow   = (is_array($params['conf']['plugins']['item'])) ? $params['conf']['plugins']['item'] : array();

		$smarty->assign(isset($params['assign']) ? $params['assign'] : 'data',$modelPages->parseData($data,$current,$newRow,(isset($params['datatype']) && $params['datatype'] === 'short')));
	}
}
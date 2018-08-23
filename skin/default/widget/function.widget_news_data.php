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

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# -- END LICENSE BLOCK -----------------------------------
/**
 * MAGIX CMS
 * @category   extends 
 * @package    Smarty
 * @subpackage function
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2013 Gerits Aurelien,
 * http://www.magix-cms.com, http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    plugin version
 * @author Gérits Aurélien <aurelien@magix-cms.com> <aurelien@magix-dev.be>
 *
 */
/**
 * Smarty plugin
 * @package     Smarty
 * @subpackage  plugins
 * Type:        function
 * Name:        widget_news_display
 * date:        25/12/2013
 * Update:   06/09/2017
 * @author   Gerits Aurélien (http://www.magix-cms.com)
 * @version  3.0
 * @param array
 * @param Smarty
 * @return string
 *
 * {widget_news_data
    conf = [
        'context' => 'all',
        'limit'=>1
        ]
        assign="pages"
    }

     *
     *  Override :
     * {widget_news_data
            conf = [
                'context' => 'all',
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
 * Example with Filter
    {widget_news_data
        conf = [
            'context' => 'all',
            'filter' => ['year'=>{$smarty.get.year},'month'=>{$smarty.get.month}]
        ]
        assign="pages"
    }
 * Example with tag
    {widget_news_data
        conf = [
            'context'   => 'tag',
            'select'    =>  {$smarty.get.tag}
        ]
        assign="pages"
    }
 * Example with Tags
    {widget_news_data
        conf = [
            'context'   => 'tags'
        ]
        assign="tags"
    }
 */
function smarty_function_widget_news_data($params, $template)
{
	if(!empty($params)) {
		$ModelNews = new frontend_model_news();
		$modelSystem = new frontend_model_core();

		// Set and load data
		$current  = $modelSystem->setCurrentId();
		$conf     = (is_array($params['conf'])) ? $params['conf'] : array();
		$override = $params['conf']['plugins']['override'] ? $params['conf']['plugins']['override'] : '';
		$data     = $ModelNews->getData($conf,$current,$override);
		$newRow   = (is_array($params['conf']['plugins']['item'])) ? $params['conf']['plugins']['item'] : array();
		$current  = $current;

		// Set Pagination
		/*$pagination =   array();
		if (isset($data['total']) AND isset($data['limit'])) {
			$pagination  =
				$ModelPager->setPaginationData(
					$data['total'],
					$data['limit'],
					'/'.$current['lang']['iso'].$ModelRewrite->mod_news_lang($current['lang']['iso']),
					$current['news']['pagination']['id'],
					'/'
				);
			unset($data['total']);
			unset($data['limit']);
		}*/
		// Format data
		$items = array();
		if ($data != null) {
			foreach ($data as $row)
			{
				if (isset($row['id_news']) OR isset($row['id_tag'])) {
					$items[] = $ModelNews->setItemData($row,$current,$newRow);
				}
			}
		}
		$assign = isset($params['assign']) ? $params['assign'] : 'data';
		$template->assign($assign,$items);

		//$assignPager = isset($params['assignPagination']) ? $params['assignPagination'] : 'paginationData';
		//$template->assign($assignPager,$pagination);
	}
}
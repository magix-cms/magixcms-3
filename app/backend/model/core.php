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
 * Date: 4/02/14
 * Time: 00:10
 * License: Dual licensed under the MIT or GPL Version
 */
class backend_model_core{
    protected $setUrl;
    public function __construct()
    {
        $this->setUrl = new http_url();
    }

    /**
     * Retourne un tableaux contenant les identifiant actif (int OR string)
     * @access public
     * @static
     * @param array $setRouter
     * @return array
     */
    public function setCurrentId (array $setRouter)
    {
        $ModelTemplate  =   new backend_model_template();
        //$HelperClean    =   new form_inputFilter();
        $inputEscape    =   new form_inputEscape();
        $current = array();

        $current['news']['record']['id'] = null;
        if ($setRouter['news']['idnews'])
            $current['news']['record']['id']    =   $inputEscape->alphaNumeric($setRouter['news']['idnews']);

        $current['news']['pagination']['id'] = 1;
        if ($setRouter['news']['page'])
            $current['news']['pagination']['id']    =   $inputEscape->numeric($setRouter['news']['page']);

        $current['news']['tag']['id'] = null;
        if ($setRouter['news']['tag'])
            $current['news']['tag']['id']    =   $inputEscape->tagClean($setRouter['news']['tag']);

        $current['cms']['record']['id'] = null;
        if ($setRouter['cms']['getidpage'])
            $current['cms']['record']['id']    =   $inputEscape->numeric($setRouter['cms']['getidpage']);

        $current['cms']['parent']['id'] = null;
        if ($setRouter['cms']['getidpage_p'])
            $current['cms']['parent']['id']    =   $inputEscape->numeric($setRouter['cms']['getidpage_p']);

        $current['catalog']['category']['id'] = null;
        if ($setRouter['catalog']['idclc'])
            $current['catalog']['category']['id']    =   $inputEscape->numeric($setRouter['catalog']['idclc']);

        $current['catalog']['subcategory']['id'] = null;
        if ($setRouter['catalog']['idcls'])
            $current['catalog']['subcategory']['id']    =   $inputEscape->numeric($setRouter['catalog']['idcls']);

        $current['catalog']['product']['id'] = null;
        if ($setRouter['catalog']['idproduct'])
            $current['catalog']['product']['id']    =   $inputEscape->numeric($setRouter['catalog']['idproduct']);

        $current['lang']['iso']  = $ModelTemplate->currentLanguage();


        return $current;

    }

    /**
     * @param $controller
     * @return string
     */
    public function setUrlController($controller){
        return $this->setUrl->getUrl().PATHADMIN.'/index.php?controller'.$controller;
    }
}
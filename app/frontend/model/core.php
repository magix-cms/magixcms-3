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
class frontend_model_core{
    /**
     * Retourne un tableaux contenant les identifiant actif (int OR string)
     * @access public
     * @static
     * @return array
     * @internal param array $setRouter
     */
    public function setCurrentId ()
    {
        $ModelTemplate  =   new frontend_model_template();
        //$HelperClean    =   new form_inputFilter();
        $formClean = new form_inputEscape();
        $current = array();
        $current['controller']['id'] = null;
        if (http_request::isGet('controller')){
            $current['controller']['name'] = $formClean->simpleClean($_GET['controller']);
        }
        if (http_request::isGet('id')){
            $current['controller']['id'] = $formClean->numeric($_GET['id']);
        }
        if (http_request::isGet('id_parent')){
            $current['controller']['id_parent'] = $formClean->numeric($_GET['id_parent']);
        }

        $current['lang']['iso']  = $ModelTemplate->currentLanguage();

        return $current;
    }
}
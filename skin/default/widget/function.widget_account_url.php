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
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {widget_profil_url} function plugin
 *
 * Type:     function
 * Name:     widget_profil_url
 * Purpose:
 * USAGE:
    {widget_profil_url}
 * Output:   
 * @link 	http://www.magix-dev.be
 * @author   Gerits Aurelien
 * @version  1.5
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_widget_profil_url($params, $template){
    plugins_Autoloader::register(); //chargement des function plugins
    $member = new plugins_profil_public();
    $template->assign('hashurl',$member->hashUrl());
    $modelSystem = new magixglobal_model_system();
    if($_GET['magixmod']!= 'profil') {
        frontend_model_smarty::getInstance()->configLoad(
            $modelSystem->base_path() . 'plugins/profil/i18n/public_local_' . frontend_model_template::current_Language() . '.conf'
        );
    }
}
<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2018 magix-cms.com <support@magix-cms.com>
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
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {widget_profil_session} function plugin
 *
 * Type: function
 * Name: widget_profil_session
 * Purpose:
 * USAGE: {account_data}
 * Output:   
 * @link http://www.magix-dev.be
 * @author Gerits Aurelien
 * @version 1.5
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_account_data($params, $template){
    $session = new http_session();
	$member = new plugins_account_public();

	$modelTemplate = new frontend_model_template();
	$modelTemplate->addConfigFile(
		array(component_core_system::basePath().'/plugins/account/i18n/'),
		array('public_local_'),
		false
	);
	$modelTemplate->configLoad();

    $session->start('mc_account');
    $session->token('token_ac');
    $array_sess = array(
        'id_account'   => $_SESSION['id_account'],
        'keyuniqid_ac' => $_SESSION['keyuniqid_ac'],
        'email_ac'     => $_SESSION['email_ac']
	);
    $session->run($array_sess);
    //$session->debug();

	$template->assign('hashpass',$session->token('token_ac'));
	$template->assign('hashurl',$member->hashUrl());
	$template->assign('account',$member->accountData());
}
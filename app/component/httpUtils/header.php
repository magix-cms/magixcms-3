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
 * Date: 13/12/13
 * Time: 18:15
 * License: Dual licensed under the MIT or GPL Version
 */
class component_httpUtils_header{
    protected $template;
    /**
     * @var bool
     */
    public $getHeader;
    /**
     * Constructor
     */
    public function __construct(){
        $this->template = new frontend_model_template();
        if(http_request::isGet('getHeader')){
            $this->getHeader = form_inputFilter::isNumeric($_GET['getHeader']);
        }
    }

    /**
     * @var
     */
    private static $header;

    /**
     * @static
     * @throws Exception
     * @return db_layer
     */
    private static function init(){
        if(class_exists('http_header')){
            self::$header = new http_header();
            if(self::$header instanceof http_header){
                return self::$header;
            }else{
                throw new Exception('Error header');
            }
        }else{
            throw new Exception('Class http_header is not exist');
        }
    }

    /**
     * Retourne l'entête suivant le code d'erreur
     * @param $code
     */
    private function getStatus($code){
        self::init()->getStatus($code);
    }
    /**
     * @param $http_error
     * @return string
     */
    private function setTitleHeader($http_error){
        if(isset($http_error)){
            switch($http_error){
                case 404:
                    $message = $this->template->getConfigVars('title_status_404');
                    self::getStatus(404);
                    $status = self::init()->setStatusCode(404,$message);
                    break;
                case 403:
                    $message = $this->template->getConfigVars('title_status_403');
                    self::getStatus(403);
                    $status = self::init()->setStatusCode(403,$message);
                    break;
            }
            return $status;
        }
    }

    /**
     * Configuration du texte à retourner suivant le status du code d'erreur
     * @param $http_error
     * @return mixed
     */
    private function setTxtHeader($http_error){
        if(isset($http_error)){
            switch($http_error){
                case 404:
                    $message = $this->template->getConfigVars('txt_status_404');
                    $status = self::init()->setStatusCode(404,$message);
                    break;
                case 403:
                    $message = $this->template->getConfigVars('txt_status_403');
                    $status = self::init()->setStatusCode(403,$message);
                    break;
            }
            return $status;
        }
    }
    /**
     * @param $http_error
     * @return string
     */
    public function getTitleHeader($http_error){
        return self::setTitleHeader($http_error);
    }

    /**
     * @param $http_error
     * @return mixed
     */
    public function getTxtHeader($http_error){
        return self::setTxtHeader($http_error);
    }
}
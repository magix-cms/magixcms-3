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
class backend_controller_dashboard{
    protected $router,$template,$employee;
    public $http_error;
    public function __construct(){
        $this->employee = new backend_controller_employee();
        $this->template = new backend_model_template();
        if(http_request::isGet('http_error')){
            $this->http_error = form_inputFilter::isAlphaNumeric($_GET['http_error']);
        }
    }
    /**
     * @param $id
     * @return array
     */
    public function setReleaseData()
    {
        $basePath = component_core_system::basePath().DIRECTORY_SEPARATOR;
        $XMLFiles = $basePath . 'release.xml';
        if (file_exists($XMLFiles)) {
            try {
                if ($stream = fopen($XMLFiles, 'r')) {
                    $streamData = stream_get_contents($stream, -1, 0);
                    $streamData = urldecode($streamData);
                    $xml = simplexml_load_string($streamData, null, LIBXML_NOCDATA);
                    $newData = array();
                    foreach ($xml->children() as $item => $value) {
                        $newData[$item] = $value->__toString();
                    }
                    fclose($stream);
                    return $newData;
                }
            } catch (Exception $e) {
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
            }
        }
    }

    public function run(){
        /**
         * Initalisation du système d'entête
         */
        $header = new component_httpUtils_header($this->template);
        if(isset($this->http_error)){
            $this->template->assign(
                'getTitleHeader',
                $header->getTitleHeader(
                    $this->http_error
                ),
                true
            );
            $this->template->assign(
                'getTxtHeader',
                $header->getTxtHeader(
                    $this->http_error
                ),
                true
            );

            $this->template->display('error/index.tpl');
        }else{
            $this->template->assign('getReleaseData',$this->setReleaseData());
            $this->employee->getItemsEmployee();
            $this->template->display('dashboard/index.tpl');
            // Create a Router
            /*$this->router->get('/', function(){
                print 'test';
            });*/
        }
    }
}
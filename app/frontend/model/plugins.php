<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2019 magix-cms.com <support@magix-cms.com>
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
class frontend_model_plugins {
    protected $template, $controller_name, $plugin ,$collectionLanguage, $DBPlugins;

	/**
	 * frontend_model_plugins constructor.
	 * @param null|frontend_model_template $t
	 */
    public function __construct($t = null) {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->DBPlugins = new frontend_db_plugins();
        if(http_request::isGet('controller')) $this->controller_name = $formClean->simpleClean($_GET['controller']);
        if(http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
        //$this->dbPlugins = new backend_db_plugins();
        //$this->data = new backend_model_data($this);
        $this->collectionLanguage = new component_collections_language();
    }

    /**
     * Check whenever the plugin is installed or not
     * @param string $name
     * @return mixed|string|null
     * @throws Exception
     */
    public function isInstalled(string $name) {
        return $this->DBPlugins->fetchData(['context' => 'one','type' => 'installed'],['name' => $name]);
    }

    /**
     * @param $routes
     * @param $template
     */
    public function addConfigDir($routes, $template = null){
    	$template = $template === null ? $this->template : $template;
        if(isset($this->controller_name)){
            $setConfigPath = component_core_system::basePath().'/'.$routes.'/'.$this->controller_name.'/i18n/';
            if(file_exists($setConfigPath)){
                $template->addConfigFile(
                    array(
                        component_core_system::basePath().'/'.$routes.'/'.$this->controller_name.'/i18n/'
                    ),
                    array(
                        'public_local_',
                    )
                    ,false
                );
            }
        }
    }

    /**
     * @param $className
     * @return mixed
     */
    public function getCallClass($className){
        try{
            $class =  new $className;
            if($class instanceof $className){
                return $class;
            }else{
                throw new Exception('not instantiate the class: '.$className);
            }
        } catch (Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * load the plugin into the webservice
     * @param $retrieve
     */
    public function getWebserviceItems($retrieve){
        if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$retrieve.DIRECTORY_SEPARATOR.'webservice.php')) {
            $class = 'plugins_' . $retrieve . '_webservice';
            if (class_exists($class)) {
                //Si la méthode sitemap existe
                if (method_exists($class, 'run')) {

                    $executeClass = $this->getCallClass($class);
                    $executeClass->run();
                }
            }
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function setWebserviceItems(){
        $data =  $this->DBPlugins->fetchData(array('context'=>'all','type'=>'list'));
        $newData = array();
        foreach($data as $item){
            if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item['name'].DIRECTORY_SEPARATOR.'webservice.php')) {
                $class = 'plugins_' . $item['name'] . '_webservice';
                if (class_exists($class)) {
                    //Si la méthode run existe
                    if (method_exists($class, 'run')) {
                        $newData[] = $item['name'];
                    }
                }
            }
        }
        return $newData;
    }
}
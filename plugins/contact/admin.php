<?php
include_once ('db.php');
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
 * MAGIX CMS
 * @category   Contact
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2018 Gerits Aurelien,
 * http://www.magix-dev.be, http://www.magix-cms.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    3.0
 * @create	  10-04-2010
 * @Update    04-07-2018
 * @author Gérits Aurélien <contact@magix-dev.be>
 * @contributor Salvatore di Salvo
 * @name plugins_contact_admin
 * Administration of Contact plugin
 */
class plugins_contact_admin extends plugins_contact_db
{
	/**
	 * @var object
	 */
    protected
		$controller,
		$data,
		$template,
		$message,
		$plugins,
		$xml,
		$sitemap,
		$modelLanguage,
		$collectionLanguage,
		$header;

	/**
	 * Globales variables
	 * @var integer $edit
	 * @var string $action
	 * @var string $tabs
	 */
	public
		$edit,
		$action,
		$tabs;

	/**
	 * Plugin variables
	 * @var array $content
	 * @var integer $id_contact
	 * @var string $email_contact
	 * @var integer $address_required
	 * @var integer $address_enabled
	 * @var integer $id_config
	 */
    public
		$content,
		$id_contact,
		$email_contact,
		$address_required,
		$address_enabled,
		$id_config;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct(){
        $this->template = new backend_model_template();
        $this->plugins = new backend_controller_plugins();
        $this->message = new component_core_message($this->template);
        $this->xml = new xml_sitemap();
        $this->sitemap = new backend_model_sitemap($this->template);
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->data = new backend_model_data($this);
        $this->header = new http_header();

		$formClean = new form_inputEscape();

        // --- GET
        if(http_request::isGet('controller')) {
            $this->controller = $formClean->simpleClean($_GET['controller']);
        }
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }

        if (http_request::isPost('content')) {
            $array = $_POST['content'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                    $array[$key][$k] = $formClean->simpleClean($v);
                }
            }
            $this->content = $array;
        }

        // --- ADD or EDIT
        if (http_request::isPost('id')) {
            $this->id_contact = (int)$formClean->simpleClean($_POST['id']);
        }
        if (http_request::isPost('email_contact')) {
            $this->email_contact = $formClean->simpleClean($_POST['email_contact']);
        }

        if (http_request::isPost('id_config')) {
            $this->id_config = $formClean->simpleClean($_POST['id_config']);
        }
        if (http_request::isPost('address_enabled')) {
            $this->address_enabled = $formClean->simpleClean($_POST['address_enabled']);
        }
        if (http_request::isPost('address_required')) {
            $this->address_required = $formClean->simpleClean($_POST['address_required']);
        }
    }

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('contact_plugin');
	}

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param string|int|null $id
     * @param string $context
     * @param boolean $assign
     * @return mixed
     */
    private function getItems($type, $id = null, $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

    /**
     * @param $data
     * @return array
     */
    private function setItemContentData($data){

        $arr = array();
        foreach ($data as $page) {

            if (!array_key_exists($page['id_contact'], $arr)) {
                $arr[$page['id_contact']] = array();
                $arr[$page['id_contact']]['id_contact'] = $page['id_contact'];
                $arr[$page['id_contact']]['email_contact'] = $page['email_contact'];
            }
            $arr[$page['id_contact']]['content'][$page['id_lang']] = array(
                'id_lang'          => $page['id_lang'],
                'active_contact'   => $page['active_contact']
            );
        }
        return $arr;
    }

    /**
     * Insert data
     * @param $config
     */
    private function add($config)
    {
        switch ($config['type']) {
            case 'contact':
            case 'content':
                parent::insert(
                    array('type' => $config['type']),
                    $config['data']
                );
                break;
        }
    }

	/**
	 * Update data
	 * @param array $config
	 */
    private function upd($config)
    {
        switch ($config['type']) {
            case 'contact':
            case 'content':
			case 'config':
                parent::update(
                    array('type' => $config['type']),
                    $config['data']
                );
                break;
        }
    }

	/**
	 * Delete a record
	 * @param $config
	 */
	private function del($config){
		switch($config['type']){
			case 'contact':
				parent::delete(
					array('type' => $config['type']),
					$config['data']
				);
				$this->message->json_post_response(true,'delete',$config['data']);
				break;
		}
	}

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->content)) {
                        $this->add(
                            array(
                                'type' => 'contact',
                                'data' => array(
                                    'email_contact' => $this->email_contact
                                )
                            )
                        );

                        $contact = $this->getItems('root',null,'one',false);

                        if ($contact['id_contact']) {
                            foreach ($this->content as $lang => $content) {
								$content['id_lang'] = $lang;
								$content['id_contact'] = $contact['id_contact'];

								$this->add(array(
									'type' => 'content',
									'data' => $content
								));
							}

                            $this->message->json_post_response(true,'add_redirect');
                        }
                    }
                    else {
                        $this->modelLanguage->getLanguage();
                        $this->template->display('add.tpl');
                    }
                    break;
                case 'edit':
                    if (isset($this->id_contact)) {
						$this->upd(
							array(
								'type' => 'contact',
								'data' => array(
									'id_pages' => $this->id_contact,
									'email_contact' => $this->email_contact
								)
							)
						);

						foreach ($this->content as $lang => $content) {
							$content['id_lang'] = $lang;
							$content['id_contact'] = $this->id_contact;

							$contentContact = $this->getItems('content',array('id_contact' => $this->id_contact, 'id_lang' => $lang),'one',false);

							if($contentContact != null) {
								$this->upd(
									array(
										'type' => 'content',
										'data' => $content
									)
								);
							}
						}

                        $this->message->json_post_response(true, 'update', array('result'=>$this->id_contact));
                    }
                    elseif(isset($this->id_config)) {
                    	$data['id_config'] = $this->id_config;
						$data['address_enabled'] = (!isset($this->address_enabled) ? 0 : 1);
						$data['address_required'] = (!isset($this->address_required) ? 0 : 1);
						$this->upd(array(
							'type' => 'config',
							'data' => $data
						));
                        $this->message->json_post_response(true, 'update', array('result'=>$this->id_config));
                    }
                    else{
                        $this->modelLanguage->getLanguage();
                        $setEditData = $this->setItemContentData($this->getItems('data',$this->edit,'all',false));
                        $this->template->assign('contact',$setEditData[$this->edit]);
                        $this->template->display('edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id_contact)) {
                        $this->del(array(
							'type' => 'contact',
							'data' => array(
								'id' => $this->id_contact
							)
						));
                    }
                    break;
            }
        }
        else {
            $this->modelLanguage->getLanguage();
            $defaultLanguage = $this->collectionLanguage->fetchData(array('context'=>'one','type'=>'default'));
            $this->getItems('contact',array('default_lang'=>$defaultLanguage['id_lang']),'all');
            $this->data->getScheme(array('mc_contact','mc_contact_content'),array('id_contact','email_contact','active_contact'));
            $this->getItems('config',null,'one','config');
            $this->template->display('index.tpl');
        }
    }

	/**
	 * @return array|bool
	 */
	public function menu_mode()
	{
		return class_exists('plugins_gmap_public') ? array('simple','dropdown') : false;
	}
}
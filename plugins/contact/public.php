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
 * @name plugins_contact_public
 * Frontend of Contact plugin
 */
class plugins_contact_public extends plugins_contact_db
{
	/**
	 * @var object
	 */
    protected $template,
		$header,
		$message,
		$data,
		$getlang,
		$moreinfo,
		$sanitize,
		$mail,
		$origin,
		$modelDomain,
		$config,
		$settings;

	/**
	 * @var array
	 */
    public $msg;

	/**
	 * @var string
	 */
    public $type;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct()
    {
        $this->template = new frontend_model_template();
        $this->sanitize = new filter_sanitize();
		$this->header = new http_header();
		$this->message = new component_core_message($this->template);
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->mail = new frontend_model_mail('contact');
        $this->modelDomain = new frontend_model_domain($this->template);
		$this->config = $this->getItems('config',null,'one',false);
		$this->settings = new frontend_model_setting();

		$formClean = new form_inputEscape();

		if (http_request::isPost('msg')) {
			$this->msg = $formClean->arrayClean($_POST['msg']);
		}

		if (http_request::isPost('type')) {
			$this->type = $formClean->simpleClean($_POST['type']);
		}

		if(http_request::isGet('__amp_source_origin')) {
			$this->origin = $formClean->simpleClean($_GET['__amp_source_origin']);
		}

		if(http_request::isGet('moreinfo')) {
			$this->moreinfo = $formClean->simpleClean($_GET['moreinfo']);
		}
		elseif (http_request::isPost('moreinfo')) {
			$this->moreinfo = $formClean->simpleClean($_POST['moreinfo']);
		}
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
	 * Retourne le message de notification
	 * @param $type
	 * @param null $subContent
	 * @return string
	 */
	/*private function setNotify($type,$subContent=null){
		$this->template->configLoad();
		switch($type){
			case 'warning':
				$warning = array(
					'empty' =>  $this->template->getConfigVars('fields_empty'),
					'mail'  =>  $this->template->getConfigVars('mail_format')
				);
				$message = $warning[$subContent];
				break;
			case 'success':
				$message = $this->template->getConfigVars('message_send_success');
				break;
			case 'error':
				$error = array(
					'installed'   =>  $this->template->getConfigVars('installed'),
					'configured'  =>  $this->template->getConfigVars('configured')
				);
				$message = sprintf('plugin_error','contact',$error[$subContent]);
				break;
		}

		return array(
			'type'      => $type,
			'content'   => $message
		);
	}*/

	/**
	 * getNotify
	 * @param $type
	 * @param null $subContent
	 */
	private function getNotify($type,$subContent=null){
		if(isset($this->origin)) {
			$domains = $this->modelDomain->getValidDomains();
			$validOrigins = array("https://cdn.ampproject.org");
			foreach ($domains as $domain) {
				$domain['url_subdomain'] = str_replace('www.','',$domain['url_domain']);
				$validOrigins[] = 'https://'.$domain['url_subdomain'].'.cdn.ampproject.org';
				$validOrigins[] = 'https://'.$domain['url_domain'].'.amp.cloudflare.com';
				$validOrigins[] = 'https://'.$domain['url_domain'];
			}
			$this->header->amp_headers($this->origin,$validOrigins,false);
			$this->header->set_json_headers();
			if($type === 'success') {
				print json_encode(array('status'=>'Success'));
			}
			else {
				switch ($type) {
					case 'error':
						$code = 500;
						break;
					case 'warning':
						$code = 400;
						break;
					default:
						$code = 500;
				}
				http_response_code($code);
				print json_encode(array('error'=>$subContent), JSON_FORCE_OBJECT);
			}
		}
		else {
			$this->message->json_post_response($type === 'send',$type);
			//$this->template->assign('message',$this->setNotify($type,$subContent));
			//$this->template->display('contact/notify/message.tpl');
		}
	}

	/**
	 * @param bool $error
	 * @return string
	 */
	private function setTitleMail($error = false){
		if($error) {
			$title = $this->msg['title'];
		}
		else {
			$about = new frontend_model_about($this->template);
			$collection = $about->getCompanyData();
			$subject = $this->template->getConfigVars('subject_contact');
			if($this->type === 'order') {
				$title = $this->template->getConfigVars('order_request');
			}
			else {
				$title = $this->template->getConfigVars('contact_request');
			}
			$website = $collection['name'];
			$title = sprintf($subject,$title,$website);
		}
		return $title;
	}

	/**
	 * @return array
	 */
	public function getContact(){
		$lang = $this->template->getLanguage();
		return $this->getItems('contacts',array('lang' => $lang),'all',false);
	}

	/**
	 * Send mail to the list of contacts
	 */
	protected function send_email() {
		if(!empty($this->msg['email'])) {
			$this->template->configLoad();
			if((empty($this->msg['lastname']) || empty($this->msg['firstname']) || empty($this->msg['email'])) && $this->msg['email'] !== 'error-mail') {
				$this->getNotify('empty');
			}
			elseif(!$this->sanitize->mail($this->msg['email']) && $this->msg['email'] !== 'error-mail') {
				$this->getNotify('email');
			}
			elseif(!empty($this->msg['moreinfo'])) {
				$this->getNotify('error_plugin_configured');
			}
			else {
				if($this->getlang) {
					$contacts = $this->getItems('contacts',array('lang' => $this->getlang),'all',false);
					if($contacts != null) {
						//Initialisation du contenu du message
						$send = false;
						$tpl = $this->type ? $this->type : 'admin';
						$error = false;
						$sender = $this->msg['email'];
						if($this->msg['email'] === 'error-mail') {
							$tpl = 'error';
							$error = true;
							$sender = '';
						}
						foreach ($contacts as $recipient) {
							$isSend = $this->mail->send_email($recipient['email_contact'],$tpl,$this->msg,$this->setTitleMail($error),$sender);
							if(!$send) $send = $isSend;
						}
						if($send)
							$this->getNotify('send');
						else
							$this->getNotify('error_plugin');
					}
					else {
						$this->getNotify('error_plugin_configured');
					}
				}
			}
		}
	}

    /**
     *
     */
    public function run(){
        if(isset($this->msg)) {
        	$this->send_email();
		}
		else {
        	if(isset($this->moreinfo)) $this->template->assign('title',$this->moreinfo);
        	$this->template->assign('address_enabled',$this->config['address_enabled']);
        	$this->template->assign('address_required',$this->config['address_required']);
			$this->template->display('contact/index.tpl');
		}
    }

	/**
	 * @param $iso
	 * @return array
	 * @throws Exception
	 */
	public function submenu($iso)
	{
		if(class_exists('plugins_gmap_public')) {
            $this->template->addConfigFile(
                array(component_core_system::basePath().'/plugins/gmap/i18n/'),
                array('public_local_'),
                false
            );
            $this->template->configLoad();

			return array(array(
				'name' => $this->template->getConfigVars('gmap'),
				'title' => $this->template->getConfigVars('gmap'),
				'url' => '/'.$iso.'/gmap/'
			));
		}
    }
}
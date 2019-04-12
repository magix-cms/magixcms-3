<?php
include_once ('db.php');
/**
 * Class plugins_test_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_contact_public extends plugins_contact_db
{
	/**
	 * @var object
	 */
    protected $template,
		$header,
		$data,
		$getlang,
		$moreinfo,
		$sanitize,
		$mail,
		$origin,
		$modelDomain,
		$config,
		$settings,
		$amp_available = true;

	/**
	 * @var array
	 */
    public $msg;

	/**
	 * @var string
	 */
    public $type,$amp;

    /**
     * frontend_controller_home constructor.
	 * @param stdClass $t
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->sanitize = new filter_sanitize();
		$this->header = new http_header();
        $this->data = new frontend_model_data($this,$this->template);
		$this->getlang = $this->template->lang;
        $this->mail = new frontend_model_mail('contact');
        $this->modelDomain = new frontend_model_domain($this->template);
		$this->config = $this->getItems('config',null,'one',false);
		$this->settings = new frontend_model_setting();
        $this->amp = http_request::isGet('amp') ? true : false;

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
	private function setNotify($type,$subContent=null){
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
	}

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
			$this->template->assign('message',$this->setNotify($type,$subContent));
			$this->template->display('contact/notify/message.tpl');
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
		return $this->getItems('contacts',array('lang' => $this->getlang),'all',false);
	}

	/**
	 * Envoi du mail
	 * Si return true retourne success.tpl
	 * sinon retourne empty.tpl
	 */
	protected function send_email() {
		if(!empty($this->msg['email'])) {
			$this->template->configLoad();
			if((empty($this->msg['lastname']) || empty($this->msg['firstname']) || empty($this->msg['email'])) && $this->msg['email'] !== 'error-mail') {
				$this->getNotify('warning','empty');
			}
			elseif(!$this->sanitize->mail($this->msg['email']) && $this->msg['email'] !== 'error-mail') {
				$this->getNotify('warning','mail');
			}
			elseif(!empty($this->msg['moreinfo'])) {
				$this->getNotify('error','configured');
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
							$isSend = $this->mail->send_email($recipient['mail_contact'],$tpl,$this->msg,$this->setTitleMail($error),$sender);
							if(!$send) $send = $isSend;
						}
						if($send)
							$this->getNotify('success');
						else
							$this->getNotify('error');
					}
					else {
						$this->getNotify('error','configured');
					}
				}
			}
		}
	}

	public function getContactConf()
	{
		$config = array();

		$config['address_enabled'] = $this->config['address_enabled'];
		$config['address_required'] = $this->config['address_required'];

        if(class_exists('plugins_recaptcha_public') && $this->amp == false){
            $recaptcha = new plugins_recaptcha_public();
            $recaptchaData = $recaptcha->setItemData();
            if($recaptchaData['published'] == 1) {
                $config['recaptcha'] = $recaptchaData;
            }
        }

        return $config;
	}

    /**
     *
     */
    public function run(){
        if(class_exists('plugins_recaptcha_public') && $this->amp == false){
            $recaptcha = new plugins_recaptcha_public();
            $recaptchaData = $recaptcha->setItemData();
            if($recaptchaData['published'] == 1) {
                $this->template->assign('recaptcha', $recaptchaData);
            }
        }
        if(isset($this->msg)) {

            if(class_exists('plugins_recaptcha_public') && $this->amp == false){
                if($recaptchaData['published'] == 1) {
                    if ($recaptcha->getRecaptcha() == true) {
                        $this->send_email();
                    }
                }else{
                    $this->send_email();
                }
            }else{
                $this->send_email();
            }
		}
		else {
        	if(isset($this->moreinfo)) $this->template->assign('title',$this->moreinfo);
            $this->template->assign('contact',$this->getContactConf());
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
				'controller' => 'gmap',
				'name' => $this->template->getConfigVars('gmap'),
				'title' => $this->template->getConfigVars('gmap'),
				'url' => '/'.$iso.'/gmap/'
			));
		}
    }

	/**
	 * @return object
	 */
	public function is_amp()
	{
		return $this->amp_available;
    }
}
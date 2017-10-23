<?php
include_once ('db.php');
/**
 * Class plugins_test_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_contact_public extends plugins_contact_db
{
    protected $template,$header,$data,$getlang,$sanitize,$mail,$origin,$modelDomain,$config,$settings;
    public $msg, $content;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct()
    {
        $this->template = new frontend_model_template();
        $formClean = new form_inputEscape();
        $this->sanitize = new filter_sanitize();
        //$this->header = new component_httpUtils_header($this->template);
		$this->header = new http_header();
        $this->data = new frontend_model_data($this);
        $this->getlang = $this->template->currentLanguage();
        $this->mail = new mail_swift('mail');
        $this->modelDomain = new frontend_model_domain($this->template);
		$this->config = $this->getItems('config',null,'one',false);
		$this->settings = new frontend_model_setting();

		if (http_request::isPost('msg')) {
			$this->msg = $formClean->arrayClean($_POST['msg']);
		}

		if(http_request::isGet('__amp_source_origin')) {
			$this->origin = $formClean->simpleClean($_GET['__amp_source_origin']);
			//$request_body = file_get_contents('php://input');
			//$this->msg = json_decode($request_body);
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
			$this->template->display('notify/message.tpl');
		}
	}

	/**
	 * @access private
	 * setBodyMail
	 */
	private function setBodyMail($debug) {
        if($debug) {
            $data = array(
                'lastname' => "My Name",
                'firstname' => "My Firstname",
                'email' => $this->testmail,
                'phone' => "+32 08080808",
                'title' => "Test Mail",
                'content' => "My test mail"
            );
        }else{
			$data = array(
				'lastname' => $this->msg['lastname'],
				'firstname' => $this->msg['firstname'],
				'email' => $this->msg['email'],
				'phone' => $this->msg['phone'],
				'title' => $this->msg['title'],
				'content' => $this->msg['content']
			);

			if ($this->config['address_enabled']) {
				$data['address'] = $this->msg['address'];
				$data['postcode'] = $this->msg['postcode'];
				$data['city'] = $this->msg['city'];
			}
        }
		return $data;
    }

	/**
	 * @return string
	 */
	private function setTitleMail(){
		$about = new frontend_model_about($this->template);
		$collection = $about->getCompanyData();
		$subject = $this->template->getConfigVars('subject_contact');
		$title   = $this->template->getConfigVars('contact_request');
		$website = $collection['name'];
		return sprintf($subject,$title,$website);
	}

	/**
	 * @param bool $debug
	 * @return string
	 */
	private function getBodyMail($debug = false){
		$cssInliner = $this->settings->getSetting('css_inliner');
		$this->template->assign('getDataCSSIColor',$this->settings->fetchCSSIColor());
		$this->template->assign('data',$this->setBodyMail($debug));

		$bodyMail = $this->template->fetch('contact/mail/admin.tpl');
		if ($cssInliner['value']) {
			$bodyMail = $this->mail->plugin_css_inliner($bodyMail,array(component_core_system::basePath().'skin/'.$this->template->themeSelected().'/contact/css' => 'foundation-emails.css'));
		}

		if($debug) {
			print $bodyMail;
		}
		else {
			return $bodyMail;
		}
	}

	/**
	 * @return array
	 */
	public function getContact(){
		$lang = $this->template->getLanguage();
		return $this->getItems('contacts',array('lang' => $lang),'all',false);
	}

	/**
	 * Envoi du mail
	 * Si return true retourne success.tpl
	 * sinon retourne empty.tpl
	 */
	protected function send_email() {
		if(!empty($this->msg['email'])) {
			$this->template->configLoad();
			if(empty($this->msg['lastname']) || empty($this->msg['firstname']) || empty($this->msg['email'])) {
				$this->getNotify('warning','empty');
			}
			elseif(!$this->sanitize->mail($this->msg['email'])) {
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
						foreach ($contacts as $recipient) {
							$message = $this->mail->body_mail(
								self::setTitleMail(),
								array($this->msg['email']),
								array($recipient['mail_contact']),
								self::getBodyMail(),
								false
							);
							$isSend = $this->mail->batch_send_mail($message);
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

    /**
     *
     */
    public function run(){
        if(isset($this->msg)) {
        	$this->send_email();
		}
		else {
        	$this->template->assign('address_enabled',$this->config['address_enabled']);
        	$this->template->assign('address_required',$this->config['address_required']);
			$this->template->display('contact/index.tpl');
		}
    }
}
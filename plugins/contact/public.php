<?php
include_once ('db.php');
/**
 * Class plugins_test_public
 * Fichier pour l'éxecution frontend d'un plugin
 */
class plugins_contact_public extends plugins_contact_db {
    /**
     * @var frontend_model_template $template
     * @var frontend_model_data $data
     * @var http_header $header
     * @var filter_sanitize $sanitize
     * @var frontend_model_mail $mail
     * @var frontend_model_setting $settings
     * @var frontend_model_domain $modelDomain
     * @var frontend_model_module $module
     */
	protected frontend_model_template $template;
	protected frontend_model_data $data;
	protected http_header $header;
	protected filter_sanitize $sanitize;
	protected frontend_model_mail $mail;
	protected frontend_model_setting $settings;
	protected frontend_model_domain $modelDomain;
	protected frontend_model_module $module;

	/**
	 * @var bool $amp_available
	 */
    protected bool $amp_available = true;

	/**
	 * @var array $mods
	 */
	protected array $mods;

    /**
     * @var array
     */
    public array $msg;

    /**
     * @var string
     */
    public string
		$origin,
		$moreinfo,
		$type,
		$lang,
		$custom_mail;

    /**
     * @var boolean
     */
    public bool $amp;

    /**
     * frontend_controller_home constructor.
     * @param frontend_model_template|null $t
     */
    public function __construct(frontend_model_template $t = null) {
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
        $this->lang = $this->template->lang;
        $this->amp = http_request::isGet('amp');

        if(http_request::isGet('__amp_source_origin')) $this->origin = form_inputEscape::simpleClean($_GET['__amp_source_origin']);
        if(http_request::isRequest('moreinfo')) $this->moreinfo = form_inputEscape::simpleClean($_REQUEST['moreinfo']);
    }

    /**
     * Assign data to the defined variable or return the data
     * @param string $type
     * @param array|int|null $id
     * @param string|null $context
     * @param boolean|string $assign
     * @return mixed
     */
    private function getItems(string $type, $id = null, string $context = null, $assign = true) {
        return $this->data->getItems($type, $id, $context, $assign);
    }

	/**
	 * Load account modules
	 */
	private function loadModules() {
		if(!isset($this->module)) $this->module = new frontend_model_module($this->template);
		if(empty($this->mods)) $this->mods = $this->module->load_module('contact');
	}

    /**
     * Retourne le message de notification
     * @param string $type
     * @param string|null $subContent
     * @return array
     */
    private function setNotify(string $type, string $subContent = null): array {
        $this->template->configLoad();
        switch($type){
            case 'warning':
                $warning = [
					'empty' =>  $this->template->getConfigVars('fields_empty'),
					'mail'  =>  $this->template->getConfigVars('mail_format')
				];
                $message = $warning[$subContent];
                break;
            case 'success':
                $message = $this->template->getConfigVars('message_send_success');
                break;
            case 'error':
                if($subContent === 'captcha') {
                    $message = $this->template->getConfigVars('error_captcha');
                }
                else {
                    $error = [
						'installed' => $this->template->getConfigVars('installed'),
						'configured' => $this->template->getConfigVars('configured')
					];
                    if(in_array($subContent,$error)) $message = sprintf($this->template->getConfigVars('plugin_error'),'contact',$error[$subContent]);
                }
                break;
        }

        return [
			'type' => $type,
			'content' => $message
		];
    }

    /**
     * getNotify
     * @param string $type
     * @param string|null $subContent
     */
    private function getNotify(string $type, string $subContent = null){
        if(isset($this->origin)) {
			$this->modelDomain = new frontend_model_domain($this->template);
            $domains = $this->modelDomain->getValidDomains();
            $validOrigins = array("https://cdn.ampproject.org");
            foreach ($domains as $domain) {
                $domain['url_subdomain'] = str_replace('www.','',$domain['url_domain']);
                $validOrigins[] = 'https://'.$domain['url_subdomain'].'.cdn.ampproject.org';
                $validOrigins[] = 'https://'.$domain['url_domain'].'.amp.cloudflare.com';
                $validOrigins[] = 'https://'.$domain['url_domain'];
            }
			$this->header = new http_header();
            $this->header->amp_headers($this->origin,$validOrigins,false);
            $this->header->set_json_headers();
            if($type === 'success') {
                print json_encode(['status'=>'Success']);
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
                print json_encode(['error'=>$subContent], JSON_FORCE_OBJECT);
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
    private function setTitleMail(bool $error = false): string {
        if($error) {
            $title = $this->msg['title'];
        }
        else {
            $about = new frontend_model_about($this->template);
            $collection = $about->getCompanyData();
            $subject = $this->template->getConfigVars('subject_contact');
            if(!empty($this->type) && $this->type === 'order') {
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
    public function getContact(): array {
        return $this->getItems('contacts',['lang' => $this->lang],'all',false) ?: [];
    }

    /**
     * Envoi du mail
     * Si return true retourne success.tpl
     * sinon retourne empty.tpl
     */
    protected function send_email() {
        if(!empty($this->msg['email'])) {
            $this->template->configLoad();
			$this->sanitize = new filter_sanitize();

            if((empty($this->msg['lastname']) || empty($this->msg['firstname']) || empty($this->msg['email'])) && $this->msg['email'] !== 'error-mail') {
                $this->getNotify('warning','empty');
            }
            elseif(!$this->sanitize->mail($this->msg['email']) && $this->msg['email'] !== 'error-mail') {
                $this->getNotify('warning','mail');
            }
            elseif(!empty($this->msg['moreinfo'])) {
                $this->getNotify('error','configured');
            }
            elseif($this->lang) {
				if(!empty($this->custom_mail)) {
					$contacts = [[
						'mail_contact' => $this->custom_mail
					]];
				}
				else {
					$contacts = $this->getItems('contacts', ['lang' => $this->lang],'all',false);
				}
				if($contacts != null) {
					//Initialisation du contenu du message
					$send = false;
					$tpl = !empty($this->type) ? $this->type : 'admin';
					$error = false;
					$sender = $this->msg['email'];
					if($this->msg['email'] === 'error-mail') {
						$tpl = 'error';
						$error = true;
						$sender = '';
					}
					$file = null;
					if(isset($_FILES["contact_file"])) {
						$upload = new component_files_upload();
						$result = $upload->setUploadFile("contact_file",null,[
							'upload_root_dir' => 'upload',
							'upload_dir' => 'contact'
						],['jpg','png','pdf','doc','odt']);
						if($result['status']) {
							$file = [
								'path' => $result['path'].$result['file'],
								'type' => $result['mime']
							];
						}
					}
					$this->settings = new frontend_model_setting($this->template);
					$from = $this->settings->getSetting('mail_sender');
					$this->mail = new frontend_model_mail($this->template,'contact');
					if(!empty($from)){
						foreach ($contacts as $recipient) {
							$isSend = $this->mail->send_email(
								$recipient['mail_contact'],
								$tpl,
								$this->msg,
								$this->setTitleMail($error),
								$sender,
								$from['value'],
								$file
							);
							if(!$send) $send = $isSend;
						}
					}
					else {
						$send = false;
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

	/**
	 * @return array|mixed
	 */
    public function getContactConf() {
        $config = $this->getItems('config',null,'one',false) ?: [];
		$this->loadModules();
		if(!empty($this->mods)) {
			$plugin = new frontend_model_plugins();
			if(key_exists('recaptcha',$this->mods)) $config['recaptcha'] = $plugin->isInstalled('recaptcha');
		}
        return $config;
    }

    /**
     *
     */
    public function run() {
		$this->loadModules();

		if(http_request::isMethod('GET')) {
			$this->template->breadcrumb->addItem($this->template->getConfigVars('contact'));
			if(isset($this->moreinfo)) $this->template->assign('title',$this->moreinfo);
			$this->getItems('page',['lang' => $this->lang],'one');
			$this->template->assign('contact_config',$this->getContactConf());
			$this->template->display('contact/index.tpl');
		}
		if(http_request::isMethod('POST')) {
			if(http_request::isPost('msg')) $this->msg = form_inputEscape::arrayClean($_POST['msg']);
			if(http_request::isPost('type')) $this->type = form_inputEscape::simpleClean($_POST['type']);
			if(http_request::isPost('custom_mail')) $this->custom_mail = form_inputEscape::simpleClean($_POST['custom_mail']);

			// --- Check the google captcha if needed
			if (key_exists('recaptcha',$this->mods) && $this->mods['recaptcha']->active && !$this->mods['recaptcha']->getRecaptcha()) {
				$this->getNotify('error','captcha');
				return;
			}

			if(isset($this->msg)) $this->send_email();
		}
    }

    /**
     * @param string $iso
     * @return array|null
     */
    public function submenu(string $iso): ?array {
        if(class_exists('plugins_gmap_public')) {
            $this->template->addConfigFile([component_core_system::basePath().'/plugins/gmap/i18n/'], ['public_local_'], false);
            $this->template->configLoad();

            return [[
				'controller' => 'gmap',
				'name' => $this->template->getConfigVars('gmap'),
				'title' => $this->template->getConfigVars('gmap'),
				'url' => '/'.$iso.'/gmap/'
			]];
        }
		return null;
    }

    /**
     * @return bool
     */
    public function is_amp(): bool {
        return $this->amp_available;
    }
}
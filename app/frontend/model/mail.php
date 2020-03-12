<?php
class frontend_model_mail {
	/**
	 * @var object
	 */
	protected $template,
		$message,
		$lang,
		$settings,
		$module,
		$sanitize,
		$modelDomain,
		$mail;

	/**
	 * @var string $tpl_dir
	 */
	public $tpl_dir = 'mail';

	/**
	 * frontend_model_mail constructor.
	 * @param null|frontend_model_template $t
	 * @param $tpl_dir
	 */
	public function __construct($t = null, $tpl_dir, $type='mail', $options=null)
	{
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->message = new component_core_message($this->template);
		$this->lang = $this->template->currentLanguage();
		$this->sanitize = new filter_sanitize();
		$this->mail = new mail_swift($type, $options);
		$this->modelDomain = new frontend_model_domain($this->template);
		$this->settings = new frontend_model_setting($this->template);

		$this->tpl_dir = $tpl_dir;
	}

	/**
	 * @param $type
	 * @return string
	 */
	private function setTitleMail($type){
		$about = new frontend_model_about($this->template);
		$collection = $about->getCompanyData();

		switch ($type) {
			default: $title = $this->template->getConfigVars($type.'_title');
		}

		return sprintf($title, $collection['name']);
	}

	/**
	 * @param $tpl
	 * @param array $data
	 * @param bool $debug
	 * @return string
	 */
	private function getBodyMail($tpl, $data = array(), $debug = false){
		$cssInliner = $this->settings->getSetting('css_inliner');
		$this->template->assign('getDataCSSIColor',$this->settings->fetchCSSIColor());
		$this->template->assign('data',$data);

		$bodyMail = $this->template->fetch($this->tpl_dir.'/mail/'.$tpl.'.tpl');
		if ($cssInliner['value']) {
			$bodyMail = $this->mail->plugin_css_inliner($bodyMail,array(component_core_system::basePath().'skin/'.$this->template->theme.'/mail/css' => 'mail.min.css'));
			//$bodyMail = $this->mail->plugin_css_inliner($bodyMail);
		}

		if($debug) {
			print $bodyMail;
		}
		else {
			return $bodyMail;
		}
	}

	/**
	 * Send a mail
	 * @param $email
	 * @param $tpl
	 * @param $data
	 * @param string $title
	 * @param string $sender
     * @param string $from
	 * @return bool
	 */
	public function send_email($email, $tpl, $data, $title = '', $sender = '', $from = '', $file = null) {
		if($email) {
			$this->template->configLoad();
			if(!$this->sanitize->mail($email)) {
				$this->message->json_post_response(false,'error_mail');
			}
			else {
				if($this->lang) {
					$noreply = $sender;

					if($sender === '') {
						$allowed_hosts = array_map(function($dom) { return $dom['url_domain']; },$this->modelDomain->getValidDomains());
						if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
							header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
							exit;
						}
						else {
							$noreply = 'noreply@'.str_replace('www.','',$_SERVER['HTTP_HOST']);
						}
					}

					if(!empty($noreply)) {

						$message = $this->mail->body_mail(
							($title === '') ? self::setTitleMail($tpl) : $title,
							array($noreply),
							array($email),
                            array($from),
							self::getBodyMail($tpl,$data),
							false
						);

						if(is_array($file)) {
							if(key_exists('path',$file) && key_exists('type',$file)) {
								if(file_exists($file['path'])) $message->attach(Swift_Attachment::fromPath($file['path'], $file['type']));
							}
						}

						//$fail = $this->mail->batch_send_mail($message,true,true);

						if($this->mail->batch_send_mail($message)) {
							return true;
						}
						else {
							$this->message->json_post_response(false,'error');
							return false;
						}
					}
					else {
						$this->message->json_post_response(false,'error_config');
						return false;
					}
				}
			}
		}
	}
}
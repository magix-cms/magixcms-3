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
class backend_controller_login extends backend_db_employee{
    // --- SESSION
    /**
     * @var $close_session
     */
    public $close_session;

    /**
     * input type hidden
     * @access protected
     *
     * @var string
     */
    protected $hashtoken,
		$header,
		$data,
		$message,
		$template,
		$mail,
		$setting,
		$settings,
		$modelDomain;

    /**
     * @var $email_admin,$passwd_admin
     */
    public $action,
		$email_admin,
		$email_forgot,
		$key,
		$passwd_admin,
		$stay_logged,
		$kpl,
		$token,
		$logout;

    public static $notify = '';

    /**
     * @var
     */
    private $employee, $session, $httpSession;

	/**
	 * backend_controller_login constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null){
		$this->template = $t ? $t : new backend_model_template;
		$this->header = new http_header();
		$this->data = new backend_model_data($this);
        $this->message = new component_core_message($this->template);
		$this->mail = new mail_swift('mail');
		$this->modelDomain = new backend_controller_domain($t);
		$this->setting = new backend_model_setting();
		$this->settings = $this->setting->getSetting();
		$formClean = new form_inputEscape();

        // --- LOGIN
		if (http_request::isPost('employee')) {
			$this->employee = (array)$formClean->arrayClean($_POST['employee']);
		}
        if(http_request::isPost('stay_logged')){
            $this->stay_logged = true;
        }
        if (http_request::isGet('logout')) {
            $this->logout = $formClean->simpleClean($_GET['logout']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        }

        // --- LOSTPASSWORD
        if(http_request::isPost('email_forgot')){
            $this->email_forgot = form_inputFilter::isMail($_POST['email_forgot']);
        }
		if (http_request::isGet('k')) {
			$this->key = $formClean->simpleClean($_GET['k']);
		}

        $this->httpSession = new http_session($this->settings['ssl']);
        $this->session = new backend_model_session();

		$this->securePage();
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
     * Crypt md5
     * @param string $hash
     * @return string
     * @static
     * @access protected
     */
    protected function hashPassCreate($hash){
        return filter_rsa::hashEncode('md5',$hash);
    }

	/**
	 *
	 */
	public function getAdminProfile()
	{
		if(isset($_SESSION['keyuniqid_admin'])) {
			$adminProfile = $this->getItems('session',array('keyuniqid_admin' => $_SESSION['keyuniqid_admin']),'one',false);
			$this->template->assign('adminProfile',$adminProfile);
		}
    }

    /**
     * Authentification sur la page de login
     * @param bool $debug
     * @throws Exception
     */
    private function getAuth($debug = false) {
		$agtoken = $this->httpSession->token('ap_auth_token');
		$this->template->assign('hashpass',$agtoken);
        if ( !empty($this->employee) ) {
            if( strcasecmp($this->employee['hashtoken'], $agtoken) == 0 ){
                if($debug == true){
                    if($this->employee['hashtoken']) {
                        if ( strcasecmp($this->employee['hashtoken'], $agtoken) == 0 ) {
                            $status = 'session success';
                        } else {
                            $status = 'session error';
                        }
                    }
                    $dataDebug = array_merge($_SESSION, array('status'=>$status));
                    $this->message->getNotify('debug',array('method' => 'debug', 'result' => $dataDebug));
                }

                //Check database Mail exist
                $account = $this->getItems('mail',array('email_admin' => $this->employee['email_admin']),'one',false);

                //check password verify
                if(password_verify($this->employee['passwd_admin'], $account['passwd_admin'])) {
                    //Check database Authentification exist
                    $authExist = $this->getItems('auth',array('email_admin' => $this->employee['email_admin'], 'passwd_admin' => $account['passwd_admin']),'one',false);

                    if (count($authExist['id_admin'])) {
						$account = $this->getItems('session',array('keyuniqid_admin' => $authExist['keyuniqid_admin']),'one',false);

						$expires = $this->stay_logged ? strtotime("+13 month") : 0;
						$this->httpSession->regenerate($expires);
						if($expires) {
							$hash = $account['keyuniqid_admin'].session_id();
							$cparams = session_get_cookie_params();
							setcookie($hash,$expires,0,'/',$cparams['domain'],($this->settings['ssl'] ? true : false),true);
						}

                        $this->session->openSession(array('id_admin' => $account['id_admin'], 'id_admin_session' => session_id(), 'keyuniqid_admin' => $account['keyuniqid_admin'], 'expires' => ($this->stay_logged ? $expires : null)));

                        $array_sess = array(
							'id_admin' => $account['id_admin'],
							'email_admin' => $account['email_admin'],
							'keyuniqid_admin' => $account['keyuniqid_admin']
						);
                        $this->httpSession->run($array_sess);

                        if ($debug == true) {
                            $dataDebug = array_merge(
                                $_SESSION,
                                array('ip' => $this->httpSession->ip())
                            );
                            $this->message->getNotify('debug', array(
								'method' => 'debug',
								'result' => $dataDebug
							));
                        }
                        else {
                        	$this->session->redirect(true);
                        }

                    }
                    else {
                        $this->message->getNotify('error_login', array('method' => 'fetch', 'assignFetch' => 'error'));
                    }
                }
                else {
                    $this->message->getNotify('error_login', array('method' => 'fetch', 'assignFetch' => 'error'));
                }
            }
            else{
                $this->message->getNotify('error_hash',array('method'=>'fetch','assignFetch'=>'error'));
            }
        }
    }

	/**
	 * Sécurise l'espace membre
	 * @param bool $debug
	 */
	public function securePage($debug = false){
		$ssid = $this->httpSession->start('mc_admin');
		$this->httpSession->token('ap_auth_token');
		$cparams = session_get_cookie_params();

		if(isset($_SESSION['keyuniqid_admin']) && !empty($_SESSION['keyuniqid_admin'])) {
			$hash = $_SESSION['keyuniqid_admin'].$ssid;
			if(isset($_COOKIE[$hash])) {
				$array_sess = array(
					'id_admin'   =>  $_SESSION['id_admin'],
					'keyuniqid_admin' =>  $_SESSION['keyuniqid_admin'],
					'email_admin'     =>  $_SESSION['email_admin']
				);
				setcookie('mc_admin',$ssid,$_COOKIE[$hash],'/',$cparams['domain'],($this->settings['ssl'] ? true : false),true);
				$this->httpSession->run($array_sess);
			}
		}
		else {
			$sess = $this->getItems('session',array('id_session'=>$ssid),'one',false);
			if($sess) {
				$account = $this->getItems('account_from_key',array('keyuniqid_admin' => $sess['keyuniqid_admin']),'one',false);

				$this->httpSession->regenerate($sess['expires']);
				$this->session->openSession(array(
					'id_admin_session' => session_id(),
					'id_admin' => $account['id_admin'],
					'keyuniqid_admin' => $account['keyuniqid_admin'],
					'expires' => $sess['expires']
				));
				$hash = $account['keyuniqid_admin'].session_id();
				setcookie($hash,$sess['expires'],0,'/',$cparams['domain'],($this->settings['ssl'] ? true : false),true);

				$array_sess = array(
					'id_admin'   => $account['id_admin'],
					'email_admin'     => $account['email_admin'],
					'keyuniqid_admin' => $account['keyuniqid_admin']
				);
				$this->httpSession->run($array_sess);
			}
		}

		if($debug) $this->httpSession->debug();
	}

	/**
	 * Check if a ticket exist
	 */
	public function checkout()
	{
		if(!isset($_SESSION["email_admin"]) || empty($_SESSION['email_admin'])) {
			//$this->template->display('login/checkout.tpl');
			$this->session->redirect(false);
		}
		else {
			$this->secure();
			$this->close();
			if(!http_request::isGet('controller')){
				$this->session->redirect(true);
			}
		}
    }

    /**
     * Sécurisation de la session
     */
    public function secure(){
        //ini_set("session.cookie_lifetime",3600);
        //$this->httpSession->start('lang');
        $compareSessionId = $this->session->compareSessionId();
        if (!isset($_SESSION["email_admin"]) || empty($_SESSION['email_admin'])){
            if (!isset($this->email_admin)) {
                $this->session->redirect(false);
            }
        }
        elseif(!$compareSessionId['id_admin_session']){
            $this->session->redirect(false);
        }
    }

    /**
     * Fermeture de la session
     * @return header
     */
    public function close(){
        if (isset($this->logout)) {
            if (isset($_SESSION['email_admin']) AND isset($_SESSION['keyuniqid_admin'])) {
                $this->session->closeSession();
                /*session_unset();
                $_SESSION = array();
                session_destroy();
                session_start();*/
				$this->httpSession->close('mc_admin');
                $this->session->redirect(false);
            }
        }
    }

	/**
	 * @param $type
	 * @param $domain
	 * @return string
	 */
	private function setTitleMail($type,$domain)
	{
		$this->template->configLoad();
		$title = $this->template->getConfigVars('titlemail');
		$subject = $this->template->getConfigVars($type);
		return sprintf($title,$subject,$domain);
    }

	/**
	 * @param $data
	 * @param $type
	 * @param $debug
	 * @return string
	 */
	private function getBodyMail($data, $type, $debug){
		$this->template->configLoad();
		$cssInliner = $this->settings['css_inliner'];
		$this->template->assign('getDataCSSIColor',$this->setting->fetchCSSIColor());
		$this->template->assign('data', $data);
		$bodyMail = $this->template->fetch('login/mail/'.$type.'.tpl');

		if ($cssInliner['css_inliner']) {
			$bodyMail = $this->mail->plugin_css_inliner($bodyMail,array(component_core_system::basePath().'/admin/template/login/css' => 'foundation-emails.css'));
		}

		if($debug){
			print $bodyMail;
		} else {
			return $bodyMail;
		}
	}

	/**
	 * @param $data
	 * @param $mail
	 * @param $type
	 */
	private function sendMail($data,$mail,$type,$json_response = false){
		$noreply = false;
		$allowed_hosts = array_map(function($dom) { return $dom['url_domain']; },$this->modelDomain->getValidDomains());
		if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
			exit;
		}
		else {
			$noreply = 'noreply@'.str_replace('www.','',$_SERVER['HTTP_HOST']);
		}
		if ($noreply) {
			$message = $this->mail->body_mail(
				self::setTitleMail($type,$_SERVER['HTTP_HOST']),
				array($noreply),
				array($mail),
                array($noreply),
				self::getBodyMail($data,$type,false),
				false
			);
			$this->mail->batch_send_mail($message);

			if($json_response){
				$this->message->json_post_response(true,'send');
			}
		}
	}

    /**
     * Execution des scripts pour les sessions et le login
     */
    public function run(){
        //if (http_request::isGet('newlogin')) {

        if (isset($this->action)) {
        	switch ($this->action) {
				case 'rstpwd':
					if(isset($this->email_forgot)){
						$data = $this->getItems('key',array('email_forgot'=> $this->email_forgot),'one',false);
						if($data) {
							$pwdTicket = filter_rsa::randString(32);
							$data['ticket'] = $pwdTicket;
							parent::update(array('context'=>'employee','type'=>'askPassword'),array('email_admin'=>$this->email_forgot,'token'=>$pwdTicket));
							$this->sendMail($data,$this->email_forgot,$this->action,true);
						} else {
							$this->message->json_post_response(false,'error_mail_account');
						}
					} else {
						$this->message->json_post_response(false,'empty');
					}
					break;
				case 'newpwd':
					if(isset($this->key)){
						$data = $this->getItems('by_key',array('keyuniqid_admin'=>$this->key,'ticket'=>$this->ticket_passwd),'one',false);
						if($data){
							$cryptpass = filter_rsa::randMicroUI();
							parent::update(array('context'=>'employee','type'=>'newPassword'),array('newPassword'=>password_hash($cryptpass, PASSWORD_DEFAULT)/*filter_rsa::hashEncode('sha1',$cryptpass)*/,'email_admin'=>$data['email_admin']));
							$this->sendMail(array('newPassword'=>$cryptpass),$data['email_admin'],$this->action);
						} else {
							$this->template->assign('error_tikcet',true);
						}
						$this->template->display('login/npwd.tpl');
					}
					break;
			}
        }
        else {
            $this->getAuth(false);
            $this->template->display('login/index.tpl');
        }
    }
}
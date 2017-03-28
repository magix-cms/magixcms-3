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
    protected $hashtoken,$header,$message,$template,$mail,$setting,$settings;

    /**
     * @var $email_admin,$passwd_admin
     */
    public $action,$email_admin,$email_forgot,$key,$passwd_admin,$stay_logged,$kpl,$ticket,$ticket_passwd,$logout;
    public static $notify = '';

    /**
     * @var
     */
    protected static $session,$httpSession;

    /**
     * Constructor
     */
    public function __construct(){
        $this->header = new http_header();
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
		$this->mail = new mail_swift('mail');
		$this->setting = new backend_model_setting();
		$this->settings = $this->setting->getSetting();
		$formClean = new form_inputEscape();

        // --- LOGIN
        if(http_request::isPost('email_admin')){
            $this->email_admin = $formClean->simpleClean($_POST['email_admin']);
        }
        if(http_request::isPost('passwd_admin')){
            $this->passwd_admin = filter_escapeHtml::clean(filter_rsa::hashEncode('sha1',$_POST['passwd_admin']));
        }
        if(http_request::isPost('stay_logged')){
            $this->stay_logged = true;
        }
        if(http_request::isPost('ticket')){
        	$this->ticket = $formClean->arrayClean($_POST['ticket']);
        }
        if (http_request::isGet('t')) {
			$this->ticket_passwd = $formClean->simpleClean($_GET['t']);
		}
        if(http_request::isPost('hashtoken')){
            $this->hashtoken = $formClean->simpleClean($_POST['hashtoken']);
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

        $this->httpSession = new http_session();
        $this->session = new backend_model_session();
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
     * Initialisation du token
     */
    private function tokenInitSession(){
        $session = new http_session();
        $session->token('ap_auth_token');
    }

    /**
     * Authentification sur la page de login
     * @param bool $debug
     */
    private function getAuth($debug = false){
        $token = isset($_SESSION['ap_auth_token']) ? $_SESSION['ap_auth_token'] : filter_rsa::tokenID();
        $tokentools = $this->hashPassCreate($token);
        $this->template->assign('hashpass',$tokentools);
        if (isset($this->email_admin) AND isset($this->passwd_admin)) {
            if(strcasecmp($this->hashtoken,$tokentools) == 0){
                if($debug == true){
                    if($this->hashtoken) {
                        if (strcasecmp($this->hashtoken, $tokentools) == 0) {
                            $status = 'session success';
                        } else {
                            $status = 'session error';
                        }
                    }

                    $dataDebug = array_merge(
                        $_SESSION,
                        array('status'=>$status)
                    );
                    $this->message->getNotify('debug',array(
                            'method'        =>  'debug',
                            'result'        =>  $dataDebug
                        )
                    );
                }

                //Check database Authentification exist
                $authExist = parent::fetchData(
                    array(
                        'type'=>'auth'
                    ),
                    array(
                        'email_admin'   =>  $this->email_admin,
                        'passwd_admin'  =>  $this->passwd_admin
                    )
                );
                if(count($authExist['id_admin']) == true){
                    $data = parent::fetchData(
                        array(
                            'type'=>'session'
                        ),
                        array(
                            'keyuniqid_admin'  =>  $authExist['keyuniqid_admin']
                        )
                    );

                    $language = new component_core_language('strLanguage');
                    $this->httpSession->start('lang');

					$array_sess = array(
						'email_admin'       =>  $data['email_admin'],
						'keyuniqid_admin'   =>  $data['keyuniqid_admin']
					);

                    if (!isset($_SESSION['email_admin']) AND !isset($_SESSION['keyuniqid_admin'])) {
                        session_regenerate_id(true);
                        $array_sess['id_admin'] = $data['id_admin'];
                    }

					$this->session->openSession(array('id_admin'=>$data['id_admin'],'id_admin_session'=>session_id(), 'keyuniqid_admin'=>$data['keyuniqid_admin']));
					$array_sess = array(
						'email_admin'       =>  $data['email_admin'],
						'keyuniqid_admin'   =>  $data['keyuniqid_admin']
					);

					$this->httpSession->run($array_sess,$language->run());
					if($debug == true){
						$dataDebug = array_merge(
							$_SESSION,
							array('ip'=>$this->httpSession->ip())
						);
						$this->message->getNotify('debug',array(
							'method'        =>  'debug',
							'result'        =>  $dataDebug
							)
						);
					}else{
						if(isset($this->stay_logged)) {
							$this->template->assign('kpl','{"m":'.json_encode($_SESSION["email_admin"]).',"k":'.json_encode($_SESSION['keyuniqid_admin']).',"t":'.json_encode(session_id()).'}');
							$this->template->display('login/checkout.tpl');
						} else {
							$this->session->redirect(true);
						}
					}

                }else{
                    $this->message->getNotify('error_login',array('method'=>'fetch','assignFetch'=>'error'));
                }
            }else{
                $this->message->getNotify('error_hash',array('method'=>'fetch','assignFetch'=>'error'));
            }
        }
    }

	/**
	 * Authentification when valid ticket found
	 */
	private function setAuth()
	{
		$token = isset($_SESSION['ap_auth_token']) ? $_SESSION['ap_auth_token'] : filter_rsa::tokenID();
		$tokentools = $this->hashPassCreate($token);
		$this->template->assign('hashpass',$tokentools);

		$data = parent::fetchData(
			array(
				'type'=>'session'
			),
			array(
				'keyuniqid_admin'  =>  $this->ticket['k']
			)
		);

		$language = new component_core_language('strLanguage');
		$this->httpSession->start('lang');

		$array_sess = array(
			'email_admin'       =>  $data['email_admin'],
			'keyuniqid_admin'   =>  $data['keyuniqid_admin']
		);

		if (!isset($_SESSION['email_admin']) AND !isset($_SESSION['keyuniqid_admin'])) {
			session_regenerate_id(true);
			$array_sess['id_admin'] = $data['id_admin'];
		}

		$this->session->openSession(array('id_admin'=>$data['id_admin'],'id_admin_session'=>session_id(), 'keyuniqid_admin'=>$data['keyuniqid_admin']));
		$array_sess = array(
			'email_admin'       =>  $data['email_admin'],
			'keyuniqid_admin'   =>  $data['keyuniqid_admin']
		);

		$this->httpSession->run($array_sess,$language->run());

		$this->header->set_json_headers();
		print '{"m":'.json_encode($_SESSION["email_admin"]).',"k":'.json_encode($_SESSION['keyuniqid_admin']).',"t":'.json_encode(session_id()).'}';
    }

	/**
	 * Verify ticket
	 */
	public function checkTicket()
	{
		$record = parent::fetchData(array('context'=>'session','type'=>'uniq_session'),$this->ticket);

		if ($record != null) {
			$this->httpSession->start('lang');
			$this->tokenInitSession();
			$this->setAuth();
		} else {
			print false;
		}
    }

	/**
	 * Check if a ticket exist
	 */
	public function checkout()
	{
		if(!isset($_SESSION["email_admin"]) || empty($_SESSION['email_admin'])) {
			$this->template->display('login/checkout.tpl');
		} else {
			$this->secure();
			$this->close();
		}
    }

    /**
     * Sécurisation de la session
     */
    public function secure(){
        //ini_set("session.cookie_lifetime",3600);
        $this->httpSession->start('lang');
        $compareSessionId = $this->session->compareSessionId();
        if (!isset($_SESSION["email_admin"]) || empty($_SESSION['email_admin'])){
            if (!isset($this->email_admin)) {
                $this->session->redirect(false);
            }
        }elseif(!$compareSessionId['id_admin_session']){
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
                session_unset();
                $_SESSION = array();
                session_destroy();
                session_start();
                $this->session->redirect(false);
            }
        }
    }

	/**
	 * @param $type
	 * @return string
	 */
	private function setTitleMail($type)
	{
		$this->template->configLoad();
		$title = $this->template->getConfigVars('titlemail');
		$subject = $this->template->getConfigVars($type);
		return sprintf($title,$subject,'monsite.be/admin/');
    }

	/**
	 * @param $data
	 * @param $type
	 * @param $debug
	 * @return string
	 */
	private function getBodyMail($data, $type, $debug){
		$this->template->configLoad();
		$fetchColor = new backend_db_setting();
		$this->template->assign('getDataCSSIColor',$fetchColor->fetchCSSIColor());
		$this->template->assign('data', $data);
		$bodyMail = $this->template->fetch('login/mail/'.$type.'.tpl');

		if ($this->settings['css_inliner']) {
			$bodyMail = $this->mail->plugin_css_inliner($bodyMail,array('login/css' => 'foundation-emails.css'));
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
		$message = $this->mail->body_mail(
			self::setTitleMail($type),
			array($this->settings['mail_admin']),
			array($mail),
			self::getBodyMail($data,$type,false),
			false
		);
		$this->mail->batch_send_mail($message);

		if($json_response){
			$this->header->set_json_headers();
			$this->message->json_post_response(true,'send');
		}
	}

    /**
     * Execution des scripts pour les sessions et le login
     */
    public function run(){
        if (http_request::isGet('newlogin')) {

        } elseif (isset($this->ticket)) {
        	$this->checkTicket();
        } elseif (isset($this->action)) {
        	switch ($this->action) {
				case 'rstpwd':
					if(isset($this->email_forgot)){
						$data = parent::fetchData(array('context'=>'employee','type'=>'key'),$this->email_forgot);
						if($data) {
							$pwdTicket = filter_rsa::randString(32);
							$data['ticket'] = $pwdTicket;
							parent::update(array('context'=>'employee','type'=>'askPassword'),array('email_admin'=>$this->email_forgot,'token'=>$pwdTicket));
							$this->sendMail($data,$this->email_forgot,$this->action,true);
						} else {
							$this->header->set_json_headers();
							$this->message->json_post_response(false,'error_mail_account');
						}
					} else {
						$this->header->set_json_headers();
						$this->message->json_post_response(false,'empty');
					}
					break;

				case 'newpwd':
					if(isset($this->key)){
						$data = parent::fetchData(array('context'=>'employee','type'=>'by_key'),array('key'=>$this->key,'ticket'=>$this->ticket_passwd));
						if($data){
							$cryptpass = filter_rsa::randMicroUI();
							parent::update(array('context'=>'employee','type'=>'newPassword'),array('newPassword'=>filter_rsa::hashEncode('sha1',$cryptpass),'email_admin'=>$data['email_admin']));
							$this->sendMail(array('newPassword'=>$cryptpass),$data['email_admin'],$this->action);
						} else {
							$this->template->assign('error_tikcet',true);
						}
						$this->template->display('login/npwd.tpl');
					}
					break;
			}
        } else {
            $this->httpSession->start('lang');
            $this->tokenInitSession();
            $this->getAuth(false);
            $this->template->display('login/index.tpl');
        }
    }
}
?>
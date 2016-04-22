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

class admin_controller_login extends admin_db_employee{
    //SESSION
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
    protected $hashtoken;
    /**
     * @var $email_admin,$passwd_admin
     */
    public $email_admin,$lo_email_admin,$passwd_admin;
    /**
     * @var
     */
    protected static $session;

    /**
     * Constructor
     */
    public function __construct(){
        //LOGIN
        if(http_request::isPost('email_admin')){
            $this->email_admin = form_inputEscape::simpleClean($_POST['email_admin']);
        }
        if(http_request::isPost('passwd_admin')){
            $this->passwd_admin = filter_escapeHtml::clean(filter_rsa::hashEncode('sha1',$_POST['passwd_admin']));
        }
        if(http_request::isPost('hashtoken')){
            $this->hashtoken = form_inputEscape::simpleClean($_POST['hashtoken']);
        }
        //LOSTPASSWORD
        if(http_request::isPost('lo_email_admin')){
            $this->lo_email_admin = form_inputFilter::isMail($_POST['lo_email_admin']);
        }
    }

    /**
     * Crypt md5
     * @param string $hash
     * @return string
     * @static
     * @access protected
     */
    static protected function hashPassCreate($hash){
        return filter_rsa::hashEncode('md5',$hash);
    }

    /**
     * Initialisation du token
     */
    private function tokenInitSession(){
        $session = new http_session();
        $session->token('mc_auth_token');
    }

    /**
     * Authentification sur la page de login
     * @param $create
     * @param bool $debug
     */
    private function getAuth($create,$debug = false){
        $token = isset($_SESSION['mc_auth_token']) ? $_SESSION['mc_auth_token'] : filter_rsa::tokenID();
        $tokentools = self::hashPassCreate($token);
        $create->assign('hashpass',$tokentools);
        if (isset($this->email_admin) AND isset($this->passwd_admin)) {
            $firebug = new debug_firephp();
            if(strcasecmp($this->hashtoken,$tokentools) == 0){
                if($debug == true){
                    $firebug->group('tokentest');
                    if($this->hashtoken){
                        if(strcasecmp($this->hashtoken,$tokentools) == 0){
                            $firebug->log('session compatible');
                        }else{
                            $firebug->error('session incompatible');
                        }
                    }
                    $firebug->log($_SESSION);
                    $firebug->groupEnd();
                }
                $auth_exist = parent::s_auth_exist($this->email_admin,$this->passwd_admin);
                if(count($auth_exist['idadmin']) == true){
                    $data = parent::s_data_session($auth_exist['keyuniqid_admin']);
                    $session = new http_session();
                    $language = new backend_model_language();
                    $session->start('mc_adminlang');
                    $sessionUtils = new admin_model_sessionUtils();
                    if (!isset($_SESSION['email_admin']) AND !isset($_SESSION['keyuniqid_admin'])) {
                        $sessionUtils->openSession($data['idadmin'],session_regenerate_id(true), $data['keyuniqid_admin']);
                        $array_sess = array(
                            'id_admin'          =>  $data['idadmin'],
                            'email_admin'       =>  $data['email_admin'],
                            'keyuniqid_admin'   =>  $data['keyuniqid_admin'],
                            'language_admin'    =>  $language->run()
                        );

                        $session->run($array_sess,$language->run());
                        if($debug == true){
                            $firebug = new debug_firephp();
                            $firebug->group('adminsession');
                            $firebug->dump('User session',$_SESSION);
                            $firebug->log($session->ip());
                            $firebug->groupEnd();
                        }
                        admin_model_redirect::login(false);
                    }else{
                        $sessionUtils->openSession($data['idadmin'],null, $data['keyuniqid_admin']);
                        $array_sess = array(
                            'email_admin'=>$data['email_admin'],
                            'keyuniqid_admin'=>$data['keyuniqid_admin']
                        );
                        $language = new admin_model_language();
                        $session->run($array_sess,$language->run());
                        if($debug == true){
                            $firebug = new debug_firephp();
                            $firebug->group('adminsession');
                            $firebug->dump('User session',$_SESSION);
                            $firebug->log($session->ip());
                            $firebug->groupEnd();
                        }
                        admin_model_redirect::login(false);
                    }
                }
            }
        }
    }

    /**
     * Sécurisation de la session
     */
    public function secure(){
        //ini_set("session.cookie_lifetime",3600);
        $session = new http_session();
        $sessionUtils = new admin_model_sessionUtils();
        $session->start('mc_adminlang');
        $compareSessionId = $sessionUtils->compareSessionId();
        if (!isset($_SESSION["email_admin"]) || empty($_SESSION['email_admin'])){
            if (!isset($this->email_admin)) {
                admin_model_redirect::login(true);
            }
        }/*elseif(!$compareSessionId['id_admin_session']){
            admin_model_redirect::login(true);
        }*/
    }

    /**
     * Fermeture de la session de l'agence
     * @return header
     */
    public function close(){
        if (isset($_SESSION['email_admin']) AND isset($_SESSION['keyuniqid_admin'])){
            $sessionUtils = new admin_model_sessionUtils();
            $sessionUtils->closeSession();
            session_unset();
            $_SESSION = array();
            session_destroy();
            session_start();
            admin_model_redirect::login(true);
        }
    }

    /**
     * Execution des scripts pour les sessions et le login
     */
    public function run(){
        $header = new http_header();
        $create = new admin_model_template();
        if(http_request::isGet('newlogin')){

        }else{
            $this->tokenInitSession();
            $this->getAuth($create,true);
            $create->display('login/index.phtml');
        }
    }
}
?>
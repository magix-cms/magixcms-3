<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Mage Pattern.
# The toolkit PHP for developer
# Copyright (C) 2012 - 2013 Gerits Aurelien contact[at]aurelien-gerits[dot]be
#
# OFFICIAL TEAM MAGE PATTERN:
#
#   * Gerits Aurelien (Author - Developer) contact[at]aurelien-gerits[dot]be
#
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
# Redistributions of source code must retain the above copyright notice,
# this list of conditions and the following disclaimer.
#
# Redistributions in binary form must reproduce the above copyright notice,
# this list of conditions and the following disclaimer in the documentation
# and/or other materials provided with the distribution.
#
# DISCLAIMER
#
# Do not edit or add to this file if you wish to upgrade Mage Pattern to newer
# versions in the future. If you wish to customize Mage Pattern for your
# needs please refer to http://www.magepattern.com for more information.
#
# -- END LICENSE BLOCK -----------------------------------

/**
 * Created by Magix Dev.
 * User: aureliengerits
 * Date: 19/07/12
 * Time: 22:05
 *
 */
class http_session extends sessionUtils{
	protected $ssl;

	/**
	 * http_session constructor.
	 */
	public function __construct($ssl)
	{
		$this->ssl = $ssl;
	}

	/**
     * @return bool
     */
    protected function _write(){
        // Write and close the session
        session_write_close();

        return TRUE;
    }

	/**
	 * Regenerate the session id
	 * and update the session
	 * @param int $lifetime
	 * @return string
	 */
	public function regenerate($lifetime = 0){
		$cparams = session_get_cookie_params();
		session_regenerate_id(true);
		$nid = session_id();
		$sname = session_name();
		$this->_write();
		session_name($sname);
		session_id($nid);
		session_start();
		setcookie($sname,$nid,$lifetime,'/',$cparams['domain'],($this->ssl ? true : false),true);

		return $nid;
	}

	/**
	 * Start a new session
	 * @param string $session_name
	 * @param array $params
	 * @return string session id
	 */
    public function start($session_name = 'mp_default_s',$params = array()){
		try {
			if (is_string($session_name) && $session_name !== '') {
				//$string = $_SERVER['HTTP_USER_AGENT'];
				//$string .= 'SHIFLETT';
				/* Add any other data that is consistent */
				//$fingerprint = md5($string);
				$ssid = session_id();

				if (!isset($_SESSION)) session_cache_limiter('nocache');

				//Fermeture de la première session, ses données sont sauvegardées.
				if(session_name() !== $session_name || !empty($params)) {
					$this->_write();

					// **PREVENTING SESSION FIXATION**
					// Session ID cannot be passed through URLs
					ini_set('session.use_only_cookies', 1);

					$cparams = array_merge(session_get_cookie_params(),$params);
					session_set_cookie_params(
						$cparams['lifetime'],
						'/',
						$cparams['domain'],
						($this->ssl ? true : false),
						true
					);

					if(!isset($_COOKIE[$session_name])) {
						session_name($session_name);
						ini_set('session.hash_function',1);
						session_start();
						session_regenerate_id();
						$ssid = session_id();
					}
					else {
						$ssid = $_COOKIE[$session_name];
						session_name($session_name);
						session_id($ssid);
						session_start();
					}
				}
				return $ssid;
			}
			else {
				throw new Exception('Unable to start a new session. No session name defined');
			}
		} catch(Exception $e) {
			$logger = new debug_logger(MP_LOG_DIR);
			$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
		}
    }

	/**
	 * Reset a session
	 * @param string|array|null $keys
	 */
    public function delete(){
    	session_unset();
    	$_SESSION = array();
        session_destroy();
        session_start();
    }

	/**
	 * Close a session
	 * @param string $session_name
	 */
    public function close($session_name){
    	session_name($session_name);
		session_start();
		session_unset();
		session_destroy();
		$CookieInfo = session_get_cookie_params();
		if ( (empty($CookieInfo['domain'])) && (empty($CookieInfo['secure'])) ) {
			setcookie($session_name, '', time()-3600, $CookieInfo['path']);
		} elseif (empty($CookieInfo['secure'])) {
			setcookie($session_name, '', time()-3600, $CookieInfo['path'], $CookieInfo['domain']);
		} else {
			setcookie($session_name, '', time()-3600, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
		}
    }

	/**
	 * Delete a session
	 */
    public function clear(){
        session_destroy();
    }

    /**
     * Création d'un token
     * @param string $tokename
     * @return string
     */
    public function token($tokename = 'token', $token = null){
        if (!isset($_SESSION[$tokename]) && empty($_SESSION[$tokename])){
            return $_SESSION[$tokename] = $token === null ? filter_rsa::tokenID() : $token;
        }
        else {
        	return $_SESSION[$tokename];
        }
    }

    /**
     *
     * initialise les variables de session
     * @param array() $session
     * @throws Exception
     * @internal param bool $debug
     */
    private function iniSessionVar($session){
        if(is_array($session)){
            foreach($session as $row => $val){
                $_SESSION[$row] = $val;
            }
        }else{
            throw new Exception('session init is not array');
        }
    }

    /**
     * @access public
     * Initialise la session ou renouvelle la session
     * @param $session_tabs
     * @param bool $setOption
     * @internal param array $session
     * @internal param bool $debug
     */
    public function run($session_tabs=false,$setOption=false){
        try {
            if($setOption != false){
                $setOption;
            }
            if($session_tabs != false){
                $this->iniSessionVar($session_tabs);
            }
        }catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }

    /**
     * @param bool $requestIp
     * @return bool
     */
    public function ip($requestIp=false){
        $matcher = new http_requestMatcher();
        if($matcher->checkIp(parent::getIp(),parent::getIp()) == true){
            return parent::getIp();
        }
    }

    /**
     * @return browser
     */
    public function browser(){
        return parent::getBrowser();
    }
    /**
     *
    $session = new http_session();
    if(!http_request::isSession('panier')){
        $array_sess = array(
            'panier'=>'test',
            'outils'=>'Le marteau du peuple'
        );
        $session->session_start('masession');
        $session->session_run($array_sess);
    }else{
        $session->debug();
    }
     */
    /**
     * @access public
     * Affiche le debug pour les sessions
     */
    public function debug(){
        var_dump($_SESSION);
    }
}
abstract class sessionUtils{
    /**
     * function register real IP
     * @return string
     */
    function getIp(){
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        }else{
            if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
                $realip = getenv( 'HTTP_X_FORWARDED_FOR' );
            } elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
                $realip = getenv( 'HTTP_CLIENT_IP' );
            } else {
                $realip = getenv( 'REMOTE_ADDR' );
            }
        }
        return $realip;
    }
    /**
     * function getBrowser
     * @return browser
     */
    function getBrowser(){
        $user_agent = getenv("HTTP_USER_AGENT");
        if ((strpos($user_agent, "Nav") !== FALSE) || (strpos($user_agent, "Gold") !== FALSE) ||
            (strpos($user_agent, "X11") !== FALSE) || (strpos($user_agent, "Mozilla") !== FALSE) ||
            (strpos($user_agent, "Netscape") !== FALSE)
            AND (!strpos($user_agent, "MSIE") !== FALSE)
                AND (!strpos($user_agent, "Konqueror") !== FALSE)
                    AND (!strpos($user_agent, "Firefox") !== FALSE)
                        AND (!strpos($user_agent, "Safari") !== FALSE))
            $browser = "Netscape";
        elseif (strpos($user_agent, "Opera") !== FALSE)
            $browser = "Opera";
        elseif (strpos($user_agent, "MSIE") !== FALSE)
            $browser = "MSIE";
        elseif (strpos($user_agent, "Lynx") !== FALSE)
            $browser = "Lynx";
        elseif (strpos($user_agent, "WebTV") !== FALSE)
            $browser = "WebTV";
        elseif (strpos($user_agent, "Konqueror") !== FALSE)
            $browser = "Konqueror";
        elseif (strpos($user_agent, "Safari") !== FALSE)
            $browser = "Safari";
        elseif (strpos($user_agent, "Firefox") !== FALSE)
            $browser = "Firefox";
        elseif ((stripos($user_agent, "bot") !== FALSE) || (strpos($user_agent, "Google") !== FALSE) ||
            (strpos($user_agent, "Slurp") !== FALSE) || (strpos($user_agent, "Scooter") !== FALSE) ||
            (stripos($user_agent, "Spider") !== FALSE) || (stripos($user_agent, "Infoseek") !== FALSE))
            $browser = "Bot";
        else
            $browser = "Autre";
        return $browser;
    }
}
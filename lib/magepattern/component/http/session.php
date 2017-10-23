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
    /**
     * @return string
     */
    protected function _regenerate(){
        // Regenerate the session id
        session_regenerate_id();

        return session_id();
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
     * @access private
     * Démarre une nouvelle session
     */
    public function start($session_name='mp_default_s'){
        if(isset($session_name)){
            $name = $session_name;
        }
        $string = $_SERVER['HTTP_USER_AGENT'];
        $string .= 'SHIFLETT';
        /* Add any other data that is consistent */
        $fingerprint = md5($string);
        //Fermeture de la première session, ses données sont sauvegardées.
        if (!isset($_SESSION)) {
            session_cache_limiter('nocache');
        }
        $this->_write();
        session_name($name);
        ini_set('session.hash_function',1);
        session_start();
    }
    public function delete(){
        session_unset();
        $_SESSION = array();
        session_destroy();
        session_start();
    }
    public function clear(){
        session_destroy();
    }

    /**
     * Création d'un token
     * @param $tokename
     * @return array
     */
    public function token($tokename){
        if (empty($_SESSION[$tokename])){
            return $_SESSION[$tokename] = filter_rsa::tokenID();
        }else{
            if (isset($_SESSION[$tokename])){
                return $_SESSION[$tokename];
            }
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
?>
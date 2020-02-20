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

class component_core_language{
    /**
     * lang setting conf
     * @var bool
     */
    protected $template,
		$setLanguage,
        $getLanguage,
        $setParams;

	/**
	 * component_core_language constructor.
	 * @param install_model_template|backend_model_template|frontend_model_template $t
	 */
	public function __construct($t){
		$this->template = $t;
        /*if(!empty($setParams)){
            $this->setParams = $setParams;
            if (http_request::isGet($setParams)){
                $this->getLanguage = $_GET[$setParams];
            }
        }*/
        $this->init();
	}

    /**
     * Return language elseif default
     * @return bool|string
     */
    public function setLanguage(){
        if(isset($this->getLanguage)){
            if(!empty($this->getLanguage)){
                $lang = $_SESSION[$this->setParams];//form_inputFilter::isAlphaNumericMax($_SESSION[self::$setParams],5);
            }
            else {
                $lang = 'fr';
            }

        }
        else
        	{
            if(http_request::isSession($this->setParams)){
                $lang = $_SESSION[$this->setParams];//form_inputFilter::isAlphaNumericMax($_SESSION[self::$setParams],5);
            }
            else {
                $lang = 'fr';
            }
        }
        return $lang;
    }

    /**
     * @return array
     */
    private function getAcceptedLanguages() {
        $httplanguages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $languages = array();
        if (empty($httplanguages)) {
            return $languages;
        }

        foreach (preg_split('/,\s*/', $httplanguages) as $accept) {
            $result = preg_match('/^([a-z]{1,8}(?:[-_][a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accept, $match);

            if (!$result) {
                continue;
            }
            if (isset($match[2])) {
                $quality = (float)$match[2];
            }
            else {
                $quality = 1.0;
            }

            $countries = explode('-', $match[1]);
            $region = array_shift($countries);
            $country_sub = explode('_', $region);
            $region = array_shift($country_sub);

            foreach($countries as $country)
                $languages[$region . '_' . strtoupper($country)] = $quality;

            foreach($country_sub as $country)
                $languages[$region . '_' . strtoupper($country)] = $quality;

            $languages[$region] = $quality;
        }

        return $languages;
    }

    /**
     * @return array|int|string
     */
    private function initLang(){
        /*$language = $this->template->lang;
		$user_langs = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);

		foreach($user_langs as $ul) {
			$iso = strtolower(substr(chop($ul),0,2));

			if(array_key_exists($iso,$this->template->langs)) {
				$language = $iso;
				break;
			}
		}*/

		/*if(empty($_SESSION[$this->setParams]) || !empty($this->getLanguage)) {
            return $_SESSION[$this->setParams] = empty($this->getLanguage) ? $language : $this->getLanguage;
		}
		else {
			return $this->getLanguage = $language;
		}*/
		if(empty($_SESSION[$this->setParams])) $_SESSION[$this->setParams] = $this->template->lang;
	}

    /**
     * Retourne l'OS courant si windows
     */
    private function getOS(){
        if(stripos($_SERVER['HTTP_USER_AGENT'],'win')){
            return 'windows';
        }
    }

    /**
     * Modification du setlocale suivant la langue courante pour les dates
     */
    private function setTimeLocal(){
    	$conf = array('locale' => 'en_US','other' => 'en');
    	switch ($this->template->lang) {
			case 'nl':
				$conf['locale'] = 'nl_NL';
				$conf['other'] = 'nl';
				break;
			case 'fr':
			case 'fr-ca':
				$conf['locale'] = 'fr_FR';
				$conf['other'] = 'fra';
				break;
			case 'de':
				$conf['locale'] = 'de_DE';
				$conf['other'] = 'de';
				break;
			case 'es':
				$conf['locale'] = 'es_ES';
				$conf['other'] = 'es';
				break;
			case 'it':
				$conf['locale'] = 'it_IT';
				$conf['other'] = 'it';
				break;
		}

		setlocale(LC_TIME, $conf['locale'].'.UTF8',$conf['other']);
    }

    /**
     * Initialisation de la création de session de langue
     * @param bool $debug
     */
    public function init($debug = false){
        $session = new http_session(0);
        $session->start('lang');
        if($debug){
            $session->debug();
        }
        $this->initLang();
        $this->setTimeLocal();
	}
}
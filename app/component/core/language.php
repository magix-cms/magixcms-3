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
    public static $setLanguage,
        $getLanguage,
        $setParams;
	/**
	 * function construct class
	 *
	 */
	public function __construct($setParams){
        if(!empty($setParams)){
            self::$setParams = $setParams;
            if (http_request::isGet($setParams)){
                self::$getLanguage = $_GET[$setParams];//form_inputFilter::isAlpha($_GET[$setParams]);
            }
        }

	}

    /**
     * Return language elseif default
     * @return bool|string
     */
    public static function setLanguage(){
        if(isset(self::$getLanguage)){
            if(!empty(self::$getLanguage)){
                $lang = $_SESSION[self::$setParams];//form_inputFilter::isAlphaNumericMax($_SESSION[self::$setParams],5);
            }else{
                $lang = 'fr';
            }

        }else{
            if(http_request::isSession(self::$setParams)){
                $lang = $_SESSION[self::$setParams];//form_inputFilter::isAlphaNumericMax($_SESSION[self::$setParams],5);
            } else {
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
        $CollectionTools = new collections_ArrayTools();
        $langCollection = $CollectionTools->defaultLanguage();
		$language = explode(",",$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $language = strtolower(substr(chop($language[0]),0,2));
        foreach($langCollection as $key => $value){
            if(array_key_exists($key,$langCollection)){
                    switch ($language) {
                        case $key:
                            $language = $key;
                            break;
                        default:
                            $language = 'fr';
                            break;
                    }
            }else{
                $language = 'fr';
            }
        }

		if(empty($_SESSION[self::$setParams]) || !empty(self::$getLanguage)) {
            return $_SESSION[self::$setParams] = empty(self::$getLanguage) ? $language : self::$getLanguage;
		}else{
            if (http_request::isGet(self::$setParams)) {
				return self::$getLanguage  = $language;
			}
		}
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
        if(self::setLanguage() == 'nl'){
            setlocale(LC_TIME, 'nl_NL.UTF8','nl');
        }elseif(self::setLanguage() == 'fr' || self::setLanguage() == 'fr-ca'){
            setlocale(LC_TIME, 'fr_FR.UTF8', 'fra');
        }elseif(self::setLanguage() == 'de'){
            setlocale(LC_TIME, 'de_DE.UTF8', 'de');
        }elseif(self::setLanguage() == 'es'){
            setlocale(LC_TIME, 'es_ES.UTF8', 'es');
        }elseif(self::setLanguage() == 'it'){
            setlocale(LC_TIME, 'it_IT.UTF8', 'it');
        }else{
            setlocale(LC_TIME, 'en_US.UTF8', 'en');
        }
    }

    /**
     * Initialisation de la création de session de langue
     * @param bool $debug
     */
    public function run($debug = false){
        $session = new http_session();
        $session->start('lang');
        if($debug){
            $session->debug();
        }
        self::initLang();
        self::setTimeLocal();
	}
}
?>
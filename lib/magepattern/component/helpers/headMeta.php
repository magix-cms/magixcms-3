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

class helpers_headMeta{
	/**
	 * Instance
	 * @var void
	 * @access private
	 */
	private static $instance = null;
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const html = 'html';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const xhtml = 'xhtml';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const rdf = 'rdf';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const xbl = 'xbl';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const xml = 'xml';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const rtf = 'rtf';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const css = 'css';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const txt = 'txt';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const xul = 'xul';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const rss = 'rss';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const smil = 'smil';
	/**
	 * Constante type Content-Type
	 * @var void
	 */
	const svg = 'svg';
	/**
	 * Constante Charset for Constante-type
	 * @var void
	 */
	const utf8 = 'utf8';
    
	/**
     * instance singleton
     * @access public
     */
    private static function getInstance(){
    	if (!isset(self::$instance)){
    		if(is_null(self::$instance)){
				self::$instance = new helpers_headMeta();
			}
      	}
		return self::$instance;
    }
	/**
	 * 
	 * Ini meta http-equiv
	 * 
	 * @param string $httpequiv
	 * @param string $content
     * @return string
     * @access protected
	 */
	protected function http_equiv($httpequiv,$content){
		if(self::getInstance()){
			return '<meta http-equiv="'.$httpequiv.'" content="'.$content.'" />';//.PHP_EOL;
		}
	}
	/**
	 * 
	 * Ini meta name
	 * 
	 * @param string $name
	 * @param string $content
     * @return string
     * @access protected
	 * 
	 */
	protected function name($name,$content){
		if(self::getInstance()){
			return '<meta name="'.$name.'" content="'.$content.'" />';//.PHP_EOL;
		}
	}

    /**
     * Config charset string
     * @param void $charset
     *
     * @return string (string)
     */
	private function charset($charset){
		if(self::getInstance()){
			switch($charset){
				case self::utf8:
					$chrs = 'charset=utf-8';
					break;
				default:
					$chrs = 'charset=iso-8859-1';
			}
			return $chrs;
		}
	}

    /**
     * Add content for css function
     * @param string $css
     *
     * @access protected
     * @return string (string)
     */
	private function css($css){
		if(self::getInstance()){
			if($css == self::css)
				return 'text/css';
		}
	}

    /**
     * Define delay for revisit-after
     * @param string $delay
     *
     * @return string (string)
     */
	private function delayRevisit($delay){
		if(self::getInstance()){
			switch($delay){
				case 'days':
					return 'days';
					break;
				case 'weeks':
					return 'weeks';
					break;
				case 'month':
					return 'month';
					break;
			}
		}
	}

    /**
     * Function control intéger params
     * @param intéger $int
     * @throws Exception
     * @return \intéger
     */
	private function numRevisit($int){
		if(self::getInstance()){
			if(is_numeric($int)){
				return $int;
			}
			throw new Exception('Error argument "int" is not numeric');
		}
	}

    /**
     * Add meta http-equiv Content-Type
     * @param string $content
     * @param string $charset
     *
     * @throws Exception
     * @access public
     * @example
     * helpers_headMeta::contentType('html','utf8');
     * <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     * @return string
     */
	public static function contentType($content=null,$charset){
		if(!null == $content){
			switch($content){
				case self::html:
					$type = 'text/html; '.self::getInstance()->charset($charset);
					break;
			}
			return self::getInstance()->http_equiv('Content-Type',$type);
		}
		throw new Exception('Missing argument content in Content-Type!!');
	}

    /**
     * Add meta http-equiv Content-Type-Style
     * @param string $style
     *
     * @throws Exception
     * @access public
     * @example
     * helpers_headMeta::contentStyleType('css');
     * <meta http-equiv="Content-Style-Type" content="text/css" />
     * @return string
     */
	public static function contentStyleType($style=null){
		if(!null == $style){
			switch($style){
				case self::css:
					$content = self::getInstance()->css(self::css);
					break;
			}
			return self::getInstance()->http_equiv('Content-Style-Type',$content);
		}
		throw new Exception('Missing argument style in Content-Style-Type!!');
	}

	/**
	 * Add meta http-equiv Content-Language
	 * @param string $content
	 * 
	 * @access public
	 * @example 
	 * helpers_headMeta::contentLanguage('fr,en,nl');
	 * <meta http-equiv="Content-Language" content="fr,en,nl" />
	 * @return string
	 */
	public static function contentLanguage($content){
		return self::getInstance()->http_equiv('Content-Language',$content);
	}

	/**
	 * Add meta name revisit-after
	 * @param intéger $int
	 * @param string $delay
	 * 
	 * @access public
	 * @example 
	 * helpers_headMeta::revisitAfter(3,'days');
	 * <meta name="revisit-after" content="3 days" />

	 * @return string
	 */
	public static function revisitAfter($int,$delay){
		return self::getInstance()->name('revisit-after',self::getInstance()->numRevisit($int).' '.self::getInstance()->delayRevisit($delay));
	}

	/**
	 * Add meta name robots
	 * @param string $content
	 * 
	 * @access public
	 * @example 
	 * helpers_headMeta::robots('index, follow, all');
	 * <meta name="robots" content="index, follow, all" />
	 * @return string
	 */
	public static function robots($content){
		return self::getInstance()->name('robots',$content);
	}

	/**
	 * Add meta name googleSiteVerification
	 * @param string $content
	 * 
	 * @access public
	 * @example 
	 * helpers_headMeta::googleSiteVerification('+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34=');
	 * <meta name="google-site-verification" content="+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34=" />
	 * @return string
	 */
	public static function googleSiteVerification($content){
		return self::getInstance()->name('google-site-verification',$content);
	}

	/**
	 * Add meta name keywords
	 * @param string $content
	 * 
	 * @access public
	 * @example 
	 * helpers_headMeta::keywords('magixcjquery,jquery,ajax');
	 * <meta name="keywords" content="magixcjquery,jquery,ajax" />
	 * @return string
	 */
	public static function keywords($content){
		return self::getInstance()->name('keywords',$content);
	}

	/**
	 * Add meta name description
	 * @param string $content
	 * 
	 * @access public
	 * @example 
	 * helpers_headMeta::description('my website');
	 * <meta name="description" content="my website" />
	 * @return string
	 */
	public static function description($content){
		return self::getInstance()->name('description',$content);
	}
}
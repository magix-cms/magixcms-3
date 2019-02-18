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

class helpers_headLink extends link_rel{
	/**
	 * Instance
	 * @var void
	 * @access private
	 */
	private static $instance = null;
	/**
	 * Constante application
	 * @var string
	 * @return application/
	 */
	const application = 'application/';
	/**
	 * Constante rss
	 * @var string
	 * @return rss+xml
	 */
	const rss = "rss+xml";
	/**
     * instance singleton
     * @access public
     */
    private static function getInstance(){
    	if (!isset(self::$instance)){
    		if(is_null(self::$instance)){
				self::$instance = new helpers_headLink();
			}
      	}
		return self::$instance;
    }
	/**
	 * 
	 * start Ini link meta
	 * 
	 * @access private
	 */
	private function startLink(){
		if(self::getInstance()){
			return '<link ';
		}
	}
	/**
	 * end link meta
	 * 
	 * @access private
	 */
	private function endLink(){
		if(self::getInstance()){
			return ' />'.PHP_EOL;
		}
	}

    /**
     *
     * helpers_headLink::linkStyleSheet()
     * <link rel="stylesheet" type="text/css" href="http://mydomaine.com/styles.css" media="screen" />
     *
     * @param string $href
     * @param string media
     * @return string
     */
	public static function linkStyleSheet($href,$media='screen'){
		if(self::getInstance()){
			return self::getInstance()->startLink().link_rel::stylesheet($href,$media).self::getInstance()->endLink();
		}
	}

    /**
     *
     * helpers_headLink::linkRss()
     * <link rel="alternate" type="application/rss+xml" href="http://mydomaine.com/rss.xml" />
     *
     * @param string $href
     * @return string
     */
	public static function linkRss($href){
		if(self::getInstance()){
			return self::getInstance()->startLink().link_rel::alternate(self::application.self::rss,$href).' title="RSS"'.self::getInstance()->endLink();
		}
	}
}
abstract class link_rel{
    /**
     * Protected define alternate params
     * @param string $type
     * @param string $href
     * @return string
     */
	protected static function alternate($type,$href){
		return 'rel="alternate" type="'.$type.'" href="'.$href.'"';
	}

    /**
     * Protected define alternate params
     * @param string $type
     * @param string $media
     * @return string
     */
	protected static function stylesheet($href,$media){
		return 'rel="stylesheet" type="text/css" href="'.$href.'" media="'.$media.'"';
	}
}
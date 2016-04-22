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

class helpers_headScript{

	/**
	 * Instance
	 * @var void
	 * @access private
	 */
	private static $instance = null;

	/**
     * instance singleton
     * @access public
     */
    private static function getInstance(){
    	if (!isset(self::$instance)){
    		if(is_null(self::$instance)){
				self::$instance = new helpers_headScript();
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
	private function startScript(){
		if(self::getInstance()){
			return '<script ';
		}
	}

	/**
	 * end script meta
	 * 
	 * @access private
	 */
	private function endScript(){
		if(self::getInstance()){
			return '</script>'.PHP_EOL;
		}
	}

	/**
	 * Retourne le type
	 * @param string $type
     * @return string
     */
	private function type($type){
		if(self::getInstance()){
			switch($type){
				case 'javascript':
					return 'type="text/javascript"';
				break;
			}
		}
	}

	/**
	 * 
	 * helpers_headScript::src($uri,$type)
	 * <script type="text/javascript" src="/monscript.js"></script>
	 * 
	 * @param string $uri
	 * @param string media
     * @return string
     */
	public static function src($uri,$type){
		if(self::getInstance()){
			return self::getInstance()->startScript().'src="'.$uri.'" '.self::getInstance()->type($type).'>'.self::getInstance()->endScript();
		}
	}
}
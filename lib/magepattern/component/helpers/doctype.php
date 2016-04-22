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

class helpers_doctype{
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const XHTML11             = 'XHTML11';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const XHTML1_STRICT       = 'XHTML1_STRICT';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const XHTML1_TRANSITIONAL = 'XHTML1_TRANSITIONAL';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const HTML4_STRICT        = 'HTML4_STRICT';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const HTML4_LOOSE         = 'HTML4_LOOSE';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const HTML5				  = 'HTML5';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const CUSTOM_XHTML        = 'CUSTOM_XHTML';
	/**
	 * Constante for doctype type
	 * @var string
	 */
	const CUSTOM              = 'CUSTOM';

	/**
	 * Default DocType
	 * @var string
     */
    protected static $_defaultDoctype = self::HTML4_LOOSE;

	/**
	 * Function construc class
	 */
	function __construct(){}

	/**
	 * 
	 * Add doctype 
	 * 
	 * @param string $doctype
     * @return string
     */
	public static function doctype($doctype=null){
		if (null !== $doctype) {
            switch ($doctype) {
            	case self::XHTML11:
            		$type = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
            		"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">';//.PHP_EOL;
            		break;
            	case self::XHTML1_STRICT:
            		$type = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   					"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';//.PHP_EOL;
            		break;
            	case self::XHTML1_TRANSITIONAL:
            		$type = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
            		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';//.PHP_EOL;
            		break;
            	case self::HTML4_STRICT:
            		$type = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
            		"http://www.w3.org/TR/html4/strict.dtd">';//.PHP_EOL;
            		break;
            	case self::HTML5:
            		$type = '<!doctype html>';//.PHP_EOL;
            		break;
            	case self::$_defaultDoctype:
            		$type = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
            		"http://www.w3.org/TR/html4/loose.dtd">';//.PHP_EOL;
            		break;
            }
		}
		return $type;
	}
}
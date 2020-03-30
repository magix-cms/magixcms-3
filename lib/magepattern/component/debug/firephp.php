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

//require dirname(__FILE__).('/FirePHPCore/FirePHP.class.php');
define('INSIGHT_IPS', '*');
define('INSIGHT_AUTHKEYS', '*');
define('INSIGHT_PATHS', dirname(__FILE__));
define('INSIGHT_SERVER_PATH', '/index.php');
define('INSIGHT_DEBUG', false);
//set_include_path(dirname(__FILE__) . '/lib-1.0b1rc6' . PATH_SEPARATOR . get_include_path());
if (MP_FIREPHP == false && !defined('FIREPHP_ACTIVATED')){
	define('FIREPHP_ACTIVATED', false);
}
//define('INSIGHT_CONFIG_PATH', dirname(__FILE__).'/package.json');
//require_once(dirname(__FILE__).('/lib/FirePHP/init.php'));
//require_once('lib-1.0b1rc6/FirePHP/Init.php');
/*
############
############ Using FirePHP on production sites can expose sensitive information ###########
############
*/
/**
 * @package debug with FirePHP
 * FirePHP (false or true)
 * define('M_FIREPHP',true);
 *
 */
class debug_firephp{
    /**
     * Instance FirePHP class
     * @var Instance
     * @access protected
     * @static
     */
  protected static $Instance;
	/**
	* timer start
	* @var timerStart
	*/
  protected static $timerStart = 0;
  /**
   * timer stop
   * @var timerEnd
   */
  protected static $timerEnd = 0;
  /**
   * @static function Instance
   * Singleton function
   */
  protected static function Instance(){
  		if (!isset(self::$Instance)){
         	self::$Instance = new FirePHP();
        }
    return self::$Instance;
  }

  /**
   * get Instance FirePHP and execute if M_FIREPHP = true
   **/
	protected static function getIniInstance(){
	    if (MP_FIREPHP){
	    	$firephp = self::Instance()->getInstance(true);
	    	$firephp->setLogToInsightConsole('Firebug');
	    }else{
	    	$firephp = self::Instance()->getInstance(false);
	    }
	    return $firephp;
	}
	protected function is_assoc($array) {
    	return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
  	}
   /**
   * Get options from the library
   *
   * @return array The currently set options
   */
  	public static function getOptions() {
  		return self::Instance()->getOptions();
  	}
  	/**
   * Set some options for the library
   * 
   * Options:
   *  - maxObjectDepth: The maximum depth to traverse objects (default: 10)
   *  - maxArrayDepth: The maximum depth to traverse arrays (default: 20)
   *  - useNativeJsonEncode: If true will use json_encode() (default: true)
   *  - includeLineNumbers: If true will include line numbers and filenames (default: true)
   * 
   * @param $maxObjectDepth
   * @param $maxArrayDepth
   * @param $useNativeJsonEncode
   * @param $includeLineNumbers
   * @return void
   */
  	public static function set_options($maxObjectDepth=10,$maxArrayDepth=20,$useNativeJsonEncode=true,$includeLineNumbers=true){
  		$tabs = array('maxObjectDepth' => $maxObjectDepth,
                 'maxArrayDepth' => $maxArrayDepth,
                 'useNativeJsonEncode' => $useNativeJsonEncode,
                 'includeLineNumbers' => $includeLineNumbers);
  		return self::Instance()->setOptions($tabs);
  	}

  /**
   * Specify a filter to be used when encoding an object
   * 
   * Filters are used to exclude object members.
   * 
   * @param string $Class The class name of the object
   * @param array $Filter An array of members to exclude
   * @return void
   * exemple :
   debug_firephp::setObjectFilter(
        'ClassName',
        array('MemberName')
   );
   */
  	public static function setObjectFilter($Class, $Filter){
  		return self::Instance()->setObjectFilter($Class, $Filter);
  	}

	/**
	 * function configErrorHandler
	 * Ini send Convert E_WARNING, E_NOTICE, E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE 
	 * and E_RECOVERABLE_ERROR to Firebug automatically.
	 * exemple in config application :
	 debug_firephp::configErrorHandler();
	 try {
	    throw new Exception('Test Exception');
	 catch(Exception $e) {
        debug_firephp::magixFireError($e);
     }
	 * 
	 */
  	public static function configErrorHandler(){
  		if (MP_FIREPHP){
  			// converts errors into exceptions
  			self::Instance()->registerErrorHandler($throwErrorExceptions=true);
  			// makes FirePHP handle exceptions and sends it to FirePHP
  			self::Instance()->registerExceptionHandler();
  			self::Instance()->registerAssertionHandler(
	  			$convertAssertionErrorsToExceptions=true,
	  			$throwAssertionExceptions=false
  			);
  		}
  	}

    /**
     *
     * Log object with label to firebug console
     *
     * @param $object
     * @param bool $label
     * @return
     */
  	public static function log($object,$label=false){
  		if(self::getIniInstance()){
  			return self::Instance()->log($object,$label);
  		}
  	}

    /**
     *
     * Log object with label to firebug console
     *
     * @param void $object
     * @param bool|string $label
     * @return
     */
	public static function info($object,$label=false){
  		if(self::getIniInstance()){
  			return self::Instance()->info($object,$label);
  		}
  	}

    /**
     *
     * Log object with label to firebug console
     *
     * @param void $object
     * @param bool|string $label
     * @return
     */
	public static function error($object,$label=false){
  		if(self::getIniInstance()){
  			return self::Instance()->error($object,$label);
  		}
  	}

    /**
     *
     * Log object with label to firebug console
     *
     * @param void $object
     * @param bool|string $label
     * @return
     */
	public static function warn($object,$label=false){
  		if(self::getIniInstance()){
  			return self::Instance()->warn($object,$label);
  		}
  	}

    /**
     * Start a group for following messages.
     *
     * @param void $object
     * @param bool|string $label
     * @return true
     */
	public static function group($object,$label=false){
  		if(self::getIniInstance()){
  			return self::Instance()->group($object,$label);
  		}
  	}

  /**
   * Ends a group you have started before
   *
   * @return true
   * @throws Exception
   */
	public static function groupEnd(){
  		if(self::getIniInstance()){
  			return self::Instance()->groupEnd();
  		}
  	}

    /**
     * Dumps key and variable to firebug server panel
     * @return true
     *
     * @param void $object
     * @param void $vars
     * @param bool|string $label (false)
     */
	public static function dump($object,$vars,$label=false){
  		if(self::getIniInstance()){
  			if (!is_array($vars)) {
  				self::log("vardump: ".$object.'=>'.$vars,$label);
  			}else {
				self::group("vardump: ".$object." (associative array)");
				if (self::is_assoc($vars)) {
					self::log("(");
					foreach($vars as $var=>$value) {
						self::log("['".$var."'] => ".$value);
					}
				}else {
					self::log("(");
					foreach($vars as $var) {
						self::log($var);
					}
				}
				self::log(")");
				self::groupEnd();
	        }
  		}
  	}

    /**
     * Log a table in the firebug console
     *
     * @see debug_firephp::magixFireTable
     * @param $label
     * @param $table
     * @internal param string $Label
     * @internal param string $Table
     * @return true
     * @example
     * $table   = array();
     * $table[] = array('Col 1 Heading','Col 2 Heading');
     * $table[] = array('Row 1 Col 1','Row 1 Col 2');
     * $table[] = array('Row 2 Col 1','Row 2 Col 2');
     * $table[] = array('Row 3 Col 1','Row 3 Col 2');
     */
  	public static function table($label,$table){
  		if(self::getIniInstance()){
  			return self::Instance()->table($label, $table);
  		}
  	}

    /**
     * Log a trace in the firebug console
     *
     * @see debug_firephp::TRACE
     * @param $label
     * @internal param string $Label
     * @return true
     */
  	public static function trace($label){
  		if(self::getIniInstance()){
  			return self::Instance()->trace($label);
  		}
  	}

	/**
	 * start Current Unix timestamp with microseconds
	 * @access protected
	 * 
	*/
	protected static function timeStart(){
	  	if(self::getIniInstance()){
		    self::$timerStart = microtime();
		    self::$timerEnd = 0;
	  	}
	}

	/**
	 * Stop Current Unix timestamp with microseconds
	 * @access protected
	*/
	protected static function timeStop(){
	  	if(self::getIniInstance()){
	    	self::$timerEnd =microtime();
	  	}
	}

  	/**
  	 * @see timeStart calculation with microtime
  	 * @access public
  	 */
  	public static function timerStart(){
  		if(self::getIniInstance()){
  			return self::timeStart();
  		}
  	}

  	/**
  	 * @see timeStop calculation end microtime
  	 * @access public
  	 */
	public static function timerStop(){
		if(self::getIniInstance()){
  			return self::timeStop();
		}
  	}

	/**
	 * @see calculation for execute start and stop
	 * @access protected
	 */
	 protected static function getResultCalculation(){
	    if(self::$timerEnd == 0) self::timerStop();
	      return self::$timerEnd - self::$timerStart;
	 }

  	/**
  	 * return result where Timerget 
  	 * @see getResultCalculation
  	 * @access public
  	 */
  	public static function timerResult(){
  		return self::log("execution time :". self::getResultCalculation() . ' seconds');
  	}
}
?>
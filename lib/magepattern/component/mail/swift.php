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
use CSSInliner\CSSInliner;

class mail_swift{
	/**
	 * 
	 * instance $_mailer
	 * @var $_mailer
	 */
	protected $_mailer;
	/**
	 * 
	 * défini les options de transport
	 * @var $_transport_options
	 */
	protected $_transport_options = array(
		        'setHost'		=>	'',
		        'setPort'		=>	25,
				'setEncryption'	=>	'',
				'setUsername'	=>	'',
				'setPassword'	=>	''
			);

    /**
     *
     * Constructor
     * @param string $type
     * @param null $Options
     * @internal param array $options
     */
	public function __construct($type,$Options=null){
		$this->_mailer = Swift_Mailer::newInstance(self::transportConfig($type,$Options));
	}
	/**
   * Get options from the library
   *
   * @return array The currently set options
   */
	private function getOptions() {
		return $this->_transport_options;
	}

    /**
     * INI transport
     * @param string $type
     * @param $Options
     * @throws
     * @internal param string $host
     * @internal param int $port
     * @return \Swift_MailTransport|\Swift_SmtpTransport
     * @access public
     * @static
     */
    private function transportConfig($type,$Options){
    	switch ($type){
    		case 'mail':
    			$config = Swift_MailTransport::newInstance();
    		break;
    		case 'smtp':
		    	if($Options) {
			      if(!is_array($Options)) {
			        throw $this->newException('Options must be defined as an array!');
			      }
			      else {
			      	
			      	$config = Swift_SmtpTransport::newInstance()
				      	->setHost($Options["setHost"])
	  					->setPort($Options["setPort"])
	  					->setEncryption($Options["setEncryption"])
				      	->setUsername($Options["setUsername"])
						->setPassword($Options["setPassword"]);
			      }
			    }else{
			    	$config = Swift_SmtpTransport::newInstance(self::getOptions());
			    }
    		break;
    	}
    	return $config;
    }

    /**
     * Le contenu du message (email,sujet,contenu,...)
     * @param string $subject
     * @param array|string $from
     * @param array|string $recipient
     * @param string $bodyhtml
     * @param bool $setReadReceiptTo
     * @internal param void $sw_message
     * @internal param string $bodytxt
     * @return \Swift_Mime_Message
     * @access public
     * @static
     */
	public function body_mail($subject,$from=array(),$recipient=array(),$bodyhtml,$setReadReceiptTo=false){
		$sw_message = Swift_Message::newInstance();
		$sw_message->getHeaders()->get('Content-Type')->setValue('text/html');
		$sw_message->getHeaders()->get('Content-Type')->setParameter('charset', 'utf-8');
		$sw_message->setSubject($subject)
		       ->setEncoder(Swift_Encoding::get8BitEncoding())
		       ->setFrom($from)
		       ->setTo($recipient)
		       ->setBody($bodyhtml,'text/html')
		       ->addPart(form_inputEscape::tagClean($bodyhtml),'text/plain');
		if($setReadReceiptTo){
			$sw_message->setReadReceiptTo($setReadReceiptTo);
		}
		return $sw_message;
    }

    /**
     * Plugin decorator
     * @param string replacement
     * @internal param void $mailer
     */
    public function plugin_decorator($replacements){
    	$decorator = new Swift_Plugins_DecoratorPlugin($replacements);
		$this->_mailer->registerPlugin($decorator);
    }

    /**
     * Plugin antiflood
     * @param string $threshold
     * @param $sleep
     * @internal param void $mailer
     */
	public function plugin_antiflood($threshold, $sleep){
    	//Use AntiFlood to re-connect after 100 emails specify a time in seconds to pause for (30 secs)
		$antiflood = new Swift_Plugins_AntiFloodPlugin($threshold, $sleep);
		$this->_mailer->registerPlugin($antiflood);
    }

    /**
     * Plugin throttler
     * @param $rate
     * @param $mode
     * @internal param void $mailer
     */
	public function plugin_throttler($rate,$mode){
    	//Use AntiFlood to re-connect after 100 emails specify a time in seconds to pause for (30 secs)
		$throttler = new Swift_Plugins_ThrottlerPlugin($rate,$mode);
		//Rate limit to 10MB per-minute OR Rate limit to 100 emails per-minute
		$this->_mailer->registerPlugin($throttler);
    }

    /**
     * fusion des plugins anti flood et throttler pour un envoi de masse
     * @param integer $threshold
     * @param integer $sleep
     * @param string $throttlermode
     * @throws Exception
     * @internal param void $mailer
     */
    public function plugins_massive_mailer($threshold = 100, $sleep = 10,$throttlermode = 'bytes'){
    	try {
	    	switch($throttlermode){
	    		case "bytes" :
	    			$rate = 1024 * 1024 * 10;
	    			$mode = Swift_Plugins_ThrottlerPlugin::BYTES_PER_MINUTE;
	    			break;
	    		case "messages" :
	    			$rate = 100;
	    			$mode = Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE;
	    			break;
	    		default:
	    			$rate = 100;
	    			$mode = Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE;
	    			break;
	    	}
	    	if(!empty($threshold) AND !empty($sleep) AND !empty($throttlermode)){
	    		if(!is_numeric($threshold)){
	    			throw new Exception("threshold is not numeric");
	    		}elseif(!is_numeric($sleep)){
	    			throw new Exception("sleep is not numeric");
	    		}else{
	    			$this->plugin_antiflood($threshold, $sleep);
					$this->plugin_throttler($rate, $mode);
	    		}
	    	}
    	}catch(Exception $e) {
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }

    /**
     * CSS Inliner for responsive mail
     * @param $html
     * @param null $css
     * @param string $path
     * @param bool $debug
     * @return string
     */
	public function plugin_css_inliner($html, $css = null, $path = '', $debug = false) {
		$inliner = new CSSInliner();

		if($css != null) {
			if(is_array($css)) {
				foreach ($css as $dir => $c) {
					if (is_array($c)) {
						foreach ($c as $d => $file) {
							$inliner->addCSS($path. $d . '/' . $file);
						}
					} else {
						$inliner->addCSS($path. $dir . '/' . $c);
					}
				}
			}
		}

		return $inliner->render($html,$debug);
	}

	/**
	 * Envoi du message avec la méthode batch send
	 * @param string $message
	 * @param bool $failures
	 * @param bool $log
	 * @internal param void $mailer
	 * @internal param void $failed
	 * @internal param string $logger
	 * @return bool
	 */
    public function batch_send_mail($message,$failures=false,$log=false){
    	if(!$this->_mailer->send($message)){
    		if($failures)
            	print ("Failures: ". $failures);
    		else
    			return false;
    	}
    	else {
    		return true;
		}
    	if($log){
	    	$echologger = new Swift_Plugins_Loggers_EchoLogger();
			$this->_mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($echologger));
	    	print "Failures: ".$echologger->dump();
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('mail', 'Failures', 'Failures : '.$echologger->dump(), debug_logger::LOG_VOID);
    	}
    }
}
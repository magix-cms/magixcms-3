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
 * Created by SC BOX.
 * User: aureliengerits
 * Date: 25/07/12
 * Time: 22:57
 *
 */
class http_curl{
    /**
     * Vérifie si l'extension curl est disponible
     * @return bool
     */
    private function curl_exist(){
        if (extension_loaded('curl')) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Copie le fichiers distant dans le dossier de destination
     * @param $url
     * @param $directory
     * @param null $status
     * @param bool $debug
     * @return bool
     */
    public function copyRemoteFile($url, $directory, $status = null, $debug = false){
        try{
            if (self::curl_exist()) {
                //INIT curl
                $ch = curl_init ($url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                //curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
                //curl_setopt($ch, CURLOPT_NOBODY,true);
                // The maximum number of seconds to allow cURL functions to execute.
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,60);
                curl_setopt($ch, CURLOPT_TIMEOUT,30);
                // Tell curl to stop when it encounters an error
                curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                $data = curl_exec($ch);
                if(!curl_errno($ch)){
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                }else{
                    return false;
                }
                curl_close ($ch);
                if($debug){
                    $firephp = new debug_firephp();
                    $firephp->log($httpCode);
                }
                if($data != false){
                    if($status === null){
                        if($httpCode < 400){
                            if(!file_exists($directory)){
                                $fp = fopen($directory,'wb');
                                fwrite($fp, $data);
                                fclose($fp);
                            }
                            //clearstatcache();
                        }else{
                            return false;
                        }
                    }elseif($status == $httpCode){
                        if(!file_exists($directory)){
                            $fp = fopen($directory,'wb');
                            fwrite($fp, $data);
                            fclose($fp);
                        }
                        //clearstatcache();
                    }
                }
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }

    /**
     * @access public
     * Check si le domaine est disponible
     * @param $url
     * @param bool $ssl
     * @param bool $debug
     * @internal param string $domain
     * @return bool
     */
    public function isDomainAvailible($url,$ssl = false,$debug=false) {
        try{
            if (self::curl_exist()) {
                //check, if a valid url is provided
                if(!filter_var($url, FILTER_VALIDATE_URL)){
                    return false;
                }
                //initialize curl
                $curlInit = curl_init($url);
                curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
                curl_setopt($curlInit,CURLOPT_HEADER,true);
                curl_setopt($curlInit,CURLOPT_NOBODY,true);
                curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
                if($ssl){
                    /*Vérification SSL*/
                    curl_setopt($curlInit, CURLOPT_SSL_VERIFYPEER, false);
                }
                //get answer
                $response = curl_exec($curlInit);
                curl_close($curlInit);
                if($debug){
                    $firephp = new debug_firephp();
                    $firephp->log($response);
                }
                if ($response) return true;
                return false;
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }
}
?>

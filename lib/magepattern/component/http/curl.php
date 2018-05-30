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
            if ($this->curl_exist()) {
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
            $logger->log('error', 'php Curl', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
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
            if ($this->curl_exist()) {
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
                $curlInfo = curl_getinfo($curlInit);
                curl_close($curlInit);
                if ($debug) {
                    var_dump($curlInfo);
                    var_dump($response);
                }
                /*if ($response) return true;
                return false;*/
                if ($curlInfo['http_code'] == '200') {
                    if ($response) {
                        return true;
                    }
                }else{
                    return false;
                }
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php Curl', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_VOID);
        }
    }
    /* ##################################### Utility with Curl for External Web Service ##########################################*/
    /**
     * Prepare request Data with Curl (no files)
     * @param $data
     * @return mixed
     *
    $json = json_encode(array(
    'category'=>array(
    'id'  =>'16'
    )));
    print_r($json);
    print $this->webservice->setPrepareSendData(array(
    'wsAuthKey' => $this->setWsAuthKey(),
    'method' => 'xml',
    'data' => $test,
    'customRequest' => 'DELETE',
    'debug' => false,
    'url' => 'http://www.mywebsite.tld/webservice/catalog/categories/'
    ));
     */
    public function setPrepareSendData($data){
        try {
            if ($this->curl_exist()) {
                $curl_params = array();
                $encodedAuth = $data['wsAuthKey'];
                $generatedData = urlencode($data['data']);
                switch ($data['method']) {
                    case 'json';
                        $headers = array("Authorization : Basic " . $encodedAuth, 'Content-type: application/json', 'Accept: application/json');
                        break;
                    case 'xml';
                        $headers = array("Authorization : Basic " . $encodedAuth, 'Content-type: text/xml', 'Accept: text/xml');
                        break;
                }

                $options = array(
                    CURLOPT_HEADER => 0,
                    CURLINFO_HEADER_OUT => 1,                // For debugging
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_NOBODY => false,
                    CURLOPT_URL => $data['url'],
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD => $encodedAuth,
                    CURLOPT_HTTPHEADER => $headers,
                    //CURLOPT_POST          => true,
                    //CURLOPT_FORBID_REUSE  => 1,
                    //CURLOPT_FRESH_CONNECT =>1,
                    CURLOPT_CONNECTTIMEOUT => 300,
                    CURLOPT_CUSTOMREQUEST => $data['customRequest'],
                    CURLOPT_POSTFIELDS => $generatedData,
                    CURLOPT_SSL_VERIFYPEER => false
                    //CURLOPT_SAFE_UPLOAD     => false*/
                );
                $ch = curl_init();
                curl_setopt_array($ch, $options);
                $response = curl_exec($ch);
                $curlInfo = curl_getinfo($ch);
                curl_close($ch);
                // Data
                /*$header = trim(substr($response, 0, $curlInfo['header_size']));
                $body = substr($response, $curlInfo['header_size']);

                print_r(array('status' => $curlInfo['http_code'], 'header' => $header, 'data' => json_decode($body)));*/
                if (array_key_exists('debug', $data) && $data['debug']) {
                    var_dump($curlInfo);
                    var_dump($response);
                }
                if ($curlInfo['http_code'] == '200') {
                    if ($response) {
                        switch ($data['type']){
                            case 'print':
                                print ($response);
                                break;
                            case 'return':
                            default:
                                return $response;
                        }
                    }
                }
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php Curl', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Prepare post Img with Curl (files only)
     * @param $data
     * @return mixed
    print $this->webservice->setPreparePostImg(array(
    'wsAuthKey' =>  $this->setWsAuthKey(),
    'url'       => 'http://www.website.tld/webservice/catalog/categories/3',
    'debug' => false,
    ));
     */
    public function setPreparePostImg($data){
        try {
            if ($this->curl_exist()) {
                if (isset($_FILES)) {
                    $ch = curl_init();

                    $curl_params = array();
                    $encodedAuth = $data['wsAuthKey'];

                    $img = array(
                        'img' =>
                            '@' . $_FILES['img']['tmp_name']
                            . ';filename=' . $_FILES['img']['name']
                            . ';type=' . $_FILES['img']['type']
                    );

                    $options = array(
                        CURLOPT_HEADER          => 0,
                        CURLOPT_RETURNTRANSFER  => true,
                        CURLINFO_HEADER_OUT     => true,
                        CURLOPT_URL             => $data['url'],
                        CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
                        CURLOPT_USERPWD         => $encodedAuth,
                        CURLOPT_HTTPHEADER      => array("Authorization : Basic " . $encodedAuth/*,"Content-Type: multipart/form-data"*/),
                        CURLOPT_CUSTOMREQUEST   => "POST",
                        CURLOPT_POST            => true,
                        CURLOPT_POSTFIELDS      => $img,
                        CURLOPT_SSL_VERIFYPEER => false
                        //CURLOPT_SAFE_UPLOAD   => false
                    );
                    $ch = curl_init();
                    curl_setopt_array($ch, $options);
                    $response = curl_exec($ch);
                    $curlInfo = curl_getinfo($ch);
                    curl_close($ch);
                    if(array_key_exists('debug',$data) && $data['debug']){
                        var_dump($curlInfo);
                        var_dump($response);
                    }
                    if ($curlInfo['http_code'] == '200') {
                        if ($response) {
                            return $response;
                        }
                    }
                }
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php Curl', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Send Copy file on remote url
     * @param $data
     * @return mixed
     */
    public function setSendCopyImg($data){
        try {
            if ($this->curl_exist()) {
                if (isset($data['file'])) {
                    $encodedAuth = $data['wsAuthKey'];
                    $img = array(
                        /*'img' =>
                            '@' . $data['file']
                            . ';filename=' . $data['filename'],*/
                        //. ';type=image/jpeg'
                        'data'  =>  $data['data']
                    );


                    if ((version_compare(PHP_VERSION, '5.5') >= 0)) {
                        //$img['img'] = new CURLFile($data['file']. ';filename=' . $data['filename']);
                        $img['img'] = new CURLFile($data['file']);
                        $options = array(
                            CURLOPT_HEADER          => 0,
                            CURLOPT_RETURNTRANSFER  => true,
                            CURLINFO_HEADER_OUT     => true,
                            CURLOPT_URL             => $data['url'],
                            CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
                            CURLOPT_USERPWD         => $encodedAuth,
                            CURLOPT_HTTPHEADER      => array("Authorization : Basic " . $encodedAuth/*,"Content-Type: image/jpeg"*//*,"Content-Type: multipart/form-data"*/),
                            //CURLOPT_CUSTOMREQUEST   => "POST",
                            CURLOPT_POST            => true,
                            CURLOPT_POSTFIELDS      => $img,
                            CURLOPT_SSL_VERIFYPEER => false
                            //CURLOPT_VERBOSE         => true,
                            //CURLOPT_SAFE_UPLOAD     => false
                        );
                        //curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                    } else {
                        $img['img'] = '@' . $data['file']
                            . ';filename=' . $data['filename'];
                        $options = array(
                            CURLOPT_HEADER          => 0,
                            CURLOPT_RETURNTRANSFER  => true,
                            CURLINFO_HEADER_OUT     => true,
                            CURLOPT_URL             => $data['url'],
                            CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
                            CURLOPT_USERPWD         => $encodedAuth,
                            CURLOPT_HTTPHEADER      => array("Authorization : Basic " . $encodedAuth/*,"Content-Type: image/jpeg"*//*,"Content-Type: multipart/form-data"*/),
                            //CURLOPT_CUSTOMREQUEST   => "POST",
                            CURLOPT_POST            => true,
                            CURLOPT_POSTFIELDS      => $img,
                            CURLOPT_SSL_VERIFYPEER => false
                        );
                    }
                    $ch = curl_init();
                    curl_setopt_array($ch, $options);
                    $response = curl_exec($ch);
                    $curlInfo = curl_getinfo($ch);
                    curl_close($ch);
                    if(array_key_exists('debug',$data) && $data['debug']){
                        var_dump($curlInfo);
                        var_dump($response);
                    }

                    if ($curlInfo['http_code'] == '200') {
                        if ($response) {
                            return $response;
                        }
                    }
                }
            }
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('error', 'php Curl', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function setPrepareGet($data){
        try {
            if ($this->curl_exist()) {
                $curl_params = array();
                $encodedAuth = $data['wsAuthKey'];
                $options = array(
                    CURLOPT_RETURNTRANSFER => true,
                    CURLINFO_HEADER_OUT => true,
                    CURLOPT_URL => $data['url'],
                    CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                    CURLOPT_USERPWD => $encodedAuth,
                    CURLOPT_HTTPHEADER => array("Content-Type: text/xml; charset=utf-8"),
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_CUSTOMREQUEST => "GET"
                );

                $ch = curl_init();
                curl_setopt_array($ch, $options);

                $response = curl_exec($ch);
                $curlInfo = curl_getinfo($ch);
                curl_close($ch);
                if (array_key_exists('debug', $data) && $data['debug']) {
                    var_dump($curlInfo);
                    var_dump($response);
                }
                if ($curlInfo['http_code'] == '200') {
                    if ($response) {
                        return $response;
                    }
                }

            }
        }catch
            (Exception $e){
                $logger = new debug_logger(MP_LOG_DIR);
                $logger->log('error', 'php Curl', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
}
?>

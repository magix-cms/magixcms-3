<?php
class frontend_model_webservice{
    protected $curl;
    public $img;
    /**
     * @var array
     */
    public $contentTypeCollection = array(
        'application/json'      =>'json',
        'text/xml'              =>'xml',
        'application/xml'       =>'xml',
        'multipart/form-data'   =>'files'
    );

    /**
     * frontend_model_webservice constructor.
     */
    public function __construct()
    {
        $this->curl = new http_curl();
        // --- Image Upload
        if(isset($_FILES['img']["name"])){
            $this->img = http_url::clean($_FILES['img']["name"]);
        }
    }

    /* ##################################### Authentification ##########################################*/
    /**
     * @param $mcWsAuthKey
     * @return bool
     */
    public function authorization($mcWsAuthKey){
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/Basic\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            list($name, $password) = explode(':', base64_decode($matches[1]));
            $_SERVER['PHP_AUTH_USER'] = strip_tags($name);
        }
        //set http auth headers for apache+php-cgi work around if variable gets renamed by apache
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && preg_match('/Basic\s+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches)) {
            list($name, $password) = explode(':', base64_decode($matches[1]));
            $_SERVER['PHP_AUTH_USER'] = strip_tags($name);
        }
        // Use for image management (using the POST method of the browser to simulate the PUT method)
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($_SERVER['PHP_AUTH_USER']) AND !empty($_SERVER['PHP_AUTH_USER'])) {
            $key = base64_encode($_SERVER['PHP_AUTH_USER']);
        } elseif (isset($_GET['key_ws'])) {
            $key = base64_encode($_GET['key_ws']);
        } else {
            header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="Welcome to Magixcms Webservice, please enter the authentication key as the login. No password required."');
            die('401 Unauthorized');
        }

        if($key === base64_encode($mcWsAuthKey)){
            return true;
        }else{
            header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="Welcome to Magixcms Webservice, please enter the authentication key as the login. No password required."');
            die('401 Unauthorized');
        }
    }
    /* ##################################### Parse XML OR JSON ##########################################*/
    /**
     * request method put, get, post, delete
     * @return mixed
     */
    public function setMethod(){
        if(isset($_SERVER['REQUEST_METHOD'])){
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    /**
     * set content-type header
     * @return array
     */
    private function setContentType(){
        $contentType = explode(";",$_SERVER['CONTENT_TYPE']);
        $contentType = $contentType[0];
        return $contentType;
    }

    /**
     * Return content type for parse détection
     * @return mixed|void
     */
    public function getContentType(){
        if(is_array($this->contentTypeCollection)){
            $contentType = $this->setContentType();
            if(!array_key_exists($contentType,$this->contentTypeCollection)){
                return;
            }else{
                return $this->contentTypeCollection[$contentType];
            }
        }
    }
    /**
     * Return header from content type
     */
    public function setHeaderType(){
        $getContentType = $this->getContentType();
        switch($getContentType){
            case 'xml':
                header('Content-type: text/xml');
                break;
            case 'json':
                header('Content-type: application/json');
                break;
        }
    }

    /**
     * Read raw data from the request body
     * @return bool|string
     */
    private function setStreamData(){
        if ($stream = fopen('php://input', 'r')) {
            $streamData = stream_get_contents($stream, -1, 0);
            $streamData = urldecode($streamData);
            fclose($stream);
            return $streamData;
        }else{
            return false;
        }
    }
    /**
     * @param $data
     * @return mixed|SimpleXMLElement
     */
    private function setParseMethod($data){
        switch($data['method']){
            case 'xml':
                return simplexml_load_string($data['data'], null, LIBXML_NOCDATA);
                break;
            case 'json':
                return json_decode($data['data']);
                break;
        }
    }

    /**
     * @param bool $debug
     * @return mixed|SimpleXMLElement
     */
    public function setParseData($debug = false){
        $parse = $this->setParseMethod(array(
            'method'    =>  $this->getContentType(),
            'data'      =>  $this->setStreamData()
        ));
        if (is_object($parse)) {
            if($debug){
                print $this->getContentType();
                print '<pre>';
                print_r($parse);
                print '</pre>';
            }else{
                return $parse;
            }
        }else{
            return 'Parse result is not object';
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
    print $this->webservice->setPreparePostData(array(
    'wsAuthKey' => $this->webservice->setWsAuthKey(),
    'method' => 'xml',
    'data' => $test,
    'customRequest' => 'DELETE',
    'debug' => false,
    'url' => 'http://www.mywebsite.tld/webservice/catalog/categories/'
    ));
     */
    public function setPrepareSendData($data){
        $curl_params = array();
        $encodedAuth = $data['wsAuthKey'];
        $generatedData = urlencode($data['data']);
        switch($data['method']){
            case 'json';
                $headers = array("Authorization : Basic " . $encodedAuth,'Content-type: application/json','Accept: application/json');
                break;
            case 'xml';
                $headers = array("Authorization : Basic " . $encodedAuth,'Content-type: text/xml','Accept: text/xml');
                break;
        }

        $options = array(
            CURLOPT_HEADER          => 0,
            CURLINFO_HEADER_OUT     => 1,                // For debugging
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_NOBODY          => false,
            CURLOPT_URL             => $data['url'],
            CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
            CURLOPT_USERPWD         => $encodedAuth,
            CURLOPT_HTTPHEADER      => $headers,
            //CURLOPT_POST          => true,
            //CURLOPT_FORBID_REUSE  => 1,
            //CURLOPT_FRESH_CONNECT =>1,
            CURLOPT_TIMEOUT         => 300,
            CURLOPT_CONNECTTIMEOUT  => 300,
            CURLOPT_CUSTOMREQUEST   => $data['customRequest'],
            CURLOPT_POSTFIELDS      => $generatedData,
            CURLOPT_SSL_VERIFYPEER  => false
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
        if(array_key_exists('debug',$data) && $data['debug']){
            var_dump($curlInfo);
            var_dump($response);
        }
        if ($curlInfo['http_code'] == '200') {
            if ($response) {
                return $response;
            }
        }elseif($curlInfo['http_code'] == '0'){
            print 'Error HTTP: code 0';
            return;
        }
    }

    /**
     * @param $data
     * @return mixed
     *
    print $this->webservice->setPrepareGet(array(
    'wsAuthKey' => $this->webservice->setWsAuthKey(),
    'method' => 'xml',
    'debug' => false,
    'url' => 'http://www.mywebsite.tld/webservice/catalog/categories/'
    ));
     */
    public function setPrepareGet($data){
        try {

            $curl_params = array();
            $encodedAuth = $data['wsAuthKey'];
            switch($data['method']){
                case 'json';
                    $headers = array("Authorization : Basic " . $encodedAuth,'Content-type: application/json','Accept: application/json');
                    break;
                case 'xml';
                    $headers = array("Authorization : Basic " . $encodedAuth,'Content-type: text/xml','Accept: text/xml');
                    break;
            }
            $options = array(
                CURLOPT_RETURNTRANSFER  => true,
                CURLINFO_HEADER_OUT     => true,
                CURLOPT_URL             => $data['url'],
                CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
                CURLOPT_USERPWD         => $encodedAuth,
                CURLOPT_HTTPHEADER      => $headers,
                CURLOPT_TIMEOUT         => 300,
                CURLOPT_CONNECTTIMEOUT  => 300,
                CURLOPT_CUSTOMREQUEST   => "GET",
                CURLOPT_SSL_VERIFYPEER  => false
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
            }elseif($curlInfo['http_code'] == '0'){
                print 'Error HTTP: code 0';
                return;
            }


        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }

    /**
     * Prepare post Img with Curl (files only)
     * @param $data
     * @return mixed
    print $this->webservice->setPreparePostImg(array(
    'wsAuthKey' =>  $this->webservice->setWsAuthKey(),
    'url'       => 'http://www.website.tld/webservice/catalog/categories/3',
    'debug' => false,
    ));
     */
    public function setPreparePostImg($data){
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
                CURLOPT_TIMEOUT         => 300,
                CURLOPT_CONNECTTIMEOUT  => 300,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POST            => true,
                CURLOPT_POSTFIELDS      => $img,
                CURLOPT_SSL_VERIFYPEER  => false
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
            }elseif($curlInfo['http_code'] == '0'){
                print 'Error HTTP: code 0';
                return;
            }
        }
    }
    /**
     * Send Copy file on remote url
     * @param $data
     * @return mixed
     */
    public function setSendCopyImg($data){
        try {
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
                        //CURLOPT_VERBOSE         => true,
                        CURLOPT_SAFE_UPLOAD     => false,
                        CURLOPT_SSL_VERIFYPEER  => false
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
                        CURLOPT_SSL_VERIFYPEER  => false
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
        }catch (Exception $e){
            $logger = new debug_logger(MP_LOG_DIR);
            $logger->log('php', 'error', 'An error has occured : ' . $e->getMessage(), debug_logger::LOG_MONTH);
        }
    }
}
?>
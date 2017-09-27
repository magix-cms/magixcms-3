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
        } elseif (isset($_GET['ws_key'])) {
            $key = base64_encode($_GET['ws_key']);
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
    public function getResultParse($debug = false){
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
}
?>
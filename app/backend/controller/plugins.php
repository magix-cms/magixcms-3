<?php
class backend_controller_plugins extends backend_db_plugins{
    protected $modelPlugins,$template,$message,$header,$data,$finder,$modelLanguage,$collectionLanguage,$system;
    public $config;

    /**
     * backend_controller_plugins constructor.
	 * @param stdClass $t
     */
    public function __construct($t = null)
    {
		$this->template = $t ? $t : new backend_model_template;
		$this->modelPlugins = new backend_model_plugins();
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $this->finder = new file_finder();
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->system = new component_core_system();
        if (http_request::isPost('config')) {
            $array = $_POST['config'];
			foreach($array as $key => $arr) {
				foreach($arr as $k => $v) {
					$array[$key][$k] = html_entity_decode($v);
				}
			}
			$this->config = $array;
        }
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

    /**
     * List of unregistered plugins
     * @return array
     * @throws Exception
     */
    private function setNotRegisterItems(){
        $newsItems = array();
		$registerItems = array();
        $pluginsDir = $this->finder->scanRecursiveDir(component_core_system::basePath().'/plugins');
        $pluginsRegister = $this->getItems('list',null,'all',false);
        foreach($pluginsRegister as $item){
            $registerItems[]=$item['name'];
        }
        $newRegisterItems = array_flip($registerItems);
        foreach($pluginsDir as $item){
            if(!isset($newRegisterItems[$item])){
                /*if(file_exists(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item.DIRECTORY_SEPARATOR.'admin.php')){
                    //Nom de la classe pour le test de la méthode
                    $class = 'plugins_'.$item.'_admin';
                    if(class_exists($class)){
                        //Si la méthode run existe on ajoute le plugin dans le menu
                        if(method_exists($class,'run')){
                            $newsItems[]=$item;
                        }
                    }
                }*/
				$class = 'plugins_' . $item . '_admin';
				$plugin = array('name' => $item, 'title' => $item);
				if (class_exists($class)) {
					//Si la méthode run existe on ajoute le plugin dans le menu
					if (method_exists($class, 'getExtensionName')) {
						$this->template->addConfigFile(
							array(component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$item.DIRECTORY_SEPARATOR.'i18n'.DIRECTORY_SEPARATOR),
							array($item.'_admin_')
						);
						//$this->template->configLoad();
						$ext = new $class();
						$plugin['title'] = $ext->getExtensionName();
					}
				}
				$newsItems[]=$plugin;
            }
        }
        $coreComponent = new component_format_array();
        $coreComponent->array_sortBy('title', $newsItems);
        return $newsItems;
    }

    /**
     * set SQL Process (setup SQL
     * @param $id
     * @throws Exception
     */
    private function setSQLProcess($id){
        $routingDB = new component_routing_db();
        $files = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'db.sql';
        if(file_exists($files)){
            $routingDB->setupSQL($files);
        }
    }

	/**
	 * set SQL Process Uninstall
	 * @param $id
	 * @return bool
	 * @throws Exception
	 */
    private function unsetSQLProcess($id){
        $routingDB = new component_routing_db();
        $files = component_core_system::basePath().'plugins'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'uninstall.sql';
        if(file_exists($files)){
            $routingDB->setupSQL($files);
			parent::delete(array('type'=>'unregister'),array('id'=>$id));
			return true;
        }
        return false;
    }

    /**
     * Register Plugin
     * @param $id
     * @throws Exception
     */
    public function register($id){
        $data = parent::fetchData(array('context'=>'one','type'=>'register'),array(':id'=>$id));
        if($data['id_plugins'] != null){
            $this->message->getNotify('setup_info',array('method'=>'fetch','assignFetch'=>'message'));
            $this->template->display('plugins/setup.tpl');
        }else{
            $config = $this->modelPlugins->readConfigXML($id);
            if($config){
                if(isset($config['release']['version'])){
					parent::insert(array('type'=>'register'),array('name'=>$id,'version'=>$config['release']['version']));
					$this->setSQLProcess($id);
					$this->message->getNotify('setup_success',array('method'=>'fetch','assignFetch'=>'message'));
                    $this->template->display('plugins/setup.tpl');
                }else{
                    $this->message->getNotify('setup_error',array('method'=>'fetch','assignFetch'=>'message'));
                    $this->template->display('plugins/setup.tpl');
                }
            }
        }
    }

    /**
     * Register Plugin
     * @param $id
     * @throws Exception
     */
    public function unregister($id){
        $data = parent::fetchData(array('context'=>'one','type'=>'register'),array(':id'=>$id));
        if($data['id_plugins'] != null){
            if($this->unsetSQLProcess($id))
            	$msg = 'uninstall_success';
            else
				$msg = 'uninstall_error';
        }else{
			$msg = 'uninstall_empty';
        }

		$this->message->getNotify($msg,array('method'=>'fetch','assignFetch'=>'message'));
		$this->template->display('plugins/uninstall.tpl');
    }

    /**
     * system for upgrade Plugin
     * @param $id
     * @throws Exception
     */
    public function upgrade($id){
        if(isset($id)){
            $data = parent::fetchData(array('context'=>'one','type'=>'register'),array(':id'=>$id));
            $routingDB = new component_routing_db();
            $currentVersion = $data['version'];
            $SQLDir = component_core_system::basePath().'/plugins/'.$id.'/sql/version/';
            if(file_exists($SQLDir)) {
                $SQLFiles = $this->finder->scanDir($SQLDir);
                foreach ($SQLFiles as $item => $value) {
                    $extension = strpos($value, '.sql');
                    $version = substr($value, 0, $extension);
                    if ($version > $currentVersion) {
                        //print floatval($config['version']);
                        //$newItems[] = explode(".", substr($value,0,$extension));
                        $newItems[] = $value;
                        $countItem = count($item);
                        if (file_exists($SQLDir . $value)) {
                            if ($routingDB->setupSQL($SQLDir . $value)) {
                                parent::update(array('type'=>'version'),array('name'=>$id,'version'=>$version));
                                $this->message->getNotify('upgrade_success', array('method' => 'fetch', 'assignFetch' => 'message'));
                            }
                        }
                    }else{
                        $this->message->getNotify('upgrade_empty', array('method' => 'fetch', 'assignFetch' => 'message'));
                    }
                }
            }else{
                $this->message->getNotify('upgrade_empty', array('method' => 'fetch', 'assignFetch' => 'message'));
            }
            $this->template->display('plugins/upgrade.tpl');
        }
    }

    /**
     * @param $id
     * @return array
     * @throws Exception
     */
    private function setConfigFile($id){

        $data = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        $arr = array();
        foreach ($data as $key) {

            $baseConfigPath = component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'/i18n/public_local_'.$key['iso_lang'].'.conf';
            if(file_exists($baseConfigPath)) {
                $parse = $this->system->parseIni($baseConfigPath);
                $arr['content'][$key['id_lang']] = $parse;
            }
        }
        return $arr;
    }

	/**
	 * @param $fh
	 * @param $data
	 */
	private function writeConfig($fh, $data)
	{
		foreach ($data as $key => $value) {
			if(is_array($value)) {
				$sec = '###';
				fwrite($fh, "{$sec} {$key}" . PHP_EOL);

				foreach ($value as $k => $v) {
					// Write to the file.
					fwrite($fh, "{$k} = {$v}" . PHP_EOL);
				}
			}
			else {
				// Write to the file.
				fwrite($fh, "{$key} = {$value}" . PHP_EOL);
			}
		}
	}

    /**
     * save config files
     */
    private function saveConfig($id){

        $data = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        foreach ($data as $lang) {

            $baseConfigPath = component_core_system::basePath().DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'/i18n/public_local_'.$lang['iso_lang'].'.conf';
            if(isset($this->config[$lang['iso_lang']]) && $lang['default_lang'] == '1'){
                $newData = $this->config[$lang['iso_lang']];
            }

            if(is_writable($baseConfigPath) && file_exists($baseConfigPath)){
                // Open the file for writing.
                $fh = fopen($baseConfigPath, 'w');
                // Loop through the data.
                if(isset($this->config[$lang['iso_lang']])) {
					$this->writeConfig($fh, $this->config[$lang['iso_lang']]);
                    /*foreach ($this->config[$lang['iso_lang']] as $key => $value) {
                        // If a value exists that should replace the current one, use it.
                        //if ( ! empty($replace_with[$key]) )
                        //$value = $replace_with[$key];

                        // Write to the file.
                        fwrite($fh, "{$key} = {$value}" . PHP_EOL);
                    }*/

                }else{
					$this->writeConfig($fh, $newData);
                    /*foreach ( $newData as $key => $value ){
                        // If a value exists that should replace the current one, use it.
                        //if ( ! empty($replace_with[$key]) )
                        //$value = $replace_with[$key];

                        // Write to the file.
                        fwrite($fh, "{$key} = {$value}" . PHP_EOL);
                    }*/
                }
                // Close the file handle.
                fclose($fh);
            }else{
                $fh = fopen($baseConfigPath, 'w');
                // Loop through the data
				$this->writeConfig($fh, $newData);
                /*foreach ( $newData as $key => $value ){
                    // If a value exists that should replace the current one, use it.
                    //if ( ! empty($replace_with[$key]) )
                    //$value = $replace_with[$key];

                    // Write to the file.
                    fwrite($fh, "{$key} = {$value}" . PHP_EOL);
                }*/
                // Close the file handle.
                fclose($fh);
            }
        }
    }

    /**
     * @param $id
     * @throws Exception
     */
    public function translate($id){
        if(isset($this->config)){
            $this->saveConfig($id);
            $this->message->json_post_response(true,'update',$id);
        }else{
            $this->modelLanguage->getLanguage();
            $translate = $this->setConfigFile($id);
            $this->template->assign('translate',$translate);
            $this->template->display('plugins/translate.tpl');
        }
    }

    /**
     *
     */
    public function run(){
        $data = $this->modelPlugins->getItems(array('type'=>'self'));
        $this->template->assign('getListPlugins',$data);
        $this->template->assign('getListPluginsNotRegister',$this->setNotRegisterItems());
        $this->template->display('plugins/index.tpl');
    }
}
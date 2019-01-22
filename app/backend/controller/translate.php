<?php
class backend_controller_translate extends backend_db_theme{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $modelLanguage, $collectionLanguage, $system;
    public $theme, $content, $type, $id, $link, $order, $skin, $config;

    public $roots = array('home','about','catalog','news','contact');

    /**
     * backend_controller_theme constructor.
	 * @param stdClass $t
     */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $this->modelLanguage = new backend_model_language($this->template);
        $this->collectionLanguage = new component_collections_language();
        $this->system = new component_core_system();
        $formClean = new form_inputEscape();

        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }

        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }

        if (http_request::isGet('skin')) {
            $this->skin = $formClean->simpleClean($_GET['skin']);
        }

        if (http_request::isPost('config')) {
            $array = $_POST['config'];
            foreach($array as $key => $arr) {
                foreach($arr as $k => $v) {
                	if(is_array($v)) {
						foreach($v as $sk => $sv) {
							$array[$key][$k][$sk] = html_entity_decode($sv);
						}
					}
                	else {
						$array[$key][$k] = html_entity_decode($v);
					}
                }
            }
            $this->config = $array;
        }
    }
    /**
     * Return skin array Data
     */
    private function setItemsData() {
        $finder = new file_finder();
        $basePath = component_core_system::basePath().'skin';
        $skin = $finder->scanRecursiveDir($basePath);
        $newSkin = array();
        foreach($skin as $key => $value){
            $newSkin[$key]['name'] = $value;
        }
        $this->template->assign('getSkin',$newSkin);
    }
    /**
     * @return mixed
     */
    private function setConfigFile(){

        $data = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        $arr = array();
        foreach ($data as $key) {

            $baseConfigPath = component_core_system::basePath().'skin/'.$this->skin.'/i18n/theme_'.$key['iso_lang'].'.conf';
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
    private function saveConfig(){

        $data = $this->collectionLanguage->fetchData(array('context'=>'all','type'=>'langs'));
        foreach ($data as $lang) {
            //print_r($this->config[$lang['iso_lang']]);
            $baseConfigPath = component_core_system::basePath().'skin/'.$this->skin.'/i18n/theme_'.$lang['iso_lang'].'.conf';
            if(isset($this->config[$lang['iso_lang']]) && $lang['default_lang'] == '1'){
                $newData = $this->config[$lang['iso_lang']];
            }

            if(is_writable($baseConfigPath) && file_exists($baseConfigPath)){
                // Open the file for writing.
                $fh = fopen($baseConfigPath, 'w');
                // Loop through the data.
                if(isset($this->config[$lang['iso_lang']])) {
					$this->writeConfig($fh, $this->config[$lang['iso_lang']]);
                }
                else {
					$this->writeConfig($fh, $newData);
                }
                // Close the file handle.
                fclose($fh);
            }else{
                $fh = fopen($baseConfigPath, 'w');
                // Loop through the data.
				$this->writeConfig($fh, $newData);
                // Close the file handle.
                fclose($fh);
            }
        }
    }

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'translate':
                    if(isset($this->skin)){
                        if(isset($this->config)){
                            $this->saveConfig();
                            $this->message->json_post_response(true,'update',$this->skin);

                        }else{
                            $this->modelLanguage->getLanguage();
                            $translate = $this->setConfigFile();
                            $this->template->assign('translate',$translate);
                            $this->template->display('translate/edit.tpl');
                        }
                    }

                    break;
            }
        }
        else {
            $this->setItemsData();
            $this->template->display('translate/index.tpl');
        }
    }
}
?>
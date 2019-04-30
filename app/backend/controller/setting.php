<?php
class backend_controller_setting extends backend_db_setting{
    public $edit, $action, $tabs;
    protected $message, $template, $header, $data, $settings, $setSkinPath;
    public $setting, $type, $color;

	/**
	 * backend_controller_setting constructor.
	 * @param stdClass $t
	 */
    public function __construct($t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->data = new backend_model_data($this);
        $this->settings = new backend_model_setting();
        $formClean = new form_inputEscape();

        // --- GET
        if (http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
        if (http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        } elseif (http_request::isPost('action')) {
            $this->action = $formClean->simpleClean($_POST['action']);
        }
        if (http_request::isGet('tabs')) {
            $this->tabs = $formClean->simpleClean($_GET['tabs']);
        }
        // --- POST
        if (http_request::isPost('setting')) {
            $this->setting = $formClean->arrayClean($_POST['setting']);
        }else{
            $this->setting = array();
        }
        if (http_request::isPost('color')) {
            $this->color = $formClean->arrayClean($_POST['color']);
        }

        if (http_request::isPost('type')) {
            $this->type = $formClean->simpleClean($_POST['type']);
        }

		$this->setSkinPath = component_core_system::basePath().'skin'.DIRECTORY_SEPARATOR;
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
     * Assign data to the defined value
     */
    public function setItemsData(){
        $newArray = array();
        $settings = $this->getItems('settings',null,'all',false);
        foreach($settings as $key){
            $newArray[$key['name']] = $key['value'];
        }
        return $newArray;
    }

    /**
     * Return skin array Data
     */
    private function setItemsSkin(){
        $currentSkin = parent::fetchData(array('context'=>'one','type'=>'skin'));
        $finder = new file_finder();
        $basePath = component_core_system::basePath().'skin';
        $skin = $finder->scanRecursiveDir($basePath);
        $newSkin = array();
        foreach($skin as $key => $value){
            if($value === $currentSkin['value']){
                $current = 'true';
            }else{
                $current = 'false';
            }
            if(file_exists($basePath.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg')){
                $screenshot['small'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg';
                $screenshot['large'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_l.jpg';
            }else{
                $screenshot['small'] = false;
                $screenshot['large'] = false;
            }
            $newSkin[$key]['name'] = $value;
            $newSkin[$key]['current'] = $current;
            $newSkin[$key]['screenshot']['small'] = $screenshot['small'];
            $newSkin[$key]['screenshot']['large'] = $screenshot['large'];
        }
        $this->template->assign('skin',$newSkin);
    }

    /**
     * @param string $data
     */
    private function robotsFiles($data = 'index'){
        $basePath = component_core_system::basePath();
        $fh = fopen($basePath.'robots.txt', 'w+');
        if(is_writable($basePath.'robots.txt')){
            if($data === 'index'){
                fwrite($fh, "User-Agent: *" . PHP_EOL);
                fwrite($fh, "Allow: /" . PHP_EOL);
            }if($data === 'noindex'){
                fwrite($fh, "User-Agent: *" . PHP_EOL);
                fwrite($fh, "Disallow: /" . PHP_EOL);
            }
            fclose($fh);
        }
    }

	/**
	 * @return string
	 */
	private function setSkinData(){
		$currentSkin = $this->settings->select_uniq_setting('theme');
		if($currentSkin['value'] != null){
			if($currentSkin['value'] == 'default'){
				if(file_exists($this->setSkinPath.'default/')){
					$setData =  'default';
				}
			}
			elseif(file_exists($this->setSkinPath.$currentSkin['value'].'/')){
				$setData = $currentSkin['value'];
			}
			else{
				$setData = 'default';
			}
			return $setData;
		}
	}

	/**
	 * @return array
	 */
	private function setSnippetPath(){
		$setData = array();
		if(file_exists($this->setSkinPath.$this->setSkinData().DIRECTORY_SEPARATOR.'snippet')){
			$setData['path'] = $this->setSkinPath.$this->setSkinData().DIRECTORY_SEPARATOR.'snippet';
			$setData['type'] = 'skin';
			$setData['directory'] = $this->setSkinData().'/snippet';
		}else{
			$setData['path'] = component_core_system::basePath().PATHADMIN.DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'snippet';
			$setData['type'] = 'admin';
			$setData['directory'] = 'snippet';
		}
		return $setData;
	}

	/**
	 * Parcourt le dossier des snippets HTML dans le skin courant ou le dossier de base
	 */
	private function setSnippet(){
		$setPath = $this->setSnippetPath();
		if(is_array($setPath)) {
			$directory = new RecursiveDirectoryIterator($setPath['path'], RecursiveDirectoryIterator::SKIP_DOTS);
			$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
			//extension
			$extensions = array("html");
			// delimiteur
			$delimiter = "\n";
			if (is_object($iterator)) {
				foreach ($iterator as $fileinfo) {
					// Compatibility with php < 5.3.6
					if (version_compare(phpversion(), '5.3.6', '<')) {
						$getExtension = pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION);
					} else {
						$getExtension = $fileinfo->getExtension();
					}
					if (in_array($getExtension, $extensions)) {
						if ($setPath['type'] === 'skin') {
							$pos = strpos($fileinfo->getPathname(), $setPath['type']);
							//$len = strlen($pos);
							if (stripos($_SERVER['HTTP_USER_AGENT'], 'win')) {
								$url = '/skin/' . $setPath['directory'] . '/' . $fileinfo->getFilename();
							} else {
								$url = DIRECTORY_SEPARATOR . substr($fileinfo->getPathname(), $pos);
							}
						} elseif ($setPath['type'] === 'admin') {
							$pos = strpos($fileinfo->getPathname(), PATHADMIN);
							//$len = strlen($pos);
							if (stripos($_SERVER['HTTP_USER_AGENT'], 'win')) {
								$url = '/' . PATHADMIN . '/template/' . $setPath['directory'] . '/' . $fileinfo->getFilename();
							} else {
								$url = DIRECTORY_SEPARATOR . substr($fileinfo->getPathname(), $pos);
							}
						}

						$files[] = /*$delimiter.*/'{'.'"title":"'.$fileinfo->getBasename('.'.$getExtension).'","url":"'.$url.'"}';
					}
				}
				if (is_array($files)) {
					asort($files, SORT_REGULAR);
					//$ouput = 'templates = [';
					$ouput = '['.implode(',', $files).']';
					//$ouput .= implode(',', $files);
					//$ouput .= $delimiter . ']';
					$this->header->set_json_headers();
					print $ouput;
				}
			}
		}
	}

    /**
     * Mise a jour des données
     * @param $data
     */
    private function upd($data)
    {
        switch ($data['type']) {
            case 'general':
				$concat = isset($this->setting['concat']) ? '1' : '0';
				$ssl = isset($this->setting['ssl']) ? '1' : '0';
				$service_worker = isset($this->setting['service_worker']) ? '1' : '0';

                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'content_css'   => $this->setting['content_css'],
                        'concat'        => $concat,
                        'ssl'           => $ssl,
                        'service_worker'=> $service_worker,
                        'cache'         => $this->setting['cache'],
                        'mode'          => $this->setting['mode']
                    )
                );
                break;
            case 'css_inliner':
                if(isset($this->setting['css_inliner'])){
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'css_inliner'   => '1',
                            'header_bg'     => $this->color['header_bg'],
                            'header_c'      => $this->color['header_c'],
                            'footer_bg'     => $this->color['footer_bg'],
                            'footer_c'      => $this->color['footer_c']
                        )
                    );
                }else{
                    parent::update(
                        array(
                            'type'=>$data['type']
                        ),array(
                            'css_inliner'   => '0'
                        )
                    );
                }
                break;
            case 'google':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'analytics'   => $this->setting['analytics'],
                        'robots'      => $this->setting['robots']
                    )
                );
                if($this->setting['robots'] === 'index,follow,all'){
                    $this->robotsFiles('index');
                }else{
                    $this->robotsFiles('noindex');
                }
                break;
            case 'theme':
                parent::update(
                    array(
                        'type'=>$data['type']
                    ),array(
                        'theme'   => $this->setting['theme']
                    )
                );
                break;
        }
        $this->message->json_post_response(true,'update',$data['type']);
    }

    /**
     *
     */
    public function run(){
        if(isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                    if (isset($this->setting)) {
                        if($this->type === 'general'){
                            $this->upd(array('type'=>'general'));
                        }elseif($this->type === 'css_inliner'){
                            $this->upd(array('type'=>'css_inliner'));
                        }elseif($this->type === 'theme'){
                            $this->upd(array('type'=>'theme'));
                        }elseif($this->type === 'google'){
                            $this->upd(array('type'=>'google'));
                        }
                    }
                    break;
				case 'getSnippet':
					$this->setSnippet();
					break;
            }
        }
        else {
            $this->template->assign('settings',$this->setItemsData());
            $this->setItemsSkin();
            $this->template->display('setting/index.tpl');
        }
    }
}
?>
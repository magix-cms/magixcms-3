<?php
class backend_controller_setting extends backend_db_setting {
	/**
	 * @var backend_model_template $template
	 * @var backend_model_data $data
	 * @var component_core_message $message
	 * @var http_header $header
	 * @var backend_model_setting $settings
	 */
	protected backend_model_template $template;
	protected backend_model_data $data;
	protected component_core_message $message;
	protected http_header $header;
	protected backend_model_setting $settings;

	/**
	 * @var string $setSkinPath
	 */
	protected string $setSkinPath;

	/**
	 * @var int $edit
	 */
	public int $edit;

	/**
	 * @var string $action
	 * @var string $tabs
	 * @var string $type
	 */
	public string
		$action,
		$tabs,
		$type;

	/**
	 * @var $setting
	 * @var $color
	 */
    public array
		$setting,
		$color;

	/**
	 * @param backend_model_template|null $t
	 */
    public function __construct(backend_model_template $t = null) {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
		$this->data = new backend_model_data($this);
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->settings = new backend_model_setting();

        // --- GET
        if (http_request::isGet('edit')) $this->edit = form_inputEscape::numeric($_GET['edit']);
		if (http_request::isGet('tabs')) $this->tabs = form_inputEscape::simpleClean($_GET['tabs']);
        if (http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);
        // --- POST
		$this->setting = http_request::isPost('setting') ? form_inputEscape::arrayClean($_POST['setting']) : [];
        if (http_request::isPost('color')) $this->color = form_inputEscape::arrayClean($_POST['color']);
        if (http_request::isPost('type')) $this->type = form_inputEscape::simpleClean($_POST['type']);
		$this->setSkinPath = component_core_system::basePath().'skin'.DIRECTORY_SEPARATOR;
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param array|int|null $id
	 * @param string|null $context
	 * @param bool|string $assign
	 * @return mixed
	 */
	private function getItems(string $type, $id = null, string $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @return array
	 */
    public function setItemsData(): array {
		$settingsData = $this->getItems('settings',null,'all',false);
		return empty($settingsData) ? [] : array_column($settingsData,'value','name');
    }

	/**
	 * @return array
	 */
    private function setItemsSkin(): array {
		$currentSkin = $this->getItems('skin',null,'one',false);
        $finder = new file_finder();
        $basePath = component_core_system::basePath().'skin';
        $skins = $finder->scanRecursiveDir($basePath);
        $skinData = [];
		if(!empty($skins)) {
			foreach($skins as $key => $value){
				$screenshot = [
					'small' => false,
					'large' => false
				];
				if(file_exists($basePath.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg')){
					$screenshot['small'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_s.jpg';
					$screenshot['large'] = DIRECTORY_SEPARATOR.'skin'.DIRECTORY_SEPARATOR.$value.DIRECTORY_SEPARATOR.'screenshot_l.jpg';
				}
				$skinData[$key] = [
					'name' => $value,
					'current' => $value === $currentSkin['value'],
					'screenshot' => $screenshot
				];
			}
		}
        return $skinData;
    }

    /**
     * @param string $data
     */
    private function robotsFiles(string $data = 'index') {
        $basePath = component_core_system::basePath();
        $fh = fopen($basePath.'robots.txt', 'w+');
        if(is_writable($basePath.'robots.txt')) {
			fwrite($fh, "User-Agent: *" . PHP_EOL);
			fwrite($fh, ($data === 'index' ? "Allow" : "Disallow").': /'.PHP_EOL);
            fclose($fh);
        }
    }

	/**
	 * @return string
	 */
	private function setSkinData(): string {
		$currentSkin = $this->settings->select_uniq_setting('theme');
		$skin = (!empty($currentSkin) && isset($currentSkin['value']) && !empty($currentSkin['value'])) ? $currentSkin['value'] : 'default';
		return file_exists($this->setSkinPath.$skin.'/') ? $skin : '';
	}

	/**
	 * @return array
	 */
	private function setSnippetPath(): array {
		$snippetPath = [];
		if(file_exists($this->setSkinPath.$this->setSkinData().DIRECTORY_SEPARATOR.'snippet')){
			$snippetPath = [
				'path' => $this->setSkinPath.$this->setSkinData().DIRECTORY_SEPARATOR.'snippet',
				'type' => 'skin',
				'directory' => $this->setSkinData().'/snippet'
			];
		}
        return $snippetPath;
	}

	/**
	 * Parcourt le dossier des snippets HTML dans le skin courant ou le dossier de base
	 * @return array
	 */
	private function setSnippetFiles(): array {
		$files = [];
		$setPath = $this->setSnippetPath();
		if(!empty($setPath)) {
			$directory = new RecursiveDirectoryIterator($setPath['path'], RecursiveDirectoryIterator::SKIP_DOTS);
			$iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
			$extensions = ["html"];
			foreach ($iterator as $fileInfos) {
				$getExtension = $fileInfos->getExtension();

				if (in_array($getExtension, $extensions)) {
					if ($setPath['type'] === 'skin') {
						$pos = strpos($fileInfos->getPathname(), $setPath['type']);
						$skinPath = '/skin/'.$setPath['directory'].'/';
					}
					elseif ($setPath['type'] === 'admin') {
						$pos = strpos($fileInfos->getPathname(), PATHADMIN);
						$skinPath = '/'.PATHADMIN.'/template/';
					}

					$url = stripos($_SERVER['HTTP_USER_AGENT'], 'win') ? $skinPath.$fileInfos->getFilename() : DIRECTORY_SEPARATOR.substr($fileInfos->getPathname(), $pos);

					$files[] = '{'.'"title":"'.$fileInfos->getBasename('.'.$getExtension).'",'.'"description":""'.',"url":"'.$url.'"}';
				}
			}
		}
		return $files;
	}

    /**
     *
     */
    public function run() {
        if(isset($this->action)) {
            switch ($this->action) {
                case 'edit':
                    if (isset($this->setting)) {
						switch ($this->type) {
						    case 'advanced':
								$params = [
									'content_css' => $this->setting['content_css'],
									'concat' => isset($this->setting['concat']) ? '1' : '0',
									'ssl' => isset($this->setting['ssl']) ? '1' : '0',
									'http2' => isset($this->setting['http2']) ? '1' : '0',
									'service_worker' => isset($this->setting['service_worker']) ? '1' : '0',
									'cache' => $this->setting['cache'],
									'mode' => $this->setting['mode'],
									'amp' => isset($this->setting['amp']) ? '1' : '0',
									'maintenance' => isset($this->setting['maintenance']) ? '1' : '0'
								];
								break;
						    case 'css_inliner':
								$params = ['css_inliner' => isset($this->setting['css_inliner']) ? '1' : '0'];
								if(isset($this->setting['css_inliner'])){
									$params['header_bg'] = $this->color['header_bg'];
									$params['header_c'] = $this->color['header_c'];
									$params['footer_bg'] = $this->color['footer_bg'];
									$params['footer_c'] = $this->color['footer_c'];
								}
								break;
						    case 'theme':
								$params = ['theme' => $this->setting['theme']];
								break;
						    case 'google':
								$params = [
									'analytics' => $this->setting['analytics'],
									'robots' => $this->setting['robots']
								];
								$this->robotsFiles($this->setting['robots'] === 'index,follow,all' ? 'index' : 'noindex');
								break;
						    case 'catalog':
								$params = [
									'vat_rate' => $this->setting['vat_rate'],
									'price_display' => $this->setting['price_display'],
									'product_per_page' => $this->setting['product_per_page']
								];
								break;
						    case 'news':
								$params = ['news_per_page' => $this->setting['news_per_page']];
								break;
						    case 'mail':
								$params = [
									'mail_sender' => $this->setting['mail_sender'],
									'smtp_enabled' => isset($this->setting['smtp_enabled']) ? '1' : '0',
									'set_host' => $this->setting['set_host'],
									'set_port' => $this->setting['set_port'],
									'set_encryption' => !empty($this->setting['set_encryption']) ? $this->setting['set_encryption'] : NULL,
									'set_username' => $this->setting['set_username'],
									'set_password' => $this->setting['set_password'],
								];
								break;
							default:
								$params = [];
						}
						if(!empty($params)) {
							parent::update(['type' => $this->type], $params);
							$this->message->json_post_response(true,'update',$this->type);
						}
                    }
                    break;
				case 'getSnippet':
					$snippet = new backend_controller_snippet();
					$stData = $snippet->getJsonData();
					$files = $this->setSnippetFiles();
					$newData = !empty($stData) ? (!empty($files) ? array_merge($stData,$files) : $stData) : $files;

					if (!empty($newData) && is_array($newData)) {
						asort($newData, SORT_REGULAR);
						$output = '['.implode(',', $newData).']';
						$this->header->set_json_headers();
						print $output;
					}
					break;
            }
        }
        else {
            $this->template->assign('settings',$this->setItemsData());
			$this->template->assign('skin',$this->setItemsSkin());
            $this->template->display('setting/index.tpl');
        }
    }
}
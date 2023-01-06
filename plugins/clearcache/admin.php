<?php
class plugins_clearcache_admin {
    /**
     * @var backend_model_template $template
     * @var backend_controller_plugins $plugins
	 * @var http_header $header
     * @var component_core_message $message
     */
	protected backend_model_template $template;
	protected component_core_message $message;
	protected debug_logger $logger;

	/**
	 * @var string $action
	 * @var string $clear
	 */
    public string
		$action,
		$clear;

    /**
     * frontend_controller_home constructor.
     */
    public function __construct() {
        $this->template = new backend_model_template();
        $this->message = new component_core_message($this->template);
    }

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string {
		return $this->template->getConfigVars('clearcache_plugin');
	}

	/**
	 * @param string $dir
	 * @return string
	 */
    private function setCacheDirectory(string $dir): string {
        $basePath = component_core_system::basePath();
        switch($this->clear) {
            case 'admin':
                $setDir = $basePath.PATHADMIN.DIRECTORY_SEPARATOR.'caching'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR;
                break;
			case 'public':
			case 'log':
			default:
				$setDir = $basePath.'var'.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR;
        }
        return $setDir;
    }

    /**
     * @param array $directories
     */
    private function setRemoveFiles(array $directories) {
		if(!empty($directories)) {
			$makeFiles = new filesystem_makefile();
			$finder = new file_finder();

			foreach ($directories as $directory) {
				$cacheDir = $this->setCacheDirectory($directory);

				if(file_exists($cacheDir)){
					$setFiles = $finder->scanDir($cacheDir,['.htaccess','.gitignore']);
					if($setFiles != null){
						foreach($setFiles as $file){
							try {
								$makeFiles->remove($cacheDir.$file);
							}
							catch(Exception $e) {
								if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
								$this->logger->log('php','clearcache',$e->getMessage(),$this->logger::LOG_MONTH);
							}
						}
					}
				}
			}
		}
    }

    /**
     *
     */
    public function run() {
		if(http_request::isMethod('POST')) {
			if(http_request::isPost('action')) $this->action = form_inputEscape::simpleClean($_POST['action']);
			if(http_request::isPost('clear')) $this->clear = form_inputEscape::simpleClean($_POST['clear']);

			if(isset($this->action) && $this->action === 'delete') {
				if(isset($this->clear)) {
					switch ($this->clear) {
						case 'admin':
						case 'public':
							$this->setRemoveFiles(['templates_c','minify','caches','tpl_caches']);
							break;
						case 'log':
							$this->setRemoveFiles(['logs']);
							break;
					}
					$this->message->json_post_response(true, 'delete_multi');
				}
				else {
					$this->message->json_post_response(false, 'error_plugin');
				}
			}
			else {
				$this->message->json_post_response(false, 'error_plugin');
			}
		}
		else {
            $this->template->display('index.tpl');
        }
    }
}
<?php
class install_controller_installer extends install_db_installer {

	public $edit, $action, $tab;
	protected $controller, $message, $template, $header, $makefiles, $routingDb, $config, $setting;

	/**
	 * backend_controller_home constructor.
	 * @param stdClass $t
	 */
	public function __construct($t = null)
	{
		$this->template = $t ? $t : new install_model_template;
		$this->message = new component_core_message($this->template);
		$this->header = new http_header();
		$this->makefiles = new filesystem_makefile();
		$this->routingDb = new component_routing_db();
		$formClean = new form_inputEscape();

		// --- GET
		if(http_request::isGet('controller')) {
			$this->controller = $formClean->simpleClean($_GET['controller']);
		}
		if (http_request::isGet('edit')) {
			$this->edit = $formClean->numeric($_GET['edit']);
		}
		if (http_request::isGet('action')) {
			$this->action = $formClean->simpleClean($_GET['action']);
		} elseif (http_request::isPost('action')) {
			$this->action = $formClean->simpleClean($_POST['action']);
		}
		if (http_request::isGet('tab')) {
			$this->tab = $formClean->simpleClean($_GET['tab']);
		}

		// --- POST
		if (http_request::isPost('config')) {
			$this->config = $formClean->arrayClean($_POST['config']);
		}
		if (http_request::isPost('setting')) {
			$this->setting = $formClean->arrayClean($_POST['setting']);
		}
	}

	/**
	 * @return array
	 */
	public function getBuildItems(){
		function is_valid($exp) {
			return $exp ? 0 : 1;
		}

		$data = array(
			'php' => array(
				'v' => phpversion(),
				'version' => is_valid(version_compare(phpversion(),'5.4','<') || version_compare(phpversion(),'7.2','>')),
				'encoding' => is_valid(!function_exists('mb_detect_encoding')),
				'iconv' => is_valid(!function_exists('iconv')),
				'ob' => is_valid(!function_exists('ob_start')),
				'xml' => is_valid(!function_exists('simplexml_load_string')),
				'dom' => is_valid(!function_exists('dom_import_simplexml')),
				'spl' => is_valid(!function_exists('spl_classes')),
			),
			'access' => array(
				'writable_config' => is_valid(!is_writable(component_core_system::basePath().'app'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR)),
				'writable_var' => is_valid(!is_writable(component_core_system::basePath().'var'.DIRECTORY_SEPARATOR))
			)
		);

		return $data;
	}

	/**
	 * Test de connexion avec les données du formulaire
	 */
	private function test_connexion(){
		if(isset($this->config['host'])
			&& isset($this->config['user'])
			&& isset($this->config['pwd'])
			&& isset($this->config['dbname']))
		{
			if(!defined('MP_DBDRIVER')
				OR !defined('MP_DBHOST')
				OR !defined('MP_DBUSER')
				OR !defined('MP_DBPASSWORD')
				OR !defined('MP_DBNAME'))
				{

				define('MP_DBDRIVER',$this->config['driver']);
				// Database hostname (usually "localhost")
				define('MP_DBHOST',$this->config['host']);
				// Database user
				define('MP_DBUSER',$this->config['user']);
				// Database password
				define('MP_DBPASSWORD',$this->config['pwd']);
				// Database name
				define('MP_DBNAME',$this->config['dbname']);
			}

			try {
				component_routing_db::layer()->connection();
				return true;
			}
			catch (Exception $e) {
				$this->message->json_post_response(false,'connexion_impossible');
			}
		}
	}

	/**
	 * @return string
	 */
	private function filesBasePath(){
		return component_core_system::basePath().'app'.DIRECTORY_SEPARATOR.'init'.DIRECTORY_SEPARATOR;
	}

	/**
	 *
	 */
	private function createConfigFiles(){
		if(isset($this->config['host']) && isset($this->config['user']) && isset($this->config['pwd']) && isset($this->config['dbname'])){
			$configFiles = $this->filesBasePath().'config.php';
			if (!is_writable(dirname($configFiles))) {
				throw new Exception(sprintf('Cannot write %s file.',$configFiles));
			}
			if (!is_file($this->filesBasePath().'config.php.in')) {
				throw new Exception(sprintf('File %s does not exist.',$this->filesBasePath().'config.php.in'));
			}
			try {
				# Creates config.php file
				$readConfigIn = file_get_contents($this->filesBasePath() . 'config.php.in');
				$this->makefiles->writeConstValue('MP_DBDRIVER', $this->config['driver'], $readConfigIn);
				$this->makefiles->writeConstValue('MP_DBHOST', $this->config['host'], $readConfigIn);
				$this->makefiles->writeConstValue('MP_DBUSER', $this->config['user'], $readConfigIn);
				$this->makefiles->writeConstValue('MP_DBPASSWORD', $this->config['pwd'], $readConfigIn);
				$this->makefiles->writeConstValue('MP_DBNAME', $this->config['dbname'], $readConfigIn);
				switch ($this->config['log']) {
					case 'debug':
						$this->makefiles->writeConstValue('MP_LOG', $this->config['log'], $readConfigIn);
						break;
					case 'log':
						$this->makefiles->writeConstValue('MP_LOG', $this->config['log'], $readConfigIn);
						break;
					case 'false':
						$this->makefiles->writeConstValue('MP_LOG', 'false', $readConfigIn, false);
				}
				$this->makefiles->writeConstValue('MP_LOG_DIR', component_core_system::basePath() . 'var' . DIRECTORY_SEPARATOR . 'logs', $readConfigIn);
				$this->makefiles->writeConstValue('MP_FIREPHP', 'false', $readConfigIn, false);

				$fp = fopen($configFiles, 'wb');
				/*if ($fp === false) {
					throw new Exception(sprintf('Cannot write %s file.', $configFiles));
					exit();
				}*/
				fwrite($fp, $readConfigIn);
				fclose($fp);
				return true;
				/*$this->databaseProcess();

				if (!headers_sent()) {
					header('location: ' . http_url::getUrl() . '/install/employee.php');
					//exit;
				}*/
			}catch(Exception $e) {
				/*$logger = new debug_logger(component_core_system::basePath() . 'var' . DIRECTORY_SEPARATOR . 'log');
				$logger->log('php', 'error', 'An error has occured : '.$e->getMessage(), debug_logger::LOG_MONTH);*/
			}
		}
	}

	/**
	 * @throws Exception
	 */
	private function databaseProcess(){
		return $this->routingDb->setupSQL(component_core_system::basePath().'install/sql/db.sql');
	}

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function add($data){
		switch($data['type']){
			case 'domain':
				parent::insert(
					array(
						'type' => $data['type']
					),
					array(
						'domain' => $this->setting['domain']
					)
				);
				break;
			case 'admin':
				parent::insert(
					array(
						'type' => $data['type']
					),
					array(
						'keyuniqid_admin' => filter_rsa::randUI(),
						'title_admin' => $this->setting['title'],
						'firstname_admin' => $this->setting['firstname'],
						'lastname_admin' => $this->setting['lastname'],
						'email_admin' => $this->setting['email'],
						'passwd_admin' => password_hash($this->setting['pwd'], PASSWORD_DEFAULT),
						'active_admin' => 1
					)
				);
				$lastInsert = parent::fetchData(array('type' => 'lastEmployee'));
				parent::insert(
					array(
						'type' => 'adminRel'
					),
					array(
						'id_admin' => $lastInsert['id_admin'],
						'id_role' => 1
					)
				);
				break;
		}
	}

	/**
	 * Update data
	 * @param $data
	 */
	private function upd($data)
	{
		switch ($data['type']) {
			case 'company':
				parent::update(
					array(
						'type' => $data['type']
					),
					array(
						'name' => $this->setting['website'],
						'type' => $this->setting['type']
					)
				);
				break;
		}
	}

    /**
     *
     */
    public function run(){
    	if(isset($this->action)) {
			switch ($this->action) {
				case 'get':
					if(isset($this->tab)) {
						switch ($this->tab) {
							case 'analysis':
								$this->template->assign('results',$this->getBuildItems());
								$html = $this->template->fetch('analysis/table.tpl');
								$this->message->json_post_response(true,null,$html);
								break;
						}
					}
					break;
				case 'save':
					if(isset($this->tab)) {
						switch ($this->tab) {
							case 'configuration':
								if($this->test_connexion()) {
									if($this->createConfigFiles()){
										$this->message->json_post_response(true,'config_success');
									}
									else {
										$this->message->json_post_response(false,'config_error');
									}
								}
								break;
							case 'setting':
								if(isset($this->setting['website']) &&
								isset($this->setting['type']) &&
								isset($this->setting['domain']) &&
								isset($this->setting['title']) &&
								isset($this->setting['firstname']) &&
								isset($this->setting['lastname']) &&
								isset($this->setting['email']) &&
								isset($this->setting['pwd']) &&
								isset($this->setting['rppwd']) &&
								$this->setting['rppwd'] === $this->setting['pwd']) {
									$config_in = '../app/init/common.inc.php';
									if (file_exists($config_in)) {
										require $config_in;
									}else{
										throw new Exception('Error Ini Common Files');
										exit;
									}

									$this->upd(array('type' => 'company'));
									$this->add(array('type' => 'domain'));
									$this->add(array('type' => 'admin'));
									$this->message->json_post_response(true, 'request_success');
								}
								else {
									$this->message->json_post_response(false, 'request_missing');
								}
								break;
						}
					}
					break;
				case 'install':
					$config_in = '../app/init/common.inc.php';
					if (file_exists($config_in)) {
						require $config_in;
					}else{
						throw new Exception('Error Ini Common Files');
						exit;
					}
					if($this->databaseProcess() === true) {
						$this->message->json_post_response(true,null);
					}
					else {
						$this->message->json_post_response(false,null);
					}
					break;
				case '': break;
				case '': break;
			}
		}
    	else {
    	    $db = false;
    	    if(file_exists('../app/init/config.php')) {
                $config_in = '../app/init/common.inc.php';
                if (file_exists($config_in)) {
                    require $config_in;
                }else{
                    throw new Exception('Error Ini Common Files');
                    exit;
                }
                $db = parent::fetchData(array('type' => 'database'));
                $db = !empty($db);
            }
            $this->template->assign('install_detected',$db);
			$this->template->display('index.tpl');
		}
    }
}
<?php
class backend_controller_files extends backend_db_files {
	/**
	 * @var backend_model_template $template
	 * @var backend_model_data $data
	 * @var component_core_message $message
	 * @var http_header $header
	 * @var component_files_images $imagesComponent
	 * @var component_collections_config $configCollection
	 * @var backend_model_plugins $modelPlugins
	 * @var component_files_upload $upload
	 */
	protected backend_model_template $template;
	protected backend_model_data $data;
	protected component_core_message $message;
	protected http_header $header;
	protected component_files_images $imagesComponent;
	protected component_collections_config $configCollection;
	protected backend_model_plugins $modelPlugins;
	protected component_files_upload $upload;

	/**
	 * @var string $action
	 * @var string $tabs
	 * @var string $attribute
	 * @var string $module
	 */
	public string
		$action,
		$tabs,
		$attribute,
		$module;

	/**
	 * @var int $edit
	 * @var int $id
	 */
	public int
		$edit,
		$id;

	/**
	 * @var array $imageConfig
	 */
	public array $imageConfig;

	/**
	 * @param backend_model_template|null $t
	 */
    public function __construct(backend_model_template $t = null) {
        $this->template = $t instanceof backend_model_template ? $t : new backend_model_template;
		$this->data = new backend_model_data($this);
        $this->message = new component_core_message($this->template);
        $this->header = new http_header();
        $this->configCollection = new component_collections_config();
        $this->imagesComponent = new component_files_images($this->template);
		$this->upload = new component_files_upload();
        $this->modelPlugins = new backend_model_plugins();

        // --- GET
        if (http_request::isGet('edit')) $this->edit = form_inputEscape::numeric($_GET['edit']);
		if (http_request::isGet('tabs')) $this->tabs = form_inputEscape::simpleClean($_GET['tabs']);
		if (http_request::isRequest('action')) $this->action = form_inputEscape::simpleClean($_REQUEST['action']);

		// --- ADD or EDIT
        if (http_request::isPost('id')) $this->id = form_inputEscape::numeric($_POST['id']);
        if (http_request::isPost('attribute')) $this->attribute = form_inputEscape::simpleClean($_POST['attribute']);
        if (http_request::isPost('module')) $this->module = form_inputEscape::simpleClean($_POST['module']);
        if (http_request::isPost('imageConfig')) $this->imageConfig = form_inputEscape::arrayClean($_POST['imageConfig']);
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

    public function run() {
        if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                    if(isset($this->imageConfig)) {
						parent::insert(['type' => 'resize'], $this->imageConfig);
						$this->message->json_post_response(true,'add_redirect');
                    }
					else {
                        $module = $this->imagesComponent->module();
                        $this->template->assign('module',$module);
                        $resize = $this->imagesComponent->resize();
                        $this->template->assign('resize',$resize);
                        $this->template->display('files/add.tpl');
                    }
                    break;
                case 'edit':
                    if(isset($this->imageConfig)) {
						$previous = $this->getItems('size',['id' => $this->edit],'one',false);
						parent::update(['type' => 'resize'], $this->imageConfig);

						if($previous['prefix_img'] !== $this->imageConfig['prefix_img'] ||
							$previous['width_img'] !== $this->imageConfig['width_img'] ||
							$previous['height_img'] !== $this->imageConfig['height_img'] ||
							$previous['resize_img'] !== $this->imageConfig['resize_img']) {
							$root = 'upload/'.$this->imageConfig['module_img'];
							if(in_array($this->imageConfig['module_img'],['pages','news','catalog'])) {
								$images = $this->getItems('images',['type' => $this->imageConfig['attribute_img'] !== $this->imageConfig['module_img'] ? $this->imageConfig['attribute_img'] : $this->imageConfig['module_img']],'all',false);
							}
							else {
								$plugin = 'plugins_'.$this->imageConfig['attribute_img'].'_admin';
								if (class_exists($plugin) && method_exists($plugin, 'getItemsImages')) {
									$class = new $plugin();
									$images = $class->getItemsImages();
								}
							}
							if(isset($this->imageConfig['attribute_img']) && $this->imageConfig['attribute_img'] !== $this->imageConfig['module_img'] && $this->imageConfig['module_img'] !== 'plugins') $root .= '/'.$this->imageConfig['attribute_img'];

							if(!empty($images)) {
								$options = ['progress' => new component_core_feedback($this->template),'template' => $this->template];
								if($previous['prefix_img'] !== $this->imageConfig['prefix_img']) {
									$this->upload->batchPrefixRename(
										$this->imageConfig['module_img'],
										$this->imageConfig['attribute_img'],
										$root,
										$images,
										$this->imageConfig['type_img'],
										$previous['prefix_img'],
										$this->imageConfig['prefix_img'],
										$options
									);
								}
								if($previous['width_img'] !== $this->imageConfig['width_img'] ||
									$previous['height_img'] !== $this->imageConfig['height_img'] ||
									$previous['resize_img'] !== $this->imageConfig['resize_img']) {
									$this->upload->batchRegenerate(
										$this->imageConfig['module_img'],
										$this->imageConfig['attribute_img'],
										$root,
										$images,
										$this->imageConfig['type_img'],
										$options
									);
								}
							}
						}
						else {
							$this->message->json_post_response(true,'update',$this->edit);
						}
                    }
					elseif(isset($this->module)) {
						$root = 'upload/'.$this->module;
						$module = $this->module;
						$attribute = $this->module;

						if(in_array($this->module,['pages','news','catalog'])) {
							$type = (isset($this->attribute) && !empty($this->attribute)) ? $this->attribute : $this->module;
							$images = $this->getItems('images',['type' => $type],'all',false);
						}
						else {
							$plugin = 'plugins_'.$this->module.'_admin';
							if (class_exists($plugin) && method_exists($plugin, 'getItemsImages')) {
								$class = new $plugin();
								$images = $class->getItemsImages();
								$module = 'plugins';
							}
						}
						if(isset($this->attribute) && !empty($this->attribute) && $this->attribute !== $this->module && $this->module !== 'plugins') $root .= '/'.$this->attribute;
						if(!empty($images)) {
							$this->upload->batchRegenerate($module,$attribute,$root,$images,null,[
								'progress' => new component_core_feedback($this->template),
								'template' => $this->template
							]);
						}
                    }
					else {
                        $this->getItems('size',$this->edit);
                        $module = $this->imagesComponent->module();
                        $this->template->assign('module',$module);
                        $resize = $this->imagesComponent->resize();
                        $this->template->assign('resize',$resize);
                        $this->template->display('files/edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id)) {
						parent::delete(['type' => 'delResize'], ['id' => $this->id]);
						$this->message->json_post_response(true,'delete',['id' => $this->id]);
					}
                    break;
            }
        }
		else {
            $this->getItems('sizes');

			$this->data->getScheme(['mc_config_img'],['id_config_img','module_img','attribute_img','width_img','height_img','type_img','prefix_img','resize_img']);

            $config = $this->configCollection->fetchData(['context'=>'all','type'=>'config']);
            $plugins = $this->modelPlugins->getItems(['type'=>'thumbnail']);
            if(!empty($plugins)) {
                foreach ($plugins as $items) {
                    $config[]['attr_name'] = $items['name'];
                }
            }

            $this->template->assign('setConfig',$config);
            $this->template->display('files/index.tpl');
        }
    }
}
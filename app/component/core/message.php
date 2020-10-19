<?php
class component_core_message{
    /**
     * @var backend_controller_template
     */
    protected $template,$header,$plugins;
    protected $default = array(
        'template'		=>'message.tpl',
        'method'		=>'display',
        'assignFetch'	=>''
    );
    /**
     *
     */
    public function __construct($template){
        $this->template = $template;
		$this->header = new http_header();
    }

    /**
     * Retourne le message de notification
     * @param $notify
     * @param array $options
     * @return string html compiled
     */
    public function getNotify($notify,$options = array()){
        $options = $options + $this->default;
        $model = $this->template;
        $this->template->configLoad();

        switch($options['method']) {
            case 'display':
                $model->assign('message',$notify);
                $model->display($options['template']);
                break;

            case 'fetch':
            case 'return':
                $model->assign('message',$notify);
                $fetch = $model->fetch($options['template']);
                if($options['method'] == 'fetch')
                    $model->assign($options['assignFetch'],$fetch);
                else
                    return $fetch;
                break;
            case 'debug':
                if (is_array($options['result'])){
                    $model->assign('debugData',$options['result']);
                    $fetch = $model->fetch('debug.tpl');
                    if($options['method'] == 'debug') {
                        $model->assign('debug', $fetch);
                    }
                }
                break;
            default:
                $model->assign('message',$notify);
                $model->display($options['template']);
        }
    }

    /**
     * Return a json object with the status of the post action, the notification and the eventual result of the post
     * @param bool $status
     * @param string $notify
     * @param bool $result
     */
	/**
	 * example of extended result data
	 * $result = array(
		'result' => 1, // can be an id for example or an array of data
	 * 	'extend' => array('id_category' => 2, 'id_subcategory' => 3)
	 * )
	 * the json output will be
	 * {"status":true,"notify":...,"result":1,"id_category":2,"id_subcategory":3}
	 */
    public function json_post_response($status=true,$notify='save',$result = null,$options = null)
    {
        if (is_array($options))
            $options = $options + $this->default;
        elseif ($options === null || !is_array($options))
            $options = $this->default;
        $options['method'] = 'return';

        if($notify != null){
            $notify = $this->getNotify($notify,$options);
        }else{
            $notify = null;
        }
		$extend = '';
		if (is_array($result) && key_exists('result',$result)) {
			$output = $result['result'];

			if(key_exists('extend',$result)) {
				if(is_array($result['extend'])) {
                    $extend .= ',"extend":{';
                    $i = 0;
					foreach ($result['extend'] as $k => $v) {
					    if($i === 0){
                            $extend .= '"'.$k.'":'.json_encode($v);
                        }else{
                            $extend .= ',"'.$k.'":'.json_encode($v);
                        }
					    $i++;
					}
                    $extend .= '}';
				}
			}
		} else {
			$output = $result;
		}

		$this->header->set_json_headers();
        print '{"status":'.json_encode($status).',"notify":'.json_encode($notify).',"result":'.json_encode($output).$extend.'}';
    }
}
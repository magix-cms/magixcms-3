<?php
class backend_controller_tableform
{
	protected
		$template,
		$message,
		$data;

	public
		$edit,
		$action,
		$controller;

	public
		$items,
		$ajax;

	/**
	 * backend_controller_tableform constructor.
	 * @param object $caller
	 * @param null|object $t
	 */
    public function __construct($caller, $t = null)
    {
        $this->template = $t ? $t : new backend_model_template;
        $this->message = new component_core_message($this->template);
		$this->data = $caller;
        $formClean = new form_inputEscape();

        // --- GET
        if (http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
        if (http_request::isGet('action')) $this->action = $formClean->simpleClean($_GET['action']);
        if (http_request::isGet('ajax')) $this->ajax = $formClean->simpleClean($_GET['ajax']);

        // --- EDIT
		if (http_request::isGet('items')) $this->items = (array) $formClean->arrayClean($_GET['items']);
    }

	/**
	 * @param $results
	 */
	private function listResults($results)
	{
		if($this->ajax) {
			$this->template->assign('ajax_form',true);
			$this->template->assign('controller',$this->controller);
			$this->template->assign('subcontroller',$results['params']['tab'] ? $results['params']['tab'] : false);
			$this->template->assign('data',$results['data']);
			$this->template->assign('section',$results['params']['section']);
			$this->template->assign('idcolumn',$results['params']['idcolumn']);
			$this->template->assign('activation',$results['params']['activation']);
			$this->template->assign('sortable',$results['params']['sortable']);
			$this->template->assign('checkbox',$results['params']['checkbox']);
			$this->template->assign('edit',$results['params']['edit']);
			$this->template->assign('dlt',$results['params']['dlt']);
			$this->template->assign('readonly',$results['params']['readonly']);
			$this->template->assign('cClass',$results['params']['cClass']);
			$display = $this->template->fetch('section/form/loop/rows-3.tpl');
			$this->message->json_post_response(true,'',$display);
		}
		else {
			$this->template->assign($results['var'],$results['data']);
			$this->template->display($results['tpl']);
		}
    }

    /**
     *
     */
    public function run(){
		if(isset($this->action) && isset($this->controller)) {
			switch ($this->action) {
				case 'active-selected':
				case 'unactive-selected':
					if(isset($this->items) && is_array($this->items) && !empty($this->items)) {
						$this->data->tableActive(array(
							'active' => ($this->action == 'active-selected'?1:0),
							'ids' => implode($this->items, ',')
						));

						$results = $this->data->tableSearch($this->ajax);
						$this->listResults($results);
					}
					break;

				default:
					$results = $this->data->tableSearch($this->ajax);
					$this->listResults($results);
			}
		}
	}
}
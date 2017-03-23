<?php
class component_core_feedback{
	protected $template, $header;
	protected $default = array('message' => '','progress' => 0);

	/**
	 * component_core_feedback constructor.
	 */
	public function __construct(){
		$this->template = new frontend_model_template();
		$this->header = new http_header();
		$this->init();
	}

	/**
	 *
	 */
	public function init()
	{
		//ob_start();
		ob_end_flush();
		ob_implicit_flush(true);
		$this->header->set_json_headers();
	}

	/**
	 * @param $feedback
	 */
	public function sendFeedback($feedback)
	{
		if (is_array($feedback))
			$feedback = $feedback + $this->default;
		elseif ($feedback === null || !is_array($feedback))
			$feedback = $this->default;

		echo json_encode($feedback);
		//ob_flush();
		//flush();
	}
}
?>
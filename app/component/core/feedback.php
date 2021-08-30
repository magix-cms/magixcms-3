<?php
class component_core_feedback{
	protected $template, $header;
	protected $default = array('message' => '','progress' => 0);

	/**
	 * component_core_feedback constructor.
	 */
	public function __construct($template){
        $this->template = $template;
		$this->header = new http_header();
		$this->init();
	}

	/**
	 *
	 */
	public function init()
	{
		//ob_start();
		//ob_end_flush();
		@ob_end_clean();
		set_time_limit(0);
		ob_implicit_flush(1);
		$this->header->set_json_headers();
		header("X-Accel-Buffering: no");
		header('Content-Encoding: none');
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

		echo str_repeat(' ',1024*64); // fill the buffer if X-Accel-Buffering header is disabled
		echo json_encode($feedback);
		while (ob_get_level() > 0) {
			ob_end_flush();
		}
		flush();
	}

	/**
	 * Feedback using event-stream
	 * @param $feedback
	 */
	public function send_message($feedback)
	{
		if (is_array($feedback))
			$feedback = $feedback + $this->default;
		elseif ($feedback === null || !is_array($feedback))
			$feedback = $this->default;

		echo "id: ".$feedback['progress'] . PHP_EOL;
		echo "data: " . json_encode($feedback) . PHP_EOL;
		echo PHP_EOL;

		while (ob_get_level() > 0) {
			ob_end_flush();
		}
		flush();
	}
}

<?php
class backend_model_setting extends component_collections_setting{
	/**
	 * Constructor
	 */
	function __construct(){}

	/**
	 * Initialise la selection du setting avec l'identifiant
	 * @param (string) $setting_id
	 * @return mixed
	 */
	public function select_uniq_setting($setting_id){
		if(!is_null($setting_id));
		return parent::publicDbSetting()->s_uniq_setting_value($setting_id);
	}

	/**
	 * @return mixed
	 */
	public function get_settings()
	{
		$data = parent::publicDbSetting()->s_all_settings();
		$settings = array();
		foreach($data as $r) {
			$settings[$r['setting_id']] = $r['setting_value'];
		}
		return $settings;
	}
	public function getSetting(){
        $data = parent::fetchAll();
        $settings = array();
        foreach($data as $r) {
            $settings[$r['setting_id']] = $r['value'];
        }
        return $settings;
    }
}
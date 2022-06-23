<?php
class collections_varTools{
	public static function if_empty($data, $replacement = NULL) {
		return empty($data) ? $replacement : $data;
	}
}
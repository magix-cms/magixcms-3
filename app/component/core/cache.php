<?php
class component_core_cache{
	protected $default_dir = 'temp';

	/**
	 * Create directory if not exist than create the file
	 * @param string $path
	 */
	private function validPath($path){
		if(!preg_match('/^\./',$path)) $path = '.'.DIRECTORY_SEPARATOR.$path;
		if(!file_exists($path)) mkdir($path,0755,true);
	}

	/**
	 * Replace directory separator by the pre defined one
	 * Check that the path ended with a directory separator
	 *
	 * @param $path
	 * @return mixed|string
	 */
	private function cleanPath($path)
	{
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
		$pattern = '/\\'.DIRECTORY_SEPARATOR.'$/';
		if(!preg_match($pattern,$path)) $path .= DIRECTORY_SEPARATOR;
		$this->validPath($path);
		return $path;
	}

	/**
	 * Define cache directory
	 * @param string $dir
	 * @return string
	 */
	private function cacheDir($dir = '')
	{
		return component_core_system::basePath() . $this->cleanPath($this->default_dir . DIRECTORY_SEPARATOR . $dir);
	}

	/**
	 * Check cache
	 * If younger than 5 min, returns it
	 * If older, delete it and return null
	 *
	 * @param string $file
	 * @param string $dir
	 * @param null|int|string $time lifetime of the file in minutes
	 * @return mixed|null
	 */
	public function getCache($file,$dir = '',$time = null)
	{
		$cache_path = $this->cachedir($dir);
		$cache_file = $cache_path . $file . '.dat';
		if(file_exists($cache_file)) {
			if($time != null) {
				$upToDate = false;

				if(is_int($time)) {
					if((filemtime($cache_file) /*+ (60*60)*/) > $time) {
						return unserialize(file_get_contents($cache_file));
					}
				}
				elseif (is_string($time)) {
					if(filemtime($cache_file) > (time() - 60 * $time)) {
						return unserialize(file_get_contents($cache_file));
					}
				}

				if(!$upToDate) unlink($cache_file); return null;
			}
			else {
				return unserialize(file_get_contents($cache_file));
			}
		}
		else {
			return null;
		}
	}

	/**
	 * Put data into cache file
	 * @param $file
	 * @param $data
	 * @param string $dir
	 */
	public function setCache($file,$data,$dir = '')
	{
		$cache_path = $this->cachedir($dir);
		$cache_file = $cache_path . $file . '.dat';
		file_put_contents($cache_file, serialize($data));
	}
}
?>
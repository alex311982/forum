<?php
final class Config
{
	private static $http;
	
	public static $upd_count = 0;
	
	public static $cache_data = array();
	public static $cached_data = 0;
	
	public static function get()
	{
		if(isset(self::$cache_data[serialize(func_get_args())]))
		{
			self::$cached_data++;
			return self::$cache_data[serialize(func_get_args())];
		}
		
		$args = func_get_args();
		$config_name = strtolower(array_shift($args));
		
		if(!file_exists(CORE_PTH_CONFIGS))
		{
			die('This shop not installed');	
		}

		if(file_exists(CORE_PTH_CONFIGS.$config_name.'.php'))
		{
			include(CORE_PTH_CONFIGS.$config_name.'.php');
            if (!isset($data)) return null;

			$result = self::getVal($data, $args);

		}
		
		self::$cache_data[serialize(func_get_args())] = $result;
		return $result; 
	}
    
	public static function localConfigExists($config_name) {
	    $result = false;

	    $config_name = strtolower($config_name);

		if(file_exists(CORE_PTH_CONFIGS) 
		    && (file_exists(CORE_PTH_CONFIGS.$config_name.'.php') || file_exists(CORE_PTH_CONFIGS.'data/'.$config_name.'.php')))
 		{
 		    $result = true;
		}
	
	    return $result;
	}
	
	private static function getVal($data, $keys)
	{
		while($key = array_shift($keys))
		{
			if(isset($data[$key]))
			{
				$data = $data[$key];
			} else
			{
				return null;
			}
		}
		
		return $data;
	}
}

<?php

if(!function_exists('get_magic_quotes_gpc'))
{
	function get_magic_quotes_gpc()
	{
		return false;
	}
}
	  
final class Request
{
	private static $instance = false;
	
	private $_get;
	private $_post;
	
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new Request();
		}
		
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->_get = &$_GET;
		$this->_post = &$_POST;
	}
	
	public static function getUri()
	{
		$query_string = trim($_SERVER['QUERY_STRING'], '&');
		if(preg_match('#\?(.+)$#', $query_string, $match))
		{
			$query_string = $match[1];
		}
		$uri =  $_SERVER['REQUEST_URI'];
		
		if(CORE_DIR_PREFIX == '/')
		{
			$uri = str_replace(array('?'.$query_string), '', $uri);
		} else
		{
			$uri = str_replace(array(CORE_DIR_PREFIX, '?'.$query_string), '', $uri);
		}
		
		$uri = preg_replace('#([a-z_]+\.php)#', '', $uri);
		$uri = preg_replace('#(\?.*)$#', '', $uri);
		return $uri;
	}
	
	public static function getUrl($params = array(), $current_params_exists = true, $unset_params = array())
	{
		$url = trim(CORE_URL_DOMAIN,'/').'/'.ltrim(self::getUri(),'/');
		
		return self::buildQuery($url, $params, $current_params_exists, $unset_params);
	}
	
	public static function buildQuery($url, $params = array(), $current_params_exists = true, $unset_params = array())
	{
		$data = array();
		
		if($current_params_exists)
		{
			foreach($_GET as $key => $item)
			{
				$data[$key] = $item;
			}
		}
		
		
		$url_info = parse_url($url);
		if(isset($url_info['query']))
		{
			$query = $url_info['query'];
			$query = explode('&', $query);
			foreach($query as $item)
			{
				$item = explode('=', $item);
				$data[$item[0]] = $item[1];
			}
			unset($query);
		}
		
		$url = $url_info['scheme'].'://'.$url_info['host'].$url_info['path'];
		unset($url_info);
		
		foreach($params as $key => $val)
		{
			$data[$key] = $val;
		}
		
		foreach($unset_params as $item)
		{
			unset($data[$item]);	
		}
		
		$query_string = http_build_query($data);
		
		if($query_string)
		{
			$url .= '?'.$query_string;
		}
		
		return $url;
	}
	
	public function isPost()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			return true;
		} else
		{
			return false;
		}
	}
	
	public function unescape($value)
	{
	 
		if(is_array($value))
		{
			foreach($value as $key=>$val)
			{
				$value[$key] = $this->unescape($val);
			}
		} else
		{
			$value = stripslashes($value);
		}
		
		return $value;
	}
	
	public function get($var, $from = 'GET')
	{
		switch($from)
		{
			case 'POST':
				$val = isset($this->_post[$var]) ? $this->_post[$var] : null;
			break;
			default:
				$val = isset($this->_get[$var]) ? $this->_get[$var] : null;
		}
		
		if(get_magic_quotes_gpc())
		{
			$val = $this->unescape($val);
		}
		
		return is_array($val) ? $val : trim($val);
	}
	
	public function post($var)
	{
		$val = isset($this->_post[$var]) ? $this->_post[$var] : null;
		
		if(get_magic_quotes_gpc())
		{
			$val = $this->unescape($val);
		}
		
		return is_array($val) ? $val : trim($val);	
	}
	
	public function getPostContent()
	{
		return file_get_contents('php://input');
	}
}
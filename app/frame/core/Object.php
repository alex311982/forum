<?php 

class Object
{
	protected $data;	
	
	public function __set($key, $value)
	{
		if(!is_array($value) && !is_object($value))
		{
			$value = trim($value);
		}
		
		$this->data[$key] = $value;
		return true;
	}
	
	public function set($key, $value)
	{
		return $this->__set($key, $value);	
	}
	
	public function __get($key)
	{
		if(isset($this->data[$key]))
		{
			return $this->data[$key];
		} else
		{
			return null;
		}
	}
	
	public function get($key, $htmlspecialchars = false)
	{
		if($htmlspecialchars && !is_array($this->__get($key)) && !is_object($this->__get($key)))
		{
			return htmlspecialchars($this->__get($key));
		}
		return $this->__get($key);
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function getData()
	{
		return $this->data;
	}
}
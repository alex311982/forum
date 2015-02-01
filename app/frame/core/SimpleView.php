<?php

class SimpleView
{
	public $http_status = 200;
	
	private $vars = array();
	private $plugins = array();
	
	public function __construct(){}
	
	public function display($template)
	{
		header($_SERVER['SERVER_PROTOCOL'].' '.$this->http_status);
		header('Content-type: text/html; charset=utf-8', true);
		
		$this->fetch($template);
	}
	
	public function fetch($template)
	{
		if(isset($this->vars['http_status']))
		{
			$this->http_status = $this->vars['http_status'];
		}
		
		foreach($this->vars as $key=>&$var)
		{
			$$key = $var;
			unset($var);
		}
		unset($this->vars);

        $file = CORE_PTH_TPL . $template;
		
		if(file_exists($file))
		{
			include_once($file);
		} else
		{
            include_once(CORE_PTH_TPL . 'overall.tpl');
		}
	}
	
	public function assign($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function setPlugin($name)
	{
		require_once($this->plugin_dir.'function.'.$name.'.php');
	}
}
?>
<?php
abstract class Model
{
	protected $object = false;
	
	public function __construct()
	{
		if(!$this->object)
		{
			$this->object = str_replace('Table', '' ,get_class($this));			
		}
	}
	
	static public function factory($object)
	{
		if($object)
		{
			$object = ucwords($object).'Table';
            return new $object();
        }else
        {
            return null;
        }
	}
	
	 public function execute($action)
	 {
	 	if($action)
	 	{
	 		$action = ucwords($action);
	 	} else
	 	{
	 		throw new Exception('Method not found: ');
	 	}
	 	
	 	if(method_exists($this, $action))
	 	{
			$this->$action();
	 	} else
	 	{
	 		throw new Exception('Method not found: '.$action);
	 	}
	 }
}
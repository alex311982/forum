<?php
class Controller
{
	protected $request;

	public static function factory($className)
	{
		$class = explode('_',$className);
		$className = '';
	 	foreach($class as $item)
	 	{
			$className .= ucwords($item);	 			
	 	}
	 		
		if(!empty($className))
		{
			if(class_exists($className))
			{
                return new $className();
			} else 
			{
				return new Controller();
			}
			
		} else
		{
			throw new Exception(FACTORY_ERROR);
		}
	 }
	 
    protected function __construct()
    {
        $this->request = Request::getInstance();
    }
	 
	public function execute($action)
	{
	 	$this->tpl = 'module_'.strtolower(str_replace('module','',get_class($this))).'_'.strtolower($action).'.tpl';

	 	if($action)
	 	{
	 		$act = explode('_',$action);
			$action = 'execute';	 		
	 		foreach($act as $item)
	 		{
				$action .= ucwords($item);	 			
	 		}
	 	} else
	 	{
	 		$action = 'executeIndex';
	 	}

	 	if(method_exists($this, $action))
	 	{
			$this->$action();
	 	} else
	 	{
	 		throw new Exception('Method not found: '.$action.' in '.get_class($this));
	 	}
	}
	 
}
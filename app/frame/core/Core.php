<?php

class Core 
{
	private static $instance;
	
	private function __construct(){}

	public function proccess()
 	{
		ob_start();

        $module = Router::getInstance()->getModule();

		$object = Controller::factory($module);
		
		$object->execute(Router::getInstance()->getAction());

		$object = get_object_vars($object);

        $output_controlls = ob_get_clean();
        $view = new View();

        if(isset($object['response']))
        {
            $view->setResponse($object['response']);
        } else
        {
            $view->setResponse('html');
        }

        $view->setVariable($object);

        ob_start();
        $view->proccess();
        $output_view = ob_get_clean();

        print($output_view);
        print($output_controlls);
	}
	
	
	public static function getInstance($configuration)
	{
		if(!self::$instance)
		{
			$_SERVER['DOCUMENT_ROOT'] = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
            define('CORE_MODEL', $configuration['model']);
            define('CORE_APP', $configuration['app']);
			
			define('CORE_PTH', str_replace('\\', '/' , dirname(__FILE__)).'/');
			define('CORE_PTH_LIB', CORE_PTH.'../libraries/');
			
			define('CORE_PTH_DOMAIN', str_replace('\\', '/', $configuration['document_root']));
			define('CORE_PTH_APP',str_replace('\\', '/', $configuration['app_root']));
			
			define('CORE_PTH_APP_CONTROLLER', CORE_PTH_APP.'controllers/'.$configuration['app'].'/');
			define('CORE_PTH_APP_MODEL', CORE_PTH_APP.'model/'.CORE_MODEL.'/');

            define('CORE_PTH_CONFIGS', CORE_PTH_DOMAIN.'configs/');

            define('CORE_PTH_TPL', CORE_PTH_DOMAIN.'template/');
			
			if(!isset($configuration['core_dir_prefix']))
			{
				$configuration['core_dir_prefix'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME'])).'/'; 
			}

            include_once(CORE_PTH . 'Autoloader.php');
            AutoLoader::registerDirectory(array(
                    CORE_PTH
                    , CORE_PTH_APP
                    , CORE_PTH_APP_CONTROLLER
                    , CORE_PTH_APP_MODEL
                )
            );

			self::$instance = new Core();
		}
		
		return self::$instance;
	}
}
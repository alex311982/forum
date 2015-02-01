<?php
class Router
{
	private static $instance;
	
	private $route;
	
	private function __construct($route)
	{
		$this->route = $route;
	}
	
	public function getModule()
	{
		return $this->route['module'];
	}
	
	public function getAction()
	{
		return $this->route['action'];
	}
	
	public static function redirect($url, $code = 302)
	{
		if(substr($url, 0, 4) !== 'http')
		{
			throw new Exception('Incorrect url '.$url);
		}
		
		header($_SERVER['SERVER_PROTOCOL'].' '.$code);
		header('Location: '.$url, true, $code);
		exit;
	}
	
	public static function getInstance()
	{
		if(self::$instance)
		{
			return self::$instance;
		}
		
		if(!Request::getUri() || Request::getUri() === '/')
		{
			self::$instance = new Router(
					array(
						'module' => 'index',
						'action' => 'index',
						'lang' => 'get'
					)
				);
			return self::$instance;
		}
		
		$uri = strtolower(ltrim(Request::getUri(), '/'));
		
		$sitemap = self::createUrlList();

		if(isset($sitemap[$uri]))
		{
			$oid = Request::getInstance()->get(Config::get('main', 'module_id_param_name', $sitemap[$uri]['module']));
			if(!isset($sitemap[$uri]['oid']) && $oid)
			{
				$sitemap[$uri]['oid'] = $oid;
			}
			
			self::$instance = new Router($sitemap[$uri]);
			
			return self::$instance;
		} else
		{
			self::redirect(self::getUrl('index'));
		}
	}
	
	private static function createUrlList()
	{
		if($sitemap = FileCache::get('sitemap_urls'))
		{
			return $sitemap;
		}
		
		$sitemap_data = Config::get('sitemap');
		
		$sitemap = array();
		while($item = array_shift($sitemap_data))
		{
			$url = $item['url'];
			unset($item['url']);
			
			$sitemap[$url] = $item;
		}

		return $sitemap;
	}
}
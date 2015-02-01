<?php
class View
{
	private $response;
	private $result;
	
	public function __construct()
	{
		
	}
	
	public function setVariable($result)
	{
		$this->result = $result;
	}
	
	public function setResponse($response)
	{
		$this->response = $response;
	}

	public function proccess()
	{
		switch($this->response)
		{
			case 'json':
				header('HTTP/1.1 200');
				header('Content-Type: text/javascript');
				
				unset($this->result['tpl']);
				unset($this->result['page_info']);
				unset($this->result['route_name']);
				
				if(!$this->result)
				{
					exit;
				}
				
				$this->result = json_encode($this->result);
				if(isset($headers['Accept-Encoding']))
				{
					$this->result = gzencode($this->result, 6);
					header('Content-Encoding: gzip');
				}
		
				print($this->result);
			break;
			
			case 'xml':
				
			break;
			case 'php':
				unset($this->result['tpl']);
				unset($this->result['page_info']);
				unset($this->result['route_name']);
				unset($this->result['response']);
				unset($this->result['user']);
				
				$response = '';
				foreach($this->result as $var => $item)
				{
					$response .= "\n \$$var = ".var_export($item, true)."; \n";
				}
				
				header('HTTP/1.1 200');
				print($response);
			break;
			default:
				$view = new SimpleView();
				
				foreach($this->result as $key=> &$var)
				{
					$view->assign($key, $var);
				}

				$view->template_dir = CORE_PTH_TPL;
				$view->plugin_dir = CORE_PTH_LIB.'simple_view/';
				
				$response = $view->display( isset($this->result['overall']) ? $this->result['overall'] : 'overall.tpl');
		}
	}
}
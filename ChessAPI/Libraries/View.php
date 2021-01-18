<?php
namespace Libraries;

class View
{
	protected $response;

	function __construct($response)	
	{
		$this->response = $response;
	}
    
    function rendering($name,$data = [],$HeaderFooter = true) //Вторая версия рендеринга с использованием шабонизатора и стандарта PSR-7
    {
    	$this->response->getBody()->write(require(__DIR__ . "/TemplateEngine.php"));
    	return $this->codeHTML200();
    }
    
    function codeHTML200() //Возврат кода 200 для html
	{
	    $this->response->withHeader();
		return $this->response
          ->withHeader('Content-Type', 'text/html')
          ->withStatus(200);
	}
	
	function error404() //Рендеринг страницы 404
	{
		$this->rendering("404",[],false);
		return $this->codeHTML200();
	}
	
	function renderingAPI($text) //Вторая версия рендеринга с использованием шабонизатора и стандарта PSR-7
    {
    	$this->response->getBody()->write(json_encode($text,JSON_UNESCAPED_UNICODE));
    	return $this->response
          ->withHeader('Content-Type', 'application/json')
          ->withStatus(200);
    }
}

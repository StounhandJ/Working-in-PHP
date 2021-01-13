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
	
	function createPagination($uri,$page,$allPage) //рабочая категория, страница сейчас, общее количество
	{
		$pagination = "";
		$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].$uri."page=";
		$col = 0;
		$mas = ($page!=$allPage) ? 1:2;
		$mas = ($allPage<=2) ? $page-1:$mas;
		$vrem = $page-$mas;
		$last = $page-1;
		if($page!=1){echo "<li><a href='$url$last'>«</a></li>";}
		while($col<$mas+1){
			if($vrem>0){
				if($page==$vrem){$pagination.= "<li class='active'><a href='$url$vrem'>$vrem</a></li>";}
				else{$pagination.= "<li><a href='$url$vrem'>$vrem</a></li>";}
				$col+=1;
			}
			$vrem+=1;
		}
		if($page!=$allPage){
			$next =($page!=1)?$page+1: ($allPage==2) ? $page+1:$page+2;
			$pagination.= "<li><a href='$url$next'>$next</a></li>";
			$pagination.= "<li><a href='$url$next'>»</a></li>";
		}
		return $pagination;
	}
}

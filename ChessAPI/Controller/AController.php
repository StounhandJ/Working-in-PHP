<?php
namespace Controller;

class AController
{
  protected $request;
  protected $response;
  protected $ChessDB;
  public $view;
  public $GET;
  public $POST;
  public $FILE;
  public $COOKIE;
  public $SERVER;
  public $InformationM;
  public $GoodsM;
  public $MaterialsM;

  function __construct()
  {
  	
  }
  
  function set_request($request)
  {
	$this->GET = $request->getQueryParams();
	$this->POST = $request->getParsedBody();
	$this->COOKIE = $request->getCookieParams();
	$this->SERVER = $request->getServerParams();
	$this->FILE = $request->getUploadedFiles();
  	$this->request = $request;
  }
  
  function set_response($response)
  {
  	$this->response = $response;
  }
  
  function get_request()
  {
  	return $this->request;
  }
  
  function get_response()
  {
  	return $this->response;
  }
  
  function before($request, $response)
  {
  	$this->set_request($request);
  	$this->set_response($response);
  	$this->view = new \Libraries\View($this->response);
  	$this->ChessDB = new \Model\ChessDB;
  }

}



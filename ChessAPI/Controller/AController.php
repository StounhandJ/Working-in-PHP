<?php
namespace Controller;

use Model\ChessDB;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Libraries\View;

class AController
{
    /**
   * @var Request
   */
  protected $request;

  /**
   * @var Response
   */
  protected $response;

    /**
     * @var ChessDB
     */
    public $ChessDB;

    /**
     * @var View
     */
    public $view;

  /**
   * @var array
   */
  public $GET;

  /**
   * @var array
   */
  public $POST;

  /**
   * @var array
   */
  public $FILE;

  /**
   * @var array
   */
  public $COOKIE;

  /**
   * @var array
   */
  public $SERVER;
  
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
  
  function get_request(): Request
  {
  	return $this->request;
  }
  
  function get_response(): Response
  {
  	return $this->response;
  }
  
  function before($request, $response)
  {
  	$this->set_request($request);
  	$this->set_response($response);
  	$this->view = new View($this->response);
  	$this->ChessDB = new ChessDB();
  }

}



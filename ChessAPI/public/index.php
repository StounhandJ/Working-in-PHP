<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
require __DIR__ . '/../vendor/autoload.php';

session_start();
$app = AppFactory::create();
        //---------Подключение классов---------//
function autoload ($class) { //Загрузка файлов
    $namespase = '';
    $dir = __DIR__.'/../';
    if($last = strripos($class,'\\'))
    {
      $namespase=substr($class,0,$last);
      $classname=substr($class,$last+1);
      $filename=$dir.$namespase.DIRECTORY_SEPARATOR;
    }
    $filename.=$classname.'.php';
    if(file_exists($filename)) {
        require $filename;
    }
};
spl_autoload_register('autoload');
        //-------------------------------//

          //Ошибка при остувие страницы//
$app->addRoutingMiddleware();
$errorMiddleware= $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function () use ($app) {
        $response = $app->getResponseFactory()->createResponse();
        $view = new \Libraries\View($response);
        return$view->error404();
});
        //-------------------------------//


            //---------API---------//

$app->group('/api', function (RouteCollectorProxy $group) {

});

            //-------------------------//
$app->run();

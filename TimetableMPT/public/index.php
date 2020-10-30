<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
require __DIR__ . '/../vendor/autoload.php';

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
    if(file_exists($filename)){
      require $filename;
    }
};

spl_autoload_register('autoload');
$app = AppFactory::create();
// $app->addRoutingMiddleware();
// $errorMiddleware= $app->addErrorMiddleware(true, true, true);
// $errorMiddleware->setDefaultErrorHandler(function () use ($app) {
//         $response = $app->getResponseFactory()->createResponse();
//         $response->getBody()->write('Page not found');
//         return $response
//             ->withStatus(404)
//             ->withHeader('Content-Type', 'text/html');
// });

$app->get('/', function ($request, $response, array $args) {
  $response->getBody()->write('<h1>Эта страница в разработке.<br> Для использования api перейдите на эту <a href="/api">страничку</a>');
    return $response
        ->withHeader('Content-Type', 'text/html')
        ->withStatus(200);
      });

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('', function ($request, $response, array $args) {
      $response->getBody()->write('<h1>Страничка в разработке.</h1>
      <h2>Доступный методы:</h2>
      <h3><a href="/api/group.all">group.all</a> - Возвращает все группы в виде словаря с ключем в виде курса. Пример ответа ("09.02.07"=>["П50-4-19","П50-5-19","П50-6-19"])</h3>
      <h3><a href="/api/group.get?group=П50-6-19">group.get</a> - Возвращает расписание определенной группы. Нужно передать GET параметр group (Название группы). Пример запросса (/api/group.get?group=П50-6-19)</h3>
      <h3><a href="/api/group.replace?group=П50-6-19">group.replace</a> - Возвращает изменения в расписание для определенной группы. Пример запросса (/api/group.replace?group=П50-6-19)</h3>');
       return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
          });

    $group->get('/group/all', function ($request, $response, array $args) {
                $Controller = new \Controller\AController;
                $Controller->set("api","AllGroup",$request,$response);
                $response = $Controller->run();
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
          });

    $group->get('/group/get', function ($request, $response, array $args) {
          $Controller = new \Controller\AController;
          $Controller->set("api","TimeTable",$request,$response);
          $response = $Controller->run();
          return $response
              ->withHeader('Content-Type', 'application/json')
              ->withStatus(200);
    });
    $group->get('/group/replace', function ($request, $response, array $args) {
          $Controller = new \Controller\AController;
          $Controller->set("api","Replacements",$request,$response);
          $response = $Controller->run();
          return $response
              ->withHeader('Content-Type', 'application/json')
              ->withStatus(200);
    });

});

$app->run();

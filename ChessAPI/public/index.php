<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
require __DIR__ . '/../vendor/autoload.php';

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
    $filename = str_replace("\\","/",$filename);
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
        return $view->renderingAPI(404);
});
        //-------------------------------//


            //---------API---------//
$app->get("/",function(Request $request, Response $response, array $args)
{
    $request->getQueryParams();
    $response->getBody()->write("<h1 style='text-align: center'>ChessAPI</h1><h2 style='color:red'>Сень, если нужен магазин напиши в ВК");
    return $response
          ->withHeader('Content-Type', 'text/html')
          ->withStatus(200);
});
$app->group('/api', function (RouteCollectorProxy $group) {


    /** (GET)Пусто - позже документация
     *
     * Не принимает параметров
     */
    $group->get('',"\Controller\apiController:index");


    /** (GET)Создает новую игру
     *
     * Не принимает параметров
     */
    $group->get('/creatGame',"\Controller\apiController:creatGame");


    /** (GET)Возвращает основную информацию о игре
     *
     * int gameID - id игры
     */
    $group->get('/GameInfo',"\Controller\apiController:GameInfo");


    /** (GET)Возвращает все возможные ходы для фигуры по координатам
     *
     * int x - координаты фигуры по X
     * int y - координаты фигуры по Y
     *         OR array data[x,y]- координаты фигуры по X и Y
     * int gameID - id игры
     */
    $group->get('/getPossibleMoves',"\Controller\apiController:getPossibleMoves");


    /** (POST)Перемещает фигуру с начальной позиции на следующую
     *
     * int oldX - старые координаты фигуры по X
     * int oldY - старые координаты фигуры по Y
     * int newX - новы координаты фигуры по X
     * int newY - новы координаты фигуры по Y
     *          OR array data[oldX, oldY, newX, newY]- координаты фигуры по X и Y
     * string key - уникальный ключ игрока
     * int gameID - id игры
     */
    $group->post('/moveFigure',"\Controller\apiController:moveFigure");
});

            //-------------------------//
$app->run();

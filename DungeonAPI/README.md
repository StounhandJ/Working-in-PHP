# API Подземелья
### [Документация](https://app.swaggerhub.com/apis/StounhandJ/Dungeon)  
При создании использовались:
1. [Slim](https://www.slimframework.com/) 4.8  
 За базовую структуру взят [Slim-Skeleton](https://github.com/slimphp/Slim-Skeleton).  
 Базу было решено не использовать из-за небольшого проекта, с одним активным подземельем.  
 Все подземелья хранятся в файле, но используется соответственно, только первое подземелье которое перезаписывается после начала новой игры.  
 Тесты недоделанны.
---
## Установка  
1. Установка нужных пакетов
```bash
composer install
```
---
## Важные файлы

 ### [Роутеры](app/routes.php) 
 ### [Контроллеры](src/Application/Actions/Game) 
 ### [Модели](src/Domain)
 ### [Middleware](src/Application/Middleware)

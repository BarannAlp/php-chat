<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require '../config/Database.php';


$app = AppFactory::create();


$app->get('/', function (Request $request, Response $response, $args) {

   if (in_array('sqlite3', PDO::getAvailableDrivers())) {
      echo 'SQLite PDO driver is available.';
  } else {
      echo 'SQLite PDO driver is not available.';
  }
   $response = $response->withHeader('Content-Type', 'application/json');
   $response = $response->withHeader('Access-Control-Allow-Origin', '*'); 
   $response->getBody()->write(json_encode("Hello World"));
   return $response;
});

require __DIR__ . '/../routes/create_tables.php';
require __DIR__ . '/../routes/create_user.php';
require __DIR__ . '/../routes/create_group.php';
require __DIR__ . '/../routes/join_group.php';
require __DIR__ . '/../routes/list_groups.php';
require __DIR__ . '/../routes/send_message.php';
require __DIR__ . '/../routes/retrieve_messages.php';


$app->run();

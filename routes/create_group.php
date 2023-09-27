<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/groups/create', function (Request $request, Response $response, $args) {
    try {
        $db = new Database();
        $connection = $db->getConnection(); 

        $name = $request->getParsedBody()['name'];
       
        $query = "INSERT INTO groups (name) VALUES (:name)";

        $stmt = $connection->prepare($query);
        $stmt->bindParam(':name', $name);

        if ($stmt->execute()) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'message' => 'Group created successfully',
            ]));
        } else {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response = $response->withStatus(500); 
            $response->getBody()->write(json_encode([
                'error' => 'Failed to create group',
            ]));
        }
    } catch (PDOException $e) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withStatus(500); 
        $response->getBody()->write(json_encode([
            'error' => 'Database error: ' . $e->getMessage(),
        ]));
    }

    return $response;
});

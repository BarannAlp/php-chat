<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/create-user', function (Request $request, Response $response, $args) {
    try {
        $db = new Database();
        $connection = $db->getConnection(); 

        $username = $request->getParsedBody()['username'];
        $number = $request->getParsedBody()['number'];
       
        $query = "INSERT INTO users (username, number) VALUES (:username, :number)";

        $stmt = $connection->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':number', $number);

        if ($stmt->execute()) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'message' => 'User created successfully',
            ]));
        } else {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response = $response->withStatus(500); 
            $response->getBody()->write(json_encode([
                'error' => 'Failed to create user',
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

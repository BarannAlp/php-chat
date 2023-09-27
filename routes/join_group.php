<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/{groups}/join', function (Request $request, Response $response, $args) {
    try {
        $db = new Database();
        $connection = $db->getConnection(); 
        $group_id = $args['groups'];
        $number = $request->getParsedBody()['number'];
       
        $query = "INSERT INTO user_group (user_id, group_id) VALUES (:number, :group_id)";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':number', $number);

        if ($stmt->execute()) {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'message' => 'Joined Group successfully',
            ]));
        } else {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response = $response->withStatus(500); 
            $response->getBody()->write(json_encode([
                'error' => 'Failed to joşn group',
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


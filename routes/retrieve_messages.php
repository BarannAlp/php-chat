<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/{groups}/messages', function (Request $request, Response $response, $args) {
    try {
        $db = new Database();
        $connection = $db->getConnection();
        $group_id = $args['groups'];

        $query = "SELECT * FROM messages WHERE group_id = :group_id ORDER BY created_at";

        $stmt = $connection->prepare($query);

        $stmt->bindParam(':group_id', $group_id);

        if ($stmt->execute()) {
            // Fetch all messages as an associative array.
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*'); 
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'messages' => $messages,
            ]));
        } else {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*'); 
            $response = $response->withStatus(500); 
            $response->getBody()->write(json_encode([
                'error' => 'Failed to fetch messages',
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


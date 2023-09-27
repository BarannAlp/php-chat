<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/{groups}/send-message', function (Request $request, Response $response, $args) {
    try {
        $db = new Database();
        $connection = $db->getConnection();

        $group_id = $args['groups'];
        $number = $request->getParsedBody()['number'];
        $message = $request->getParsedBody()['message'];

      
            $query = "INSERT INTO messages (group_id, user_id, message) VALUES (:group_id, :user_id, :message)";

            $stmt = $connection->prepare($query);

            $stmt->bindParam(':group_id', $group_id);
            $stmt->bindParam(':user_id', $number);
            $stmt->bindParam(':message', $message);

            if ($stmt->execute()) {
                $response = $response->withHeader('Content-Type', 'application/json');
                $response = $response->withHeader('Access-Control-Allow-Origin', '*');
                $response = $response->withStatus(201); 
                $response->getBody()->write(json_encode([
                    'message' => 'Message sent successfully',
                ]));
            } else {
                $response = $response->withHeader('Content-Type', 'application/json');
                $response = $response->withHeader('Access-Control-Allow-Origin', '*');
                $response = $response->withStatus(500); 
                $response->getBody()->write(json_encode([
                    'error' => 'Failed to send message',
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

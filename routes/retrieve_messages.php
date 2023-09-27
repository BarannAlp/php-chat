<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/{groups}/messages', function (Request $request, Response $response, $args) {
    try {
        // Assuming you have a Database class that handles database connections.
        $db = new Database();
        $connection = $db->getConnection();

        // Get the group_id from the route parameters.
        $group_id = $args['groups'];

        // Prepare the SQL query to retrieve messages for a specific group_id.
        $query = "SELECT * FROM messages WHERE group_id = :group_id ORDER BY created_at";

        $stmt = $connection->prepare($query);

        // Bind the group_id parameter to the prepared statement.
        $stmt->bindParam(':group_id', $group_id);

        // Execute the query.
        if ($stmt->execute()) {
            // Fetch all messages as an associative array.
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*'); // Allow requests from any origin (*)
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'messages' => $messages,
            ]));
        } else {
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Access-Control-Allow-Origin', '*'); // Allow requests from any origin (*)
            $response = $response->withStatus(500); // HTTP 500 Internal Server Error
            $response->getBody()->write(json_encode([
                'error' => 'Failed to fetch messages',
            ]));
        }
    } catch (PDOException $e) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withHeader('Access-Control-Allow-Origin', '*'); // Allow requests from any origin (*)
        $response = $response->withStatus(500); // HTTP 500 Internal Server Error
        $response->getBody()->write(json_encode([
            'error' => 'Database error: ' . $e->getMessage(),
        ]));
    }

    return $response;
});


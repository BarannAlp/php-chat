<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/create-tables', function (Request $request, Response $response, $args) {
    try {
        $db = new Database();
        $connection = $db->getConnection(); 
        $connection->exec('CREATE TABLE IF NOT EXISTS groups (
            id INTEGER PRIMARY KEY,
            name TEXT
        )');

        // Create the messages table
        $connection->exec('CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY,
            group_id INTEGER,
            user_id INTEGER,
            message TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (group_id) REFERENCES groups(id)
        )');

        $connection->exec('CREATE TABLE IF NOT EXISTS users (
            number INTEGER PRIMARY KEY,
            username TEXT UNIQUE
        )');
        $connection->exec('CREATE TABLE IF NOT EXISTS user_group (
            user_id INTEGER,
            group_id INTEGER,
            PRIMARY KEY (user_id, group_id),
            FOREIGN KEY (user_id) REFERENCES users(number),
            FOREIGN KEY (group_id) REFERENCES groups(id)
        )');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['message' => 'Tables created successfully']));

        return $response->withStatus(200);
        } catch (PDOException $e) {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));

        return $response->withStatus(500);
        
        }
});


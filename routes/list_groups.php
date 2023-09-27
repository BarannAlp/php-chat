<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/groups', function (Request $request, Response $response) {
    try {
        // Create a new database connection
        $db = new Database();
        $connection = $db->getConnection();

        // Query to retrieve groups and their users
        $sql = '
            SELECT groups.id AS group_id, groups.name AS group_name,
            users.number AS user_id, users.username AS username
            FROM groups
            LEFT JOIN user_group ON groups.id = user_group.group_id
            LEFT JOIN users ON user_group.user_id = users.number
        ';

        // Execute the SQL query
        $stmt = $connection->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organize the data into a structured format
        $groupData = [];
        foreach ($result as $row) {
            $group_id = $row['group_id'];
            $group_name = $row['group_name'];
            $user_id = $row['user_id'];
            $username = $row['username'];

            // Create a group entry if it doesn't exist
            if (!isset($groupData[$group_id])) {
                $groupData[$group_id] = [
                    'group_id' => $group_id,
                    'group_name' => $group_name,
                    'users' => [],
                ];
            }

            // Add the user to the group's users array
            if ($user_id !== null) {
                $groupData[$group_id]['users'][] = [
                    'user_id' => $user_id,
                    'username' => $username,
                ];
            }
        }

        // Convert the structured data to an array
        $groupList = array_values($groupData);

        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withHeader('Access-Control-Allow-Origin', '*'); // Allow requests from any origin (*)
        $response->getBody()->write(json_encode($groupList));

        return $response->withStatus(200);
    } catch (Exception $e) {
        // Handle exceptions and return an error response if needed
        $responseData = ['error' => $e->getMessage()];

        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($responseData));
        return $response->withStatus(500);
    }
});

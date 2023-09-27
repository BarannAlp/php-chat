<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "connected";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Get the user_id from the query parameters
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $queryParameters);
        
        if (isset($queryParameters['group_id'])) {
            $group_id = $queryParameters['group_id'];
            $conn->group_id = $group_id;
            echo "User ID set for connection {$conn->resourceId}: {$group_id}\n";
        } else {
            echo "User ID not provided for connection {$conn->resourceId}\n";
        }
    
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        
        echo "New connection! ({$conn->resourceId})\n";
    }
//     var conn = new WebSocket('ws://localhost:8080?user_id='+'12');

// };
// conn.onopen = function(e) {
//     console.log("Connection established!");
// };

// conn.onmessage = function(e) {
//     console.log(e.data);

public function onMessage(ConnectionInterface $from, $msg) {
    $data = json_decode($msg, true);
        $group_id = $data['group_id'];
        $number = $data['number'];
        $message = $data['message'];

        foreach ($this->clients as $client) {
            // Check if the client is in the same group as the sender
            if (isset($client->group_id) && $client->group_id === $group_id) {
                // Send the message to clients in the same group
                $client->send($msg);
            }
        }
  
}
// var message = {
//     group_id: '12',
//     number: '531',
//     message: 'Hello, WebSocket server!'
// };
// conn.send(JSON.stringify(message));

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
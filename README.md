# Chat Application Backend in PHP with WebSocket Support

This is a  chat application backend built in PHP using the Slim framework. Users can create chat groups, join these groups, send messages within them, and receive real-time updates using WebSocket. 
The groups are public, allowing any user to join any group. Users are identified by a unique token, username, or ID in the HTTP messages and the database.

## Features

- Create chat groups
- Join chat groups
- Send messages within groups
- List all messages within a group
- Real-time messaging using WebSocket

## Requirements

- PHP 7.0 or higher
- SQLite database
- Composer (for managing dependencies)

## Running

1. run "composer install"
2. php -S localhost:8888 to run the application
3. php server.dp for running the websocket for real-time messaging

you can use test.html for simple interface to test real-time messaging by opening two different chrome pages.
you can create user and save to database by filling the form or it wil use the default number and username.
you can find the other api's under routes folder
```shell
git clone https://github.com/yourusername/chat-app-backend.git
cd chat-app-backend

# Task List Rest API

A Json Restful API built using Laravel with routes protected by Laravel sanctum.

## Version 1

## Business requirements

- User can Create, Read, Update and Delete a tasks that belong to them
- User can filter tasks using multiple query parameters
- User can sort tasks
- User can register
- User can Login (personal access token)
- User can update their details
- Use rate limiting
- Write some tests
- Code coverage of tests
- Use cache to increase performance (server side: Reduce latency, Reduce load on servers)
- Use database index to increase performance of search (title, description)
- Document how to use the API

### Task structure

- Id
- Title
- Description
- Completed
- Due date
- Updated date
- Created date

### Task filter options

- title
- description
- completed

### Task sort options

- Title
- Description
- Completed
- Due date
- Updated date (default)
- Created date

### User structure

- Id
- Name
- email
- password
- remember_token
- Updated date
- Created date

## Technology stack used in this project

- apache/2.4.29
- php version 8.2.1
- composer version 2.5.1
- laravel version 9
- Xdebug version 3.2.0
- php unit
- PHPStorm (IDE)
- curl (cli)
- postman
- mysql 8
- docker version 4.16.2
- redis

- Developed using M1 macbook air

## Set Environment

Copy .env.example to .env and make the following changes to the .env file:

- Database (remove mysql settings)
    - DB_CONNECTION=mysql
    - DB_HOST=127.0.0.1
    - DB_PORT=3306
    - DB_DATABASE=laravel
    - DB_USERNAME=root
    - DB_PASSWORD=
- Auth token name for personal access tokens
    - USER_TOKEN=user_token
- Rate limit max
    - RATE_LIMIT_ATTEMPTS=60
- Caching with redis
    - REDIS_CACHE_DB=cache
- Xdebug 
    - SAIL_XDEBUG_MODE=develop,debug,coverage 
    - SAIL_XDEBUG_CONFIG="client_host=host.docker.internal"
    - PHP_IDE_CONFIG="serverName=localhost"

## Setup

Start the containers

```bash
./vendor/bin/sail up -d
```

Your can run the following command to install the dependencies.

```bash
./vendor/bin/sail composer install
```

The run the migrations

```bash
./vendor/bin/sail php artisan migrate
```

Generate app key (a new value will be set for APP_KEY in the .env file)
```bash
./vendor/bin/sail php artisan key:generate
```

## Run tests

Run tests using laravel sail command

```bash
./vendor/bin/sail artisan test
```

Run tests for code coverage using laravel sail command

```bash
./vendor/bin/sail artisan test --coverage
```

If you have issue with xdebug try to to rebuild the containers

```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
./vendor/bin/sail artisan test --coverage
```

## API usage

There are 3 ways to use the API.

1. A basic frontend repo [here](https://github.com/tapskuji/task-list-frontend).
2. Below are some usage examples, using curl as a client.
3. Postman collection /laravel-rest-api.postman_collection.json

### Register user

Request:

```bash
curl -X POST http://localhost:80/api/register \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -d '{"name": "Example name", "email": "test@example.com", "password": "password", "password_confirmation": "password"}'
```

Response:

```json
{
    "data": {
        "id": "7",
        "name": "Example name",
        "email": "test@example.com",
        "createdAt": "2023-03-08 07:41:39",
        "updatedAt": "2023-03-08 07:41:39"
    }
}
```

### Login user

Request:

```bash
curl -X POST http://localhost:80/api/login \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -d '{"email": "test@example.com", "password": "password"}'
```

Response:

```json
{
    "data": {
        "token_type": "Bearer",
        "expires_in": 900,
        "access_token": "47|74iBW9RJjqEoCCxckM8SH6LQ3TZfIyqpz1ARLvT9"
    }
}
```

### Logout user

Request:

```bash
curl -X POST http://localhost:80/api/logout \
    -H 'Accept: application/json' \
    -H 'Authorization: Bearer 47|74iBW9RJjqEoCCxckM8SH6LQ3TZfIyqpz1ARLvT9'
```

Response: empty

### Get user profile information

Request:

```bash
curl -X GET http://localhost:80/api/users \
   -H 'Accept: application/json' \
   -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b'
```

Response:

```json
{"data":{"id":"7","name":"Example name","email":"test@example.com","createdAt":"2023-03-08 07:41:39","updatedAt":"2023-03-08 07:41:39"}}
```

### Update user

Request:

```bash
curl -X PUT http://localhost:80/api/users \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b' \
    -d '{"name": "New name"}'
```

Response:

```json
{"data":{"id":"7","name":"New name","email":"test@example.com","createdAt":"2023-03-08 07:41:39","updatedAt":"2023-03-08 07:48:28"}}
```

### Create a task

Request:

```bash
curl -X POST http://localhost:80/api/tasks \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b' \
    -d '{"title": "New Task 1", "description": "My first task description here", "completed": false, "dueDate": "2023-04-26 10:00:00"}'
```

Response:

```json
{"data":{"id":"20","title":"New Task 1","description":"My first task description here","completed":false,"dueDate":"2023-04-26 10:00:00","createdAt":"2023-03-08 07:50:04","updatedAt":"2023-03-08 07:50:04"}}
```

### Update a task

Request:

```bash
curl -X PUT http://localhost:80/api/tasks/20 \
    -H 'Accept: application/json' \
    -H 'Content-Type: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b' \
    -d '{"completed": true}'
```

Response:

```json
{"data":{"id":"20","title":"New Task 1","description":"My first task description here","completed":true,"dueDate":"2023-04-26 10:00:00","createdAt":"2023-03-08 07:50:04","updatedAt":"2023-03-08 07:51:06"}}
```

### Get a task

Request:

```bash
curl -X GET http://localhost:80/api/tasks/20 \
    -H 'Accept: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b'
```

Response:

```json
{"data":{"id":"20","title":"New Task 1","description":"My first task description here","completed":true,"dueDate":"2023-04-26 10:00:00","createdAt":"2023-03-08 07:50:04","updatedAt":"2023-03-08 07:51:06"}}
```

### Delete a task

Request:

```bash
curl -X DELETE http://localhost:80/api/tasks/20 \
    -H 'Accept: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b'
```

Response:

```json
[]
```

### Get all tasks

Request:

```bash
curl -X GET http://localhost:80/api/tasks \
    -H 'Accept: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b'
```

Response:

```json
{"data":[{"id":"24","title":"Backend project","description":"consume and poll api using cron job","completed":false,"dueDate":"2023-06-15 00:00:00","createdAt":"2023-03-08 07:56:29","updatedAt":"2023-03-08 07:56:29"},{"id":"23","title":"Learn new framework","description":"create api with new framework","completed":true,"dueDate":"2023-05-26 00:00:00","createdAt":"2023-03-08 07:55:15","updatedAt":"2023-03-08 07:55:15"},{"id":"22","title":"New Task 2","description":"My new task description here","completed":true,"dueDate":"2023-04-26 00:00:00","createdAt":"2023-03-08 07:54:32","updatedAt":"2023-03-08 07:54:32"},{"id":"21","title":"Create APIs","description":null,"completed":false,"dueDate":null,"createdAt":"2023-03-08 07:54:02","updatedAt":"2023-03-08 07:54:02"}]}
```

### Get all tasks with filters and sort

Request:

```bash
curl -X GET http://localhost:80/api/tasks?search=api&completed=0&sort=title&direction=asc \
    -H 'Accept: application/json' \
    -H 'Authorization: Bearer 48|MM2GLtRmvnN4HYEeZJw1SIOxWiB8gPtiNiLi9R2b'
```

Response:

```json
{
    "data": [
        {
            "id": "24",
            "title": "Backend project",
            "description": "consume and poll api using cron job",
            "completed": false,
            "dueDate": "2023-06-15 00:00:00",
            "createdAt": "2023-03-08 07:56:29",
            "updatedAt": "2023-03-08 07:56:29"
        },
        {
            "id": "21",
            "title": "Create APIs",
            "description": null,
            "completed": false,
            "dueDate": null,
            "createdAt": "2023-03-08 07:54:02",
            "updatedAt": "2023-03-08 07:54:02"
        }
    ]
}
```


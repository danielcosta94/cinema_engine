# Cinema Price Engine

## Installation

Requirements

1. [Docker](https://docs.docker.com/engine/install/ubuntu/)
2. [Docker-Compose](https://docs.docker.com/compose/install/)

### Clone and enter into the project

```sh
https://github.com/danielcosta94/cinema_engine.git
cd cinema_engine/
```

### Install dependencies

1. Enter the project's container

```sh
docker exec -ti cinema-price-engine bash
```

2. Into the container install the dependencies

```sh
composer install
```

### Creating database schema and tables

1. Create database schema

```sh
php bin/console doctrine:database:create
```

2. Create tables

```sh
php bin/console doctrine:migrations:migrate 
```

### Populating database with fixtures (seeders)

```sh
php bin/console doctrine-fixtures-load
```

### Login

1. Enter the running [localhost page](http://localhost:8080/)
1. Check MySQL database `cinema_engine`
2. List table `users`
3. Select any user available
4. Login with email and password `12345` for test purposes

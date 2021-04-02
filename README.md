## Simple over engineered schedule REST API

## Requirements

* Docker
* Git

## Installation

After cloning the repo, go to the repo directory, then:

* Build using docker with docker

`docker-compose build && docker-compose up`

* Using composer (from docker php image) install all dependencies

`docker exec -it schedule_php composer install`

or separately

`docker exec -it schedule_php`

and then

`composer install`

...

And that's all!

## Usage

To see documentation and example usage of API calls, CURLs commands just go to Swagger UI page (you can also try using calls from there):

http://localhost:8088/docs

To run unit tests:

`bin/phpunit tests/`

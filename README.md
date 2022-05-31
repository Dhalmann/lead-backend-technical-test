# La Fourche Backend Developer test

This is a boilerplate for La Fourche Backend Developer test.
You were sent what to do from here by email.

This repository is a fork of [Symfony Docker](https://github.com/dunglas/symfony-docker).
It setups a Symfony in docker containers with php 7.4 and postgres 13.

The application is a an ecommerce app that handles orders. It must implement an HTTP API so the back office app can call against it

1. Here is what it needs:

-   add 1 new endpoint that automcatically adds tags to an order (update the schema in order to add tags)
-   if an order weighs more than 40 kg: add "heavy" tag
-   if an order is delivered out of France: add "foreignWarehouse" tag
-   if an order includes an anomaly or discrepancy: add "hasIssues" tag. Here are the possible anomalies:
    -   the order email contact is empty
    -   the order weighs more than 60 kg
    -   the order is delivered in France but has no valid French address (a valid French address is verified by https://geo.api.gouv.fr/adresse with a score greater than or equals to 0.6)

2. In the end, "hasIssues" tag was not efficient. 2 other actions are needed:

-   An analytic report should be generated asynchronously (in a worker) automatically when an order is tagged "hasIssues". The report is composed of all the anomalies (A JSON file will do fine). You can use Symfony Messenger with the Doctrine Transport to create the worker and the queue (documentation below).
-   The report should be stored in an object storage, MinIO is provided here to act as an AWS S3 bucket, where you can store your report files.

3. Code functional tests. The test of the tags adding endpoint at the very least

You are free to update the database as you wish.

You have in between 2 and 3 hours for this test, project setup time in local environment included.

Good luck and thank you !

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build --pull --no-cache` to build fresh images
3. Run `docker-compose up` (the logs will be displayed in the current shell)
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Open `https://localhost:8080` to query database, DSN in .env file

## Setup vendors and database

```
# run cli in the php container
docker-compose exec php /bin/sh

# add vendors
composer install

# update the database schema
bin/console doctrine:schema:update -f

# to load fixtures data
bin/console doctrine:fixtures:load
```

## MinIO Access and Config

MinIO accessKey and secretKey are "root" and "root".

You can access MinIO web console here : http://localhost:9001

You can create the bucket in the web console and provide the name here or create it programatically if it does not exist.

## Provided documentation

[Symfony Messenger](https://symfony.com/doc/current/messenger.html)
[Symfony Messenger Doctrine Transport](https://symfony.com/doc/current/messenger.html#doctrine-transport)
[MinIO](https://min.io/)
[MinIO in PHP](https://docs.min.io/docs/how-to-use-aws-sdk-for-php-with-minio-server.html)

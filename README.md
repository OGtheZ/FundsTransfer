# FundsTransfer is a simple funds transfer API on Symfony 6

### Setup

1. After downloading the project, go to the project directory and run composer install
   1. requires PHP 8.1 or higher and composer installed globally on your system
   2. may require additional PHP extensions to be installed
2. Setup .env , database url and api key for the api currency exchange rate
   1. in this implementation the api used is https://apilayer.com/marketplace/currency_data-api
   2. you may choose to write a different implementation following the interface
   3. the database type used in this project is Postgresql
3. after setting up .env variables, use bin/console doc:database:create to create the database
4. run migrations with doc:mig:mig
5. populate database with bin/console doc:fix:load (run DataFixtures)
    1. the fixtures will load currencies from the API to the database
    2. the fixtures will create 3 users, each with 3 accounts with 3 different currencies and balance of 1000 e.g.10 USD
    3. balance is stored in minimal nominal value. e.g. cents, Euro cents etc.
6. if you have symfony-cli installed, you can start the server with symfony server:start
   1. to install symfony-cli visit https://symfony.com/download

### Features
1. The application allows the transfer of funds between accounts, exchanging the amount if needed via an API call.
   1. route /api/transfer-funds
   2. the request should be sent as a form type (via Postman for example)
   3. the form expects:
      1. amount sent in cents (amounts are stored in minimal nominal value)
      2. accountFrom : id of sender account
      3. accountTo : id of receiving account
      4. currency : id of currency sent
   4. the amount sent can not exceed the balance of sending account
   5. accounts accept only the currency that they have, but can send any currency (will get exchanged)

2. You can retrieve all users accounts via /api/client/{client}/accounts/list.
   1. Place client id instead of {client}. e.g. 1
3. List of all transactions on account /api/account/{account}/transaction-list
   1. insert account id instead of {account} e.g. 1
   2. user query params page and limit for pagination e.g. ?page=2&limit=10
   3. returns newest transactions as first
   4. default values for page and limit if none provided in query - page=1, limit=10


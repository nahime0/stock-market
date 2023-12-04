# Stock market

![Static analysis](https://github.com/nahime0/stock-market/actions/workflows/static.yml/badge.svg)
![Automated testing](https://github.com/nahime0/stock-market/actions/workflows/tests.yml/badge.svg)

This application will periodically fetch stock prices from the [Alpha Vantage API](https://www.alphavantage.co/) and store them in a database. 
The data can be accessed through a REST API.

This is just a PoC, should not be used in production.

# Installation

After cloning the repository, you need to install the dependencies using composer
(refer to the composer documentation to install the tool):

```bash
composer install
```

Clone the env file and edit it to match your environment:

```bash
cp .env.example .env
vim .env
```

You need to specify credential for the `MySQL` connection and for the `Alpha Vantage` API.
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stock_market
DB_USERNAME=application
DB_PASSWORD=application


STOCK_MARKET_ALPHA_VANTAGE_API_KEY=demo
STOCK_MARKET_ALPHA_VANTAGE_API_URL=https://www.alphavantage.co
```

The application leverages the Laravel `Cache` module.

By default it's configured to use the `file` driver.
If you want to use a different driver, you can change it in the `.env` file:

For redis (the application already requires the needed predis/predis): 

```dotenv
CACHE_DRIVER=redis

REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=6379
```

Generate the application key:

```bash
php artisan key:generate
```

Run the migrations:

```bash
php artisan migrate
```

Fill in the default Symbols: 

```bash
php artisan app:fill-symbols
````

At this time you can run the application using either
a local web server (for example Laravel Valet or Laravel Herd)
or using the built-in PHP web server:

```bash
php artisan serve
```

# Running with Docker

The project can be run using `Docker`.

In the repository, a simple `docker-compose.yml` is provided.
It uses `mysql` for the database and a custom `php8.2-apache` image for the web server.

Due to time contraints, the docker image is not optimized for production.

A better solution would involve using nginx or caddy as a web server, and a separate container for php-fpm.

If you want to use docker, use the provided .env file:

```bash
cp .env.docker .env
```

Then run:

```bash
docker-compose up --build
```

At this point you need to run all the commands in the previous section, but using docker:

```bash
docker-compose exec php composer install
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan app:fill-symbols
```

To fetch the pricing, you need to run the scheduler:

```bash
docker-compose exec php php artisan schedule:work
```

or fetch one time

```bash
docker-compose exec php php artisan app:fetch-pricing
```

Remember to adapt the `.env` file to match your environment.
By default the api will point to the host machine on port `5000`, on which
the mock server will be running (if started).

The mapped port for the application is `8085`. So, once started, you can access the application
using the browser and navigating to `http://localhost:8085`.

# Usage

Once you have the list of the Symbol defined in the database, 
you can start fetching the stock pricing using the following command:

```bash
php artisan schedule:work
```

Each minute the pricing for all the symbols will be fetched and stored in the database.
The intraDay (with 1min interval) will be used.

If you want to manually fetch the pricing you can run:

```bash
php artisan app:fetch-pricing
```

## Web interface

The project has a very simple web interface, that you can access using the browser and 
navigating to the the app url of your project.

The web interface has a dashboard that shows the latest pricing for all the symbols in the database,
with the price variation from the previous period.

## REST API

At ths point you can start using the REST API.
For simplicity, in the repository there is a `Postman` collection that you can use to test the API.
You can find it in the `var` folder.

Edit the variables of the collection to match your environment.

There are three endpoints available:

### GET /api/symbols

This endpoint will return the list of `Symbols` available in the database.
The response will be a JSON array with shape:
```json
[
  {
    "symbol": "AAPL",
    "name": "Apple Inc."
  }
]
```

### GET /api/history/{symbol}

This endpoint will return the pricing history for the given `Symbol`.
The response will be a JSON array with shape:
```json
{
  "data": [
    {
      "datetime": "2023-12-03 15:01:00",
      "open": "0.0000",
      "high": "0.0000",
      "low": "0.0000",
      "close": "0.0000",
      "volume": "0"
    }
  ]
}
```

### GET /api/ticker/{symbol}

This endpoint will return the latest pricing for the given `Symbol`,
and the price variation from the previous period.

This API endpoints uses the Laravel `Cache` to avoid hitting the database every time.

The response will be a JSON array with shape:
```json
{
  "current": {
    "datetime": "2023-12-03 15:01:00",
    "open": "0.0000",
    "high": "0.0000",
    "low": "0.0000",
    "close": "0.0000",
    "volume": "0"
  },
  "previous": {
    "datetime": "2023-12-03 15:00:00",
    "open": "0.0000",
    "high": "0.0000",
    "low": "0.0000",
    "close": "0.0000",
    "volume": "0"
  },
  "change": "0.0000",
  "change_percent": "0.0000"
}
```

# Testing

## Automated tests

The application is covered by unit and feature tests.
The tests have been created using the [pest](https://pestphp.com/) framework, a wrapper around phpunit.
To launch tests, just run:

```bash
./vendor/bin/pest
```

or use the composer shortcut:

```bash
composer test
```

## Static analysis

The application is covered by static analysis using phpstan.
To launch the static analysis, just run:

```bash
./vendor/bin/phpstan
```

or use the composer shortcut:

```bash
composer static
```

## Test real world scenario

You can test the application, also without the real Alpha Vantage API key.
Just use the provided mock server.

## Using the provided Alpha Vantage mock server

If you want to test the application, without using the real Alpha Vantage API endpoint,
you can use the provided python mock server.

Just enter the `mock` folder, activate a python virtual environment, install requirements and
you are ready to start the server:

```bash
cd mock
python -m venv venv
source venv/bin/activate
pip install -r requirements.txt
python server.py
```

The mock service will listen on the default python flask port `5000`
You can specify this mock server in the `.env` file:

```dotenv
STOCK_MARKET_ALPHA_VANTAGE_API_KEY=mock
STOCK_MARKET_ALPHA_VANTAGE_API_URL=http://127.0.0.1:5000
```

The Api Key can be anything, it will not be checked by the mock server.

## Testing just the application with fake data

If you're just interested in testing the application, without fetching any data from the API,
you can fill the database with fake pricing using the following command:

```bash
php artisan db:seed Database\\Seeders\\PriceSeeder
```

The previous command will fill the pricing for all the `Symbols` in the database.
This works only if you already have the `Symbols` data in the database (see the installation section).

If you want to use also fake `Symbols` data, you can run:

```bash
php artisan db:seed Database\\Seeders\\SymbolSeeder
```

# Information about the project

## Architecture

The application is built using the `Laravel framework`.
The main logic for the Alpha Vantage API is located in the `app/AlphaVantage` folder.

The code has been written to be statically typed, using the `PHPStan` tool.
The architecture of each file is tested during the Unit testing.

strict_types has been used to enforce strict typing among the application.

It leverage the Laravel `Cache` to avoid hitting the database every time the `/api/ticker/{symbol}` endpoint is called.

### Classes

#### App\AlphaVantage\Client\Client

This is the main class that interacts with the Alpha Vantage API.
It will handle communication with the API and data transformation.
Results for every call will be a Laravel Collection of StockPrice objects.

Dependency injection for the class is handle in the `app\Providers\AppServiceProvider.php` file.

#### App\AlphaVantage\Client\StockPrice

This is the representation of a single Stock Price. It is valid either for 
the intraDay and daily pricing. 
It contains data fetched from the API, transormed in the right data type.

#### App\AlphaVantage\Enums\Functions

This Enum contains the functions of the Alpha Vantage API supported by the
application.
The method `jsonKey()` provides a convenient way of establishing the key of the
json response that will be used to fetch the data.

#### App\AlphaVantage\AlphaVantage

This is a Laravel Facade, with root accessors to the Alpha Vantage Client.
This is used to easily access the Alpha Vantage Client from the application.

### Configuration

The configuration for the application is stored in the `config/stock_market.php` file.
Each configuration key will be available in the application using the `config()` helper, 
and can be set-up using environment variables.

### Commands

#### App\Console\Commands\FillSymbols

This command will fill the database with the default symbols.
Usage:

```bash
php artisan app:fill-symbols
```

#### App\Console\Commands\FetchPricing

This command will fetch the pricing for all the symbols in the database.
Usage:

```bash
php artisan app:fetch-pricing
```

This command is scheduled (in the `app/Console/Kernel.php` file) to run every minute.
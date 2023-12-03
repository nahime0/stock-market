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

TBD

# Running with Docker

TBD

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
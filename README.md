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

To test the application in a real world scenario, without using the real Alpha Vantage API endpoint,
you can seed the database with fake data and use the provided mock server (see next chapter).

To seed the database:

```bash
php artisan db:seed Database\\Seeders\\SymbolSeeder
```

This command will populate the `symbols` table with fake companies.
You can then use those fake companies to fetch stock prices.

If you want to seed also pricing data, you call the db:seed without arguments:

```bash
php artisan db:seed
```

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

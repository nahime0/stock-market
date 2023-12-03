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

# Testing

## Automated tests

TBD

## Test real world scenario

To test the application in a real world scenario, without using the real Alpha Vantage API endpoint,
you can seed the database with fake data and use the provided mock server (see next chapter).

To seed the database:

```bash
php artisan db:seed
```

This command will populate the `symbols` table with fake companies.
You can then use those fake companies to fetch stock prices.

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

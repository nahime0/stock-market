# Stock market

This application will periodically fetch stock prices from the [Alpha Vantage API](https://www.alphavantage.co/) and store them in a database. 
The data can be accessed through a REST API.

# Installation

TBD

# Running with Docker

TBD

# Using the provided Alpha Vantage mock server

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

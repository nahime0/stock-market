import random
from datetime import datetime, timedelta
from flask import Flask, request

app = Flask(__name__)
# Disable sorting of JSON keys to preserve reverse date order, like the Alpha Advantage API
app.json.sort_keys = False

@app.route('/', methods=['GET'])
def api():
    symbol = request.args.get('symbol')
    interval = request.args.get('interval')
    if symbol is None:
        return 'Please provide a symbol', 400

    if interval is None:
        return 'Please provide an interval', 400
    elif interval == '1min':
        minutes = 1
    elif interval == '5min':
        minutes = 5
    elif interval == '15min':
        minutes = 15
    elif interval == '30min':
        minutes = 30
    elif interval == '60min':
        minutes = 60
    else:
        return 'Invalid interval', 400

    time_series = {}
    now = datetime.now()
    price_open = random.randint(100, 200)
    for i in range(100):
        t_datetime = now - timedelta(minutes=i * minutes)
        price_change = random.uniform(0.99, 1.01) # +- 1%
        price_close = price_open * price_change
        volume = random.randint(1, 1000)
        price_max = max([price_open, price_close])
        price_min = min([price_open, price_close])
        time_series[t_datetime.strftime("%Y-%m-%d %H:%M:00")] = {
            '1. open': "%.4f" % price_open,
            '2. high': "%.4f" % price_max,
            '3. low': "%.4f" % price_min,
            '4. close': "%.4f" % price_close,
            '5. volume': volume
        }
        price_open = price_close

    return {
        'Meta Data': {
            '1. Information': "Intraday (%smin) open, high, low, close prices and volume" % minutes,
            '2. Symbol': symbol,
            '3. Last Refreshed': now.strftime("%Y-%m-%d %H:%M:00"),
            '4. Interval': "%smin" % minutes,
            '5. Output Size': 'Compact',
            '6. Time Zone': 'US/Eastern'
        },
        "Time Series (%smin)" % minutes: time_series
    }

if __name__ == '__main__':
    app.run(debug=True)

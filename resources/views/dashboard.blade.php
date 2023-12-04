<x-layout>
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="w-full text-center pb-4 text-lg">
                <p>This page will automatically fetch new data from the API each minute.</p>
                <a href="/" class="underline hover:bg-red-200">Go back to homepage</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($symbols as $symbol)
                    <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 hover:outline hover:outline-2 hover:outline-red-500">
                        <div class="flex flex-col justify-between w-full">
                            <h2 class="text-lg text-red-900 font-bold">{{ $symbol->name }} ({{ $symbol->symbol }})</h2>
                            <div
                                    x-data='{
                                        symbol: @json($symbol->symbol),
                                        ticker: {
                                            current: {
                                                close: "0"
                                            },
                                            previous: {
                                                close: "0"
                                            },
                                            change: "0",
                                            change_percent: "0"
                                        },
                                        start() {
                                            setInterval(() => {
                                                this.retrieve();
                                            }, 60000);
                                            this.retrieve();
                                        },
                                        async retrieve() {
                                            this.ticker = await (await fetch("/api/ticker/" + this.symbol)).json();
                                        }
                                    }'
                                    x-init="start()"
                                    class="flex justify-between"
                            >
                                <div class="flex flex-col">
                                    <span x-text="ticker.current.close" class="text-right"></span>
                                    <span x-text="ticker.previous.close" class="text-right"></span>
                                </div>
                                <div class="flex flex-col">
                                    <span x-text="ticker.change" :class="parseFloat(ticker.change_percent) > 0 ? 'text-green-900' : 'text-red-900'" class="font-bold text-right"></span>
                                    <span x-text="ticker.change_percent + '%'" :class="parseFloat(ticker.change_percent) > 0 ? 'text-green-900' : 'text-red-900'" class="font-bold text-right"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>
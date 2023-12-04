<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Symbol;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'symbols' => Symbol::all(),
        ]);
    }
}

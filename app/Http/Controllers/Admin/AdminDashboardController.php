<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the administration dashboard.
     */
    public function __invoke(Request $request): View
    {
        return view('admin.dashboard', [
            'user' => $request->user(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Display the calendar view.
     * Accepts optional query parameters `month` and `year`.
     */
    public function index(Request $request)
    {
        $month = (int) $request->query('month', date('n'));
        $year = (int) $request->query('year', date('Y'));

        return view('calendar', compact('month', 'year'));
    }
}

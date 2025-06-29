<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puzzle;

class TopTenController extends Controller
{
    public function index(Request $request)
    {   
        // Fetch the top 10 scores from the database
        $scores = Puzzle::with('student')
            ->orderBy('total_score', 'desc')
            ->take(10)
            ->get();

        return view('top_ten', compact('scores'));
    }
}

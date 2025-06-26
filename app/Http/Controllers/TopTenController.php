<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HighScore;

class TopTenController extends Controller
{
    public function index(Request $request)
    {
        $scores = HighScore::select('word')
            ->with(['student'])
            ->selectRaw('MAX(score) as score, MIN(id) as first_id')
            ->groupBy('word')
            ->orderByDesc('score')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return HighScore::with('student')->find($item->first_id);
            });

        return view('top_ten', compact('scores'));
    }
}

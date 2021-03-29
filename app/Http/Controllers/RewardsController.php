<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class RewardsController extends Controller
{
    public function index()
    {
        return Inertia::render('Rewards/Index', [
            'filters' => Request::all('search'),
            'rewards' => Auth::user()->account->rewards()
                ->orderBy('name')
                ->filter(Request::only('search'))
                ->paginate()
                ->withQueryString()
                ->through(function ($reward) {
                    return [
                        'id' => $reward->id,
                        'name' => $reward->name,
                    ];
                }),
        ]);
    }
}

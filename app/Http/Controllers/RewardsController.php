<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class RewardsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Inertia::render('Rewards/Index', [
            'filters' => FacadesRequest::all('search'),
            'rewards' => $user->rewards()
                ->orderBy('name')
                ->filter(FacadesRequest::only('search'))
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

    public function input(Request $request)
    {
        return Inertia::render('Rewards/Redeem');
    }

    public function check(Request $request)
    {
        $this->validate($request, [
            'redeem_code' => 'required|string|min:10|max:50'
        ]);

        return back()->withError( 'Redeem code does not exist.' );

        return Inertia::render('Orders/Index', compact('filters', 'orders'));
    }

    public function redeem(Request $request)
    {
        return back()->withError('Redeem code does not exist.');

        return Inertia::render('Orders/Index', compact('filters', 'orders'));
    }
}

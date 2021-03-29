<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ActivitiesController extends Controller
{
    public function index()
    {
        return Inertia::render('Activitiess/Index', [
            'filters' => Request::all('search'),
            'activities' => Auth::user()->activities()
                ->filter(Request::only('search'))
                ->paginate()
                ->withQueryString()
                ->through(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'reward' => $activity->reward ? $activity->reward->only('name') : null,
                    ];
                }),
        ]);
    }

}

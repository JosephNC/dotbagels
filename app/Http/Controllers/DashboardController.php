<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        if ( auth()->user()->isAdmin ) return $this->adminDashboard();

        return Inertia::render('Dashboard/Index');
    }
    
    private function adminDashboard()
    {
        return Inertia::render('Dashboard/Admin', [
            'dashboard' => [
                'total_users_today' => User::mergeNameRoleForFilter()
                    ->whereDate( 'created_at', today())
                    ->where('role', 'user')
                    ->count(),
                'total_agents_today' => User::mergeNameRoleForFilter()
                    ->whereDate('created_at', today())
                    ->where('role', 'agent')
                    ->count()
            ]
        ]);
    }
}

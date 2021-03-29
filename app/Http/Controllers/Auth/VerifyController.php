<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyController extends Controller
{

    /**
     * Where to redirect if the authenticated user is already verified.
     * 
     * OR after a verification token is verified.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the application's login form.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = auth()->user();

        if ( $user->hasVerifiedEmail() ) return redirect( $this->redirectTo );

        return Inertia::render('Auth/Verify');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect( $this->redirectTo )->with('success', 'Congrats! Your account is now verified.');;
    }

    public function send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}

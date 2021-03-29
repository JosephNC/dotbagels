<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ResetPassword Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after forgot password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the application's forgot password form.
     *
     * @return \Inertia\Response
     */
    public function showResetForm( Request $request )
    {
        // $token = $request->route()->parameter('token');

        return Inertia::render(
            'Auth/ResetPassword',
            [
                'token' => $request->token,
                'email' => $request->email
            ]
        );
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token'     => 'required',
            'email'     => 'required|email',
            'password'  => 'required|string|min:6|max:30|confirmed',
        ];
    }

    // /**
    //  * Get the password reset validation error messages.
    //  *
    //  * @return array
    //  */
    // protected function validationErrorMessages()
    // {
    //     return [];
    // }
}

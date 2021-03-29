<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use RegistersUsers;

    /**
     * Show the application's registration form.
     *
     * @return \Inertia\Response
     */
    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data = array_map( 'trim', $data ); // Trim all data

        return Validator::make($data, [
            'email'         => 'required|string|email|max:100|unique:users',
            'password'      => 'required|string|min:6|max:30',
            'first_name'    => 'required|string|min:2|max:25',
            'last_name'     => 'required|string|min:2|max:25',
            'phone'         => 'required|string|phone:mobile',
            'phone_country' => 'required_with:phone',
            'tos'           => 'required|accepted',
        ], [
            'required'      => ':attribute is required.',
            'tos.required'  => 'Accept our terms to proceed.',
            'phone'         => ':attribute is invalid.',
        ], [
            'email'         => 'Email address',
            'first_name'    => 'First name',
            'last_name'     => 'Last name',
            'phone'         => 'Phone number',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data = array_map( 'trim', $data ); // Trim all data

        // Create User
        $user = (new User)->create([
            'email'     => $data['email'],
            'password'  => $data['password']
        ]);

        // Create User Meta
        $user->user_metas()->createMany([
            [ 'key' => 'first_name', 'value' => ucfirst($data['first_name']) ],
            [ 'key' => 'last_name', 'value' => ucfirst($data['last_name']) ],
            [ 'key' => 'phone', 'value' => PhoneNumber::make($data['phone'], $data['phone_country'])->formatE164() ],
            [ 'key' => 'role', 'value' => 'user' ]
        ]);

        return $user;
    }

    public function redirectTo()
    {
        return route( 'verification.notice' );
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $msg = 'Check your inbox. We emailed you a link to activate your account.';

        $request->session()->flash('success', $msg);
    }
}

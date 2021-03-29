<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;

class UsersController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->get()
                ->transform(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'owner' => $user->owner,
                        'photo' => $user->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
                        'deleted_at' => $user->deleted_at,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    // public function store()
    // {
    //     Request::validate([
    //         'first_name' => ['required', 'max:50'],
    //         'last_name' => ['required', 'max:50'],
    //         'email' => ['required', 'max:50', 'email', Rule::unique('users')],
    //         'password' => ['nullable'],
    //         'owner' => ['required', 'boolean'],
    //         'photo' => ['nullable', 'image'],
    //     ]);

    //     Auth::user()->account->users()->create([
    //         'first_name' => Request::get('first_name'),
    //         'last_name' => Request::get('last_name'),
    //         'email' => Request::get('email'),
    //         'password' => Request::get('password'),
    //         'owner' => Request::get('owner'),
    //         'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
    //     ]);

    //     return Redirect::route('users')->with('success', 'User created.');
    // }

    public function edit(User $user)
    {
        // $auth_user = auth()->user();

        // $args = [];

        // If authenticated user is admin and not his/her account.
        // if ( $auth_user->isAdmin && $auth_user->id != $user->id ) $args['user'][ 'deleted_at' ] = $user->deleted_at;

        return Inertia::render('Users/Edit');
    }

    public function update(User $user)
    {
        // if (App::environment('demo') && $user->isDemoUser()) {
        //     return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        // }

        $data = request()->all();

        Validator::make( $data, [
            // 'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'first_name'    => 'required|string|min:2|max:25',
            'last_name'     => 'required|string|min:2|max:25',
            'phone'         => 'required|string|phone:mobile',
            'phone_country' => 'required_with:phone',
            'password'      => 'nullable|string|min:6|max:30',
            'photo'         => 'nullable|image',
        ], [
            'required'      => ':attribute is required.',
            'phone'         => ':attribute is invalid.',
        ], [
            'email'         => 'Email address',
            'first_name'    => 'First name',
            'last_name'     => 'Last name',
            'phone'         => 'Phone number',
        ])->validate();

        $part = Request::only('first_name', 'last_name', 'phone');

        foreach( $part as $key => $value ) {
            $value = in_array( $key, [ 'first_name', 'last_name' ] ) ? ucfirst( $value ) : $value;

            if ( $key == 'phone' ) $value = PhoneNumber::make($data['phone'], $data['phone_country'])->formatE164();

            $user->meta( $key, is_null( $value ) ? '' : $value, true );
        }

        if (Request::file('photo')) {
            // Remove the old photo
            Storage::delete( $user->meta( 'photo' ) );

            // Upload the new photo
            $photo_path = Request::file('photo')->store('users');

            // Update the meta
            $user->meta('photo', $photo_path, true);
        }

        if (Request::get('password')) $user->update(['password' => Request::get('password')]);

        return Redirect::back()->with('success', 'Account updated.');
    }

    public function destroy(User $user)
    {
        // if (App::environment('demo') && $user->isDemoUser()) {
        //     return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        // }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        // dd(User::mergeNameRoleForFilter()
        //     ->orderByName()
        //     ->filter(Request::only('search', 'role', 'trashed'))
        //     ->paginate(settings('users_per_page', '10'))
        //     ->withQueryString()
        //     ->through(function ($user) {
        //         return [
        //             'id' => $user->id,
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'role' => settings('roles')[$user->meta('role', 'user')],
        //             'photo' => $user->photoUrl(['w' => 40, 'h' => 40, 'fit' => 'crop']),
        //             'deleted_at' => $user->deleted_at,
        //         ];
        //     }));
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => User::mergeNameRoleForFilter()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate(settings('users_per_page', '10'))
                ->withQueryString()
                ->through(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'verified'      => $user->hasVerifiedEmail(),
                        'role' => settings('roles')[$user->meta('role', 'user')],
                        'photo' => $user->photoUrl(['w' => 100, 'h' => 100, 'fit' => 'crop']),
                        'created_at' => $user->created_at->diffForHumans(),
                        'deleted_at' => $user->deleted_at,
                    ];
                }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store()
    {
        $roles = join(',', array_keys(settings('roles')));
        $data = request()->all();

        Validator::make($data, [
            'email'         => 'required|string|email|max:100|unique:users',
            'password'      => 'required|string|min:6|max:30',
            'first_name'    => 'required|string|min:2|max:25',
            'last_name'     => 'required|string|min:2|max:25',
            'phone'         => 'required|string|phone:mobile',
            'phone_country' => 'required_with:phone',
            'role'          => "required|string|in:$roles",
            'auto_verify'   => 'boolean',
        ], [
            'required'      => ':attribute is required.',
            'phone'         => ':attribute is invalid.',
        ], [
            'email'         => 'Email address',
            'first_name'    => 'First name',
            'last_name'     => 'Last name',
            'phone'         => 'Phone number',
        ])->validate();

        $data = array_map('trim', $data); // Trim all data

        // Create User
        $user = (new User)->create([
            'email'     => $data['email'],
            'password'  => $data['password']
        ]);

        // Create User Meta
        $user->user_metas()->createMany([
            ['key' => 'first_name', 'value' => ucfirst($data['first_name'])],
            ['key' => 'last_name', 'value' => ucfirst($data['last_name'])],
            ['key' => 'phone', 'value' => PhoneNumber::make($data['phone'], $data['phone_country'])->formatE164()],
            ['key' => 'role', 'value' => $data['role']],
            ['key' => 'photo', 'value' => Request::file('photo') ? Request::file('photo')->store('users') : '']
        ]);

        if (!$data['auto_verify']) event(new Registered($user)); // Announce event

        return redirect(route('users'))->with('success', 'User created.');
    }

    public function account()
    {
        return Inertia::render('Users/Account');
    }

    public function accountUpdate()
    {
        // if (!auth()->user()->isAdmin && $user->id != auth()->user()->id) {
        //     return request()->expectsJson()
        //         ? abort(403, 'What are you trying to do?')
        //         : back()->withError('What are you trying to do?');
        // }

        return $this->update(auth()->user());
    }

    public function edit(User $user)
    {
        $auth_user = auth()->user();

        if ($user->id == $auth_user->id) return redirect(route('account'));

        if ($user) $phone_number = PhoneNumber::make($user->meta('phone', ''));

        return Inertia::render('Users/Edit', [
            'user' => [
                'id'            => $user->id,
                'email'         => $user->email,
                'is_admin'      => $user->isAdmin,
                'full_name'     => $user->name,
                'first_name'    => $user->meta('first_name', ''),
                'last_name'     => $user->meta('last_name', ''),
                'role'          => $user->meta('role', 'user'),
                'phone'         => $phone_number->formatNational(),
                'phone_country' => $phone_number->getCountry(),
                'points'        => (int) $user->meta('points', 0),
                'photo'         => $user->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
                'deleted_at'    => $user->deleted_at
            ]
        ]);
    }

    public function update(User $user)
    {
        $auth_user = auth()->user();
        $can_admin_update = $auth_user->isAdmin && $auth_user->id != $user->id;
        $data = request()->all();
        $roles = join(',', array_keys(settings('roles')));
        $validator_rules = [
            'first_name'    => 'required|string|min:2|max:25',
            'last_name'     => 'required|string|min:2|max:25',
            'phone'         => 'required|string|phone:mobile',
            'phone_country' => 'required_with:phone',
            'password'      => 'nullable|string|min:6|max:30',
            'photo'         => 'nullable|image',
            'role'          => "nullable|string|in:$roles",
        ];

        if ($can_admin_update) $validator_rules['email'] = ['required', 'string', 'max:50', 'email', Rule::unique('users')->ignore($user->id)];

        Validator::make($data, $validator_rules, [
            'required'      => ':attribute is required.',
            'phone'         => ':attribute is invalid.',
        ], [
            'email'         => 'Email address',
            'first_name'    => 'First name',
            'last_name'     => 'Last name',
            'phone'         => 'Phone number',
        ])->validate();

        $data = array_map('trim', $data); // Trim all data

        $part = Request::only('first_name', 'last_name', 'phone', 'role');

        foreach ($part as $key => $value) {
            if ($key == 'role' && !$can_admin_update) continue;

            $value = in_array($key, ['first_name', 'last_name']) ? ucfirst($value) : $value;

            if ($key == 'phone') $value = PhoneNumber::make($data['phone'], $data['phone_country'])->formatE164();

            $user->meta($key, is_null($value) ? '' : $value, true);
        }

        if (Request::file('photo')) {
            // Remove the old photo
            Storage::delete($user->meta('photo'));

            // Upload the new photo
            $photo_path = Request::file('photo')->store('users');

            // Update the meta
            $user->meta('photo', $photo_path ?? '', true);
        }

        if ($can_admin_update && $user->email != $data['email']) $user->update(['email' => $data['email']]);
        if ($data['password']) $user->update(['password' => $data['password']]);

        return Redirect::back()->with('success', 'Account updated.');
    }

    public function destroy(User $user)
    {
        $auth_user = auth()->user();
        $can_admin_destroy = $auth_user->isAdmin && $auth_user->id != $user->id;

        if (!$can_admin_destroy) return back()->withError('You cannot perform this operation.');

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function restore(User $user)
    {
        $user->restore();

        return back()->with('success', 'User restored.');
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Propaganistas\LaravelPhone\PhoneNumber;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $user = $request->user();

        $data = [
            'auth' => function () use ($request, $user) {
                if ($user) $phone_number = PhoneNumber::make($user->meta('phone', ''));

                return [
                    'user' => $user ? [
                        'id'            => $user->id,
                        // 'uuid'          => $user->uuid,
                        'email'         => $user->email,
                        'is_admin'      => $user->isAdmin,
                        'full_name'     => $user->name,
                        'first_name'    => $user->meta('first_name', ''),
                        'last_name'     => $user->meta('last_name', ''),
                        'phone'         => $phone_number->formatNational(),
                        'phone_country' => $phone_number->getCountry(),
                        'points'        => (int) $user->meta('points', 0),
                        'photo'         => $user->photoUrl(['w' => 60, 'h' => 60, 'fit' => 'crop']),
                    ] : null,
                ];
            },
            'flash' => function () use ($request) {
                return [
                    'success'   => $request->session()->get('success'),
                    'status'    => $request->session()->get('status'),
                    'error'     => $request->session()->get('error'),
                ];
            },
        ];

        if ( $user && $user->isAdmin ) {
            $data['settings'] = collect( settings() )->filter( function( $value, $key ) {
                return stripos( 'square_data_', $key ) === false;
            } );
            $data['settings']['currency'] = $data['settings']['currency'] ?? 'USD';
            $data['settings']['currency_data'] = currencies($data['settings']['currency']);
            $data['admin_layout'] = $user->isAdmin || $user->isAgent;
        }

        return array_merge(parent::share($request), $data);
    }
}

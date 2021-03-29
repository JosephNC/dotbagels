<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

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
        return array_merge(parent::share($request), [
            'auth' => function () use ($request) {
                $user = $request->user();

                return [
                    'user' => $user ? [
                        'id'            => $user->id,
                        'email'         => $user->email,
                        'first_name'    => $user->meta( 'first_name', '' ),
                        'last_name'     => $user->meta( 'last_name', '' ),
                        'phone'         => $user->meta( 'phone', '' ),
                        // 'phone'         => $phone_number,
                        // 'phone_country' => $phone_country,
                        'points'        => (int) $user->meta( 'points', 0 ),
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
        ]);
    }
}

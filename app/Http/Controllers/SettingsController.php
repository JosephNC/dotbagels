<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Square\Environment;
use Square\Exceptions\ApiException;
use Square\SquareClient;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Index');
    }

    public function update(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'square_app_id'         => 'required|string',
            'square_access_token'   => 'required|string',
            'square_sandbox'        => 'required|boolean',
        ], [
            'required'      => ':attribute is required.',
        ])->validate();

        $only = $request->only('square_app_id', 'square_access_token');
        $only['square_sandbox'] = filter_var($data['square_sandbox'], FILTER_VALIDATE_BOOLEAN);

        // Try the API credentials
        $result = $this->tryCredentials($only);

        if ($result['type'] == 'error' ) return back()->withError( $result['message'] );

        $only[ 'currency' ]             = $result['merchant']['currency'];
        $only[ 'square_location_id' ]   = $result['merchant']['main_location_id'];

        $save = settings($only, true);

        if ($save) {
            $request->session()->flash('success', "Settings saved for {$result['merchant']['business_name']}");
        } else {
            $request->session()->flash('error', 'Cannot save settings.');
        }

        return back();
    }

    private function tryCredentials(array $credentials)
    {
        extract($credentials);

        $result = [
            'type' => 'error',
            'message' => 'Error occurred',
        ];

        try {
            $client = new SquareClient([
                'accessToken' => $square_access_token ?? '',
                'environment' => $square_sandox ?? true ? Environment::SANDBOX : Environment::PRODUCTION,
            ]);
            $api_response = $client->getMerchantsApi()->retrieveMerchant('me');

            if ($api_response->isError()) throw new \Exception($api_response->getErrors()[0]->getDetail());

            $result = [
                'type' => 'success',
                'message' => 'Valid Credentials.',
                'merchant' => $api_response->getResult()->getMerchant()->jsonSerialize()
            ];

        } catch (ApiException $e) {
            $result['message'] = $e->getMessage();
        } catch (\Throwable $e) {
            $result['message'] = $e->getMessage();
        }

        return $result;
    }
}

<?php

use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Square\Environment;
use Square\SquareClient;

if (!function_exists('is_first_user')) :
    /**
     * Check whether we are doing a new installation
     *
     * @return bool Returns the true or false
     */
    function is_first_user(): bool
    {
        $user = User::first();

        return !$user || empty($user);
    }
endif;

if (!function_exists('is_admin')) :
    /**
     * Check whether the current or specified user is an admin
     *
     * @return bool Returns the true or false
     */
    function is_admin($user = 0): bool
    {

        if ($user == 0) {
            $user_id = (int) @auth()->user()->id;
        } elseif ($user > 0) {
            $user_id = (int) $user;
        } elseif (is_object($user)) {
            $user_id = (int) @$user->id;
        }

        $user = User::find($user_id);

        return !$user || !$user->isAdmin ? false : true;
    }
endif;

// if (!function_exists('square_raw_data')) :
//     /**
//      * Check whether the current or specified user is an admin
//      *
//      * @return mixed
//      */
//     function square_raw_data($key = null, $value = null, $update = false)
//     {
//         $data = settings(square_raw_data', []);

//         if ( empty( $key ) ) return $data;

//         if ( ! $update ) return $data[ $key ] ?? $value;

//         $data[ $key ] = $value;

//         return settings( 'square_raw_data', $data, true );
//     }
// endif;

if (!function_exists('square_raw_data')) :
    /**
     * Check whether the current or specified user is an admin
     *
     * @return mixed
     */
    function square_raw_data(string $key = null, $value = null, $update = false)
    {
        $data = settings('square_data_' . $key, []);

        if ( empty( $key ) ) return $data;

        if ( ! $update ) return $data ?? $value;

        $data = $value;

        return settings( 'square_data_' . $key, $data, true );
    }
endif;

if (!function_exists('settings')) :
    /**
     * Check whether the current or specified user is an admin
     *
     * @return mixed
     */
    function settings($key = null, $value = '', $update = false)
    {
        $defaults = [
            'roles' => [
                'user' => 'User',
                'agent' => 'Agent',
                'admin' => 'Admin',
            ],
        ];
        $settings = Setting::get();

        /**
         * Multi Data
         */

        // Get
        if (!isset($key) || empty($key)) {
            $data = [];

            foreach ($settings as $setting) {
                $get = $setting->value;
                $get = $get == 'false' || $get == 'true' ? filter_var($get, FILTER_VALIDATE_BOOLEAN) : (is_json($get) ? to_array($get) : $get);
                $data[$setting->key] = $get;
            }

            return collect($data + $defaults);
        }

        // Update
        else if (is_array($key) && $value === true) {
            foreach ($key as $k => $v) settings($k, $v, true);

            return true;
        }

        
        /**
         * Single Data
         */
        
        $get = Setting::where('key', $key)->first();

        // Get
        if ($update === false) {
            if (empty($get)) return array_key_exists($key, $defaults) ? $defaults[$key] : $value;

            $get = trim($get->value);

            return $get == 'false' || $get == 'true' ? filter_var($get, FILTER_VALIDATE_BOOLEAN) : ( is_json( $get ) ? to_array( $get ) : $get );
        }

        // Update
        elseif ($update === true) {
            $value = is_bool($value) ? var_export($value, true) : (is_array($value) || is_object($value) ? json_encode($value) : $value);

            if (empty($get)) {
                Setting::create(compact('key', 'value'));
            } else {
                $get->value  = $value;
                $get->save();
            }

            return true;
        }

        return false;
    }
endif;

if (!function_exists('currencies')) :
    /**
     * Returns Supported currencies
     * @param string $currency Optional currency data to retrieve.
     * @return object
     */
    function currencies($currency = null): object
    {

        /**
         * List of support currencies for
         * 1 - This type of currency does not support decimals.
         *
         * @var object
         */
        $currencies = to_object([
            'AUD' => [
                'name'          => 'Australian Dollar',
                'major'         => 'dollar',
                'minor'         => 'cent',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ' ',
            ],

            'BRL' => [
                'name'          => 'Brazilian Real',
                'major'         => 'real',
                'minor'         => 'centavo',
                'symbol'        => 'R$',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => '.',
            ],

            'CAD' => [
                'name'          => 'Canadian Dollar',
                'major'         => 'dollar',
                'minor'         => 'cent',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'CNY' => [
                'name'          => 'Chinese Yuan Renminbi',
                'major'         => 'yuan renminbi',
                'minor'         => 'jiao',
                'symbol'        => '¥',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'CZK' => [
                'name'          => 'Czech Koruna',
                'major'         => 'koruna',
                'minor'         => 'haler',
                'symbol'        => 'Kč',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => '.',
            ],

            'DKK' => [
                'name'          => 'Danish Krone',
                'major'         => 'krone',
                'minor'         => 'øre',
                'symbol'        => 'kr',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => '.',
            ],

            'EUR' => [
                'name'          => 'Euro',
                'major'         => 'euro',
                'minor'         => 'cent',
                'symbol'        => '€',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'HKD' => [
                'name'          => 'Hong Kong Dollar',
                'major'         => 'dollar',
                'minor'         => 'cent',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'HUF' => [
                'name'          => 'Hungarian Forint',
                'major'         => 'forint',
                'minor'         => '',
                'symbol'        => 'Ft',
                'decimals'      => 0,
                'decimal_sep'   => '',
                'thousand_sep'  => '.',
            ], // 1

            'ILS' => [
                'name'          => 'Israeli New Shekel',
                'major'         => 'new shekel',
                'minor'         => 'agora',
                'symbol'        => '₪',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'JPY' => [
                'name'          => 'Japanese Yen',
                'major'         => 'yen',
                'minor'         => 'sen',
                'symbol'        => '¥',
                'decimals'      => 0,
                'decimal_sep'   => '',
                'thousand_sep'  => ',',
            ], // 1

            'MYR' => [
                'name'          => 'Malaysian Ringgit',
                'major'         => 'ringgit',
                'minor'         => 'sen',
                'symbol'        => 'RM',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'MXN' => [
                'name'          => 'Mexican Peso',
                'major'         => 'peso',
                'minor'         => 'centavo',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'NZD' => [
                'name'          => 'New Zealand Dollar',
                'major'         => 'dollar',
                'minor'         => 'cent',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'NOK' => [
                'name'          => 'Norwegian Krone',
                'major'         => 'krone',
                'minor'         => 'øre',
                'symbol'        => 'kr',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => '.',
            ],

            'PHP' => [
                'name'          => 'Philippine Peso',
                'major'         => 'peso',
                'minor'         => 'centavo',
                'symbol'        => '₱',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'PLN' => [
                'name'          => 'Polish Zloty',
                'major'         => 'zloty',
                'minor'         => 'grosz',
                'symbol'        => 'zł',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => ' ',
            ],

            'GBP' => [
                'name'          => 'Pound Sterling',
                'major'         => 'pound',
                'minor'         => 'pence',
                'symbol'        => '£',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'RUB' => [
                'name'          => 'Russian Ruble',
                'major'         => 'ruble',
                'minor'         => 'kopeck',
                'symbol'        => '₽',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => '.',
            ],

            'SGD' => [
                'name'          => 'Singapore Dollar',
                'major'         => 'dollar',
                'minor'         => 'cent',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'SEK' => [
                'name'          => 'Swedish Krona',
                'major'         => 'krona',
                'minor'         => 'öre',
                'symbol'        => 'kr',
                'decimals'      => 2,
                'decimal_sep'   => ',',
                'thousand_sep'  => ' ',
            ],

            'CHF' => [
                'name'          => 'Swiss Franc',
                'major'         => 'franken',
                'minor'         => 'rappen',
                'symbol'        => 'CHF',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => "'",
            ],

            'TWD' => [
                'name'          => 'Taiwan New Dollar',
                'major'         => 'new dollar',
                'minor'         => 'cent',
                'symbol'        => 'NT$',
                'decimals'      => 0,
                'decimal_sep'   => '',
                'thousand_sep'  => ',',
            ], // 1

            'THB' => [
                'name'          => 'Thai baht',
                'major'         => 'baht',
                'minor'         => 'satang',
                'symbol'        => '฿',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],

            'USD' => [
                'name'          => 'United States Dollar',
                'major'         => 'dollar',
                'minor'         => 'cent',
                'symbol'        => '$',
                'decimals'      => 2,
                'decimal_sep'   => '.',
                'thousand_sep'  => ',',
            ],
        ]);

        if (!empty($currency) && isset($currencies->$currency)) return $currencies->$currency;

        return $currencies;
    }
endif;

if (!function_exists('money_form')) :
    /**
     * Formats a number as a currency string
     *
     * @param int|float(double) $number The number to be formatted
     * @param string $code Currency to use(Alpha ISO 4217 Code) , will be replaced with symbol if symbol is true.
     * @param bool $symbol Whether to use the currency symbol or not
     * @param string $position Where to place the currency/symbol. Accepts 'left', 'right', 'left_space', 'right_space'
     * @return string Returns the formatted string
     */
    function money_form($number, $code = '', $symbol = false, $position = ''): string
    {
        $settings   = to_object(settings());
        $code       = empty($code) ? $settings->currency ?? 'USD' : $code;
        $position   = empty($position) ? $settings->currency_pos ?? 'left' : $position;

        $currency   = currencies(strtoupper($code));
        $number     = empty($number) ? 0.00 : floatval($number);
        $symbol     = $symbol ? $currency->symbol : $code;

        $decimals       = $currency->decimals;
        $decimal_sep    = $currency->decimal_sep;
        $thousand_sep   = $currency->thousand_sep;
        $number         = number_format($number, $decimals, $decimal_sep, $thousand_sep);

        switch ($position) {
            case 'left':
                $money = "$symbol$number";
                break;
            case 'left_space':
                $money = "$symbol $number";
                break;
            case 'right':
                $money = "$number$symbol";
                break;
            case 'right_space':
                $money = "$number $symbol";
                break;
            default:
                $money = "$symbol$number";
                break;
        }

        return $money;
    }
endif;

if (!function_exists('number_form')) :
    /**
     * Format a number with grouped thousands
     *
     * @param int|float(double) $number The number to be formatted
     */
    function number_form($number)
    {

        $currency       = currencies(settings('currency', 'USD'));
        $decimals       = $currency->decimals;
        $decimal_sep    = $currency->decimal_sep;
        $thousand_sep   = $currency->thousand_sep;

        $number = empty($number) ? 0.00 : floatval($number);
        $number = number_format($number, $decimals, $decimal_sep, $thousand_sep);

        return $number;
    }
endif;

if (!function_exists('number_form_raw')) :
    /**
     * Format a number without grouped thousands
     *
     * @param int|float(double) $number The number to be formatted
     */
    function number_form_raw($number)
    {

        $currency       = currencies(settings('currency'));
        $decimals       = $currency->decimals;
        $decimal_sep    = $currency->decimal_sep;
        $thousand_sep   = $currency->thousand_sep;

        $number = empty($number) ? 0.00 : $number;

        if ($thousand_sep == '.') {
            if ($decimal_sep == ',' && (strpos($number, ',') !== false || substr_count($number, '.') > 1)) {
                $number = str_replace(['.', ','], [',', '.'], $number);
            } elseif ($decimal_sep == '') {
                $number = str_replace(['.'], [','], $number);
            }
        } else {
            $number = str_replace([$thousand_sep, $decimal_sep], ['', '.'], $number);
        }

        $number = $decimals == 0 ? ceil($number) : $number;

        return floatval($number);
    }
endif;

if (!function_exists('get_locations')) :
    /**
     * Get locations
     */
    function get_locations(): array
    {
        $string     = file_get_contents(storage_path() . '/countries.json');
        $countries  = json_decode($string);

        foreach ($countries as $country) {
            $string = file_get_contents(storage_path() . '/states.json');
            $states = json_decode($string, true);

            if (empty($states[$country->code])) continue;

            $data[] = [
                'code'      => $country->code,
                'name'      => $country->name,
                'states'    => array_map(function ($state) {
                    return $state['state'];
                }, $states[$country->code]),
            ];
        }

        return (array) $data;
    }
endif;

if (!function_exists('_error_log')) :
    /**
     * Custom Error Log
     */
    function _error_log($s)
    {
        $trace = debug_backtrace();

        // Dump the imediate trace
        $s = "\n";
        $s .= "\t" . "[file]" . "\t\t\t\t" . "=> " . $trace[0]['file'] . "\n";
        $s .= "\t" . "[line]" . "\t\t\t\t" . "=> " . $trace[0]['line'] . "\n";

        foreach ($trace[0]['args'] as $key => $arg) {
            if (is_array($arg)) {
                ob_start();
                print_r($arg);
                $arg = preg_replace("/[\n\r]/", "\n\t", ob_get_clean());
                preg_match("/(Array[\n\r\t]+\()([\s\S]+)(\))/i", $arg, $matches);
                $arg = $matches[2];
            } elseif (is_object($arg)) {
                $arg = print_r($arg, true);
            }

            $s .= "\t" . "[args][$key]" . "\t\t\t" . "=> " . $arg . "\n";
        }

        error_log($s);
    }
endif;

if (!function_exists('is_json')) :
    /**
     * Check if string is json
     */
    function is_json($string): bool
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }
endif;

if (!function_exists('to_object')) :
    /**
     * Force argument to object
     */
    function to_object($arg): object
    {
        $decode = is_string($arg) ? @json_decode($arg) : null;

        return $decode == null ? (object) json_decode(json_encode($arg)) : $decode;
    }
endif;

if (!function_exists('to_array')) :
    /**
     * Force argument to array
     */
    function to_array($arg): array
    {
        $decode = is_string($arg) ? @json_decode($arg, true) : null;

        return $decode == null ? (array) json_decode(json_encode($arg), true) : $decode;
    }
endif;

if (!function_exists('generate_uuid')) :
    /**
     * Custom Error Log
     */
    function generate_uuid($prefix = ''): string
    {
        return $prefix . '' . (string) Str::uuid();
    }
endif;

if (!function_exists('square_client')) :
    /**
     * Returns Square Client
     */
    function square_client(): SquareClient
    {
        extract(collect(settings())->only('square_access_token', 'square_sandbox')->toArray());

        return new SquareClient([
            'accessToken' => $square_access_token ?? '',
            'environment' => $square_sandox ?? true ? Environment::SANDBOX : Environment::PRODUCTION,
        ]);
    }
endif;

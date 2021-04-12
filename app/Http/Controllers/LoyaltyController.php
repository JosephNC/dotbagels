<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Str;

class LoyaltyController extends Controller
{
    public function index()
    {
        $loyalty_programs = square_raw_data('loyalty_programs', []);

        if (!empty($loyalty_programs)) return redirect(route('loyalty.edit', $loyalty_programs[0]['id']));

        return Inertia::render("Loyalty/Index");
    }

    public function edit(String $program_id)
    {
        $loyalty_programs = square_raw_data('loyalty_programs', []);

        $loyalty_program = [];

        foreach ($loyalty_programs as $program) {

            if ($program['id'] != $program_id) continue;

            $loyalty_program = $program;

            break;
        }

        if (empty($loyalty_program)) return back()->withError('Loyalty programme not found.');

        $collections = collect($loyalty_program)->only('id', 'accrual_rules', 'terminology', 'reward_tiers');
        $loyalty_program = $collections->merge($collections->get('accrual_rules')[0])->forget('accrual_rules');

        return Inertia::render('Loyalty/Edit', compact('loyalty_program'));
    }

    public function create()
    {
        return Inertia::render('Loyalty/Create');
    }

    public function store()
    {
        $this->validator();

        $data = request()->all();

        $created_at = now()->toISOString();

        $reward_tiers = array_map(function ($tier) {
            return [
                'points'            => (int) $tier['points'],
                'name'              => trim($tier['name']),
                'scope'             => $tier['scope'],
                'discount_type'     => $tier['discount_type'],
                'discount_value'    => (float) $tier['discount_value'],
            ];
        }, $data['reward_tiers']);

        $loyalty_program = [
            'id' => (string) Str::uuid(),
            'status' => 'ACTIVE',
            'reward_tiers' => $reward_tiers,
            'terminology' => $data['terminology'],
            'location_ids' => [settings('square_location_id')],
            'created_at' => $created_at,
            'updated_at' => $created_at,
            'accrual_rules' => [
                [
                    'accrual_type' => $data['accrual_type'],
                    'points' => (int) $data['points'],
                    'amount' => (float) $data['amount']
                ]
            ]
        ];

        square_raw_data('loyalty_programs', [$loyalty_program], true);

        return redirect(route('loyalty'))->with('success', 'Loyalty programme created.');
    }

    public function update()
    {
        $this->validator();

        $data = request()->all();

        if (!isset($data['id']) || empty($data)) return back()->withError('Program to update was not provided.');

        $loyalty_programs = square_raw_data('loyalty_programs', []);

        $loyalty_program = [];

        foreach ($loyalty_programs as $program) {
            if ($program['id'] != $data['id']) continue;

            $loyalty_program = $program;

            break;
        }

        $updated_at = now()->toISOString();

        $reward_tiers = $loyalty_program['reward_tiers'];

        foreach ($data['reward_tiers'] as $key => $tier) {
            $reward_tiers[$key] = array_merge($reward_tiers[$key], [
                'points'            => (int) $tier['points'],
                'name'              => trim($tier['name']),
                'scope'             => $tier['scope'],
                'discount_type'     => $tier['discount_type'],
                'discount_value'    => (float) $tier['discount_value'],
            ]);
        }

        $loyalty_program = array_merge($loyalty_program, [
            'reward_tiers' => $reward_tiers,
            'terminology' => $data['terminology'],
            'location_ids' => [settings('square_location_id')],
            'updated_at' => $updated_at,
            'accrual_rules' => [
                [
                    'accrual_type' => $data['accrual_type'],
                    'points' => (int) $data['points'],
                    'amount' => (float) $data['amount']
                ]
            ]
        ]);

        square_raw_data('loyalty_programs', [$loyalty_program], true);

        return back()->with('success', 'Loyalty programme updated.');
    }

    private function validator()
    {
        $this->validate(request(), [
            'terminology'   => 'required|array|size:2',
            'terminology.*' => 'required|string|min:1|max:30',
            'accrual_type'  => 'required|string|in:VISIT,SPEND',
            'points'        => 'required|integer|numeric|min:1',
            'amount'        => 'required|numeric|min:1',
            'reward_tiers'                      => 'required|array',
            'reward_tiers.*.name'               => 'required|string|distinct|min:3|max:100',
            'reward_tiers.*.scope'              => 'required|string|in:ORDER',
            'reward_tiers.*.points'             => 'required|integer|numeric|min:1',
            'reward_tiers.*.discount_type'      => 'required|string|in:FIXED_PERCENTAGE,FIXED_AMOUNT',
            'reward_tiers.*.discount_value'     => 'required|numeric|min:1',
        ], [
            'required'      => ':attribute is required.',
            'reward_tiers.required'  => 'You must have at least one :attribute.',
        ], [
            'reward_tiers' => 'Reward tier',
            'reward_tiers.*.name'  => 'Reward name',
            'reward_tiers.*.scope'  => 'Reward type',
            'reward_tiers.*.points'  => 'Reward points',
            'reward_tiers.*.discount_type'  => 'Discount type',
            'reward_tiers.*.discount_value'  => 'Discount value',
            'terminology.one' => 'Singluar terminology',
            'terminology.other' => 'Plural terminology',
        ]);
    }
}

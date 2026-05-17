<?php

namespace Database\Seeders;

use App\Models\WithdrawalMethod;
use Illuminate\Database\Seeder;

class WithdrawalMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name'               => 'bkash',
                'label'              => 'Bkash',
                'icon_emoji'         => '🟣',
                'color_class'        => 'pink',
                'is_active'          => true,
                'min_amount'         => 50,
                'charge_type'        => 'percent',
                'charge_value'       => 0,
                'account_label'      => 'Bkash নম্বর',
                'account_placeholder'=> '017XXXXXXXX',
                'account_min_length' => 11,
                'account_max_length' => 14,
                'sort_order'         => 1,
                'instructions'       => null,
            ],
            [
                'name'               => 'nagad',
                'label'              => 'Nagad',
                'icon_emoji'         => '🟠',
                'color_class'        => 'orange',
                'is_active'          => true,
                'min_amount'         => 50,
                'charge_type'        => 'percent',
                'charge_value'       => 0,
                'account_label'      => 'Nagad নম্বর',
                'account_placeholder'=> '017XXXXXXXX',
                'account_min_length' => 11,
                'account_max_length' => 14,
                'sort_order'         => 2,
                'instructions'       => null,
            ],
            [
                'name'               => 'rocket',
                'label'              => 'Rocket',
                'icon_emoji'         => '🟣',
                'color_class'        => 'purple',
                'is_active'          => false,
                'min_amount'         => 50,
                'charge_type'        => 'percent',
                'charge_value'       => 0,
                'account_label'      => 'Rocket নম্বর',
                'account_placeholder'=> '017XXXXXXXX',
                'account_min_length' => 11,
                'account_max_length' => 14,
                'sort_order'         => 3,
                'instructions'       => null,
            ],
            [
                'name'               => 'recharge',
                'label'              => 'Mobile Recharge',
                'icon_emoji'         => '📱',
                'color_class'        => 'sky',
                'is_active'          => true,
                'min_amount'         => 20,
                'charge_type'        => 'fixed',
                'charge_value'       => 0,
                'account_label'      => 'মোবাইল নম্বর',
                'account_placeholder'=> '017XXXXXXXX',
                'account_min_length' => 11,
                'account_max_length' => 14,
                'sort_order'         => 4,
                'instructions'       => 'যেকোনো অপারেটরের নম্বরে রিচার্জ পাবেন।',
            ],
        ];

        foreach ($methods as $method) {
            WithdrawalMethod::updateOrCreate(['name' => $method['name']], $method);
        }
    }
}

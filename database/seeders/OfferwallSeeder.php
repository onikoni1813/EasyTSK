<?php

namespace Database\Seeders;

use App\Models\Offerwall;
use Illuminate\Database\Seeder;

class OfferwallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. CPAGrip Dynamic Offerwall
        Offerwall::updateOrCreate(
            ['slug' => 'cpagrip'],
            [
                'name'                 => 'CPAGrip',
                'display_name'         => 'সিপিএগ্রিপ ওয়াল',
                'description'          => 'সার্ভে এবং সিম্পল অফার সম্পন্ন করে আনলিমিটেড পয়েন্ট আর্ন করুন।',
                'icon_emoji'           => '🎁',
                'color'                => 'indigo',
                'is_active'            => true,
                'sort_order'           => 1,
                'iframe_url_template'  => 'https://www.cpagrip.com/show.php?l=0&u={user_id}',
                'api_key'              => 'cpagrip_api_key_placeholder',
                'secret_key'           => 'cpagrip_postback_secret_12345',
                'postback_method'      => 'ANY',
                'security_type'        => 'secret_match',
                'security_field'       => 'key',
                'hmac_fields'          => null,
                'ip_whitelist'         => null,
                'field_user_id'        => 'user_id',
                'field_reward'         => 'points',
                'field_transaction_id' => 'txid',
                'field_campaign_id'    => 'campaign_id',
                'reward_type'          => 'usd',
                'conversion_rate'      => 10000.0, // $1 USD = 10,000 Points
                'platform_share_pct'   => 20.0,    // 20% admin share
                'requires_task_lock'   => true,
            ]
        );

        // 2. Torox Dynamic Offerwall
        Offerwall::updateOrCreate(
            ['slug' => 'torox'],
            [
                'name'                 => 'Torox',
                'display_name'         => 'টোরক্স ওয়াল',
                'description'          => 'বিভিন্ন অ্যাপস ডাউনলোড এবং গেম খেলে আকর্ষণীয় রিওয়ার্ড জিতে নিন।',
                'icon_emoji'           => '⚡',
                'color'                => 'orange',
                'is_active'            => true,
                'sort_order'           => 2,
                'iframe_url_template'  => 'https://torox.io/wall/{api_key}/{user_id}',
                'api_key'              => 'torox_api_key_placeholder',
                'secret_key'           => 'torox_secret_key_placeholder',
                'postback_method'      => 'ANY',
                'security_type'        => 'secret_match',
                'security_field'       => 'secret',
                'hmac_fields'          => null,
                'ip_whitelist'         => null,
                'field_user_id'        => 'user_id',
                'field_reward'         => 'amount',
                'field_transaction_id' => 'id',
                'field_campaign_id'    => 'o_name',
                'reward_type'          => 'points',
                'conversion_rate'      => 10.0, // 1 Torox point = 10 Site Points
                'platform_share_pct'   => 20.0, // 20% admin share
                'requires_task_lock'   => true,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'midtrans_server_key' => env('MIDTRANS_SERVER_KEY', ''),
            'midtrans_client_key' => env('MIDTRANS_CLIENT_KEY', ''),
            'mail_mailer' => env('MAIL_MAILER', 'smtp'),
            'mail_host' => env('MAIL_HOST', ''),
            'mail_port' => env('MAIL_PORT', ''),
            'mail_username' => env('MAIL_USERNAME', ''),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', ''),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', ''),
            'mail_from_name' => env('MAIL_FROM_NAME', ''),
            'activation_key' => env('ACTIVATION_KEY', ''),
            'backup_frequency' => 'daily',   // daily, weekly, monthly, yearly
            'backup_max_files' => '7',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}

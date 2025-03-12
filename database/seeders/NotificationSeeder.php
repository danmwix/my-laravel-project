<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $messages = [
            'Eat one extra meal every day during pregnancy',
            'Eat plenty of fruits and vegetables',
            'Drink plenty of water at least 8 glasses per day (2 litres)',
            'Take iron and folic acid tablets',
            'Avoid heavy work, rest more',
            'Sleep under a long-lasting insecticidal net (LLIN)',
            'Go for ANC visit as soon as possible, and at least 4 times during the pregnancy',
        ];

        foreach ($messages as $message) {
            Notification::create(['message' => $message]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\EmailList;
use App\Models\Subscriber;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Validation\Rules\Email;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TemplateSeeder::class,
            EmailListSeeder::class,
            CampaignSeeder::class,
        ]);
    }
}

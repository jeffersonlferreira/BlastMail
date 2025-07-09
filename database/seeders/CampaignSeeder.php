<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Template;
use App\Models\EmailList;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $emailList = EmailList::query()->inRandomOrder()->first();
            $template = Template::query()->inRandomOrder()->first();

            Campaign::factory()->create([
                'email_list_id' => $emailList->id,
                'template_id' => $template->id,
            ]);
        }
    }
}

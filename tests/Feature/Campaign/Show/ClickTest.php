<?php

use App\Models\Campaign;
use App\Models\EmailList;
use App\Models\Subscriber;
use App\Models\CampaignMail;

use function Pest\Laravel\get;

beforeEach(function () {
    login();

    $emailList = EmailList::factory()->has(
        Subscriber::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Joe Doe Test'],
                ['name' => 'Jane', 'email' => 'jane@exemplo.com'],
                ['name' => 'Charles'],
            )
    )->create();

    $this->campaign = Campaign::factory()->for($emailList)->create(['send_at' => now()->addDays(2)->format('Y-m-d')]);
    $this->mail1 = CampaignMail::query()->create(['clicks' => 7, 'openings' => 0, 'campaign_id' => $this->campaign->id, 'subscriber_id' => $emailList->subscribers[0]->id, 'sent_at' => $this->campaign->send_at]);
    $this->mail2 = CampaignMail::query()->create(['clicks' => 5, 'openings' => 23, 'campaign_id' => $this->campaign->id, 'subscriber_id' => $emailList->subscribers[1]->id, 'sent_at' => $this->campaign->send_at]);
    $this->mail3 = CampaignMail::query()->create(['clicks' => 0, 'openings' => 54, 'campaign_id' => $this->campaign->id, 'subscriber_id' => $emailList->subscribers[2]->id, 'sent_at' => $this->campaign->send_at]);
});

test('should list all campaign mails and show the clicks on each mail', function () {
    get(route('campaigns.show', ['campaign' => $this->campaign, 'what' => 'clicked']))
        ->assertViewHas('what', 'clicked')
        ->assertViewHas('query', function ($query) {
            expect($query)->toHaveCount(3);

            return true;
        })
        ->assertSeeInOrder([
            $this->mail1->subscriber->name,
            $this->mail1->clicks,
            $this->mail1->subscriber->email,
            $this->mail2->subscriber->name,
            $this->mail2->clicks,
            $this->mail2->subscriber->email,
            $this->mail3->subscriber->name,
            $this->mail3->clicks,
            $this->mail3->subscriber->email
        ]);
});

test('should be possible to filter by name', function () {
    // $this->withoutExceptionHandling();
    get(route('campaigns.show', ['campaign' => $this->campaign, 'what' => 'clicked', 'search' => 'Test']))
        ->assertViewHas('what', 'clicked')
        ->assertViewHas('query', function ($query) {
            expect($query)->toHaveCount(1);

            return true;
        })
        ->assertSeeInOrder([
            $this->mail1->subscriber->name,
            $this->mail1->clicks,
            $this->mail1->subscriber->email
        ]);
});

test('should be possible to filter by email', function () {
    get(route('campaigns.show', ['campaign' => $this->campaign, 'what' => 'clicked', 'search' => 'jane@']))
        ->assertViewHas('what', 'clicked')
        ->assertViewHas('query', function ($query) {
            expect($query)->toHaveCount(1);

            return true;
        })
        ->assertSeeInOrder([
            $this->mail2->subscriber->name,
            $this->mail2->clicks,
            $this->mail2->subscriber->email
        ]);
});

test('should be possible to filter by clicks', function () {
    get(route('campaigns.show', ['campaign' => $this->campaign, 'what' => 'clicked', 'search' => 7]))
        ->assertViewHas('what', 'clicked')
        ->assertViewHas('query', function ($query) {
            expect($query)->toHaveCount(1);

            return true;
        })
        ->assertSeeInOrder([
            $this->mail1->subscriber->name,
            $this->mail1->clicks,
            $this->mail1->subscriber->email
        ]);
});

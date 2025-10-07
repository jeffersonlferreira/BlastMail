<?php

use App\Models\Campaign;
use App\Models\Template;
use App\Models\EmailList;
use App\Models\Subscriber;
use App\Models\CampaignMail;

use function Pest\Laravel\get;

beforeEach(function () {
    $template = Template::factory()->create([
        'body' => '<div>Hello World! <a href="http://www.google.com">Click here</a> <div>'
    ]);
    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $this->campaign = Campaign::factory()->for($emailList)->create(['body' => $template->body, 'send_at' => now()->addDays(2)->format('Y-m-d')]);
    $subscriber = $emailList->subscribers->first();

    $this->mail = CampaignMail::query()
        ->create([
            'campaign_id' => $this->campaign->id,
            'subscriber_id' => $subscriber->id,
            'sent_at' => $this->campaign->send_at,
        ]);
});

test('should increment clicks on the database if the campaign is tracking clicks', function () {
    $this->campaign->update(['track_click' => true]);

    get(route('tracking.clicks', ['mail' => $this->mail, 'f' => 'http://www.google.com']));

    expect($this->mail)->refresh()->clicks->toBe(1);
});

test('should not increment clicks on the database if the campaign is not tracking clicks', function () {
    $this->campaign->update(['track_click' => false]);

    get(route('tracking.clicks', ['mail' => $this->mail, 'f' => 'http://www.google.com']));

    expect($this->mail)->refresh()->clicks->toBe(0);
});

test('should redirect the user for the given url', function () {
    get(route('tracking.clicks', ['mail' => $this->mail, 'f' => 'http://www.google.com']))
        ->assertRedirect('http://www.google.com');
});

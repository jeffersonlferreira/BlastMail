<?php

use App\Jobs\SendEmailCampaign;
use App\Jobs\SendEmailsCampaign;
use App\Mail\EmailCampaign;
use App\Models\Campaign;
use App\Models\Template;
use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\assertDatabaseHas;

test('making sure that a job is create for each one of the subscribers', function () {
    Bus::fake();

    Template::factory()->create();
    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $campaign = Campaign::factory()->for($emailList)->create();

    (new SendEmailsCampaign($campaign))->handle();

    Bus::assertDispatchedTimes(SendEmailCampaign::class, 3);
    Bus::assertDispatched(SendEmailCampaign::class, function (SendEmailCampaign $job) use ($campaign) {
        expect($job->campaign->id)->toBe($campaign->id);

        return true;
    });
});

test('when a campaign is set to send now an email should be send right a way', function () {
    Mail::fake();

    Template::factory()->create();
    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $campaign = Campaign::factory()->for($emailList)->create(['send_at' => now()->format('Y-m-d')]);
    $subscriber = $emailList->subscribers->first();

    SendEmailCampaign::dispatch($campaign, $subscriber);

    Mail::assertQueued(EmailCampaign::class, function (EmailCampaign $mail) use ($subscriber) {
        expect($mail->hasTo($subscriber->email, $subscriber->name))->toBeTrue();

        return true;
    });
});

test('when dispatching the job to send the email we should create a CampaignMail Record', function () {
    Mail::fake();

    Template::factory()->create();
    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $campaign = Campaign::factory()->for($emailList)->create(['send_at' => now()->format('Y-m-d')]);
    $subscriber = $emailList->subscribers->first();

    SendEmailCampaign::dispatch($campaign, $subscriber);

    assertDatabaseHas('campaign_mails', [
        'campaign_id'   => $campaign->id,
        'subscriber_id'    => $subscriber->id,
        'sent_at'       => $campaign->send_at
    ]);
});

test('when a campaign is set to sent later the email should be schedule to be sent in the given date', function () {
    Mail::fake();

    Template::factory()->create();
    $emailList = EmailList::factory()->has(Subscriber::factory()->count(3))->create();
    $campaign = Campaign::factory()->for($emailList)->create(['send_at' => now()->addDays(2)->format('Y-m-d')]);
    $subscriber = $emailList->subscribers->first();

    SendEmailCampaign::dispatch($campaign, $subscriber);

    Mail::assertQueued(EmailCampaign::class, function (EmailCampaign $mail) use ($subscriber, $campaign) {
        expect($mail->delay->eq($campaign->send_at->format('Y-m-d')))->toBeTrue();

        expect($mail->hasTo($subscriber->email, $subscriber->name))->toBeTrue();

        return true;
    });
});

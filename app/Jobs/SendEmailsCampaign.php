<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Mail\EmailCampaign;
use App\Models\CampaignMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailsCampaign implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Campaign $campaign
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->campaign->emailList->subscribers as $subscriber) {
            SendEmailCampaign::dispatch($this->campaign, $subscriber);
        }
    }
}

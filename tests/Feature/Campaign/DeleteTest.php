<?php

use App\Models\Campaign;
use function Pest\Laravel\delete;
use function Pest\Laravel\assertSoftDeleted;

it('should be able to delete a campaign', function () {
    $this->withoutExceptionHandling();
    login();

    $campaign = Campaign::factory()->create();

    delete(route('campaigns.destroy', ['campaign' => $campaign]))
        ->assertRedirect(route('campaigns.index'));

    assertSoftDeleted('campaigns', ['id' => $campaign->id]);
})->todo();

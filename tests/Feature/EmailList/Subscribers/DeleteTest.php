<?php

use App\Models\Subscriber;

use function Pest\Laravel\{delete, assertSoftDeleted};

it('should be able to delete a subscriber', function () {
    login();

    $subscriber = Subscriber::factory()->create();

    delete(route('subscribers.destroy', ['emailList' => $subscriber->emailList, 'subscriber' => $subscriber]))
        ->assertRedirect(route('subscribers.index', $subscriber->emailList));

    assertSoftDeleted('subscribers', ['id' => $subscriber->id]);
});

<?php

namespace Tests\Feature\EmailList;

use Tests\TestCase;
use App\Models\EmailList;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    public function test_it_should_be_able_to_delete_an_email_list(): void
    {
        //Arrange
        $this->login();

        $emailList = EmailList::factory()->create();

        $subscribers = Subscriber::factory()->count(10)->create(['email_list_id' => $emailList->id]);

        //Act
        $response = $this->delete(route('email-list.delete', ['emailList' => $emailList]));

        //Assert
        $response->assertRedirect(route('email-list.index'));

        $this->assertSoftDeleted('email_lists', ['id' => $emailList->id]);

        foreach ($subscribers as $subscriber) {
            $this->assertSoftDeleted('subscribers', ['id' => $subscriber->id]);
        }
    }
}

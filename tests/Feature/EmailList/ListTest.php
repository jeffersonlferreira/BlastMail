<?php

namespace Tests\Feature\EmailList;

use Tests\TestCase;
use App\Models\User;
use App\Models\EmailList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListTest extends TestCase
{
    public function test_needs_to_be_authenticated()
    {
        $this->getJson(route('email-list.index'))->assertUnauthorized();

        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('email-list.index'))->assertSuccessful();
    }

    public function test_it_should_br_paginate()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        EmailList::factory()->count(10)->create();

        $response = $this->get(route('email-list.index'));

        $response->assertViewHas('emailLists', function ($list) {
            $this->assertInstanceOf(LengthAwarePaginator::class, $list);

            $this->assertCount(5, $list);

            return true;
        });
    }

    public function test_it_should_be_able_to_search_a_list()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);

        EmailList::factory()->count(10)->create();
        EmailList::factory()->create(['title' => 'Title 1']);
        $emailList = EmailList::factory()->create(['title' => 'Title Testing 2']);

        $response = $this->get(route('email-list.index', ['search' => 'Testing 2']));

        $response->assertViewHas('emailLists', function ($list) use ($emailList) {

            $this->assertCount(1, $list);
            $this->assertEquals($emailList->id, $list->first()->id);

            return true;
        });
    }
}

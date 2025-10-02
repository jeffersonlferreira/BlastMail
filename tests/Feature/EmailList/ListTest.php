<?php

use App\Models\User;
use App\Models\EmailList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;


beforeEach(function () {
    login();
});

test('needs to be authenticated', function () {
    Auth::logout();

    $this->getJson(route('email-list.index'))->assertUnauthorized();

    login();

    $this->get(route('email-list.index'))->assertSuccessful();
});

test('it should br paginate', function () {
    EmailList::factory()->count(10)->create();

    $response = $this->get(route('email-list.index'));

    $response->assertViewHas('emailLists', function ($list) {
        expect($list)->toBeInstanceOf(LengthAwarePaginator::class);

        expect($list)->toHaveCount(5);

        return true;
    });
});

test('it should be able to search a list', function () {
    EmailList::factory()->count(10)->create();
    EmailList::factory()->create(['title' => 'Title 1']);
    $emailList = EmailList::factory()->create(['title' => 'Title Testing 2']);

    $response = $this->get(route('email-list.index', ['search' => 'Testing 2']));

    $response->assertViewHas('emailLists', function ($list) use ($emailList) {

        expect($list)->toHaveCount(1);
        expect($list->first()->id)->toEqual($emailList->id);

        return true;
    });
});

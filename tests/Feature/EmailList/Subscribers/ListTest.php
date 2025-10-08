<?php

use App\Models\EmailList;
use App\Models\Subscriber;

use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use function Pest\Laravel\{delete, get, getJson, assertSoftDeleted};

beforeEach(function () {
    $this->emailList = EmailList::factory()->create();

    login();
});

it('only logged users can access the subscribers', function () {
    Auth::logout();

    getJson(route('subscribers.index', $this->emailList))->assertUnauthorized();
});

it('should be possible see the entire list of subscribers', function () {
    Subscriber::factory()->count(5)->create(['email_list_id' => $this->emailList->id]);

    get(route('subscribers.index', $this->emailList))
        ->assertViewHas('emailList', $this->emailList)
        ->assertViewHas('subscribers', function ($value) {
            expect($value)->toHaveCount(5);

            expect($value)->first()->email_list_id->toBe($this->emailList->id);

            return true;
        });
});

it('should be able to search a subscribers', function () {
    Subscriber::factory()->count(5)->create(['email_list_id' => $this->emailList->id]);
    $subscriber = Subscriber::factory()->create([
        'name' => 'Charlie Smith',
        'email' => 'joe@doe.com',
        'email_list_id' => $this->emailList->id
    ]);

    //Filtrar com Email
    get(route('subscribers.index', [$this->emailList, 'search' => 'joe']))
        ->assertViewHas('subscribers', function ($value) use ($subscriber) {
            expect($value)->toHaveCount(1);

            expect($value)->first()->id->toBe($subscriber->id);

            return true;
        });

    //Filtrar com Nome
    get(route('subscribers.index', [$this->emailList, 'search' => 'smith']))
        ->assertViewHas('subscribers', function ($value) use ($subscriber) {
            expect($value)->toHaveCount(1);

            expect($value)->first()->id->toBe($subscriber->id);

            return true;
        });
});

it('should be able to search a subscribers (REFATORADO)', function () {
    Subscriber::factory()
        ->count(5)
        ->sequence(fn($sequence) => [
            'email_list_id' => $this->emailList->id,
            'name' => 'Subscriber Generic',
            'email' => "user{$sequence->index}@example.com",
        ])
        ->create();

    $charlie = Subscriber::factory()->create([
        'name' => 'Charlie Smith',
        'email' => 'joe@doe.com',
        'email_list_id' => $this->emailList->id
    ]);

    // Filtrar por email
    get(route('subscribers.index', [$this->emailList, 'search' => 'joe']))
        ->assertViewHas('subscribers', function ($subscribers) use ($charlie) {
            expect($subscribers)->toHaveCount(1);
            expect($subscribers->first()->id)->toBe($charlie->id);
            return true;
        });

    // Filtrar por nome
    get(route('subscribers.index', [$this->emailList, 'search' => 'smith']))
        ->assertViewHas('subscribers', function ($subscribers) use ($charlie) {
            expect($subscribers)->toHaveCount(1);
            expect($subscribers->first()->id)->toBe($charlie->id);
            return true;
        });
});

it('should be able to search by id', function () {
    Subscriber::factory()->create([
        'name' => 'Joe Doe',
        'email' => 'joe@doe.com',
        'email_list_id' => $this->emailList->id
    ]);

    Subscriber::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jone@doe.com',
        'email_list_id' => $this->emailList->id
    ]);

    get(route('subscribers.index', [$this->emailList, 'search' => 2]))
        ->assertViewHas('subscribers', function ($value) {
            expect($value)->toHaveCount(1);

            expect($value)->first()->id->toBe(2);

            return true;
        });
});

it('should be able to show delete an records', function () {
    $subscriber = Subscriber::factory()->create(['email_list_id' => $this->emailList->id]);

    $response = delete(route('subscribers.destroy', [$this->emailList, $subscriber]));

    $response->assertRedirect(route('subscribers.index', $this->emailList));

    assertSoftDeleted('subscribers', ['id' => $subscriber->id]);
});

it('should be able to show deleted records', function () {
    Subscriber::factory()->create(['deleted_at' => now()]);
    Subscriber::factory()->create();

    get(route('subscribers.index', ['emailList' => $this->emailList]))
        ->assertViewHas('subscribers', function ($value) {
            expect($value)->count(1);

            return true;
        });
});

it('should be paginated', function () {
    Subscriber::factory()->count(30)->create(['email_list_id' => $this->emailList->id]);

    $response = get(route('subscribers.index', $this->emailList));

    $response->assertViewHas('subscribers', function ($value) {
        expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

        expect($value)->toHaveCount(5);

        return true;
    });
});

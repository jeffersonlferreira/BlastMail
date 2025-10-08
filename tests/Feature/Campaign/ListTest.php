<?php

use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use function Pest\Laravel\{get, getJson};

beforeEach(function () {
    login();
});

it('only logged users can access the campaign', function () {
    Auth::logout();

    getJson(route('campaigns.index'))->assertUnauthorized();
});

it('should be possible see the entire list of campaign', function () {
    $campaigns = Campaign::factory()->count(5)->create();

    get(route('campaigns.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

            expect($value->count())->toBeLessThanOrEqual(5);

            return true;
        });
});

it('should be able to search a campaign', function () {
    Campaign::factory()->count(5)->create();
    Campaign::factory()->create(['name' => 'Testing', 'deleted_at' => null]);

    //Filtrar com Nome
    get(route('campaigns.index', ['search' => 'Testing']))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->toHaveCount(1);

            return true;
        });
});

it('should be able to search by id', function () {
    Campaign::factory()->count(5)->create();
    $campaign = Campaign::factory()->create(['name' => 'Testing', 'deleted_at' => null]);

    //Filtrar com ID
    get(route('campaigns.index', ['search' => $campaign->id]))
        ->assertViewHas('campaigns', function ($value) use ($campaign) {
            expect($value)->toHaveCount(1);

            expect($value)->first()->id->toBe($campaign->id);

            expect($value)->first()->name->toBe('Testing');

            return true;
        });
});

it('should be able to show deleted campaign', function () {
    Campaign::factory()->create(['deleted_at' => now()]);
    Campaign::factory()->create();

    get(route('campaigns.index'))
        ->assertViewHas('campaigns', function ($value) {
            expect($value)->count(1);

            return true;
        });
});

it('should be paginated', function () {
    Campaign::factory()->count(30)->create();

    $response = get(route('campaigns.index'));

    $response->assertViewHas('campaigns', function ($value) {
        expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

        expect($value)->toHaveCount(5);

        return true;
    });
});

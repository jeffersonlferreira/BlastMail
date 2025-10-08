<?php

use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use function Pest\Laravel\{delete, get, getJson, assertSoftDeleted};

beforeEach(function () {
    login();
});

it('only logged users can access the templates', function () {
    Auth::logout();

    getJson(route('templates.index'))->assertUnauthorized();
});

it('should be possible see the entire list of templates', function () {
    $templates = Template::factory()->count(5)->create();

    get(route('templates.index'))
        ->assertViewHas('templates', function ($value) use ($templates) {
            expect($value)->toHaveCount(5);

            expect($value->pluck('id'))->toEqual($templates->pluck('id'));

            return true;
        });
});

it('should be able to search a templates', function () {
    Template::factory()->count(5)->create();
    Template::factory()->create([
        'name' => 'Template Testing 2',
        'body' => 'Some body content'
    ]);

    //Filtrar com Nome
    get(route('templates.index', ['search' => 'Testing 2']))
        ->assertViewHas('templates', function ($value) {
            expect($value)->toHaveCount(1);

            return true;
        });
});

it('should be able to search by id', function () {
    Template::factory()->count(5)->create();
    $template = Template::factory()->create([
        'name' => 'Template Testing 2',
        'body' => 'Some body content'
    ]);

    //Filtrar com ID
    get(route('templates.index', ['search' => 6]))
        ->assertViewHas('templates', function ($value) use ($template) {
            expect($value)->toHaveCount(1);

            expect($value)->first()->id->toBe($template->id);

            expect($value)->first()->name->toBe('Template Testing 2');

            expect($value)->first()->body->toBe('Some body content');

            return true;
        });
});

it('should be able to show preview an template', function () {
    $template = Template::factory()->create([
        'name' => 'Template Testing',
        'body' => 'Some body content'
    ]);

    get(route('templates.show', $template))
        ->assertViewHas('template', function ($value) use ($template) {
            expect($value->id)->toBe($template->id);
            expect($value->name)->toBe('Template Testing');
            expect($value->body)->toBe('Some body content');

            return true;
        });
});

it('should be able to show update an template', function () {
    $template = Template::factory()->create();

    get(route('templates.edit', $template))
        ->assertViewHas('template', $template);
});

it('should be able to show delete an template', function () {
    $template = Template::factory()->create();

    $response = delete(route('templates.destroy', $template));

    $response->assertRedirect(route('templates.index'));

    assertSoftDeleted('templates', ['id' => $template->id]);
});

it('should be able to show deleted template', function () {
    Template::factory()->create(['deleted_at' => now()]);
    Template::factory()->create();

    get(route('templates.index'))
        ->assertViewHas('templates', function ($value) {
            expect($value)->count(1);

            return true;
        });
});

it('should be paginated', function () {
    Template::factory()->count(30)->create();

    $response = get(route('templates.index'));

    $response->assertViewHas('templates', function ($value) {
        expect($value)->toBeInstanceOf(LengthAwarePaginator::class);

        expect($value)->toHaveCount(5);

        return true;
    });
});

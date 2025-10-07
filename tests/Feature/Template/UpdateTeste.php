<?php

use App\Models\Template;

use function Pest\Laravel\{put, assertDatabaseHas};

it('it should be able update a template', function () {
    login();

    $template = Template::factory()->create();

    put(route('templates.update', $template), [
        'name' => 'Template Name',
        'body' => 'Some body content'
    ])
        ->assertRedirectToRoute('templates.index')
        ->assertSessionHasNoErrors();

    assertDatabaseHas('templates', [
        'id' => $template->id,
        'name' => 'Template Name',
        'body' => 'Some body content'
    ]);
});

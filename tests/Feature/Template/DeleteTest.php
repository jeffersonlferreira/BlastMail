<?php

use App\Models\Template;

use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;

it('should be able to delete a template', function () {
    login();

    $template = Template::factory()->create();

    delete(route('templates.destroy', ['template' => $template]))
        ->assertRedirect(route('templates.index'));

    assertSoftDeleted('templates', ['id' => $template->id]);
});

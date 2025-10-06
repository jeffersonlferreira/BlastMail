<?php

use function Pest\Laravel\{post, assertDatabaseHas};

beforeEach(function () {
    login();

    $this->route = route('templates.store');
});

it('name should be required', function () {
    post($this->route, [
        'name' => null,
        'body' => 'Some body content'
    ])
        ->assertSessionHasErrors([
            'name' => __('validation.required', ['attribute' => 'name'])
        ]);
});

it('name should have a max of 255 characters', function () {
    post($this->route, [
        'name' => str_repeat('*', 256),
        'body' => 'Some body content'
    ])
        ->assertSessionHasErrors([
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])
        ]);
});

it('body should be required', function () {
    post($this->route, [
        'name' => 'Template Name',
        'body' => null
    ])
        ->assertSessionHasErrors([
            'body' => __('validation.required', ['attribute' => 'body'])
        ]);
});

it('it should be able create a template', function () {
    post($this->route, [
        'name' => 'Template Name',
        'body' => 'Some body content'
    ])
        ->assertRedirectToRoute('templates.index')
        ->assertSessionHasNoErrors();

    assertDatabaseHas('templates', [
        'name' => 'Template Name',
        'body' => 'Some body content'
    ]);
});

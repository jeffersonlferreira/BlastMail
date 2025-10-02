<?php

use Illuminate\Http\UploadedFile;
use function Pest\Laravel\{post, assertDatabaseHas};

beforeEach(function () {
    login();
});

it('title should be required', function () {
    post(route('email-list.create'), [])
        ->assertSessionHasErrors(['title']);
});

it('title should have a max of 255 characters', function () {
    post(route('email-list.create'), ['title' => str_repeat('*', 256)])
        ->assertSessionHasErrors(['title']);
});

it('file should be required', function () {
    post(route('email-list.create'), [])
        ->assertSessionHasErrors(['file']);
});

it('it should be able create an email list', function () {
    // withoutExceptionHandling(); // MOSTRAR MAIS DETALHES DE ERRO
    // Arrange
    $data = [
        'title' => 'Email List Test',
        'file'  => UploadedFile::fake()->createWithContent(
            'sample_names.csv',
            <<<'CSV'
        Name;Email
        Joe Doe;joe@doe.com
        CSV
        ),
    ];

    // Act
    $response = post(route('email-list.create'), $data);

    // Assert
    $response->assertRedirectToRoute('email-list.index');

    assertDatabaseHas('email_lists', [
        'title' => 'Email List Test',
    ]);

    assertDatabaseHas('subscribers', [
        'email_list_id' => 1,
        'name'          => 'Joe Doe',
        'email'         => 'joe@doe.com'
    ]);
});

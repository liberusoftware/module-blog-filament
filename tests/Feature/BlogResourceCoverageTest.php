<?php

use Liberu\Blog\Core\Models\Post;
use Liberu\Blog\Filament\Resources\PostResource\Pages\ListPosts;
use Liberu\PackageTestbench\TestUser;
use Livewire\Livewire;

/**
 * The host version created a `Team`, set it as the panel tenant and opened the
 * gate with `Gate::before(fn () => true)` to get past Shield's policies. In a
 * package there is no tenant and no Shield: Filament permits an action when no
 * policy is registered for the model, so the resource is reachable without any
 * of it.
 *
 * A record is created so the table actually resolves its columns — including
 * `author.name`, which goes through `blog.author_model`. The host version
 * rendered an empty table, which cannot fail on a broken column definition.
 */
it('renders the post resource table', function () {
    $author = TestUser::factory()->create(['name' => 'Ada Lovelace']);

    Post::create([
        'user_id' => $author->id,
        'title' => 'A published post',
        'slug' => 'a-published-post',
        'body' => 'Visible in the table.',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->actingAs($author);

    Livewire::test(ListPosts::class)
        ->assertOk()
        ->assertSee('A published post')
        ->assertSee('Ada Lovelace');
});

<?php

use Liberu\Blog\Core\Models\Post;
use Liberu\Blog\Filament\Resources\PostResource\Pages\CreatePost;
use Liberu\PackageTestbench\TestUser;
use Livewire\Livewire;

/**
 * The host version of this test built a `Team`, made it the acting user's current
 * tenant, created a `super_admin` role inside that team's permission context and
 * set the tenant on the panel. None of that was the subject: it was the price of
 * driving the host's tenant-scoped, Shield-gated admin panel.
 *
 * What the test actually pins down is one line of `CreatePost` —
 * `$data['user_id'] ??= auth()->id()` — and it is a regression guard, because
 * `module_blog_posts.user_id` is NOT NULL and is not on the form, so a create
 * without it is a database error rather than a validation message.
 */
it('assigns the acting user as author when creating a post (user_id has no default)', function () {
    $author = TestUser::factory()->create();

    $this->actingAs($author);

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'body' => 'First post.',
            'status' => 'published',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $post = Post::firstOrFail();
    expect($post->user_id)->toBe($author->id);
});

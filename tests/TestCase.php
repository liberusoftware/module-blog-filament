<?php

namespace Liberu\Blog\Filament\Tests;

use Filament\Facades\Filament;
use Liberu\Blog\Core\BlogServiceProvider;
use Liberu\Blog\Filament\Tests\Fixtures\TestPanelProvider;
use Liberu\PackageTestbench\PackageTestCase;
use Liberu\PackageTestbench\TestUser;
use Liberu\PackageTestbench\UsesTestUser;

/**
 * Filament is the one dependency `PackageTestCase`'s scoped discovery cannot
 * cover on its own.
 *
 * `PackageTestCase` registers `extra.laravel.providers` of this package's
 * *direct* dependencies, which for `filament/filament` is exactly one provider.
 * A panel needs the rest of the stack — support, schemas, forms, tables,
 * actions, notifications, widgets, Livewire, the icon packages — and every one
 * of those is transitive. So this case widens the same walk to everything
 * installed, which is what Laravel's own package discovery does in an
 * application, and appends the fixture panel.
 */
abstract class TestCase extends PackageTestCase
{
    use UsesTestUser;

    protected function setUp(): void
    {
        parent::setUp();

        // No route is being visited, so nothing has resolved a panel from the
        // request; a resource page needs one to be current before it can mount.
        Filament::setCurrentPanel('admin');
    }

    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('auth.providers.users.model', TestUser::class);
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return array_values(array_unique([
            ...$this->discoveredProviders(),
            BlogServiceProvider::class,
            ...parent::getPackageProviders($app),
            TestPanelProvider::class,
        ]));
    }

    /**
     * Every `extra.laravel.providers` entry in the installed tree.
     *
     * Sibling Liberu modules are unaffected: their manifests declare that array
     * empty precisely so installation never implies boot, so this picks up the
     * framework packages and nothing else — `blog-core`'s provider is named
     * above for exactly that reason, since it loads the `module_blog_posts`
     * migration and merges `config/blog.php`.
     *
     * @return array<int, class-string>
     */
    private function discoveredProviders(): array
    {
        $installed = json_decode(
            (string) file_get_contents($this->packageRoot().'/vendor/composer/installed.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $providers = [];

        foreach ($installed['packages'] ?? [] as $package) {
            foreach ((array) ($package['extra']['laravel']['providers'] ?? []) as $provider) {
                $providers[] = $provider;
            }
        }

        return $providers;
    }
}

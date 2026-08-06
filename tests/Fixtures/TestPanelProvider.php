<?php

namespace Liberu\Blog\Filament\Tests\Fixtures;

use Filament\Panel;
use Filament\PanelProvider;
use Liberu\Blog\Filament\BlogFilamentPlugin;

/**
 * The panel this package's resources need in order to be resources at all.
 *
 * A Filament resource is only reachable through a panel, and this package ships
 * a plugin rather than a panel — the host composes one. So the suite composes
 * the smallest panel that registers the plugin the manifest declares.
 *
 * Deliberately *not* a copy of the host's `AdminPanelProvider`. That panel is
 * tenant-scoped to a `Team`, gated by Shield, and themed from site settings;
 * none of that is this package's, and reproducing it would mean the tests were
 * asserting on the host's composition instead of on `PostResource`. The id is
 * `admin` only because that is the panel the manifest's
 * `presentation.filament.admin` key names.
 */
final class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugins([BlogFilamentPlugin::make()]);
    }
}

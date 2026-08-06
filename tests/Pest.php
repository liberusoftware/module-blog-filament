<?php

use Liberu\Blog\Filament\Tests\TestCase;
use Liberu\PackageTestbench\PackageTestCase;

// Only the feature suite needs the panel, a database and an actor; the rest boot
// this package and nothing else.
pest()->extend(PackageTestCase::class)->in('Architecture', 'Integration', 'Unit');
pest()->extend(TestCase::class)->in('Feature');

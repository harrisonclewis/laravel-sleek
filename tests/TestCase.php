<?php

namespace Tests;

use Harlew\Sleek\SleekServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [SleekServiceProvider::class];
    }
}

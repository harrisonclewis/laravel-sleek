<?php

namespace Tests;

use HarlewCom\LaravelSleek\SleekServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [SleekServiceProvider::class];
    }
}

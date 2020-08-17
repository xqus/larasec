<?php

namespace xqus\laraSec\Tests;

use Illuminate\Support\Facades\Artisan;
use xqus\laraSec\laraSecServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('larasec:update');
    }

    protected function getPackageProviders($app)
    {
        return [
            laraSecServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}

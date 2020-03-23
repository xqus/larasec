<?php

namespace xqus\laraSec\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use xqus\laraSec\Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class UpdateVulnDbTest extends TestCase
{
    /** @test */
    function the_update_command_downloads_files()
    {
        $this->assertTrue(Storage::exists('larasec/laravel/framework/2020-03-13-1.yaml'));
    }
}
